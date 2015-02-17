use utf8;
package Angler::Drone::Schema;

our $VERSION = '0.001';

use strict;
use warnings;

use base 'DBIx::Class::Schema';

__PACKAGE__->load_components( 'Helper::Schema::DateTime',
    'Helper::Schema::QuoteNames' );

__PACKAGE__->load_namespaces;


1;
