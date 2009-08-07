#!/usr/local/bin/perl
#                                                          # -*-Perl-*-
#=======================================================================
#            :
#        File: mj_build_aliases.pl     
#            :
#     Purpose: This routine builds .majordomo_aliases using the ownership
#            : info in the individual list config files. Lists without an
#            : "owner" defined will not be activated in the aliases file.
#            : The .majordomo_aliases file can then be combined with other
#            : system aliases to make a complete aliases file for sendmail.
#            :
#            : The changes from the original mj_build_aliases are shown
#            : at the end of this file, where RCS log. information goes.
#            : I advice you to take a look down there :)
#-----------------------------------------------------------------------
#            :
#   $Revision: 1.4 $        
#       $Date: 1997/07/16 15:25:28 $
#      $State: Exp $
#            :
#     $Source: /u/ricky/perl/majordomo/RCS/mj_build_aliases2.pl,v $
#    $RCSfile: mj_build_aliases2.pl,v $
#            :
#     $Locker:  $
#            :
#=======================================================================
#
# RCS stuff
#

$MJBA_revision  = ' $Revision: 1.4 $ ';
$MJBA_revision  =~ s/^\D*(\d+\.\d+)\D*$/$1/;


# In addition, the "owner" field is used to automatically define
# a "majordomo-users" mailing list. Addresses listed in the
# %ignore_owners array will not be included in the list.

$cf = $ENV{"MAJORDOMO_CF"} || "/etc/majordomo.cf"; 
$aliases = ".majordomo_aliases";
$admins = "majordomo-users";
$owner = "postmaster";

# these owners will not be added to the majordomo-users list
#
%ignore_owners = (
	'nobody',		1,
	'wwis.sdhelp',		1,
	'sdassign',		1,
	'web.master',		1,
);

# Before doing anything else tell the world I am majordomo
# The mj_ prefix is reserved for tools that are part of majordomo proper.
$main'program_name = 'mj_build_aliases2.pl';

require "getopts.pl";


$usage_message = <<"EOM";

Usage: $main'program_name [-C config_file] [-a aliases_file] 
              [-l owners_file] [-o owner] [-v] [-f] [-m] [-t]
      
Options:  -C config_file    Set config_file
          -a aliases_file   build aliases_file ($aliases)
          -l owner_file     use owners_file or list ($admins)
          -o owner          Set the main majordomo/majorcool owner ($owner)
          -f                Force
          -m                Mail results to majordomo owner
          -t                Test only
          -v                Be verbose

EOM
      
die($usage_message) unless &Getopts("C:a:l:o:tvfm");

#
# The following is for compatibility with the old mj_build_aliases
# script
#

$cf =       $opt_C if $opt_C;
$aliases =  $opt_a if $opt_a;
$admins =   $opt_l if $opt_l;
$owner =    $opt_o if $opt_o;

#
# Reference and temporary files...
#

$aliases_ref = "$aliases.ref";
$aliases_tmp = "$aliases.tmp";

# Read and execute the .cf file
die("$cf not readable; stopped") unless -r $cf;
die("require of majordomo.cf failed $@") unless require $cf;

# 1.94 has this set in $CF already
$sendmail_command = "/usr/lib/sendmail" unless $sendmail_command;

# Go to the home directory specified by the .cf file
chdir("$homedir");

# All these should be in the standard PERL library
unshift(@INC, $homedir);
require "ctime.pl";		# To get MoY definitions for month abbrevs
require "majordomo_version.pl";	# What version of Majordomo is this?
require "majordomo.pl";		# all sorts of general-purpose Majordomo subs
require "shlock.pl";		# NNTP-style file locking
require "config_parse.pl";	# functions to parse the config files

# Here's where the fun begins...

# check to see if the cf file is valid
die("listdir not defined. Is majordomo.cf being included correctly?")
	if !defined($listdir);

# where do we look for files, by default?
$filedir = $listdir unless defined($filedir);
$filedir_suffix = ".archive" unless defined($filedir_suffix);

#----------------------------------------------------------------------------

$majordomo_cmd =
	"|$homedir/wrapper majordomo";			# "-l $list" optional
$owner_bounce_cmd =
	"|$homedir/wrapper mj_owner_bounce";		# no args needed
$filter_cmd =
	"|$homedir/wrapper mj_filter";			# no args needed
$log_cmd =
	"|$homedir/wrapper mj_log2 -m ";		# "$list" needed
$unavail_cmd =
	"|$homedir/wrapper mj_list-unavail";		# "$list" needed
$newlist_cmd =
	"|$homedir/wrapper new-list";			# "$list" needed
$resend_cmd =
	"|$homedir/wrapper resend -h $whereami -l";	# "$list", "$list-outgoing"
$archive_cmd = 
	"|$homedir/wrapper archive2.pl -a -f";		# archive-base, frequency (-[dmy])
$digest_cmd =
	"|$homedir/wrapper digest -r -C -l";		# "$list", "$list-outgoing"

# check if process already running
#
unless (($opt_t) || &shlock("$listdir/.build.LOCK")) {
	printf STDERR "$main'program_name: build already running\n" if ($opt_v);
	exit;
}

# check if $listdir, config files, or lists have changed
#
if (-r "$ENV{HOME}/$aliases" && !($opt_f)) {
	unless (`find $listdir/. -type f -name '.[a-z]*' -prune -o \\( -name '*.config' -o -name '.' \\) -newer $ENV{HOME}/$aliases -print`) {
		print STDERR "$main'program_name: no Majordomo list changes detected.\n";
		exit(0);
	}
}

# open alias file
#
unless ($opt_t) {
	printf STDERR "$main'program_name: rebuilding $aliases\n" if ($opt_v);
	open(STDOUT, ">$ENV{HOME}/$aliases_tmp");
}

# match all lists
#
local($nlists_total, $nlists_active) = 0;
opendir(RD_DIR, $listdir) || die("opendir failed $!");
@lists = readdir(RD_DIR);
closedir(RD_DIR);

# these are top-level MD aliases
#
printf STDERR "$main'program_name: adding Majordomo aliases\n" if ($opt_v);
print <<"EOM";
#=======================================================================
#            :
#        File: $aliases
#            :
#     Purpose: This file contains the automatically builded aliases for 
#            : majordomo. It was produced by running the program
#            : $main'program_name (Rev. $MJBA_revision)
#            :
#-----------------------------------------------------------------------
#            :
#   \$\urevision: \$
#       \$\udate: \$
#      \$\ustate: \$
#            :
#     \$\usource: \$
#    \$\urCSfile: \$
#            :
#     \$\ulocker: \$
#            :
#=======================================================================
#
# MAJORDOMO CONFIGURATION
#
#-----------------------------------------------------------------------
majordomo:		"$majordomo_cmd"
majordomo-owner:	$owner
owner-majordomo:	$owner
owner-owner:		$owner
listmanager:	$owner
pubs:			"$filter_cmd"

EOM

foreach (sort @lists) {
	local($list) = $_;
	$list =~ s,^.*/,,;				# strip off leading path
	$list =~ /[^-_0-9a-zA-Z]/ && next; # skip non-list files (*.info, etc.)

	# get/make config file
	&get_config($listdir, $list) if !&cf_ck_bool($list, '', 1);
	# make alias entries
	$owner = &do_list_aliases($list);
	if ($owner) {
		$owner =~ tr/A-Z/a-z/;			# match lowercase
		next if $ignore_owners{$owner};		# some we ignore
		$owner_list{$owner} .= "$list,";	# save the owner
	}
}

print <<"EOM";

#=======================================================================
#
#                                  END of FILE
#
#                       No not change any thing below this line
#
#=======================================================================
EOM

exit(0) if ($opt_t);
close(STDOUT);

#
# Make a backup copy of the $aliases file into $aliases_ref
# (Used to compute the description message for mailing results or
# optionally for RCS description messages..
#
if (-f "$ENV{HOME}/$aliases") {
    rename("$ENV{HOME}/$aliases", "$ENV{HOME}/$aliases_ref");
}

rename("$ENV{HOME}/$aliases_tmp", "$ENV{HOME}/$aliases");

printf STDERR "$main'program_name: $nlists_total lists processed ($nlists_active active)\n"
	if ($opt_v);

# open owner-list file
#
printf STDERR "$main'program_name: rebuilding $admins\n" if ($opt_v);
open(FILE, ">$listdir/$admins");

foreach (keys(%owner_list)) {
	chop($owner_list{$_});
	print FILE "$_ ($owner_list{$_})\n";
}
close(FILE);
unlink("$listdir/.build.LOCK");

#
# Mail results??
#

if ($opt_m) {

    $desc = &get_changes_description("$ENV{HOME}/$aliases","$ENV{HOME}/$aliases_ref");
    print STDERR "--- $main'program_name: Description of changes are ---\n$desc--\n" if ($opt_v);

    #
    # Now mail the results :)
    #
    &set_mailer($bounce_mailer ? $bounce_mailer : $mailer);
    &set_mail_sender($whoami_owner);
    &set_mail_from($whoami_owner);
    &sendmail(MAIL, $opt_o, "Mail aliases changed by $main'program_name");
    print MAIL <<"EOF";
Hello Majordomo owner:

The following changes were done on the mail aliases
'$ENV{HOME}/$aliases' file

The changes are:
------------------------------------------------------------------------------

$desc
------------------------------------------------------------------------------


FYI, a copy of the old '$aliases' file 
was saved as '$aliases_ref' file. 

--- $whoami
P.D. Contrary to popular belief, Unix is user friendly.  
     It just happens to be selective about who it makes friends with.
EOF

    close(MAIL);

}

# do all additional tasks as background process
#
if (-x "$homedir/mj_background_tasks") {
	printf STDERR "$main'program_name: invoking background tasks\n" if ($opt_v);
	system "$homedir/wrapper mj_background_tasks" . (($opt_v) ? " -v" : "") . "&";
}

#----------------------------------------------------------------------------

# Everything from here on down is subroutine definitions

sub do_list_aliases {
	local($list) = shift;
	local($list_owner,$list_moderator,$supplemental);
	$list_owner = $config_opts{$list,'owner'};
	$list_moderator = $config_opts{$list,'moderator'};
	$list_moderator = $list_owner unless ($list_moderator);

	# 'owner' keyword is not implemented! default to Mj owner
	$list_owner = $list_moderator = $owner
		unless defined($config_opts{$list,'owner'});

	# check validity of owner/moderator addresses
	$list_owner = &valid_addr($list_owner);
	$list_moderator = &valid_addr($list_moderator);

	# Archive and Digest are stubs for work-in-progress
	if (&cf_ck_bool($list,'archive')) {
		local($dir,$freq);
		printf STDERR "$main'program_name: <$list> is archived\n" if ($opt_v);
		$dir = $config_opts{$list,'archive_dir'};
		$dir = "$filedir/$list$filedir_suffix" unless $dir;
		unless (-d $dir) {
			printf STDERR "$main'program_name: creating archive directory\n" 
				if ($opt_v);
			printf STDERR "$main'program_name: $dir mkdir failed ($!)\n" unless
				mkdir($dir, 0770);
		}
		$freq = $config_opts{$list,'archive_frequency'};
		$freq = "-d" if $freq eq "daily";
		$freq = "-m" if $freq eq "monthly";
		$freq = "-y" if $freq eq "yearly";
		$freq = "-m" unless $freq;	# default
		$supplemental .= ", \"$archive_cmd $dir/archive $freq\"";
	}
	if (&cf_ck_bool($list,'digest')) {
		printf STDERR "$main'program_name: <$list> is digested\n" if ($opt_v);
		$supplemental .= ", \"$digest_cmd $list-digest $list-digest-outgoing\"";
	}

	$nlists_total++;
	unless ($list_owner && $list_moderator) {
		printf STDERR "$main'program_name: owner/moderator invalid or not specified for <$list>\n" if ($opt_v);
		print <<"EOM";

#=======================================================================
#
# $list (UNAVAILABLE)
#
#-----------------------------------------------------------------------
$list:		"$unavail_cmd $list"
$list-request:	"$majordomo_cmd -l $list"

EOM
		return;
	}


	$nlists_active++;
	printf STDERR "$main'program_name: active list <$list>\n" if ($opt_v);
	print <<"EOM";

#=======================================================================
#
# $list (ACTIVE)
#
#-----------------------------------------------------------------------
$list:		"$resend_cmd $list $list-outgoing", "$log_cmd $list"
$list-request:	"$majordomo_cmd -l $list"
$list-outgoing:	:include:$listdir/$list $supplemental
$list-approval:	$list_moderator
$list-owner:	$list_owner
$list-outgoing-owner:	$list_owner
owner-$list:	$list_owner
owner-$list-outgoing:	$list_owner

EOM
	if ($config_opts{$list,"digest"}) {
		print <<"EOM";
$list-digest:	$list
$list-digest-request:	"$majordomo_cmd -l $list-digest"
$list-digest-outgoing:	:include:$listdir/$list-digest
$list-digest-approval:	$list_moderator
$list-digest-owner:	$list_owner
$list-digest-outgoing-owner:	$list_owner
owner-$list-digest:	$list_owner
owner-$list-digest-outgoing:	$list_owner

EOM
	}
	return $list_owner;
}

sub valid_addr {
	local($addr) = shift;
	return $addr;
	$_ = $addr;
	# all comparisons in l/c
	tr/A-Z/a-z/;
	# remove local domain names
	s/sandiegoca.ncr.com//;
	s/elsegundoca.ncr.com//;
	# remove trailing ., @
	s/\.$//; s/\@$//;

	# here will end up with:
	#	userid
	#	first.last
	#	anything@localhost
	#	anything@non-local.domain

	# skip anything non-local
	return $addr if /\./ && /\@/;

	# skip all @localhost
	return $addr if /\@/;

	local(@trace);
	@trace = `/usr/local/bin/rolo -u1 -L name $_ 2>&1` if /\./;
	@trace = `/bin/ypmatch -k $_ aliases 2>&1` if /^[_\w]+$/;
	return ($? ? "" : "$_");
}

sub get_changes_description {
    local($new_file,$old_file) = @_;
    local(@oldies,@newies);
    local(%MER);

    grep(($MER{$_}++) && 0,    &get_aliases_from_file($old_file));
    grep(($MER{$_} += 2) && 0, &get_aliases_from_file($new_file));

    foreach $element ( keys %MER) {
	#
	# case of $MER{$element}:
	#      1 OLD
	#      2 NEW
	#      3 No change...
	if ($MER{$element} == 1) {
            push(@oldies,$element);
	} elsif ($MER{$element} == 2) {
            push(@newies,$element);
	}
        
    }
    
    #
    # Prepare The description (Was for RCS)
    #

    $desc = '';
    if (@oldies) {
        $desc .= join(' ',"DELETED or MODIFIED:\n", sort @oldies);
    }
    if (@newies) {
        $desc .= join(' ',"ADDED or MODIFIED:\n", sort @newies);
    } 
    
    $desc = "None changes recorded.\n" if ($desc =~ /^$/);

    return $desc;

}

sub get_aliases_from_file {
    local($filename) = @_;
    local(@tmp_ref) = ();

    printf STDERR "$main'program_name: Reading $filename\n" if ($opt_v);

    if (-e $filename) {
        open(ALIASFILE, "$filename");
        @tmp_ref = <ALIASFILE>;
        @tmp_ref = grep(/:/, @tmp_ref);
        @tmp_ref = grep(!/^\#/, @tmp_ref);
        close(ALIASFILE);
    }

    return @tmp_ref;

}


##############################################################################
#
#                                  END of FILE
#
#                       No not change any thing below this line
#
##############################################################################
# 
# RCS LOG Information
# -------------------
# $Log: mj_build_aliases2.pl,v $
# Revision 1.4  1997/07/16 15:25:28  ricky
# $archive_cmd is now 'archive2.pl'
# $log_cmd is now 'mj_log2.pl'
#
# Revision 1.3  1997/07/16 15:16:35  ricky
# Removing the use of RCS, due that the aliases are always computed
# from the existent config files. (If you need/want RCS, just drop me
# a message to ricky@ornet.co.il and ask for it)
#
# Revision 1.2  1997/07/16 15:13:01  ricky
# - Adding a new flag: -m (mail) to send via e-mail a report of changes,
#   computed from the old $aliases file. (Verbose mode will also print
#   the changes on STDERR.
# - Changing the command line options parsing, now it uses Getopts.
# - Adding some comments on the resulting aliases file. (With RCS Information)
#   (Ricky 16/Jul/1997)
#
# Revision 1.1  1997/07/16 15:02:34  ricky
# Initial revision
#
#
#=============================================================================
# Do NOT remove these lines. They set mode in Emacs.
# perl-mode is OK for font locking.
#
#@ Local Variables: ***
#@ mode: perl ***
#@ End: ***
#=============================================================================
