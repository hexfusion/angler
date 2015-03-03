package Angler::Routes::Validator;

use Dancer ':syntax';
use Angler::Interchange6::Schema;
use Dancer::Plugin::Interchange6;
use Dancer::Plugin::Ajax;
use Dancer::Plugin::DBIC;

=head1 ROUTES

All of the Validator routes are ajax and are prefix with "/validator"

=cut

prefix '/validator';

=head2 /postcode_for_country

Expected params are C<country> which should be ISO country code
and C<postal_code>.

Returns 200 OK for all countries except US. For US it checks zip3 against zones.

=cut

ajax '/postcode_for_country' => sub {

    my %query = params('query');

    if (   $query{country}
        && uc( $query{country} ) eq 'US'
        && $query{postal_code} )
    {

        return fail_response( 404, "invalid zipcode" )
          unless $query{postal_code} =~ /^(\d{3})\d{2}$/;

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

        return fail_response( 404, "zipcode not found" ) unless $zone;
    }

    to_json({ type => "success" });
};

=head1 METHODS

=head2 fail_response( $code, $message )

=cut

sub fail_response {
    my ( $code, $msg ) = @_;
    status($code);
    return to_json({ type => "fail", message => $msg });
}

# paranoia
prefix undef;
true;
