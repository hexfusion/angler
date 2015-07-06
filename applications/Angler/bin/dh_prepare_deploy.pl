#!/usr/bin/env perl

use strict;
use warnings;
use Dancer qw/:script/;
use Dancer::Plugin::Interchange6;
use aliased 'Angler::Interchange6::Schema::DeploymentHandler' => 'DH';
use Getopt::Long;

my $force_overwrite = 0;

unless ( GetOptions( 'force_overwrite!' => \$force_overwrite ) ) {
    die "Invalid options";
}

my $schema = shop_schema;

my $dh = DH->new(
     {
         schema              => $schema,
         script_directory    => "../dbicdh",
         databases           => 'MySQL',
         sql_translator_args => { add_drop_table => 0 },
         force_overwrite     => $force_overwrite,
     }
 );

$dh->prepare_deploy;
