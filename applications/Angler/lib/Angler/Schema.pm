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

1;
