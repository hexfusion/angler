package Angler::Schema;

use strict;
use warnings;

use base 'DBIx::Class::Schema';

use Interchange6::Schema::Result::Product;

Interchange6::Schema::Result::Product->add_columns(
    manufacturer_sku => {
        data_type => 'varchar',
        size      => 64,
    },
);

*Interchange6::Schema::Result::Product::details = sub {
    return "location.href=('/" . shift->uri . "')";
};

*Interchange6::Schema::Result::Product::image = sub {
    my ( $self, $size ) = @_;

    $size = "325x325" unless $size;

    my $schema = $self->result_source->schema;

    my $image_type =
      $schema->resultset('MediaType')->find( { type => 'image' } );

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

    # FIXME this should come from config.
    $image = $schema->resultset('Media')->find( { uri => 'default.jpg' } )
      unless $image;

    if ( my $uri = $image->display_uri("product_$size") ) {
        return $uri;
    }
    else {
        return undef;
    }
};
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
