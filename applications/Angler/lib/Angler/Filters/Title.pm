package Angler::Filters::Title;

=head1 NAME

Angler::Filters::Title - page title filter

=head1 DESCRIPTION

Sets default page title to "West Branch Angler" and if arg is supplied then
appends " :: $arg" to title.

=cut

use strict;
use warnings;

use base 'Template::Flute::Filter';

sub filter {
    my ( $self, $arg ) = @_;
    my $title = "West Branch Angler";
    if ( $arg ) {
        $title .= " :: $arg";
    }
    return $title;
}

1;
