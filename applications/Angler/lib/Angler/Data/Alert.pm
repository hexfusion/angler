package Angler::Data::Alert;

use strict;
use warnings;

=head1 DESCRIPTION

This is a collection of useful alert messages.  The tokens that are returned
are meant to be used with the bootstrap 3 default alerts.  Each new alter must
contain the following tokens.  

alert-class: availables classes are alert-danger, alert-warning, alert-success, alert-info
alert-notice: Error!, Warning!, Success!, Info!

=cut

=head2

=cut

sub username_exists {
    my $tokens;
    $tokens->{'alert-class'} = 'alert-danger';
    $tokens->{'alert-notice'} = 'Error!';
    $tokens->{'alert-msg'} = 'An account with this email address already exists.  Please choose another or
        <a href="#login-modal" data-toggle="modal" data-target="#login-modal">Login</a>';

    return $tokens;
}

sub registration_success {
    my $tokens;
    $tokens->{'alert-class'} = 'alert-success';
    $tokens->{'alert-notice'} = 'Success!';
    $tokens->{'alert-msg'} = 'Congratulations you are now a West Branch Angler user login and get started!';

    return $tokens;
}


1;
