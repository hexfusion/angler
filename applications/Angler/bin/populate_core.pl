#! /usr/bin/env perl

use strict;
use warnings;

package Fixtures;
use Moo;
with 'Interchange6::Test::Role::Fixtures';

has 'ic6s_schema' => (
    is      => 'ro',
);

package main;
use Dancer ':script';
use Angler::Schema;
use Dancer::Plugin::Interchange6;
use Fixtures;

set logger        => 'console';
set logger_format => '%m';

my $schema = shop_schema;
$schema->deploy( { add_drop_table => 1 } );

