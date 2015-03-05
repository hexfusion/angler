package Angler::Routes::Validator;

use Dancer ':syntax';
use Dancer::Plugin::Ajax;
use Angler::Validator;

=head1 ROUTES

All of the Validator routes are ajax and are prefix with "/validator"

=cut

prefix '/validator';

=head2 /postcode_for_country

Expected params are C<country> which should be ISO country code
and C<postal_code>.

=cut

ajax '/postcode_for_country' => sub {

    if (
        Angler::Validator::country_and_postal_code(
            param('country'), param('postal_code')
        )
      )
    {
        to_json( { type => "success" } );
    }
    else {
        status 404;
        to_json( { type => "fail" } );
    }
};

prefix undef;
true;
