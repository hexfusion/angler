# Copyright 2002-2007 Interchange Development Group and others
# 
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License, or
# (at your option) any later version.  See the LICENSE file for details.
# 
# $Id: rss.tag,v 1.0 2009/03/06 Sam Batschelet 23:40:57 pajamian Exp $

UserTag rss Order        rss_address 
UserTag rss Interpolate
UserTag rss Version      $Revision: 1.0 $
UserTag rss Routine      <<EOR

sub {

my ($rss_address, $url, $rss, $raw, $title) = @_;


$rss_address .= $_->{rss_address};

# import packages
use XML::RSS;
use LWP::Simple;

# initialize object
$rss = new XML::RSS();

# get RSS data
$raw = get('$rss_address');
#$raw = get('http://www.slashdot.org/index.rss');

# parse RSS feed
$rss->parse($raw);

# create RSS table
print "<table width=160 cellspacing=0 cellpadding=5>"; print "<tr><td align=center bgcolor=Silver>" . $rss->channel('title') .
"</td></tr>";
print "<tr><td>";

# print titles and URLs of news items
foreach my $item (@{$rss->{'items'}})
{
        $title = $item->{'title'};
        $url = $item->{'link'};
        print "<a href='$url'>$title</a><p\>"; }

# print footers
print "</td></tr>";
print "</table>";

}
EOR
