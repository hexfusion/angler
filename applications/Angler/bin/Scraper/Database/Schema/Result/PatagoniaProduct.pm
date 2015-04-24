use utf8;
package Database::Schema::Result::PatagoniaProduct;

use strict;
use warnings;

use base 'DBIx::Class::Core';

__PACKAGE__->table("patagonia_product");

__PACKAGE__->add_columns(
  "sku",
  { data_type => "varchar", is_nullable => 0, size => 45 },  
  "id",
  { data_type => "varchar", is_nullable => 1, size => 45 },  
  "size",
  { data_type => "varchar", is_nullable => 1, size => 45 },  
  "color",
  { data_type => "varchar", is_nullable => 1, size => 45 },  
  "color_name",
  { data_type => "varchar", is_nullable => 1, size => 45 },  
  "path",
  { data_type => "varchar", is_nullable => 1, size => 90 },  
  "category_name",
  { data_type => "varchar", is_nullable => 1, size => 90 },  
  "category_id",
  { data_type => "varchar", is_nullable => 1, size => 90 },  
  "name",
  { data_type => "varchar", is_nullable => 1, size => 90 },  
  "fit",
  { data_type => "varchar", is_nullable => 1, size => 45 },  
  "price",
  { data_type => "float", is_nullable => 1 },  
  "rating",
  { data_type => "int", is_nullable => 0 },  
  "img",
  { data_type => "text", is_nullable => 1},
  "sizes",
  { data_type => "text", is_nullable => 1},
  "description",
  { data_type => "text", is_nullable => 1},
  "long_description",
  { data_type => "text", is_nullable => 1},
  "features",
  { data_type => "text", is_nullable => 1},
  "videos",
  { data_type => "text", is_nullable => 1 },
  "html",
  { data_type => "text", is_nullable => 1 },
  "similar",
  { data_type => "text", is_nullable => 1 },
  "similar_js",
  { data_type => "text", is_nullable => 1 },
  "last_update",
  {
    data_type => "timestamp",
    datetime_undef_if_invalid => 1,
    default_value => \"current_timestamp",
    is_nullable => 1,
  },
);


__PACKAGE__->set_primary_key("sku");


sub add_color {
	my ($self, $color) = @_;
	return 0 unless $color;
	my @colors = $self->_colors ? split '\|', $self->_colors : ();
	my %color_hash = map {$_ => 1} @colors;
	unless ($color_hash{$color}){ 
		push @colors, $color;
		$self->_colors(join '|', @colors);
		return 1;
	}
	return 0;
}


sub colors {
	my ($self) = @_;
	my @colors = $self->_colors ? split '|', $self->_colors : ();	
	return @colors;
}


1;
