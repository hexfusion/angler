package Angler::Plugin::History;

=head1 NAME

Angler::Plugin::History - a Dancer plugin for Angler for page history

=cut

use warnings;
use strict;

use Dancer ':syntax';
use Dancer::Plugin;
use Angler::History;

=head1 DESCRIPTION

The C<add_to_history> keyword which is exported by this plugin allow you to 
add interesting items to the history lists. We also export the C<history>
keyword which returns the current L<Angler::History> object.

=cut

=head1 HOOKS

This plugin makes use of the following hooks:

=head2 before

Add current page to history type C<all> as long as request is not ajax.

=cut

hook before => sub {
    return if request->is_ajax;
    &add_to_history( type => 'all' );
};

=head2 before_template_render

Puts history into the token C<history>.

=cut

hook before_template_render => sub {
    my $tokens = shift;
    $tokens->{history} = var('history');
};

=head2 after_layout_render

Save history back into session as long as request is not ajax.

=cut

hook after_layout_render => sub {
    return if request->is_ajax;
    session history => var('history')->pages;
};

sub add_to_history {
    my ( $self, @args ) = plugin_args(@_);

    my $conf         = plugin_setting;
    my $path         = request->path;
    my $query_params = params('query');


    my %args = (
        path         => $path,
        query_params => $query_params,
        uri          => uri_for( $path, $query_params )->path_query,
        @args,
    );

    unless ( var('history') ) {

        # var history has not yet been defined so pull history from session

        my $session_history = session('history');
        $session_history = {} unless ref($session_history) eq 'HASH';

        my %args = (
            pages     => $session_history,
        );
        $args{max_items} = $conf->{max_items} if $conf->{max_items};
        $args{methods} = $conf->{methods}
          if ( $conf->{methods} && ref( $conf->{methods} ) eq 'ARRAY' );

        my $history = Angler::History->new( %args );

        var history => $history;
    }

    # add the page
    var('history')->add( %args );
}

register add_to_history => \&add_to_history;

register history => sub {
    return var('history');
};

register_plugin;

1;
