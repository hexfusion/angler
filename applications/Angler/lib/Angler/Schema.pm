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

*Interchange6::Schema::Result::Product::image = sub {
    my $self = shift;

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

    if ( my $uri = $image->display_uri('product_200x200') ) {
        return $uri;
    }
    else {
        return undef;
    }
};

*Interchange6::Schema::Result::Product::details = sub {
    return "location.href=('/" . shift->uri . "')";
};

1;
