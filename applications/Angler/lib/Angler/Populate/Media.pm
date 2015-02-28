package Angler::Populate::Media;

=head1 NAME

Angler::Populate::Media

=head1 DESCRIPTION

This module provides population capabilities for the Media class.

=cut

use strict;
use warnings;

use File::Spec;
use HTTP::Tiny;
use HTML::Entities;
use Imager;
use Data::Dumper::Concise;

set logger => 'console';
set log    => 'info';

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
            warning "failed to get $url: " . $response->{reason};
        }
    }
    return $path
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

=head2 add_video($product, $url)

=cut

sub add_video {
    my ($product, $url) = @_;

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
    print "video added for " . $product->sku . "\n";
    return 1;
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

=head2 find_image_mediatype

returns the MediaType image object

=cut

sub find_image_mediatype_id {

    my $image_mediatype = $schema->resultset('MediaType')->find( { type => 'image' } );
    die "MediaType type image not found" unless $mediatype_image;

    return $image_mediatype->id
}

=head2 find_video_mediatype

returns the MediaType video object

=cut

sub find_video_mediatype_id {

    my $image_mediatype = $schema->resultset('MediaType')->find( { type => 'image' } );
    die "MediaType type image not found" unless $mediatype_image;

    return $image_mediatype->id
}

1;
