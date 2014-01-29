# Interchange UserTag rt_add.tag 
#
# Copyright (C) 2009 Sam Batschelet
#
# This program is free software; you can redistribute it and/or
# modify it under the terms of the GNU General Public License
# as published by the Free Software Foundation; either version 2
# of the License, or (at your option) any later version.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with this program; if not, write to the Free Software
# Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA
# 02111-1307, USA.
#
#
#UserTag rt_add Order   attribute
#UserTag rt_add addAttr
#UserTag rt_add Version $Revision: 0.1 $
#UserTag rt_add Routine <<EOR
#sub {
#
# use Error qw(:try);
# use RT::Client::REST;
# use RT::Client::REST::Ticket;
#
#my $user='sam@westbranchresort.com';
#my $pass='mayfly6969';
#
#  my $rt = RT::Client::REST->new(
#    server => 'http://rt.westbranchresort.com',
#    timeout => 30,
#);
#
#  try {
#    $rt->login(username => $user, password => $pass);
#  } catch Exception::Class::Base with {
#    die "problem logging in: ", shift->message;
#  };
#
#
#  # Create a new ticket:
#  my $ticket = RT::Client::REST::Ticket->new(
#    rt => $rt,
#    queue => "General",
#    subject => "This is my remote user test",
#  )->store(text => "This is the initial text of the ticket");
#  print "Created a new ticket, ID ", $ticket->id, "\n";
#
#}
#EOR
#
