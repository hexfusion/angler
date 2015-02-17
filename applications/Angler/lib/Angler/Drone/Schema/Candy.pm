use utf8;
package Angler::Drone::Schema::Candy;

=head1 NAME
Angler::Drone::Schema::Candy - add DBIx::Class::Candy to our Result classes
=cut

use base 'DBIx::Class::Candy';

=head1 METHODS
=head2 base
Set base to either what is set by the Result class or else to DBIx::Class::Core
=cut

sub base { $_[1] || 'DBIx::Class::Core' }

=head2 autotable
Set autotable to either what is set by the Result class or else to 1
=cut

sub autotable { $_[1] || 1 }

1;
