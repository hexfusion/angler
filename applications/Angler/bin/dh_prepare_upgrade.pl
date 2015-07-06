#!/usr/bin/env perl

use strict;
use warnings;
use Dancer qw/:script/;
use Dancer::Plugin::Interchange6;
use aliased 'Angler::Interchange6::Schema::DeploymentHandler' => 'DH';
use Getopt::Long;

my ($from_version, $to_version, $help);

my $force_overwrite = 0;

GetOptions(
    "from=s"          => \$from_version,
    "to=s"            => \$to_version,
    "overwrite"       => \$force_overwrite,
    "help"            => \$help,
);

pod2usage(1) if $help;

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

$dh->prepare_upgrade({
   from_version => $from_version,
   to_version   => $to_version,
   version_set  => [$from_version, $to_version]
});

__END__

=head1 NAME

dh_prepare_upgrade.pl - creates upgrade script between 2 Angler schemas

=head1 SYNOPSIS

dh_prepare_upgrade.pl [options]

 Options:
  -f | --from               schema version migrating from
  -t | --to                 schema version migrating to
  -o | --overwrite          force file overwrite
  -h | --help               help message

=cut

1;
