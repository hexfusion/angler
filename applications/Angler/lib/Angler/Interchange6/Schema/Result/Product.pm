package Angler::Interchange6::Schema::Result::Product;

use strict;
use warnings;

use base 'DBIx::Class::Schema';

use Interchange6::Schema::Result::Product;
package Interchange6::Schema::Result::Product;

=head1 NAME

Angler::Interchange6::Schema::Result::Product

=head1 DESCRIPTION

Adds extra columns and methods to L<Interchange6::Schema::Result::Product>.

=head1 ACCESSORS

=head2 manufacturer_sku

=cut

__PACKAGE__->add_columns(
    manufacturer_sku => {
        data_type => 'varchar',
        size      => 64,
    },
    cost => {
        data_type     => "numeric",
        default_value => "0.0",
        size          => [ 10, 2 ]
    },
);

=head1 METHODS

=head2 details

Returns javascript to change location href to uri of product.

=cut

sub details {
    return "location.href=('/" . shift->uri . "')";
}

=head2 image( $size )

Returns uri of a C<$size> image for the product. Size defaults to 325x325.

=cut

sub image {
    my ( $self, $size ) = @_;

    $size = "325x325" unless $size;

    my $schema = $self->result_source->schema;

    my $image = $self->media_products->search_related(
        'media',
        {
            'media_type.type' => 'image',
        },
        {
            join => 'media_type',
            rows => 1
        }
    )->single;

    if ( !$image && $self->canonical_sku ) {

        # no image for variant so try parent product
        $image = $self->media_products->search_related(
            'media',
            {
                'media_type.type' => 'image',
            },
            {
                join => 'media_type',
                rows => 1
            }
        )->single;
    }

    # fallback to default image
    # FIXME this should come from config.
    $image = $schema->resultset('Media')->find( { uri => 'default.jpg' } )
      unless $image;

    if ( $image ) {
        return $image->display_uri("product_$size");
    }
    else {
        return undef;
    }
}

=head2 image_NNNxNNN

=cut

sub image_35x35 {
    return shift->image("35x35");
}
sub image_75x75 {
    return shift->image("75x75");
}
sub image_100x100 {
    return shift->image("100x100");
}
sub image_110x110 {
    return shift->image("110x110");
}
sub image_200x200 {
    return shift->image("200x200");
}
sub image_325x325 {
    return shift->image("325x325");
}
sub image_975x975 {
    return shift->image("975x975");
}
sub no_variants {
    return shift->variant_count ? 0 : 1;
}

1;

