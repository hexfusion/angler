package Angler::Interchange6::Schema::Result::Product;

use strict;
use warnings;

use base 'DBIx::Class::Schema';

use Interchange6::Schema::Result::Product;

=head1 METHODS

=cut

=head2 add_columns

the following fields will be added to the schema on deploy

=cut

Interchange6::Schema::Result::Product->add_columns(
    manufacturer_sku => {
        data_type => 'varchar',
        size      => 64,
    },
);

=head2 details

=cut

*Interchange6::Schema::Result::Product::details = sub {
    return "location.href=('/" . shift->uri . "')";
};

=head2 image

=cut

*Interchange6::Schema::Result::Product::image = sub {
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
};

=head2 image_NNNxNNN

=cut

*Interchange6::Schema::Result::Product::image_35x35 = sub {
    return shift->image("35x35");
};
*Interchange6::Schema::Result::Product::image_75x75 = sub {
    return shift->image("75x75");
};
*Interchange6::Schema::Result::Product::image_100x100 = sub {
    return shift->image("100x100");
};
*Interchange6::Schema::Result::Product::image_110x110 = sub {
    return shift->image("110x110");
};
*Interchange6::Schema::Result::Product::image_200x200 = sub {
    return shift->image("200x200");
};
*Interchange6::Schema::Result::Product::image_325x325 = sub {
    return shift->image("325x325");
};
*Interchange6::Schema::Result::Product::image_975x975 = sub {
    return shift->image("975x975");
};
*Interchange6::Schema::Result::Product::no_variants = sub {
    return shift->variant_count ? 0 : 1;
};

1;

