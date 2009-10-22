  # Copyright 2002-2007 Interchange Development Group and others
  # This program is free software; you can redistribute it and/or modify
  # it under the terms of the GNU General Public License as published by
  # the Free Software Foundation; either version 2 of the License, or
  # (at your option) any later version.  See the LICENSE file for details.
  # $Id: review.tag,v 1.0.0 02.22.2008 23:40:56 Sam Batschelet Exp $
 
 UserTag review Order	itemid
 UserTag review AddAttr
 UserTag review Interpolate 
 UserTag review Routine <<EOR
sub {

 my ($itemid) = @_;

$itemid .= $_->{itemid};


my $url = "[area no_session_id=1 no_count=1]review/test2.php?item_id=$itemid" ;


  use LWP::Simple;
  my $page= get $url;
  die "Couldn't get $url" unless defined $page;

  # Then go do things with $page, like this:

  	return $page;
}  

EOR
