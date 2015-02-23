package Angler::Populate::Video;

use strict;
use warnings;

use Moo;
use Dancer ':script';

set logger => 'console';
set log => 'info';

=head1 NAME

Angler::Populate::Video

=head1 DESCRIPTION

This module helps with populating video assets.

=head1 SYNOPSIS

=cut

=head2 url

Returns url of video 

=cut

has url => (
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
    is => 'ro',
    required => 1,
);

=head2 product
+
L<Interchange6::Schema::Result::Product> object.
+
=cut
+
has product => (
    is => 'ro',
    required => 1,
);

=head2 add

=cut

sub add {
    my ($self) = @_;
    my $schema = $self->schema;

    #FIXME hack alert
    my (undef, $authority, $path, undef, undef) = $self->decode_url;

    if ( $authority and  $path ) {
        # format video url
         my $clean_url = "https://" . $authority . $path;

        my $mediatype_video =
          $schema->resultset('MediaType')->find( { type => 'video' } );
        die "MediaType type video not found" unless $mediatype_video;

        my $media = $schema->resultset('Media')->find_or_create(
            {
                file => $clean_url,
                uri => $clean_url,
                media_types_id => $mediatype_video->id,
            },
            {
                key => 'medias_file',
            }
        );

        $schema->resultset('MediaProduct')->find_or_create(
            {
                media_id => $media->id,
                sku => $self->product->sku,
            }
        );
    }
        info "video added for ", $self->product->sku;
}

=head2 decode_url

decode url into $scheme, $authority, $path, $query, $fragment

=cut

sub decode_url {
    my ($self) = @_;
    my($scheme, $authority, $path, $query, $fragment) =
      $self->url =~ m|(?:([^:/?#]+):)?(?://([^/?#]*))?([^?#]*)(?:\?([^#]*))?(?:#(.*))?|;

    return ($scheme, $authority, $path, $query, $fragment);
}

1;
