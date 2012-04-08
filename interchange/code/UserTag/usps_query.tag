# Copyright 2002-2011 Interchange Development Group and others
# 
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License, or
# (at your option) any later version.  See the LICENSE file for details.

UserTag  usps-query  Order   service weight
UserTag  usps-query  addAttr
UserTag  usps-query  Version 3.0
UserTag  usps-query  Routine <<EOR
sub {
use XML::Simple;
use POSIX;
    my ($service, $weight, $opt) = @_;
    my ($rate, $response, $xml, $mailtype, @intl, $m_rep, $m_mod, $delivery, $data, $gendata, $servicedesc, $ounces, $i, $usps_country);
	my ($maxweight, $maxdimensions, $prohibitions, $restrictions, $observations, $areas_served);
#Log("USPS:".__LINE__.": service=$service, weight=$weight, opt=".::uneval($opt));
    my %supported_services = (
			      'EXPRESS'     => 1,
			      'FIRST CLASS' => 1,
			      'PRIORITY'    => 1,
			      'PARCEL'      => 1,
			      'BPM'         => 1,
			      'LIBRARY'     => 1,
			      'MEDIA'       => 1,
			      'GLOBAL EXPRESS GUARANTEED (GXG)'                        => 1,
			      'GLOBAL EXPRESS GUARANTEED NON-DOCUMENT RECTANGULAR'     => 1,
			      'GLOBAL EXPRESS GUARANTEED NON-DOCUMENT NON-RECTANGULAR' => 1,
			      'USPS GXG ENVELOPES'                                     => 1,
			      'EXPRESS MAIL INTERNATIONAL'                             => 1,
			      'EXPRESS MAIL INTERNATIONAL FLAT RATE BOXES'             => 1,
			      'EXPRESS MAIL INTERNATIONAL FLAT RATE ENVELOPE'          => 1,
			      'EXPRESS MAIL INTERNATIONAL LEGAL FLAT RATE ENVELOPE'    => 1,
			      'PRIORITY MAIL INTERNATIONAL'                            => 1,
			      'PRIORITY MAIL INTERNATIONAL FLAT RATE ENVELOPE'         => 1,
			      'PRIORITY MAIL INTERNATIONAL LEGAL FLAT RATE ENVELOPE'   => 1,
			      'PRIORITY MAIL INTERNATIONAL PADDED FLAT RATE ENVELOPE'  => 1,
			      'PRIORITY MAIL INTERNATIONAL SMALL FLAT RATE ENVELOPE'   => 1,
			      'PRIORITY MAIL INTERNATIONAL GIFT CARD FLAT RATE ENVELOPE'   => 1,
			      'PRIORITY MAIL INTERNATIONAL WINDOW FLAT RATE ENVELOPE'  => 1,
			      'PRIORITY MAIL INTERNATIONAL MEDIUM FLAT RATE BOX'       => 1,
			      'PRIORITY MAIL INTERNATIONAL LARGE FLAT RATE BOX'        => 1,
			      'PRIORITY MAIL INTERNATIONAL SMALL FLAT RATE BOX'        => 1,
			      'PRIORITY MAIL INTERNATIONAL DVD FLAT RATE BOX'          => 1,
			      'PRIORITY MAIL INTERNATIONAL LARGE VIDEO FLAT RATE BOX'  => 1,
			      'FIRST-CLASS MAIL INTERNATIONAL LARGE ENVELOPE'          => 1,
			      'FIRST-CLASS MAIL INTERNATIONAL PARCEL'                 => 1,
			      'MATTER FOR THE BLIND - ECONOMY MAIL'                    => 1,
			      );
    my %package_sizes = (
			 'REGULAR'  => 1,
			 'LARGE'    => 1,
			 'OVERSIZE' => 1,
			 );
	my %container = (
			  'VARIABLE' => 1,
			  'FLAT RATE ENVELOPE' => 1,
			  'PADDED FLAT RATE ENVELOPE' => 1,
			  'LEGAL FLAT RATE ENVELOPE' => 1,
			  'SM FLAT RATE ENVELOPE' => 1,
			  'WINDOW FLAT RATE ENVELOPE' => 1,
			  'GIFT CARD FLAT RATE ENVELOPE' => 1,
			  'FLAT RATE BOX' => 1,
			  'SM FLAT RATE BOX' => 1,
			  'MD FLAT RATE BOX' => 1,
			  'LG FLAT RATE BOX' => 1,
			  'REGIONALRATEBOXA' => 1,
			  'REGIONALRATEBOXB' => 1,
			  'REGIONALRATEBOXC' => 1,
			  'RECTANGULAR' => 1,
			  'NONRECTANGULAR' => 1
			 );

    my %mailtypes = (
		     'Package'                  => 1,
		     'Postcards or aerogrammes' => 1,
 		     'Envelope'                 => 1,
			 'LargeEnvelope'            => 1,
			 'FlatRate'                 => 1,
		     );

    my $error_msg = 'USPS: ';
    my $origin = $opt->{origin} || $::Variable->{USPS_ORIGIN} || $::Variable->{UPS_ORIGIN};
    my $destination = $opt->{destination} || $::Values->{zip} || $::Variable->{SHIP_DEFAULT_ZIP};
    my $userid = $opt->{userid} || $::Variable->{USPS_ID};
    my $passwd = $opt->{passwd} || $::Variable->{USPS_PASSWORD};
    my $url = $opt->{url} || $::Variable->{USPS_URL} || 'http://Production.ShippingAPIs.com/ShippingAPI.dll';
    my $container = $opt->{container} || $::Variable->{USPS_CONTAINER} || 'VARIABLE';
	my $intlcontainer = $opt->{container} || 'RECTANGULAR';
    my $machinable = $opt->{machinable} || $::Variable->{USPS_MACHINABLE} || 'False';
	my $firstclassmailtype = $opt->{firstclassmailtype} || 'LETTER';
	my $countrycode = $opt->{'country'} || $::Values->{'country'} || 'US';
	my $valueofcontents = $opt->{'value'} || $::Values->{'uspsvalue'} || '0.00';
	my $poboxflag = $opt->{'poboxflag'} || $::Values->{'poboxflag'} || 'N';
	my $giftflag = $opt->{'giftflag'} || $::Values->{'giftflag'} || 'N';
	my $width = $opt->{'width'} || $::Values->{'width'} || '3';
    my $height = $opt->{'height'} || $::Values->{'height'} || '3';
    my $length = $opt->{'length'} || $::Values->{'length'} || '4';
	my $girth = $opt->{'girth'} || $::Values->{'girth'} || '0';

       $service = uc $service;
	   $countrycode = uc($countrycode);

    if (! $supported_services{$service}) {
		$error_msg .= "unknown service type $service.";
		return($error_msg);
    }

    my $size = uc ($opt->{size} || $::Variable->{USPS_SIZE} || 'REGULAR');
    if (! $package_sizes{$size}) {
		$error_msg .= "unknown package size $size.";
		return;
	}

    if ($service eq 'PARCEL') {
	  if ($weight < .375 or $weight > 35) {
		  $machinable = 'False';
		}
    }

	$mailtype =   ($opt->{mailtype} || $::Variable->{USPS_MAILTYPE} || 'Package');
	  unless ($mailtypes{$mailtype}) {
	    $error_msg = "unknown mail type '$mailtype'.";
	    return;
		}

    my $modulo = $opt->{modulo} || $::Variable->{USPS_MODULO};
    if ($modulo and ($modulo < $weight)) {
		$m_rep = int $weight / $modulo;
		$m_mod = $weight % $modulo;
		$weight = $modulo;
    }

	my $db = dbref('country') or warn "Cannot open 'dsrates'\n";
	my $dbh = $db->dbh() or warn "Cannot get handle on tbl $db\n";
	my $sth = $dbh->prepare("SELECT name FROM country WHERE code='$countrycode'");
	   $sth->execute() or die $sth->errstr;
	my $countryname = $sth->fetchrow();

RATEQUOTE: {
	  if ($opt->{wholeweight}) {
		  $weight = ceil($weight);
		  $ounces = '0';
		  }
	  else {
		  $ounces = int(($weight - int($weight)) * 16);
		  $weight = int($weight);
	  }

#Log("USPSquery".__LINE__.": wholeweight=$opt->{wholeweight}, weight=$weight");   
 
   if ($countrycode) {
        my %map = (
			q{US} => q{domestic},
            q{MH} => q{domestic},
			q{AS} => q{domestic},
			q{GU} => q{domestic},
			q{MM} => q{domestic},
			q{MP} => q{domestic},
			q{PW} => q{domestic},
			q{PR} => q{domestic},
			q{VI} => q{domestic},
			q{FM} => q{domestic},
            q{UK} => q{Great Britain},
            q{GB} => q{Great Britain},
			q{AP} => q{Portugal},
			q{BA} => q{Bosnia-Herzegovina},
            q{VG} => q{British Virgin Islands},
            q{VN} => q{Vietnam},
            q{TZ} => q{Tanzania},
            q{SK} => q{Slovak Republic},
            q{RS} => q{Serbia},
			q{ME} => q{Montenegro},
            q{WS} => q{Western Samoa},
            q{KN} => q{Saint Christopher and Nevis},
            q{NV} => q{Saint Christopher and Nevis},
            q{RU} => q{Russia},
            q{PN} => q{Pitcairn Island},
            q{MD} => q{Moldova},
            q{MK} => q{Macedonia, Republic of},
			q{ME} => q{Portugal},
            q{LY} => q{Libya},
            q{LA} => q{Laos},
            q{KR} => q{South Korea},
            q{IR} => q{Iran},
            q{VA} => q{Vatican City},
            q{GE} => q{Georgia, Republic of},
            q{FK} => q{Falkland Islands},
			q{GS} => q{Falkland Islands},
            q{CI} => q{Cote d'Ivoire},
            q{CD} => q{Congo, Democratic Republic of the},
            q{CG} => q{Congo, Republic of the},
            q{BA} => q{Bosnia-Herzegovina},
			q{TP} => q{Indonesia},
			q{GE} => q{Georgia, Republic of},
			q{WF} => q{Wallis and Futuna Islands},
			q{UM} => q{domestic},
			q{AN} => q{Netherlands Antilles},
			q{KP} => q{North Korea},
			q{TA} => q{French Polynesia},
			q{XE} => q{France},
			q{FX} => q{France},
			q{FR} => q{France},
			q{YT} => q{France},
			q{MC} => q{France},
			q{MM} => q{Burma},
			q{CX} => q{Australia},
			q{CC} => q{Australia},
			q{NF} => q{Australia},
			q{CK} => q{New Zealand},
			q{NU} => q{New Zealand},
			q{SH} => q{Saint Helena},
			q{LC} => q{Saint Lucia},
			q{VC} => q{Saint Vincent and the Grenadines},
			q{PM} => q{Saint Pierre and Miquelon},
			q{VA} => q{Vatican City},
			q{SY} => q{Syria},
			q{HM} => q{skip},
			q{ZR} => q{skip},
			q{AQ} => q{skip},
			q{BV} => q{skip},
			q{IO} => q{skip},
			q{TF} => q{skip},
			q{EH} => q{skip},  
			q{PS} => q{skip},
			q{SJ} => q{skip},
			q{YU} => q{skip},
        );
	
         $usps_country = $map{ $countrycode } || $countryname;

#Log("USPS:".__LINE__.": code=$countrycode name=$countryname; uspscountry=$usps_country; des=$destination; origin=$origin, service=$service, weight=$weight");

		  goto DOMESTIC if $usps_country eq 'domestic';

		if (($usps_country) and ($service !~ /GLOBAL|INTERNATIONAL/i)) {
			$error_msg .= "invalid service type $service to $usps_country";
			return($error_msg);
		}


	$xml = qq{API=IntlRateV2\&XML=<IntlRateV2Request USERID="$userid" PASSWORD="$passwd">};
	$xml .= <<EOXML;
	  <Revision>2</Revision>
	  <Package ID="0">
	    <Pounds>$weight</Pounds>
	    <Ounces>$ounces</Ounces>
	    <Machinable>$machinable</Machinable>
	    <MailType>$mailtype</MailType>
	    <GXG>
		  <POBoxFlag>$poboxflag</POBoxFlag>
		  <GiftFlag>$giftflag</GiftFlag>
	    </GXG>
	    <ValueOfContents>$valueofcontents</ValueOfContents>
	    <Country>$usps_country</Country>
	    <Container>$intlcontainer</Container>
	    <Size>$size</Size>
EOXML
	$xml .= <<EOXML if length $width;
	    <Width>$width</Width>
	    <Length>$length</Length>
		<Height>$height</Height>
        <Girth>$girth</Girth>
EOXML
	$xml .= <<EOXML;
	</Package>
	</IntlRateV2Request>
EOXML
    }
    else {
# Domestic shipping
DOMESTIC:
	$error_msg .= " no destination zip code" unless $destination;
	return($error_msg) unless $destination;

# Map intl service types to nearest domestic, for when the service is remapped to
# domestic above. Default to something sensible
	if ($weight <= '4') {
	  $service = 'EXPRESS' if $service =~ /EXPRESS/i;
	  $service = 'PRIORITY' if $service =~ /PRIORITY/i;
	  $service = 'FIRST CLASS' if $service =~ /FIRST-CLASS/i;
	  }
	else {
	  $service = 'PARCEL' if $service =~ /PACKAGE/i;

	 }
	  $service ||= $::Variable->{'USPS_DOMESTIC_SHIPPING'} || 'PRIORITY';

	$xml = qq{API=RateV4\&XML=<RateV4Request USERID="$userid" PASSWORD="$passwd">};
	$xml .= <<EOXML;
	<Package ID="0">
	    <Service>$service</Service>
EOXML
	$xml .= <<EOXML if $service =~ /FIRST CLASS/i;
		<FirstClassMailType>$firstclassmailtype</FirstClassMailType>
EOXML
	$xml .= <<EOXML;
	    <ZipOrigination>$origin</ZipOrigination>
	    <ZipDestination>$destination</ZipDestination>
	    <Pounds>$weight</Pounds>
	    <Ounces>$ounces</Ounces>
	    <Container>$container</Container>
	    <Size>$size</Size>
	    <Machinable>$machinable</Machinable>
	</Package>
	</RateV4Request>
EOXML
    }
#Log("USPS:".__LINE__.": xmlOut=$xml");
	 my $ua = LWP::UserAgent->new;
	    $ua->timeout(30);
	 my $req = HTTP::Request->new('POST' => $url);
		$req->content_type('text/xml');
		$req->content_length( length($xml) );
		$req->content($xml);
	 my $resp = $ua->request($req);
	 my $respcode = $resp->status_line;

	if ($resp->is_success && $resp->content){
	    $response = $resp->content();
#Log("USPS:".__LINE__.": status=$resp->status_line\n#######\n" . ::uneval($resp->content()));
		$error_msg = 'USPS: ';
    } 
    else {
		$error_msg .= 'Error obtaining rate quote from usps.com.';
		return $resp->status_line;
    }

	my $xmlIn = new XML::Simple();
	my $data = $xmlIn->XMLin($response);
	my $uspserror = $data->{'Error'}{'Description'}; # system error, not invalid weight
#Log("USPS:".__LINE__.": xmlback=".::uneval($data));
	   $gendata = $data->{'Package'};
	   $data = $data->{'Package'}{'Service'};
#Log("USPS:".__LINE__.": xmlback=".::uneval($data));

    if ($gendata->{'Error'} or $uspserror) {
# generally because the shipment is overweight for that country
#Log("USPS".__LINE__.": xmlback=".::uneval($gendata) . "\n------------------------\n");
		$error_msg =  "USPS: $gendata->{'Error'}{'Description'} $uspserror";
#Log("USPS:".__LINE__.": error=$error_msg");
		return($error_msg);
		  }
    else {
	if ($usps_country ne 'domestic') {
	   for my $i (0 .. 34) {
			if ($data =~ /ARRAY/) {
			 $rate = $data->[$i]{'Postage'}; 
			  last unless length $rate;
			 $servicedesc = $data->[$i]{'SvcDescription'};
			 $delivery = lc($data->[$i]{'SvcCommitments'});
			 $maxweight = $data->[$i]{'MaxWeight'};
			 $maxdimensions = $data->[$i]{'MaxDimensions'};
						  }
			else {
			 $rate = $data->{'Postage'}; 
			  last unless length $rate;
			 $servicedesc = $data->{'SvcDescription'};
			 $delivery = lc($data->{'SvcCommitments'});
			 $maxweight = $data->{'MaxWeight'};
			 $maxdimensions = $data->{'MaxDimensions'};
			 }
			 $prohibitions = $gendata->{'Prohibitions'};
			 $prohibitions =~ s|\n|<br>|g;
			 $restrictions = $gendata->{'Restrictions'};
			 $restrictions =~ s|\n|<br>|g;
			 $observations = $gendata->{'Observations'};
			 $observations =~ s|\n|<br>|g;
			 $servicedesc =~ s|&lt;sup&gt;&amp;reg;&lt;/sup&gt;||g;
			 $servicedesc =~ s|&lt;sup&gt;&amp;trade;&lt;/sup&gt;||g;
			 $servicedesc =~ s|\*\*||g;
			 $servicedesc =~ s/\*//g;
			 $::Scratch->{'shipmethod'} = $servicedesc;
			 $::Scratch->{'shipdelivery'} = $delivery;
			 $::Scratch->{'shipnotes'} = "<b>Prohibitions:</b><br>" . $prohibitions . "<p><b>Restrictions:</b> <br>" .$restrictions . "<p><b>Observations:</b> <br>" . $observations;
			 $::Scratch->{'shipnotes'} = '' unless length $prohibitions;
#Log("USPS:".__LINE__.": rate=$rate, servicedesc=$servicedesc, delivery=$delivery");
		  next unless $servicedesc =~ /$service/i;
			 $error_msg = "Service type $service not valid for $usps_country" unless ($servicedesc =~ /$service/i);
#Log("USPS:".__LINE__.": rate=$rate, servicedesc=$servicedesc, delivery=$delivery");
		  last if (($servicedesc =~ /$service/i) or ($servicedesc = ''));
			 return($error_msg);
			  }
			}
	else {
# Domestic US
			$rate = $data->{'Rate'};
			$::Scratch->{'usps_response'} .= "<p>Domestic rate = $rate";
			$::Scratch->{'shipmethod'} = ucfirst(lc($data->{'MailService'}));
			$::Scratch->{'shipmethod'} =~ s/\&.*//g;
			$::Scratch->{'shipdelivery'} = '';
			$::Scratch->{'shipnotes'} = '';
			undef $error_msg;
		}
    }
}

    if ($m_rep) {
	  $rate *= $m_rep; 
	  undef $m_rep;
    } 
    if ($m_mod) {
	  $weight = $m_mod; 
	  undef $m_mod;
	  goto RATEQUOTE;
    }

    $::Session->{'ship_message'} .= " $error_msg" if $error_msg;
#Log("USPS:".__LINE__.": rate=$rate, service=$servicedesc, delivery=$delivery");
	$rate += $opt->{adder} if $opt->{adder};
	$rate *= $opt->{multiplier} if $opt->{multiplier};

    return $rate;

}
EOR

UserTag  usps-query  Documentation <<EOD

=head1 NAME


usps-query tag -- calculate USPS costs via www

=head1 SYNOPSIS

  [usps-query
    service="service name"
    weight="NNN"
    userid="USPS webtools user id"*
    passwd="USPS webtools password"*
    origin="NNNNN"*
    destination="NNNNN"*
    url="applet URL"*
    container="container type"*
    size="package size"*
    machinable="True/False"*
    mailtype="mailing type"*
    country="Country name"*
    modulo="NN"*
  ]
	
=head1 DESCRIPTION

Calculates USPS costs via the WWW using the United States Postal Service Rate
Rate Calculator API. You *MUST* register with USPS in order to use this service.
Visit http://www.usps.com/webtools and follow the link(s) to register. You will
receive a confirmation email upon completing the registration process. You 
*MUST* follow the instructions in this email to obtain access to the production
rate quote server. THIS USERTAG WILL NOT WORK WITH USPS's TEST SERVER.

Tell USPS you are using this tag ("3rd party software") when asking for access to
the production server and you will not have to go through the testing process.


=head1 PARAMETERS

=head2 Base Parameters (always required):


=over 4

=item service

The USPS service you wish to get a rate quote for. Services currently supported:

    EXPRESS
    FIRST CLASS
    PRIORITY
    PARCEL
    BPM
    LIBRARY
    MEDIA
    GLOBAL EXPRESS GUARANTEED
    GLOBAL EXPRESS GUARANTEED NON-DOCUMENT RECTANGULAR
    GLOBAL EXPRESS GUARANTEED NON-DOCUMENT NON-RECTANGULAR
    USPS GXG ENVELOPES
    EXPRESS MAIL INTERNATIONAL
    EXPRESS MAIL INTERNATIONAL FLAT RATE ENVELOPE
    EXPRESS MAIL INTERNATIONAL LEGAL FLAT RATE ENVELOPE
    PRIORITY MAIL INTERNATIONAL FLAT RATE ENVELOPE
    PRIORITY MAIL INTERNATIONAL LEGAL FLAT RATE ENVELOPE
	PRIORITY MAIL INTERNATIONAL PADDED FLAT RATE ENVELOPE
	PRIORITY MAIL INTERNATIONAL SMALL FLAT RATE ENVELOPE
	PRIORITY MAIL INTERNATIONAL GIFT CARD FLAT RATE ENVELOPE
	PRIORITY MAIL INTERNATIONAL WINDOW FLAT RATE ENVELOPE
	PRIORITY MAIL INTERNATIONAL MEDIUM FLAT RATE BOX
	PRIORITY MAIL INTERNATIONAL LARGE FLAT RATE BOX
	PRIORITY MAIL INTERNATIONAL SMALL FLAT RATE BOX
	PRIORITY MAIL INTERNATIONAL DVD FLAT RATE BOX
	PRIORITY MAIL INTERNATIONAL LARGE VIDEO FLAT RATE BOX
	PRIORITY MAIL INTERNATIONAL
    FIRST-CLASS MAIL INTERNATIONAL LARGE ENVELOPE
    FIRST-CLASS MAIL INTERNATIONAL PACKAGE
    MATTER FOR THE BLIND - ECONOMY MAIL


=item weight

The total weight of the items to be mailed/shipped.

=item userid

Your USPS webtools userid, which was obtained by registering.
This will default to $Variable->{USPS_ID}, which is the preferred
way to set this parameter.

=item passwd

Your USPS webtools passwd, which was obtained by registering.
This will default to $Variable->{USPS_PASSWORD}, which is the 
preferred way to set this parameter.

=back

=head2 Extended Parameters (domestic and international services)


=over 4

=item url

The URL of the USPS rate quote API. The default is $Variable->{USPS_URL}
or 'http://Production.ShippingAPIs.com/ShippingAPI.dll'.

=item modulo

Enables a rudimentary method of obtaining rate quotes for multi-box shipments. 
'modulo' is a number which represents the maximum weight per box; the default 
is $Variable->{USPS_MODULO}. When modulo > 0, the shipping weight will be divided 
into the number of individual parcels of max. weight 'modulo' which will accommodate 
the whole shipment, and the total rate will be calculated accordingly. 
Example: with modulo = 10, a 34.5lbs. shipment will be calculated as 3 parcels 
weighing 10lbs. each, plus one parcel weighing 4lbs. 8oz.

=back

=head2 Extended Parameters for domestic (U.S.) services only


=over 4

=item origin

Origin zip code. Default is $Variable->{USPS_ORIGIN} or $Variable->{UPS_ORIGIN}.

=item destination

Destination zip code. Default is $Values->{zip} or $Variable->{SHIP_DEFAULT_ZIP}.

=item container

The USPS-defined container type for the shipment. Default is
Variable->{USPS_CONTAINER} or 'None". Please see the Technical Guide to the
Domestic Rates Calculator Application Programming Interface for a complete
list of container types.

=item size

The USPS-defined package size for the shipment. Valid choices are
'REGULAR', 'LARGE', and 'OVERSIZE'. The default is $Variable->{USPS_SIZE} or
'REGULAR'. Please see the Technical Guide to the Domestic Rates Calculator 
Application Programming Interface for a definition of package sizes.

=item machinable (for PARCEL service only)

Possible value are 'True' and 'False'. Indicates whether or not the shipment
qualifies for machine processing by UPS. Default is $Variable->{USPS_MACHINABLE}
or 'False". Consult the USPS service guides for more info on this subject.

=back

=head2 Extended parameters for International services only


=over 4

=item mailtype

The USPS-defined mail type for the shipment. Valid choices are:

    package
    postcards or aerogrammes
    matter for the blind
    envelope

Default is $Variable->{USPS_MAILTYPE} or 'package'. See the USPS international 
service guides for more information on this topic.

=item country (required for international services)

Destination country. No default. You must pass the name of the country, not the ISO
code or abbreviation (eg, 'Canada', not 'CA'). Note that USPS maintains a table of
valid country names which does not necessarily match all entries in the country
table which is distributed with the standard demo, so modifications may be needed
if you intend to use USPS international services. Consult the USPS International
Services guide for more information.

Update August 2011: now accepts the code ([value country]) and the tag will lookup
the country table in the db to find the name; it will then remap the name if 
necessary from the IC version to the USPS version of the country name. Any names
which are US possessions (or the US itself) are remapped 'domestic' and passed
over to the domestic query. Several names are marked to skip, as they seem to be 
unlisted by USPS, usually as they are uninhabited, or used exclusively by the military.
The code XS listed in IC's country.txt for Serbia-Montenegro is obsolete, you might
want to change that to RS for Serbia, and change the currently listed ME to Montenegro
as being the current ISO assignments.

=back

=head1 BUGS

We shall see....

=head1 AUTHORS

 Ed LaFrance <edl@newmediaems.com>
 Josh Lavin <josh@perusion.com>
 Mathew Jones <mat@bibliopolis.com>


Lyn St George, lyn@zolotek.net
Discovered it had been obsoleted so updated various bits. 
Changed to using the IC country code and then looking up the db for the country name
Changed to XML to parse the returned data.
Made valid several invalid country name mappings (not all are known though)
Reroute to domestic query rather than intl for countries which are US possessions
Fixed invalid service types, added missing types
Added error handling for invalid service types
Added option to pass 'wholepounds=1' which rounds up to the next whole pound

March 2012: updated to V4 domestic and V2 intl as the old versions are now deprecated

Also wrote a usps_update.tag based on this, which creates and/or updates a database
of International destinations for a speedier lookup. And a usps_db.tag to read the db.

=cut
EOD
