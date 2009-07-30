# Copyright 2002-2007 Interchange Development Group and others
# 
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License, or
# (at your option) any later version.  See the LICENSE file for details.
# 
# $Id: print-ups.tag,v 1.0 2008/03/20 23:40:57 pajamian Exp $

UserTag print-ups Order        tracking
UserTag print-ups Version      $Revision: 1.0 $
UserTag print-ups Routine      <<EOR

sub {

my ($tracking) = @_;


$tracking .= $_->{tracking};
my $gif = "$tracking.gif";
my $pdf = "$tracking.pdf";
my $image_dir;
my $mydata;

# $image_dir = "/home/virtual/site12/fst/var/www/catalogs/site/images/shipping_labels/ups"



use Shell;

 

system convert("/home/virtual/site12/fst/var/www/html/test_code/$tracking.gif /home/virtual/site12/fst/var/www/html/test_code/$tracking.pdf");

system chmod("774","/home/virtual/site12/fst/var/www/html/test_code/$tracking.pdf");

if ( $? == -1 ){  print "command failed: $!\n";}
else
{
  printf "command exited with value %d", $? >> 8;
}

}
EOR
