#!/usr/bin/perl -w

# Movable Type (r) Open Source (C) 2001-2008 Six Apart, Ltd.
# This program is distributed under the terms of the
# GNU General Public License, version 2.
#
# $Id: mt-ftsearch.cgi 1502 2008-03-11 07:56:17Z fumiakiy $

use strict;
use lib $ENV{MT_HOME} ? "$ENV{MT_HOME}/lib" : 'lib';
use MT::Bootstrap App => 'MT::App::Search::FreeText';
