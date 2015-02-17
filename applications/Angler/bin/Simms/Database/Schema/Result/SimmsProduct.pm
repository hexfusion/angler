use utf8;
package Database::Schema::Result::SimmsProduct;

use strict;
use warnings;

use base 'DBIx::Class::Core';

__PACKAGE__->table("simms_product");

__PACKAGE__->add_columns(
  "id",
  { data_type => "varchar", is_nullable => 0, size => 90 },  
  "name",
  { data_type => "varchar", is_nullable => 0, size => 90 },  
  "sku",
  { data_type => "varchar", is_nullable => 0, size => 90 },  
  "price",
  { data_type => "float", is_nullable => 0 },  
  "rating",
  { data_type => "int", is_nullable => 0 },  
  "img",
  { data_type => "text", is_nullable => 1},
  "description",
  { data_type => "text", is_nullable => 1},
  "features",
  { data_type => "text", is_nullable => 1},
  "product_care",
  { data_type => "text", is_nullable => 1},  
  "technologies",
  { data_type => "varchar", is_nullable => 1, size => 15 },
  "videos",
  { data_type => "text", is_nullable => 1 },
  "html",
  { data_type => "text", is_nullable => 1 },
  "last_update",
  {
    data_type => "timestamp",
    datetime_undef_if_invalid => 1,
    default_value => \"current_timestamp",
    is_nullable => 1,
  },
);


__PACKAGE__->set_primary_key("id");


1;
