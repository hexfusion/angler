package Angler::Populate::Image;

=head1 NAME

Angler::Populate::Image

=head1 DESCRIPTION

This module provides image population capabilities for the Media class.

=cut

use strict;
use warnings;

use Moo;

use Dancer ':script';
use File::Spec;
use HTTP::Tiny;
use HTML::Entities;
use File::Path;
use Imager;

set logger => 'console';
set log => 'info';

=head1 DESCRIPTION

This module helps with downloading populating and formatting image assets
for the Media and MediaProduct classes.

=head1 SYNOPSIS

my $product = shop_product->find({ sku => 'WBA2001'});
my $url = 'http://placehold.it/950x400.jpg';
my $img_dir = '/home/camp/angler/rsync/htdocs/assetstore/site/images/items';
my $manufacturer = 'simms';

my $image = Angler::Populate::Image->new(
    schema => shop_schema,
    url   => $url,
    manufacturer  => $manufacturer,
    product =>  $product,
    img_dir => $img_dir
);

$image->download;
$image->process;

=cut

=head2 url

Returns url of image

=cut

has url => (
    is => 'rw',
    required => 1,
);

=head2 img_dir

Returns the full system path for the image dir

=cut

has img_dir => (
    is => 'ro',
    required => 1,
);

=head2 original_files

Returns full system path for image file

=cut

has original_files => (
    is => 'lazy'
);

=head2 manufacturer

Returns the product manufacturer

=cut

has manufacturer => (
    is => 'ro',
    required => 1,
);

=head2 schema
+
L<Interchange6::Schema> object.
+
=cut
+
has schema => (
    is => 'rw',
    required => 1,
);

=head2 product
+
L<Interchange6::Schema::Result::Product> object.
+
=cut
+
has product => (
    is => 'rw',
    required => 1,
);

=head2 file_path

Returns file path

=cut

has file_path => (
    is => 'lazy',
);

sub _build_file_path {
    my ($self) = @_;
    ( my $file = $self->url ) =~ s/^.+\///;
    return File::Spec->catfile( $self->original_files, $file );
}

sub _build_original_files {
    my ($self) = @_;
    my $original_files =
      File::Spec->catdir( $self->img_dir, "original_files", $self->manufacturer );
    File::Path->make_path($original_files);
    return $original_files;
}

=head2 download

=cut

sub download {
    my ( $self ) = @_;

    # download image
    my $http = HTTP::Tiny->new();

    # get image if it doesn't already exist
    unless ( -r $self->file_path ) {
        my $response = $http->mirror( $self->url, $self->file_path );
        sleep rand(0.5); # don't hit them too hard
        unless ( $response->{success} ) {
            warning "failed to get $self->url: " . $response->{reason};
        }
    }
}

=head2 process;

Returns true on success.

=cut

sub process {
    my ( $self ) = @_;
    my @img_sizes = qw/35 75 100 110 200 325 975/;

    my $file = [ File::Spec->splitpath($self->file_path) ]->[2];

    ( my $ext = lc($file) ) =~ s/^.+\.//;
    $ext =~ s/\s+$//;

    my $sku = $self->product->sku;

    SIZE: foreach my $size (@img_sizes) {

        my $dir =
          File::Spec->catdir( $self->img_dir, "${size}x${size}", $self->manufacturer );

        File::Path->make_path($dir);

        my $new_path = File::Spec->catfile( $dir, $file );

        unless ( -r $new_path ) {

            # we don't have this image yet so create it

            my $img = Imager->new( file => $self->file_path );

            unless ($img) {
                error "Imager read failed for $sku $self->file_path " . Imager->errstr;
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
                  . "x$size on $sku $self->file_path: "
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

        my $mediatype_image =
          $self->schema->resultset('MediaType')->find( { type => 'image' } );
        die "MediaType type image not found" unless $mediatype_image;

        # make sure we've got the database entry for this image

        my $media = $self->schema->resultset('Media')->find_or_create(
            {
                file           => $self->file_path,
                uri            => File::Spec->catfile( $self->manufacturer, $file ),
                mime_type      => "image/$ext",
                media_types_id => $mediatype_image->id,
            },
            {
                key => 'medias_file',
            }
        );
        $self->schema->resultset('MediaProduct')->find_or_create(
            {
                media_id => $media->id,
                sku      => $sku,
            }
        );
    }
    return 1;
}


1;
