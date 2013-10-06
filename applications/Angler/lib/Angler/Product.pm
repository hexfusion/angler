package Angler::Product;

use warnings;
use strict;

use Moo;
use base 'Nitesi::Product';

has image => (
    is => 'rw',
);

1;

