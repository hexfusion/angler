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

=head2 /cart/quote

Validate US zip codes in cart 'estimate shipping and tax'

=cut

ajax '/cart/quote' => sub {

    my %query = params('query');

    if ( $query{country} && $query{country} eq 'US' && $query{postal_code} ) {

        if ( $query{postal_code} !~ /^(\d{3})\d{2}$/ ) {
            status(404);
            return( to_json({ response => "Invalid zipode"}) )
        }

        my $zip3 = $1;

        my $zone = schema->resultset('Zone')->search(
            {
                'zone_countries.country_iso_code' => 'US',
                zone => "US postal $zip3",

            },
            {
                join => 'zone_countries'
            }
        )->single;

        if ( not $zone ) {
            status(404);
            return( to_json({ response => "zipode not found"}) )
        }
    }
    to_json({ type => "success" });
};

# paranoia
prefix undef;
true;
