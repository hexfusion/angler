#!/usr/bin/env perl

use warnings;
use strict;
use v5.14;

package Product;

use Moo;

package main;

use Dancer ':script';
use Dancer::Plugin::DBIC;
use Dancer::Plugin::Interchange6;
use File::Basename;
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
my $drone_schema = schema('drone');

my $img_dir = "/srv/www/static/westbranchangler.com/images/products";
if ( File::Spec->rel2abs( basename $0) !~ m(^/home/camp) ) {
    # dev
    $img_dir = "/home/camp/angler/rsync/htdocs/assetstore/site/images/items";
}

my @img_sizes = qw/35 75 100 110 200 325 975/;

my ( $active, $file, $help, $manufacturer, %nav_lookup, %inventory, %navs );


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

        $navs{clothing} =
          shop_navigation->find( { uri => "clothing/orvis" } );

        $navs{manufacturer} = shop_navigation->find( { uri => "orvis" } );

        # pull in nav data

        my $nav_excel_file =
          File::Spec->catfile( [ File::Spec->splitpath($0) ]->[1],
            '..', 'shared', 'data', 'orvis_nav.xlsx' );

        my $parser = Spreadsheet::ParseXLSX->new;

        # parse the file

        my $workbook = $parser->parse($nav_excel_file);
        if ( !defined $workbook ) {
            die $parser->error(), ".\n";
        }

        # we need at least one worksheet

        die "no worksheets found" unless $workbook->worksheet_count;

        my $worksheet = $workbook->worksheet(0);

        die "worksheet not found" unless $worksheet;

        # col/row ranges in use

        my ( $row_min, $row_max ) = $worksheet->row_range();

        foreach my $row ( $row_min .. $row_max ) {
            my $col4 = $worksheet->get_cell( $row, 4 );
            next unless $col4;
            my $nav = $col4->value;
            next unless $nav;
            my @row;
            foreach my $col ( 0 .. 3 ) {
                push @row, $worksheet->get_cell( $row, $col )->value;
            }
            $nav_lookup{ join( "_", @row ) } = $nav;
        }

        my $inventory_report = 
          File::Spec->catfile( [ File::Spec->splitpath($0) ]->[1],
            '..', 'shared', 'data', 'OrvisInventoryReport.xls' );

        $parser = Spreadsheet::ParseExcel->new;

        # parse the file

        $workbook = $parser->parse($inventory_report);
        if ( !defined $workbook ) {
            die $parser->error(), ".\n";
        }

        # we need at least one worksheet

        die "no worksheets found" unless $workbook->worksheet_count;

        $worksheet = $workbook->worksheet(0);

        die "worksheet not found" unless $worksheet;

        # col/row ranges in use

        ( $row_min, $row_max ) = $worksheet->row_range();
        my ( $col_min, $col_max ) = $worksheet->col_range();

        # find columns we are interested in
        my ( $item_col, $inv_col, $sku_col );

        foreach my $col ( $col_min .. $col_max ) {
            my $value = $worksheet->get_cell( $row_min, $col )->value;
            if ( $value eq 'Item' ) {
                $item_col = $col;
            }
            elsif ( $value eq 'Inventory' ) {
                $inv_col = $col;
            }
            elsif ( $value eq 'SKU' ) {
                $sku_col = $col;
            }
        }
        die "Cannot find required columns in OrvisInventoryReport"
          unless ( $item_col && $inv_col && $sku_col );

        foreach my $row ( $row_min + 1 .. $row_max ) {
            my $sku =
                "WB-OR-"
              . $worksheet->get_cell( $row, $item_col )->value . '-'
              . $worksheet->get_cell( $row, $sku_col )->value;
            my $qty = $worksheet->get_cell( $row, $inv_col )->value;
            $inventory{$sku} = $qty;
        }

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
            twig_handlers => { Cross_Sells => \&process_orvis_cross_sell } );

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

=head2 clean_uri($uri)

Removes junk from potential uri including RFC3986 reserved characters

=cut

sub clean_uri {
    my $uri = shift;
    $uri =~ s/\W+/-/g;  # non-word chars
    $uri =~ s/_+/-/g;   # underscores
    $uri =~ s/\-+/-/g;  # multiple dashes
    return $uri;
}

=head2 decode_url($url)

decode url into $scheme, $authority, $path, $query, $fragment

=cut

sub decode_url {
    my $url = shift;
    my($scheme, $authority, $path, $query, $fragment) =
      $url =~ m|(?:([^:/?#]+):)?(?://([^/?#]*))?([^?#]*)(?:\?([^#]*))?(?:#(.*))?|;

    return ($scheme, $authority, $path, $query, $fragment);
}

=head2 short_description($description)

Tries to shorten a description to fit in short_description varchar(500) column

=cut

sub short_description {
    my $description = shift;

    # nothing todo
    return $description if length($description) <= 500;

    # try to produce something sensible using some of the earlier sentences
    # in from description
    my @desc_sentences = split( /\./, $description );
    my $short_description = shift(@desc_sentences) . '.';
    foreach my $sentence (@desc_sentences) {
        if ( length($short_description) + length($sentence) + 2 <= 500 ) {
            $short_description .= " $sentence.";
        }
        else {
            last;
        }
    }
    if ( length($short_description) > 500 ) {

        # still too long so get a substr
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

    my @color_values;
    my @size_values;


   #FIXME this is a dirty hack and should be done in the loop below
   foreach my $row ( 2 .. $row_max ) {
        my %cells =
          map {
            $headers{$_} => &trim( $worksheet->get_cell( $row, $_ )->value )
          } $col_min .. $col_max;

        # get all attribute values
        if ($cells{"Color"}) {
            push @color_values, $cells{"Color"};
        }
        if ($cells{"Size"}) {
            push @size_values, $cells{"Size"};
        }
    }

    # populate all attributes and attribute_values found in file
    &add_attribute_values( 'color', 'Color', @color_values );
    &add_attribute_values( 'size', 'Size', @size_values );


    my @variants;

    my ( $sku, $code, $features, $full_name, $price, $color, $size, $manf_sku,
      $name, $short_description, $description, $keywords, $active, $data,
      $technologies );
    foreach my $row ( 2 .. $row_max ) {
        my %cells =
          map {
            $headers{$_} => &trim( $worksheet->get_cell( $row, $_ )->value )
          } $col_min .. $col_max;

        # add canonical
        if ( $code && $cells{"Product Group Code"} ne $code ) {
            &insert_canonical_product( $data );
            undef $data;
        }
        # these are variants lets save them for later
        elsif ($data) {

            $data->{'variant'} = '1';

            #FIXME this can be done by assigning in $data
            if ($data->{'color'}) {
                $data->{'attributes'}->{color} = &clean_attribute_value($data->{'color'});
                $data->{'name'} = "$data->{'name'} $data->{color}";
                delete $data->{'color'};
            }
            if ($data->{'size'}) {
                $data->{'attributes'}->{size} =  &clean_attribute_value($data->{'size'});
                $data->{'name'} = "$data->{'name'} $data->{size}";
                delete $data->{'size'};
            }

            push @variants, $data;

           # print "creating variants $data->{'sku'}\n";
           undef $data;
        }

        # define headers
        $code = $cells{"Product Group Code"};
        $manf_sku = $cells{"SKU"};
        $name = $cells{"Product Name - Display"};
        $price = $cells{"SRP"};
        $color = $cells{"Color"};
        $size = $cells{"Size"};
 
        $data = (
        {
            code              => $code,
            manufacturer_code => 'SF',
            manufacturer_sku  => $manf_sku,
            name              => $name,
            price             => $price,
            short_description => $short_description,
            description       => $description,
            technologies      => $technologies,
            features          => $features,
            keywords          => $keywords,
            season            => 'S15',
            active            => $active,
            color             => $color,
            size              => $size
        },
        );
    }

    # now lets create variants
    foreach (@variants) {
        &create_variant($_);
    }
}

=head2 add_to_nav_by_uri( $uri, $product_obj )

Find nav with $uri and find or create NavigationProduct entry

=cut

sub add_to_nav_by_uri {
    my ( $uri, $product ) = @_;

    my $nav = $schema->resultset('Navigation')->find( { uri => $uri } );
    if ( not $nav ) {
        die "uri $uri not found in Navigation for: " . $product->name;
    }

    $nav->find_or_create_related( 'navigation_products',
        { sku => $product->sku } );

    # make sure the leaf nav has higher priority than parents

    $nav->update( { priority => 100 } ) unless $nav->priority;

    my @ancestors = $nav->ancestors;

    if ( @ancestors && $ancestors[-1]->uri eq 'clothing' ) {

        # clothing by brand
        $product->find_or_create_related(
            'navigation_products',
            {
                navigation_id => $navs{clothing}->id
            }
        );
    }

    # manufacturer
    $product->find_or_create_related(
        'navigation_products',
        {
            navigation_id => $navs{manufacturer}->id
        }
    );

    return $nav;
}

=head2 create_variants

=cut

sub create_variant {
    my ( $data ) = @_;

    # format product
    $data = &format_product($data);

    print "creating variant $data->{'sku'}\n";

    my $product = $schema->resultset('Product')->find( { sku => $data->{'canonical_sku'} } );

    # canonical product should have 0 for price.
    if ($product->price ne '0.00') {
        $product->update({ price => '0.00'});
    }

   $product->add_variants(
    {
        sku    => $data->{sku},
        name   => $data->{name},
        uri    => $data->{uri},
        price  => $data->{price},
        attributes => $data->{attributes},
     });

    # add manufacturer as default nav route
    &set_manufacturer_navigation($data->{sku});
}

=head2 format_product( $data );

input product or variant data and this filter will return clean data
ready for db insert.

=cut

sub format_product {
    my ( $data ) = @_;

    # step through non default data checks
    if ($data->{'description'}) {
         $data->{'short_description'} = &short_description($data->{'description'});
    }
    if ($data->{'keywords'}) {
        $data->{'keywords'} = decode_entities($data->{'keywords'});
    }

    $data->{'name'} = clean_name($data->{'name'});
    $data->{'sku'} = 'WB-' . $data->{'manufacturer_code'} . '-' . $data->{'code'};
    $data->{uri} = &clean_uri(lc( unidecode("$data->{'name'} $data->{'code'}") ));

    # if this a variant do a few extra steps
    if ($data->{'variant'}) {
        $data->{'canonical_sku'} = 'WB-' . $data->{'manufacturer_code'} . '-' . $data->{code};
        $data->{'uri'} = &clean_uri(lc( unidecode("$data->{name}") ));
        $data->{'sku'} = 'WB-' . $data->{'manufacturer_code'} . '-' . $data->{'manufacturer_sku'};
    }
    else {
        $data->{'manufacturer_sku'} = $data->{'code'};
    }

    $data->{uri} =~ s/\s+/-/g;
    $data->{uri} =~ s/\//-/g;

    return $data;
}

=head2 add_drone_data( $data )

if the manufacturer has drone data it will be added to $data

=cut

sub add_drone_data {
    my ( $data ) = @_;

    # simms drone data is linked to manufacturer_sku
    my $drone_product = $drone_schema->resultset('SimmsProduct')->find(
                        {sku => $data->{'manufacturer_sku'} });    

    # lets check the drone for an image
    if (defined($drone_product) and $drone_product->img) {
        # download and add image to media.
        my $path = &download_images($drone_product->img);
        if ($path) {
            &process_image($data->product, $path);
        }
    }
}


sub insert_canonical_product {
    my ( $data ) = @_;

    my $drone_product = $drone_schema->resultset('SimmsProduct')->find(
                        {sku => $data->{'code'} });

    if (defined($drone_product) and $drone_product->description) {
        print "found description\n";
        $data->{'description'} = $drone_product->description;
        if ($drone_product->features) {
            $data->{'description'} = $drone_product->description . " <br><hr><h3>Features</h3> ". $drone_product->features;
        }
    }

    #print "description: ", $data->{'description'};

    # format data
    $data = &format_product($data);

    print "creating product $data->{'sku'}\n";

    my $product = $schema->resultset('Product')->find_or_create(
        {
            sku => $data->{'sku'},
            manufacturer_sku => $data->{'manufacturer_sku'},
            name => $data->{'name'},
            short_description => $data->{'short_description'} ||'',
            description => $data->{'description'} ||'',
            price => $data->{'price'},
            uri => $data->{'uri'},
            weight => '1',
            inventory_exempt => '1',
        }
    );

    # lets check the drone for an image
    if (defined($drone_product) and $drone_product->img) {
        # download and add image to media.
        my $path = &download_images($drone_product->img);
        if ($path) {
            &process_image($product, $path);
        }
    }

    if (defined($drone_product) and $drone_product->videos) {
        # could be a few videos comma delimited
        my @videos = split(/,/, $drone_product->videos);

        foreach (@videos) {
            &process_video($product, $_);
        }
    }

    # add manufacturer as default nav route
    &set_manufacturer_navigation($data->{sku});

}

=head2 download_images($url)

=cut

sub download_images {
    my ( $url ) = @_;

    # download image
    my $http = HTTP::Tiny->new();
    ( my $file = $url ) =~ s/^.+\///;
    my $path = File::Spec->catfile( $original_files, $file );

    # get image if it doesn't already exist
    unless ( -r $path ) {
        my $response = $http->mirror( $url, $path );
        sleep rand(0.5); # don't hit them too hard
        unless ( $response->{success} ) {
            debug "failed to get $url: " . $response->{reason};
        }
    }
    return $path
}

=head2 set_manufacturer_navigation($sku)

gives the product a default navigation route defining the manufacturer.

=cut

sub set_manufacturer_navigation {
    my ( $sku ) = @_;

    my $nav = $schema->resultset('Navigation')->find({ uri => $manufacturer});

    if ($nav) {
        $schema->resultset('NavigationProduct')->find_or_create(
            {
                sku => $sku,
                navigation_id => $nav->id
            }
        );
    }
    else {
        die "no manufacturer route populated in Navigation";
    }
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

=head2 process_video($product, $url)

=cut

sub process_video {
    my ($product, $url) = @_;

   # info "process video url ", $url;

    #FIXME hack alert
   my (undef, $authority, $path, undef, undef) = decode_url($url);


    if ( $authority and  $path ) {

        # format video url
         $url = "https://" . $authority . $path;

        my $mediatype_video =
          $schema->resultset('MediaType')->find( { type => 'video' } );
        die "MediaType type video not found" unless $mediatype_video;

        my $media = $schema->resultset('Media')->find_or_create(
            {
                file => $url,
                uri => $url,
                media_types_id => $mediatype_video->id,
            },
            {
                key => 'medias_file',
            }
        );
        $schema->resultset('MediaProduct')->find_or_create(
            {
                media_id => $media->id,
                sku => $product->sku,
            }
        );
    }
        info "video added for ", $product->sku;
}
=head2 process_orvis_cross_sell

twig handler for Orvis xml Cross_Sell

=cut

sub process_orvis_cross_sell {
    my ( $t, $xml ) = @_;

    info "processed $count cross_sells" unless ( ++$count % 100 );

    my $sku = "WB-OR-" . $xml->{'att'}->{'PF_ID'};
    my $product = shop_product($sku);
    return unless $product;

    foreach my $Cross_Sell ( $xml->children ) {
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

=head2 set_orvis_lead_time( $product, $sku )

=cut

sub set_orvis_lead_time {
    my ( $product, $sku ) = @_;

    my $min = my $max = my $stock = 0;

    # supplier lead time

    if ( my $Avail_Status = $sku->first_child_trimmed_text('Avail_Status') ) {
        if ( $Avail_Status eq 'Y' ) {
            $min = 3;
            $max = 5;
        }
        elsif ( $Avail_Status eq 'D' ) {
            if ( my $Avail_Date = $sku->first_child_trimmed_text('Avail_Date') )
            {
                if ( $Avail_Date =~ /(\d+)\s+WEEK/ ) {
                    $min = 7 * ( $1 + 1 );
                    $max = 7 * ( $1 + 2 );
                }
            }
        }
        elsif ( grep { $Avail_Status eq $_ } (qw/ A N O W /) ) {

            # ignore these
        }
        else {
            warning "Avail_Status '$Avail_Status' for: ", $product->sku;
        }
    }
    else {
        warning "No Avail_Status for: ", $product->sku;
    }

    # supplier stock

    if ( $inventory{ $product->sku } ) {
        $stock = $inventory{ $product->sku };
    }
    else {

        # stock not found in inventory report so check xml
        if ( my $Estimated_Quantity =
            $sku->first_child_trimmed_text('Estimated_Quantity') )
        {
            $stock = $Estimated_Quantity if $Estimated_Quantity =~ /^\d+$/;
        }
    }
    if ( $stock ) {

        # manufacturer has stock so nice short lead time
        $min = 3;
        $max = 5;
    }

    debug "sku " . $product->sku . " min $min max $max stock $stock";

    # now update/insert

    if ($product->inventory) {
        if ( $product->inventory->quantity == 0 && $max == 0 ) {
            $product->inventory->delete;
            $product->update({ active => 0 });
            return;
        }
        $product->inventory->update(
            {
                lead_time_min_days    => $min,
                lead_time_max_days    => $max,
                manufacturer_quantity => $stock,
            }
        );
    }
    else {
        if ( $max == 0 ) {
            $product->update({ active => 0 });
            return;
        }
        $product->create_related(
            'inventory',
            {
                quantity              => 0,
                lead_time_min_days    => $min,
                lead_time_max_days    => $max,
                manufacturer_quantity => $stock,
            }
        );
    }
}

=head2 process_orvis_product

twig handler for Orvis xml Product

=cut

sub process_orvis_product {
    my ( $t, $xml ) = @_;

    my $do_not_add = 0;

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
        $do_not_add = 1;
    }

    # do we skip due to lack of navigation entry?

    my $nav_uri =
      $nav_lookup{
        join( "_", map { $_->text } $xml->first_child('Navigator')->children )
      };

    if ( !$nav_uri ) {
        debug "skipping $pf_id due to lack of navigation entry";
        $do_not_add = 1;
    }

    # grab Product info

    my $description =
      decode_entities( $xml->first_child('PF_Description')->text );

    my $short_description = &short_description($description);

    my $keywords = decode_entities( $xml->first_child('Keywords')->text );

    my $pf_name = clean_name( $xml->first_child('PF_Name')->text );
    my $name    = "Orvis $pf_name";

    my $sku = "WB-OR-" . $pf_id;

    my $uri = &clean_uri( lc( unidecode("$name-$pf_id") ) );

    my $product = shop_product->find_or_new(
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

    # item's InStock can give us a clue that product might be end of line
    # and so will never be available
    my $InStock = $xml->first_child_trimmed_text('InStock');
    $do_not_add = 1 unless $InStock;

    # handle things we're going to skip because of $do_not_add
    if ( $do_not_add ) {
        if ( $product->in_storage ) {
            # we don't want this product or its variants to be visible
            $product->update( { active => 0 } );
            $product->variants->update_all( { active => 0 } );
        }
        return;
    }

    # product might not yet be stored in database
    $product->insert unless $product->in_storage;

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
                    sleep rand(0.2);    # don't hit them too hard
                    unless ( $response->{success} ) {
                        debug "failed to get $url: " . $response->{reason};
                        next TAG;
                    }
                }

                last TAG if &process_image( $product, $path );
            }
        }
    }

    # add navigation for this canonical product

    my $nav = &add_to_nav_by_uri( $nav_uri, $product );

    # set weight from nav
    $product->update(
        {
            weight => $nav->search_related(
                'navigation_attributes',
                {
                    'attribute.name' => 'weight',
                    'attribute.type' => 'navigation',
                },
                {
                    columns    => [],
                    '+columns' => { weight => 'attribute_value.value' },
                    join       => [
                        'attribute',
                        {
                            navigation_attribute_values => 'attribute_value'
                        },

                    ],
                    rows => 1,
                }
            )->single->get_column('weight')
        }
    );

    # Product->Item

    my @items = $xml->children('Item');

    if ( scalar @items == 1 ) {

        # simple situation with just one Item entry for this product

        my $item = $items[0];

        # Product->Item->Sku

        my @skus = $item->children('Sku');

        if ( scalar @skus == 1 ) {

            # a simple canonical product with no variants

            my $sku = $skus[0];

            if ( $product->variants->has_rows ) {

                # but we have variants in the DB so remove them

                debug unidecode("removing old variants for $sku $name\n");

                $product->variants->delete;
            }
            else {
                $product->price( $sku->first_child('Regular_Price')->text );
                $product->update;
                &set_orvis_lead_time( $product, $sku );
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

                my $uri = &clean_uri(
                    lc( unidecode("${sku_name}-${item_code}") ) );

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
                        manufacturer_sku => $item_code,
                        name             => $sku_name,
                        price            => $regular_price,
                        uri              => $uri,
                        attributes       => {},
                    );

                    my @option_string =
                      split( /,/, $sku->first_child('Option_String')->text );

                    foreach my $i ( 0 .. $#option_names ) {
                        $attributes{attributes}
                          ->{ &clean_attribute_value( $option_names[$i] ) } =
                          &clean_attribute_value( $option_string[$i] );
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

                &set_orvis_lead_time( $variant, $sku );

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
                            debug "failed to get $url: " . $response->{reason};
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

                my $uri = &clean_uri(
                    lc( unidecode("${sku_name}-${pf_id}${item_code}") ) );

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
                        attributes       => {},
                    );

                    my @option_string =
                      split( /,/, $sku->first_child('Option_String')->text );

                    if (   @option_names
                        && $option_names[$#option_names] eq 'option' )
                    {
                        push @option_string, &clean_attribute_value($item_name);
                    }

                    foreach my $i ( 0 .. $#option_names ) {
                        $attributes{attributes}
                          ->{ &clean_attribute_value( $option_names[$i] ) } =
                          &clean_attribute_value( $option_string[$i] );
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

                &set_orvis_lead_time( $variant, $sku );

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

    if ( $product->variants->count ) { 
        # we have variants
        if ( $product->variants->active->count ) {
            # some are active
            $product->update({ active => 1 });
        }
        else {
            $product->update({ active => 0 });
        }
    }
    
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
