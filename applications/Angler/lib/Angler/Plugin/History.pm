package Dancer::Plugin::History;

use warnings;
use strict;

use Dancer ':syntax';
use Dancer::Plugin;
use Angler::History;

=head1 HOOKS

This plugin makes use of the following hooks:

=head2 before

Loads history from session, unshifts the current page onto the start of
the 'all' history list and finally caches history in a var.

=cut

hook before => sub {
    my $history = &from_session;

    my $path         = request->path;
    my $query_params = params('query');

    $history->add(
        path         => $path,
        query_params => $query_params,
        uri          => uri_for( $path, $query_params )->path_query,
    );

    var history => $history;
};

=head2 before_template_render

Puts history into the token C<history>.

=cut

hook before_template_render => sub {
    my $tokens = shift;

    $tokens->{history} = var('history');
};

=head2 after

Saves history back into session.

=cut

hook after => sub {
    my $history = var('history');
    &to_session($history);
};

=head1 DESCRIPTION

The C<add_to_history> keyword which is exported by this plugin allow you to 
add interesting items

=cut

=head1 METHODS

=head2 from_session

Load history from session, create L<Angler::History> object and return it.

=cut

sub from_session {
    my $history;

    my $session_history = session('history');
    if ( ref($session_history) eq 'HASH' ) {
        $history = $session_history;
    }
    else {
        $history = {};
    }

    return Angler::History->new(
        pages => $history,
    );
}

=head2 to_session

Save L<Angler::History/pages> to session.

=cut

sub to_session {
    session history => shift->pages;
}

register add_to_history => sub {
    my ($self, @args) = plugin_args(@_);

    my $path         = request->path;
    my $query_params = params('query');

    my %args = (
        path         => $path,
        query_params => $query_params,
        uri          => uri_for( $path, $query_params )->path_query,
    );

};

register_plugin;
1;
