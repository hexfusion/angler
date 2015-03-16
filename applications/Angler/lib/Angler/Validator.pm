package Angler::Validator;

=head1 NAME

Angler::Validator - shared validation methods

=cut

use Dancer ':syntax';
use Dancer::Plugin::Interchange6;
use Dancer::Plugin::DBIC;

=head1 METHODS

=head2 country_and_postalcode

Expected arguments are C<country> which should be ISO country code
and C<postal_code>. Returns 1 on success and undef on failure.

=cut

sub country_and_postal_code {
    my ( $country, $postal_code ) = @_;

    return undef unless defined $country;

    if ( uc($country) eq 'US' ) {

        return undef
          unless ( defined $postal_code
            && $postal_code =~ /^(\d{3})\d{2}/ );

        my $zip3 = $1;

        my $zone = schema->resultset('Zone')->search(
            {
                'zone_countries.country_iso_code' => 'US',
                zone                              => "US postal $zip3",

            },
            {
                join => 'zone_countries'
            }
        )->single;

        return undef unless $zone;
    }
    else {
        my $zone = schema->resultset('Zone')->search(
            {
                'zone_countries.country_iso_code' => $country,
                zone                              => "Deliverable Countries",
            },
            {
                join => 'zone_countries',
            }
        )->single;

        return undef unless $zone;
    }

    return 1;
}

1;
