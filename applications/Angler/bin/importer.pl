#!/usr/bin/env perl

use warnings;
use strict;
use v5.14;

package Product;

use Moo;

package main;

use Dancer ':script';
use Angler::Schema;
use Dancer::Plugin::Interchange6;
use File::Path qw/make_path/;
use File::Copy;
use File::Spec;
use Getopt::Long;
use HTML::Entities;
use HTML::Obliterate qw/strip_html/;
use HTTP::Tiny;
use Imager;
use List::Util qw/all/;
use Pod::Usage;
use Spreadsheet::ParseExcel;
use Spreadsheet::ParseXLSX;
use Text::Unidecode;
use Time::HiRes qw/sleep/;
use Try::Tiny;
use URI::Escape;
use XML::Twig;
use YAML qw/LoadFile/;
use Data::Dumper::Concise;

set logger => 'console';
set log    => 'info';

my $count = 0;

my $config =
  LoadFile( File::Spec->catfile( config->{appdir}, "importer.yml" ) );

my $schema = shop_schema;

my $img_dir = "/home/camp/angler/rsync/htdocs/assetstore/site/images/items";

my @img_sizes = qw/35 75 100 110 200 325 975/;

my ( $active, $file, $help, $manufacturer );

GetOptions(
    "active"         => \$active,
    "file=s"         => \$file,
    "help"           => \$help,
    "manufacturer=s" => \$manufacturer,
);
pod2usage(1) if $help;

# initial checks

unless ( $file && $manufacturer ) {
    print "ERROR: file and manufacturer must be supplied as options.\n";
    pod2usage(1);
}

unless ( -r $file ) {
    print "ERROR: unable to read file";
    exit 1;
}

unless ( defined $config->{manufacturers}->{$manufacturer} ) {
    print "ERROR: manufacturer not found. Valid manufacturers are\n";
    print( join( " ", keys %{ $config->{manufacturers} } ), "\n" );
    exit 1;
}

unless ( defined $config->{manufacturers}->{$manufacturer}->{type} ) {
    print "ERROR: type not defined for manufacturer $manufacturer in config\n";
    exit 1;
}

$active = 0 unless $active;

my @product_columns = shop_product->result_source->columns;
my $mediatype_image =
  $schema->resultset('MediaType')->find( { type => 'image' } );
die "MediaType type image not found" unless $mediatype_image;

# where we store downloaded images

my $original_files =
  File::Spec->catdir( $img_dir, "original_files", $manufacturer );
make_path($original_files);

# parse by type

my $type = $config->{manufacturers}->{$manufacturer}->{type};
if ( $type =~ /^xls/ ) {

    # spreadsheet

    if ( $manufacturer eq 'simms' ) {
        &parse_excel($file);
    }
    else {
        die "xls processor for $manufacturer does not exist";
    }
}
elsif ( $type eq 'xml' ) {

    # XML

    my $twig_handlers;

    if ( $manufacturer eq 'orvis' ) {
        $twig_handlers = { Product => \&process_orvis_product };
    }
    else {
        die "xml processor for $manufacturer does not exist";
    }

    my $twig = XML::Twig->new( twig_handlers => $twig_handlers );
    $twig->parsefile($file);

    if ( $manufacturer eq 'orvis' ) {

        # now orvis products are all inserted we need to reparse
        # to add cross-sell info

        info "processing cross-sells";
        $count = 0;
        my $twig =
          XML::Twig->new(
            twig_handlers => { Product => \&process_orvis_cross_sell } );

        $twig->parsefile($file);
    }

}
else {

    # unknown

    die "ERROR: unexpected type $type found in config\n";
}

=head2 add_attribute_values( $name, $title, @values );

=cut

sub add_attribute_values {
    my ( $name, $title, @values ) = @_;

    my $attribute = $schema->resultset('Attribute')->find_or_create(
        {
            name  => &clean_attribute_value($name),
            title => $title,
            type  => 'variant',
        },
        {
            key => 'attributes_name_type'
        }
    );

    foreach my $value (@values) {
        $schema->resultset('AttributeValue')->find_or_create(
            {
                attributes_id => $attribute->attributes_id,
                value         => &clean_attribute_value($value),
                title         => $value,
            },
            {
                key => 'attribute_values_attributes_id_value'
            }
        );
    }
}

=head2 clean_attribute_value($value)

return a cleaned up value for AttributeValue value column

=cut

sub clean_attribute_value {
    my $value = lc(shift);
    $value =~ s/\s+/_/g;
    while ( grep { $value eq $_ } @product_columns ) {
        $value = "X$value";
    }
    return $value;
}

=head2 clean_name($name)

Decodes HTML entities and purges any html tags from arg.

Also removes freight charge from name, e.g.: (+$30).

=cut

sub clean_name {
    my $name = strip_html( decode_entities(shift) );

    # freight charge in name
    $name =~ s/\(\+\$\s*\d+\)//;

    return $name;
}

=head2 short_description($description)

Tries to shorten a description to fit in short_description varchar(500) column

=cut

sub short_description {
    my $description = shift;

    my @desc_sentences = split( /\./, $description );
    my $short_description = '';
    foreach my $sentence (@desc_sentences) {
        if ( length($short_description) + length($sentence) + 1 < 500 ) {
            $short_description .= ".$sentence";
        }
        else {
            last;
        }
    }
    if ($short_description) {

        # append final full stop
        $short_description .= ".";
    }
    else {

        # all sentences were too long so get a substr
        $short_description = substr( $description, 500 );
    }
    return $short_description;
}

=head2 trim

Remove leading and trailing spaces.

=cut

sub trim {
    my $text = shift;
    $text =~ s/^\s+|\s+$//g;
    return $text;
}

=head2 parse_excel

Parse Excel file (xls or xlsx) in order to add products to database

=cut

sub parse_excel {

    my $config = $config->{manufacturers}->{$manufacturer};

    # choose parser

    my $parser =
      $config->{type} eq 'xlsx'
      ? Spreadsheet::ParseXLSX->new
      : Spreadsheet::ParseExcel->new;

    # parse the file

    my $workbook = $parser->parse(shift);
    if ( !defined $workbook ) {
        die $parser->error(), ".\n";
    }

    # we need at least one worksheet

    die "no worksheets found" unless $workbook->worksheet_count;

    my $worksheet = $workbook->worksheet( $config->{worksheet} );

    die "worksheet not found" unless $worksheet;

    # col/row ranges in use

    my ( $col_min, $col_max ) = $worksheet->col_range();
    my ( $row_min, $row_max ) = $worksheet->row_range();

    # grab headers

    my %headers;
    foreach my $col ( $col_min .. $col_max ) {
        $headers{$col} =
          &trim( $worksheet->get_cell( $config->{header_row}, $col )->value );
    }

    # check headers

    die "Unexpected headers in file"
      unless (
        join( "|", sort values %headers ) eq
        join( "|", sort @{ $config->{headers} } ) );

    my ( $sku, %data );
    foreach my $row ( 2 .. $row_max ) {
        my %cells =
          map {
            $headers{$_} => &trim( $worksheet->get_cell( $row, $_ )->value )
          } $col_min .. $col_max;

        if ( $sku && $cells{"Product Group Code"} ne $sku ) {
            &insert_product( $sku, %data );
            undef %data;
        }
        $sku = $cells{"Product Group Code"};

        #unless ( grep { $_ eq $cells{"Item Category Code"} } qw/
        #my $men_women =
        #if (
        #my $icc = $cells{"Item Category Code"};
        #if ( $icc eq 'HEAD'
        #for ( $cells{"Item Category Code"} ) {
        #    when (
        #}

        %data = (

        );
    }
    &insert_product( $sku, %data );
}

sub insert_product {
    my ( $sku, %data ) = @_;
    print "insert $sku\n";
}

=head2 process_image( $product, $path );

Arguments are:

=over

=item * L<Interchange6::Schema::Result::Product>

=item * path to original image

=back

Returns true on success.

=cut

sub process_image {
    my ( $product, $path ) = @_;

    my $file = [ File::Spec->splitpath($path) ]->[2];

    ( my $ext = lc($file) ) =~ s/^.+\.//;
    $ext =~ s/\s+$//;

    my $sku = $product->sku;

  SIZE: foreach my $size (@img_sizes) {

        my $dir =
          File::Spec->catdir( $img_dir, "${size}x${size}", $manufacturer );

        make_path($dir);

        my $new_path = File::Spec->catfile( $dir, $file );

        unless ( -r $new_path ) {

            # we don't have this image yet so create it

            my $img = Imager->new( file => $path );

            unless ($img) {
                error "Imager read failed for $sku $path " . Imager->errstr;
                return;
            }

            # scale
            $img = $img->scale(
                xpixels => $size,
                ypixels => $size,
                type    => 'min'
            );

            unless ($img) {
                error "Imager scale barfed for $size"
                  . "x$size on $sku $path: "
                  . $img->errstr;
                return;
            }

            # write it
            if ( $ext eq 'jpg' ) {
                my $ret = $img->write(
                    file        => $new_path,
                    jpegquality => 90,
                    type        => 'jpeg',
                );
                unless ($ret) {
                    error "Imager jpeg write failed for $new_path: "
                      . $img->errstr;
                    return;
                }
            }
            else {
                my $ret = $img->write( file => $new_path );
                unless ($ret) {
                    error "Imager $ext write failed for $new_path: "
                      . $img->errstr;
                    return;
                }
            }
        }

        # make sure we've got the database entry for this image

        my $media = $schema->resultset('Media')->find_or_create(
            {
                file           => $path,
                uri            => File::Spec->catfile( $manufacturer, $file ),
                mime_type      => "image/$ext",
                media_types_id => $mediatype_image->id,
            },
            {
                key => 'medias_file',
            }
        );
        $schema->resultset('MediaProduct')->find_or_create(
            {
                media_id => $media->id,
                sku      => $sku,
            }
        );
    }
    return 1;
}

=head2 process_orvis_cross_sell

twig handler for Orvis xml Cross_Sell

=cut

sub process_orvis_cross_sell {
    my ( $t, $xml ) = @_;

    info "processed $count products" unless ( ++$count % 100 );

    my $Cross_Sells = $xml->first_child('Cross_Sells');

    my $sku = "WB-OR-" . $Cross_Sells->{'att'}->{'PF_ID'};
    my $product = shop_product($sku);
    return unless $product;

    foreach my $Cross_Sell ( $Cross_Sells->children ) {
        my $related_sku = "WB-OR-" . $Cross_Sell->{'att'}->{'PF_ID'};
        my $related_product = shop_product($related_sku);
        next unless $related_product;
        $schema->resultset('MerchandisingProduct')->find_or_create(
            {
                sku         => $sku,
                sku_related => $related_sku,
                type        => 'related',
            },
            {
                key => 'merchandising_products_sku_sku_related_type'
            }
        );
    }
}

=head2 process_orvis_product

twig handler for Orvis xml Product

=cut

sub process_orvis_product {
    my ( $t, $xml ) = @_;

    info "processed $count products" unless ( ++$count % 100 );

    # get attribute 'option'

    my $option_attribute = $schema->resultset('Attribute')->find_or_create(
        {
            name  => 'option',
            title => 'Option',
            type  => 'variant',
        },
        {
            key => 'attributes_name_type'
        }
    );

    my $pf_id = $xml->first_child('PF_ID')->text;

    # should we skip this item due to freight charges?

    if (   $xml->first_child('Freight_From_Price_Flag')->text * 1 > 0
        || $xml->first_child('Min_Freight')->text * 1 > 0
        || $xml->first_child('Max_Freight')->text * 1 > 0 )
    {
        debug "skipping $pf_id due to freight charge";
        return;
    }

    # do we skip due to category?

    if (
        $xml->first_child('Dir_Name')->text eq 'Distinctive Home'
        || (
            $xml->first_child('Dir_Name')->text eq 'Sale Outlet'
            && (   $xml->first_child('Group_Name')->text eq 'Distinctive Home'
                || $xml->first_child('Cat_Name')->text =~ /Home/ )
        )
        || $xml->first_child('Group_Name')->text eq 'Gift Card'
        || $xml->first_child('Cat_Name')->text eq 'Gift Card'
        || $xml->first_child('Dir_Name')->text =~ /School/i
      )
    {
        debug "skipping $pf_id due to category";
        return;
    }

    # grab Product info

    my $description =
      decode_entities( $xml->first_child('PF_Description')->text );

    my $short_description = &short_description($description);

    my $keywords = decode_entities( $xml->first_child('Keywords')->text );

    my $pf_name = clean_name( $xml->first_child('PF_Name')->text );
    my $name    = "Orvis $pf_name";

    my $sku = "WB-OR-" . $pf_id;

    my $uri = lc( unidecode("$name-$pf_id") );
    $uri =~ s/\s+/-/g;
    $uri =~ s/\//-/g;

    my $product = shop_product->find_or_create(
        {
            sku               => $sku,
            manufacturer_sku  => $pf_id,
            name              => $name,
            short_description => $short_description,
            description       => $description,
            uri               => $uri,
            active            => $active,
        },
        {
            key => 'primary'
        }
    );

    # download image

    my $http = HTTP::Tiny->new();

  TAG: foreach my $tag (qw/ LargeImageURL ImageURL /) {

        if ( my $elt = $xml->first_child($tag) ) {

            # found the tag

            if ( my $url = $elt->text ) {

                # should have an image URL

                ( my $file = $url ) =~ s/^.+\///;
                my $path = File::Spec->catfile( $original_files, $file );

                # get image if it doesn't already exist

                unless ( -r $path ) {
                    my $response = $http->mirror( $url, $path );
                    sleep rand(0.5);    # don't hit them too hard
                    unless ( $response->{success} ) {
                        error "failed to get $url: " . $response->{reason};
                        next TAG;
                    }
                }

                last TAG if &process_image( $product, $path );
            }
        }
    }

    # add navigation for this canonical product

    my $navigator = $xml->first_child('Navigator');

    if ($navigator) {
        my $first_child = $navigator->first_child;
        my $text        = $first_child->text;
        my $uri         = lc( unidecode($text) );
        $uri =~ s/\s+/-/g;

        my $nav = $schema->resultset('Navigation')->find_or_create(
            {
                uri   => $uri,
                type  => 'nav',
                scope => 'menu-main',
                name  => $text,
            },
            {
                key => 'navigations_uri',
            }
        );

        foreach my $sibling ( $first_child->siblings ) {

            my $text = $sibling->text;
            $uri .= "/" . lc($text);
            $uri =~ s/\s+/-/g;

            $nav = $schema->resultset('Navigation')->find_or_create(
                {
                    uri       => $uri,
                    type      => 'manufacturer',
                    scope     => 'orvis',
                    name      => $text,
                    parent_id => $nav->id,
                },
                {
                    key => 'navigations_uri',
                }
            );
        }

        # add product to the final nav

        $schema->resultset('NavigationProduct')->find_or_create(
            {
                navigation_id => $nav->id,
                sku           => $product->sku
            }
        );
    }
    else {
        warning unidecode("no Navigator element for $sku $name\n");
    }

    # Product->Item

    my @items = $xml->children('Item');

    if ( scalar @items == 1 ) {

        # simple situation with just one Item entry for this product

        my $item = $items[0];

        # Product->Item->Sku

        my @skus = $item->children('Sku');

        if ( scalar @skus == 1 ) {

            # a simple canonical product with no variants

            if ( $product->variants->has_rows ) {

                # but we have variants in the DB so remove them

                debug unidecode("removing old variants for $sku $name\n");

                $product->variants->delete;
            }
            else {
                $product->price( $skus[0]->first_child('Regular_Price')->text );
                $product->update;
                debug unidecode("product with no variants $sku $name\n");
            }
        }
        else {

            # we have variants

            my @options = $item->children('Option');

            # make sure we have all variant attributes in DB and collect
            # attribute names along the way for use later

            my @option_names;
            foreach my $option (@options) {

                my $option_name =
                  lc( $option->first_child('OptionName')->text );

                my $title = ucfirst($option_name);

                $option_name =~ s/\s+/_/g;

                $title = "Line weight"   if $option_name eq "line_weigh";
                $title = "Magnification" if $option_name eq "magnificat";

                push @option_names, $option_name;

                my @option_values =
                  split( /,/, $option->first_child('OptionValue')->text );

                &add_attribute_values( $option_name, $title, @option_values );
            }

            # process each variant

          SKU: foreach my $sku (@skus) {

                my $pf_id     = $xml->first_child('PF_ID')->text;
                my $item_code = $sku->first_child('Item_Code')->text;

                my $sku_name =
                  clean_name( $sku->first_child('Sku_Name')->text );
                unless ( $sku_name =~ /^\Q$pf_name\E/ ) {
                    $sku_name = "$pf_name $sku_name";
                }
                $sku_name = "Orvis $sku_name";

                # prefix sku with WB-OR-
                my $variant_sku = "WB-OR-" . $pf_id . "-" . $item_code;

                my $regular_price = $sku->first_child('Regular_Price')->text;

                my $uri = lc( unidecode("${sku_name}-${pf_id}${item_code}") );
                $uri =~ s/\s+/-/g;
                $uri =~ s/\//-/g;

                my $variant = shop_product->find(
                    {
                        sku => $variant_sku,
                    }
                );

                if ($variant) {

                    # we already have this variant so just update price
                    $variant->price($regular_price);
                    $variant->update;
                }
                else {

                    # a new variant

                    debug unidecode("new variant $variant_sku $sku_name\n");

                    my %attributes = (
                        sku              => $variant_sku,
                        manufacturer_sku => $pf_id . "-" . $item_code,
                        name             => $sku_name,
                        price            => $regular_price,
                        uri              => $uri,
                    );

                    my @option_string =
                      split( /,/, $sku->first_child('Option_String')->text );

                    foreach my $i ( 0 .. $#option_names ) {
                        $attributes{ &clean_attribute_value( $option_names[$i] )
                        } = &clean_attribute_value( $option_string[$i] );
                    }

                    # we add one variant at a time since orvis have a small
                    # number of conflicting variants (different sku but same
                    # attribute options)
                    try {
                        $product->add_variants( ( \%attributes ) );
                        $variant = $product->related_resultset('variants')
                          ->find($variant_sku);
                        unless ($variant) {
                            warning "adding variant $variant_sku failed";
                        }
                    }
                    catch {
                        warning $_;
                    };
                }
                next SKU unless $variant;

                # images

                my %images;
                foreach my $SkuImage ( $sku->descendants('SkuImage') ) {

                    my $Label_Name =
                      $SkuImage->first_child_trimmed_text('Label_Name');

                    next
                      if $Label_Name !~ /^((large|alt|product)\s+(image|view))/;

                    $images{$1} =
                      $SkuImage->first_child_trimmed_text('Image_URL');
                }

                delete $images{"product image"} if $images{"large view"};

                foreach my $url ( values %images ) {

                    ( my $file = $url ) =~ s/^.+\///;
                    my $path = File::Spec->catfile( $original_files, $file );

                    # get image if it doesn't already exist

                    unless ( -r $path ) {
                        my $http = HTTP::Tiny->new();
                        my $response = $http->mirror( $url, $path );
                        sleep rand(0.5);    # don't hit them too hard
                        unless ( $response->{success} ) {
                            error "failed to get $url: " . $response->{reason};
                        }
                    }

                    &process_image( $variant, $path ) if -r $path;
                }
            }
        }
    }
    else {

        # multiple items can mean multiple canonical products or one
        # canonical product with multiple variants

        debug unidecode("product with multiple items $sku $name\n");

        foreach my $item (@items) {

            my $item_name = clean_name( $item->first_child('Item_Name')->text );

            my @options = $item->children('Option');

            my @all_option_values;
            my @option_names;
            foreach my $option (@options) {

                my $option_name =
                  lc( $option->first_child('OptionName')->text );

                my $title = ucfirst($option_name);

                $option_name =~ s/\s+/_/g;

                $title = "Line weight"   if $option_name eq "line_weigh";
                $title = "Magnification" if $option_name eq "magnificat";

                push @option_names, $option_name;

                my @option_values =
                  split( /,/, $option->first_child('OptionValue')->text );

                push @all_option_values, @option_values;

                &add_attribute_values( $option_name, $title, @option_values );
            }

            if ( !grep { lc($_) eq lc($item_name) } @all_option_values ) {

                # Item_Name is not in option_values so it needs to
                # be added as extra variant option

                push @option_names, 'option';

                &add_attribute_values( 'option', 'Option',
                    &clean_attribute_value($item_name) );
            }

            my @skus = $item->children('Sku');

          SKU: foreach my $sku (@skus) {

                my $item_code = $sku->first_child('Item_Code')->text;

                my $sku_name =
                  clean_name( $sku->first_child('Sku_Name')->text );
                unless ( $sku_name =~ /^\Q$pf_name\E/ ) {
                    $sku_name = "$pf_name $sku_name";
                }
                $sku_name = "Orvis $sku_name";

                my $variant_sku = "WB-OR-" . $item_code;

                my $price = $sku->first_child('Regular_Price')->text;

                my $uri = lc( unidecode("${sku_name}-${pf_id}${item_code}") );
                $uri =~ s/\s+/-/g;
                $uri =~ s/\//-/g;

                my $variant = shop_product->find(
                    {
                        sku => $variant_sku,
                    }
                );

                if ($variant) {

                    # we already have this variant so just update price
                    $variant->price($price);
                    $variant->update;
                }
                else {

                    # a new variant

                    my %attributes = (
                        sku              => $variant_sku,
                        manufacturer_sku => $item_code,
                        name             => $sku_name,
                        price            => $price,
                        uri              => $uri,
                    );

                    my @option_string =
                      split( /,/, $sku->first_child('Option_String')->text );

                    if (   @option_names
                        && $option_names[$#option_names] eq 'option' )
                    {
                        push @option_string, &clean_attribute_value($item_name);
                    }

                    foreach my $i ( 0 .. $#option_names ) {
                        $attributes{ &clean_attribute_value( $option_names[$i] )
                        } = &clean_attribute_value( $option_string[$i] );
                    }

                    try {
                        $product->add_variants( ( \%attributes ) );
                        $variant = $product->related_resultset('variants')
                          ->find($variant_sku);
                        unless ($variant) {
                            warning "adding variant $variant_sku failed";
                        }
                    }
                    catch {
                        warning $_;
                    };
                }
                next SKU unless $variant;

                # images

                my %images;
                foreach my $SkuImage ( $sku->descendants('SkuImage') ) {

                    my $Label_Name =
                      $SkuImage->first_child_trimmed_text('Label_Name');

                    next
                      if $Label_Name !~ /^((large|alt|product)\s+(image|view))/;

                    $images{$1} =
                      $SkuImage->first_child_trimmed_text('Image_URL');
                }

                delete $images{"product image"} if $images{"large view"};

                foreach my $url ( values %images ) {

                    ( my $file = $url ) =~ s/^.+\///;
                    my $path = File::Spec->catfile( $original_files, $file );

                    # get image if it doesn't already exist

                    unless ( -r $path ) {
                        my $http = HTTP::Tiny->new();
                        my $response = $http->mirror( $url, $path );
                        sleep rand(0.5);    # don't hit them too hard
                        unless ( $response->{success} ) {
                            error "failed to get $url: " . $response->{reason};
                        }
                    }

                    &process_image( $variant, $path ) if -r $path;
                }
            }
        }
    }
    $xml->purge;
}

__END__

=head1 NAME

importer.pl - Import manufacturer products lists into Angler

=head1 SYNOPSIS

inporter.pl [options]

 Options:
  -a | --active             set active to 't' for all items (defaults to 'f')
  -f | --file               file to import
  -m | --manufacturer       manufacturer name
  -h | --help               help message

=cut
