  # Copyright 2002-2007 Interchange Development Group and others
  # This program is free software; you can redistribute it and/or modify
  # it under the terms of the GNU General Public License as published by
  # the Free Software Foundation; either version 2 of the License, or
  # (at your option) any later version.  See the LICENSE file for details.
  # $Id: review.tag,v 1.0.0 02.22.2008 23:40:56 Sam Batschelet Exp $

UserTag ups-ship Order to_company_name to_name to_address_1 to_address_2 to_address_3 to_city to_state to_zip to_email to_phone pk_service pk_invoice pk_weight pk_length pk_width pk_height pk_box pk_shipping_id
# UserTag ups-ship AddAttr
# UserTag ups-ship Interpolate
# UserTag ups-ship addAttr  
# UserTag ups-ship NoReparse 1
# UserTag ups-ship Interpolate 1
# UserTag ups-ship HasEndTag   1
UserTag ups-ship Routine <<EOR

sub {

 my ( $to_company_name, $to_name, $to_address_1, $to_address_2, $to_address_3, $to_city, $to_state, $to_zip, $to_email, $to_phone, $pk_service, $pk_invoice, $pk_weight, $pk_length, $pk_width, $pk_height, $pk_box, $pk_shipping_id) = @_;

$to_company_name .= $_->{to_company_name};
$to_name .= $_->{to_name};
$to_address_1 .= $_->{to_address_1};
$to_address_2 .= $_->{to_address_2};
$to_address_3 .= $_->{to_address_3};
$to_city .= $_->{to_city};
$to_state .= $_->{to_state};
$to_zip .= $_->{to_zip};
$to_email .= $_->{to_email};
$to_phone .= $_->{to_phone};
$pk_service .= $_->{pk_service};
$pk_invoice .= $_->{pk_invoice};
$pk_weight .= $_->{pk_weight};
$pk_length .= $_->{pk_length};
$pk_width .= $_->{pk_width};
$pk_height .= $_->{pk_height};
$pk_box .= $_->{pk_box};
$pk_shipping_id .= $_->{pk_shipping_id};


my @strings=( $to_company_name, $to_name, $to_address_1, $to_address_2, $to_address_3, $to_city, $to_state, $to_zip, $to_email, $to_phone, $pk_service, $pk_invoice, $pk_weight, $pk_length, $pk_width, $pk_height, $pk_box, $pk_shipping_id);



foreach my $string(@strings){
        trim($string);
}
    
sub trim{
    $_[0]=~s/^\s+//;
    $_[0]=~s/\s+$//;
    return;
}

use LWP 5.64; # Loads all important LWP classes, and makes
                #  sure your version is reasonably recent.

my $browser = LWP::UserAgent->new;

my $url = "https://www.westbranchangler.com/test_code/xmlship_example.php?company=$to_company_name&name=$to_name&address_1=$to_address_1&address_2=$to_address_2&address_3=$to_address_3&city=$to_city&state=$to_state&zip=$to_zip&email=$to_email&phone=$to_phone&shipping_service=$pk_service&order=$pk_invoice&package_weight=$pk_weight&package_length=$pk_length&package_width=$pk_width&package_height=$pk_height&box=$pk_box&shipping_id=$pk_shipping_id";

  my $response = $browser->get( $url );
  die "Can't get $url -- ", $response->status_line
   unless $response->is_success;

  die "Hey, I was expecting HTML, not ", $response->content_type
   unless $response->content_type eq 'text/html';
     # or whatever content-type you're equipped to deal with

#  use LWP::Simple;
#  my $page= get $url;
#die "Couldn't get $url" unless defined $page;


  # Then go do things with $page, like this:

        return $response->content;
}

EOR


