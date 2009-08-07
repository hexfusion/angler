#!PERLBIN
#-----------------------------------------------------------------------------
# MajorCool, a Web Interface to the Majordomo mailing list manager. See
#	http://ncrinfo.ncr.com/pub/contrib/unix/MajorCool/
# for online documentation, update history, and download information.
# See $homedir/majorcool_*.cf for local version & config information.
#
# --
# Bill.Houle@sanDiegoCA.NCR.COM
# (c) 1996-1998 NCR Corporation
#-----------------------------------------------------------------------------

$icon_locked		= "WEB_IMGURL/mc_lock.gif";
$icon_disabled		= "WEB_IMGURL/mc_not.gif";
$icon_size		= "HEIGHT=18 WIDTH=18";

$tip{about}		= "ABOUT this software. Both <I>Majordomo</I> "
			. "and <I>MajorCool</I> are freely available "
			. "programs. Find out more about them here.";

$tip{browse}		= "BROWSE allows you to determine your current "
			. "<I>Majordomo</I> list subscription status, change "
			. "subscriptions, and discover information about "
			. "various lists available on this server.";

$tip{modify}		= "MODIFY allows you to manage any <I>Majordomo</I> "
			. "lists that you may administer. You may modify the "
			. "list configuration file, maintain the subscriber "
			. "list, and edit other supporting files.";

$tip{create}		= "CREATE provides you with a mechanism to request a "
			. "new <I>Majordomo</I> mailing list on this server.  "
			. "Depending upon the site configuration, your request "
			. "may be forwarded to the site coordinator or acted "
			. "upon immediately.";

$tip{rename}		= "RENAME provides you with a mechanism to request "
			. "renaming of a <I>Majordomo</I> mailing list. "
			. "Depending upon the site configuration, your request "
			. "may be forwarded to the site coordinator or acted "
			. "upon immediately.";

$tip{delete}		= "DELETE provides you with a mechanism to request "
			. "removal of a <I>Majordomo</I> mailing list that "
			. "is no longer needed. "
			. "Depending upon the site configuration, your request "
			. "may be forwarded to the site coordinator or acted "
			. "upon immediately.";

$tip{prefs}		= "PREFS allow you to customize the <I>MajorCool</I> "
			. "user interface and its interaction with "
			. "<I>Majordomo</I>. Selections are saved via browser "
			. "<B>Cookies</B>, making them persistent across "
			. "sessions.";

$tip{help}		= "HELP provides more detailed information about the "
			. "usage of this <I>MajorCool</I> application. "
			. "Screen snapshots, explanations of each function, "
			. "and customization tips are available here.";

$tip{mail}		= "FEEDBACK sends mail to the site <I>Majordomo</I> "
			. "owner. Got a question about this list-server "
			. "implementation? Ask the site coordinator...";

$tip{home}		= "HOME will return you to the home page for this "
			. "site.";

$tip{restart}		= "RESTART will return to the initial screen of "
			. "<I>MajorCool</I>.";

$member_msg		= "Only list members can view the requested "
			. "information.";

$private_msg		= "The info you have requested is marked 'private' "
			. "and is restricted to list members. Although you "
			. "may be a member of the list, it is not possible "
			. "to authenticate your Web identity with 100% "
			. "confidence. Therefore, you will be receiving the "
			. "requested info via a more secure e-mail mechanism.";

$disabled_msg		= "Access to this information has been disabled "
			. "for all Web users.";

$secret_msg		= "Access to this information is closed to all "
			. "users.";

# Cookie-based default preferences
# Per spec, no more than:
#	300 per cookie jar (client)
#  --->	20 Cookies per host
#	4k per Cookie
#
%prefs = (

# GENERAL OPTIONS (apply to most pages)
#

GenCache,		1,
GenCache_Type,		'boolean',
GenCache_Text,		"Allow Browser to Cache Pages & Form Data",

GenFontCSS,		1,
GenFontCSS_Type,	'boolean',
GenFontCSS_Text,	"Enable Style Sheet Font Control (Where Supported)",

GenFontFace,		'Helvetica', 			# <FONT FACE=?>
GenFontFace_Type,	'choice:Arial,Courier,Garamond,Geneva,'.
			'Helvetica,MS Sans Serif,MS Serif,New York,'.
			'Palatino,Times,Times New Roman,Verdana',
GenFontFace_Text,	"Default Font Face",

GenFontSize,		'12',				# <FONT SIZE=?>
GenFontSize_Type,	'integer:2',
GenFontSize_Text,	"Default Font Point Size",

GenJavaScript,		1,
GenJavaScript_Type,	'boolean',
GenJavaScript_Text,	"Use JavaScript Enhancements",

GenListSorted,		1,
GenListSorted_Type,	'boolean',
GenListSorted_Text,	"Sort Subscriber List in BROWSE & MODIFY Views",

GenMenuMode,		1,
GenMenuMode_Type,	'boolean',
GenMenuMode_Text,	"Use 'Menu' Style User Interface",

GenMenuStart,		'',
GenMenuStart_Type,	'choice:,browse,modify,create,rename,delete,prefs',
GenMenuStart_Text,	"Default Action for Menu Mode",

GenScreenWidth,		'550',
GenScreenWidth_Type,	'word:5',
GenScreenWidth_Text,	"Default Screen Width (in Pixels or Specify %)",

GenSubmitImages,	1,
GenSubmitImages_Type,	'boolean',
GenSubmitImages_Text,	"Use Images for Form Buttons (eg Submit/Apply)",

GenToolTips,		1,
GenToolTips_Type,	'boolean',
GenToolTips_Text,	"Tool Tips on Button Bars (Requires JavaScript)",

# BROWSE OPTIONS
#

BrowseListNested,	1,
BrowseListNested_Type,	'boolean',
BrowseListNested_Text,	"Show Nested Lists as Links",

BrowseListNestedWho,	0,
BrowseListNestedWho_Type,	'boolean',
BrowseListNestedWho_Text,	"Link to Subscribers when Following Nested Lists",

BrowseListNumber,	0,
BrowseListNumber_Type,	'boolean',
BrowseListNumber_Text,	"Show Line Numbers in Subscriber List",

# MODIFY OPTIONS
#

ModifyConf2Column,	1,
ModifyConf2Column_Type,	'boolean',
ModifyConf2Column_Text,	"Use 2-Column Table for Configuration Options",

ModifyConfHelp,		1,
ModifyConfHelp_Type,	'boolean',
ModifyConfHelp_Text,	"Show Help with Configuration Options Fields",

ModifyConfSubsys,	1,
ModifyConfSubsys_Type,	'boolean',
ModifyConfSubsys_Text,	"Show Majordomo Subsystem for Each Configuration Field",

ModifyInfoWrap,		0,
ModifyInfoWrap_Type,	'boolean',
ModifyInfoWrap_Text,	"Wrap Text in Info File Edit Window",

ModifyListMaxSize,	25000,			#   NN4: 28760
ModifyListMaxSize_Type,	'integer:7',
ModifyListMaxSize_Text,	"Maximum Size Supported by Browser TextArea (in Bytes)",
);

###############################################################################

select((select(STDOUT), $|=1)[0]);		# force line by line flush
*STDERR = *STDOUT;				# errors to stdout
&init_args(@ARGV);				# set environ, get form args
$url	= $ENV{SCRIPT_NAME};			# this script called as...
$domo	= $ENV{MAJORDOMO_CF}; 			# this Majordomo is...
$remote	= $ENV{REMOTE_HOST};			# this Web user is...
$remote	.= "/$ENV{REMOTE_ADDR}" unless $ENV{REMOTE_HOST} =~ /^[\d\.]+$/o;

# Read and execute the Majordomo .cf file
&send_error("$domo Not Readable; Stopped.") unless -r $domo;
&send_error("Inclusion Of $domo Failed $@") unless require "$domo";

# Go to the home directory specified by the .cf file
chdir("$homedir");

# Load needed Majordomo files
unshift(@INC, $homedir);
require "ctime.pl";
require "majordomo_version.pl";
require "majordomo.pl";
require "shlock.pl";
require "config_parse.pl";

# overload Majordomo's $main'abort() so that it goes to the Web
$main'abort = send_error;

# check to see if the cf file is valid
&send_error("\$listdir Not Defined. Is majordomo.cf Being Included Correctly?")
	unless defined($listdir);

# Read and execute the MajorCool .cf file
&send_error("$cf Not Readable; Stopped.") unless -r $cf;
&send_error("Inclusion Of $cf Failed $@") unless require "$cf";

# where do we look for files, by default?
$TMPDIR = "/usr/tmp" unless defined($TMPDIR);
$filedir = $listdir unless defined($filedir);
$filedir_suffix = ".archive" unless defined($filedir_suffix);

# Set up logging info (logfile, host, process, user)
&set_log($log, $ENV{SERVER_NAME}, $ENV{SCRIPT_NAME}, "MajorCool: $remote");

&init_prefs();					# set/fix preferences, Cookies

if ($arg{action} =~ /help|about|remote/o) {
#
# If HELP, ABOUT, or REMOTE is selected, we need to avoid setting any 
# default 'active' buttons. For HELP, changing the module will change
# the context for the help file.
#
}
elsif ($prefs{GenMenuMode} && ! $arg{'module'}) {
#
# For other modes, we need a default module when none has been selected
# for MenuMode...
#
	if ($prefs{GenMenuStart} && $modules{$prefs{GenMenuStart}}) {
		# if mode is in prefs and that mode is valid...
		$arg{'module'} = $prefs{GenMenuStart}; 
	}
	elsif ($modules{'browse'}) { $arg{'module'} = "browse"; }
	elsif ($modules{'modify'}) { $arg{'module'} = "modify"; }
	elsif ($modules{'create'}) { $arg{'module'} = "create"; }
	elsif ($modules{'rename'}) { $arg{'module'} = "rename"; }
	elsif ($modules{'delete'}) { $arg{'module'} = "delete"; }
	else {
		$arg{'module'} = "prefs";	# last-ditch screen
	}
}
&init_page();					# set HTML header/footer

# Use multi-part server push for BROWSE delay status? Check
#   browser type and undo the cf setting if necessary.
if ($opt_multipart) {
	# only Netscape supports 'mixed-replace' content push headers
	$opt_multipart = 0 if $ENV{HTTP_USER_AGENT} !~ /Mozilla|Netscape/o;
	# ...and even with Navigator, it's only on Windows
	$opt_multipart = 0 if $ENV{HTTP_USER_AGENT} !~ /Win/o;
	# ...MS says they are Mozilla-compat, but not 100%!
	$opt_multipart = 0 if $ENV{HTTP_USER_AGENT} =~ /MSIE|Microsoft/o;
	# If there are others that will support, please let me know!
}

# This function is not %module restricted; 'action'
#   must exist or you get the main screen.
if (! defined($arg{'action'}) || $arg{'action'} eq "prefs") {
	# Prefs module is free-standing & not optional like other %modules
	# Note: module=prefs is form; action=prefs sets the preferences
	if ($arg{'module'} eq "prefs") {
		&log("PREFS get '$ENV{HTTP_USER_AGENT}'");
		&send_prefs_form();
	}
	else {
		&send_main_form();
	}
	&send_done();
}
# This is not technically a module; it is for remote forms
#   & pages to directly invoke Majordomo-style commands.
#   Only a small portion of valid commands are accepted,
#   and nothing you can't do with Majordomo mail...
elsif ($arg{'action'} eq "remote") {
	($user,$address,$pattern) = &siteaddr($arg{'siteaddr'});
	if ($arg{command} eq "subscribe" || $arg{command} eq "unsubscribe") {
		&send_subunsub($arg{'list'},$address,$pattern,$arg{command});
	}
	elsif ($arg{'command'} eq "info") {
		&send_info($arg{'list'},$address,$pattern);
	}
	elsif ($arg{'command'} eq "intro") {
		&send_intro($arg{'list'},$address,$pattern);
	}
	elsif ($arg{'command'} eq "who") {
		&send_who($arg{'list'},$address,$pattern);
	}
	else {
		&send_error("Remote Command \"$arg{command}\" Unavailable.");
	}
	&send_done();
}
elsif ($arg{'action'} eq "status" && $modules{'browse'}) {
	# get ready to use the 'update' feature
	&send_multi();
	# 'module' should be set from previous form, but just in
	#   case, set to make sure menu button appears 'active'
	$arg{'module'} = "browse";
	&send_error("An E-Mail Address Is Required To Browse Lists.")
		unless $arg{'siteaddr'};
	&send_error("No Search Pattern Specified.") 
		if $arg{'criteria'} eq "matched" && ! $arg{'list_match'};
	# main form input; build regexp
	($user,$address,$pattern) = &siteaddr($arg{'siteaddr'});
	&load_cache();
	if ($arg{list_exact}) {
		&send_details($arg{'list_match'},$address,$pattern);
	}
	else {
		&get_status($address,$pattern);
		@lists = &get_lists($address,$pattern,
			$arg{'criteria'},$arg{'list_match'});	
		&send_status_form($user,$address,$pattern,
			$arg{'criteria'},$arg{'list_match'},@lists);
	}
	&send_done();
}
elsif ($arg{'action'} eq "view" && $modules{'browse'}) {
	# get ready to use the 'update' feature
	&send_multi();
	# 'module' should be set from previous form, but just in
	#   case, set to make sure menu button appears 'active'
	$arg{'module'} = "browse";
	($user,$address,$pattern) = &siteaddr($arg{'siteaddr'});
	&load_cache();
	&get_status($address,$pattern,$arg{'list'});
	if ($arg{'view'} eq "info") {
		&send_info($arg{'list'},$address,$pattern);
	}
	elsif ($arg{'view'} eq "intro") {
		&send_intro($arg{'list'},$address,$pattern);
	}
	elsif ($arg{'view'} eq "list") {
		&send_who($arg{'list'},$address,$pattern);
	}
	elsif ($arg{'view'} eq "details") {
		&send_details($arg{'list'},$address,$pattern);
	}
	elsif ($arg{'view'} eq "config") {	# 'config' is synonym
		&send_details($arg{'list'},$address,$pattern);
	}
	else {
		&send_error("View Type \"$arg{'view'}\" Unavailable.");
	}
	&send_done();
}
elsif ($arg{'action'} eq "do_subunsub" && $modules{'browse'}) {
	# get ready to use the 'update' feature
	&send_multi();
	# 'module' should be set from previous form, but just in
	#   case, set to make sure menu button appears 'active'
	$arg{'module'} = "browse";
	($user,$address,$pattern) = &siteaddr($arg{'siteaddr'});
	&load_cache();
	&get_status($address,$pattern);
	@lists = &get_lists($address,$pattern,
		$arg{'criteria'},$arg{'list_match'});	
	&do_subunsub($address,@lists);
	&send_done();
}
elsif ($arg{'action'} eq "edit" && $modules{'modify'}) {
	# 'module' should be set from previous form, but just in
	#   case, set to make sure menu button appears 'active'
	$arg{'module'} = "modify";
	if ($arg{'view'} eq "config") {
		&send_config_form($arg{'list'},$arg{'passwd'},
			($arg{'keyview'} ? $arg{'keyview'} : "basic"));
	}
	elsif ($arg{'view'} eq "list") {
		# sizeaction says what to do if the list is too big
		&send_who_form($arg{'list'},$arg{'passwd'},
			$arg{'sizeaction'});
	}
	elsif ($arg{'view'} eq "info") {
		&send_info_form($arg{'list'},$arg{'passwd'});
	}
	elsif ($arg{'view'} eq "intro") {
		&send_intro_form($arg{'list'},$arg{'passwd'});
	}
	elsif ($arg{'view'} eq "queue") {
		&send_queue_form($arg{'list'},$arg{'passwd'});
	}
	elsif ($arg{'view'} eq "queuemsg") {
		&send_queuemsg($arg{'list'},$arg{'passwd'},$arg{'msgid'});
	}
	elsif ($arg{'view'} eq "queuemsg_edit") {
		&send_queuemsg_form($arg{'list'},$arg{'passwd'},$arg{'msgid'});
	}
	else {
		&send_error("View For \"$arg{'view'}\" Unavailable.");
	}
	&send_done();
}
elsif ($arg{'action'} eq "do_queue" && $modules{'modify'}) {
	# 'module' should be set from previous form, but just in
	#   case, set to make sure menu button appears 'active'
	$arg{'module'} = "modify";
	&do_queue($arg{'list'},$arg{'passwd'});
	&send_done();
}
elsif ($arg{'action'} eq "do_queuemsg" && $modules{'modify'}) {
	# 'module' should be set from previous form, but just in
	#   case, set to make sure menu button appears 'active'
	$arg{'module'} = "modify";
	&do_queuemsg($arg{'list'},$arg{'passwd'},$arg{'msgid'});
	&send_done();
}
elsif ($arg{'action'} eq "do_approve" && $modules{'modify'}) {
	# 'module' should be set from previous form, but just in
	#   case, set to make sure menu button appears 'active'
	$arg{'module'} = "modify";
	&do_approve($arg{'list'},$arg{'passwd'},$arg{'who'});
	&send_done();
}
elsif ($arg{'action'} eq "do_mkdigest" && $modules{'modify'}) {
	# 'module' should be set from previous form, but just in
	#   case, set to make sure menu button appears 'active'
	$arg{'module'} = "modify";
	&do_mkdigest($arg{'list'},$arg{'passwd'});
	&send_done();
}
elsif ($arg{'action'} eq "do_config" && $modules{'modify'}) {
	# 'module' should be set from previous form, but just in
	#   case, set to make sure menu button appears 'active'
	$arg{'module'} = "modify";
	&do_config($arg{'list'},$arg{'passwd'});
	&send_done();
}
elsif ($arg{'action'} eq "do_newconfig" && $modules{'modify'}) {
	# 'module' should be set from previous form, but just in
	#   case, set to make sure menu button appears 'active'
	$arg{'module'} = "modify";
	&do_newconfig($arg{'list'},$arg{'passwd'}, 
		&get_keywords(($arg{'keyview'} ? $arg{'keyview'} : "basic")));
	&send_done();
}
elsif ($arg{'action'} eq "do_passwd" && $modules{'modify'}) {
	# 'module' should be set from previous form, but just in
	#   case, set to make sure menu button appears 'active'
	$arg{'module'} = "modify";
	&do_passwd($arg{'list'},$arg{'old_passwd'},
		$arg{'cf_admin_passwd'},$arg{'admin_passwd2'});
	&send_done();
}
elsif ($arg{'action'} eq "do_newinfo" && $modules{'modify'}) {
	# 'module' should be set from previous form, but just in
	#   case, set to make sure menu button appears 'active'
	$arg{'module'} = "modify";
	&do_newinfo($arg{'list'},$arg{'passwd'},$arg{'info'});
	&send_done();
}
elsif ($arg{'action'} eq "do_newintro" && $modules{'modify'}) {
	# 'module' should be set from previous form, but just in
	#   case, set to make sure menu button appears 'active'
	$arg{'module'} = "modify";
	&do_newintro($arg{'list'},$arg{'passwd'},$arg{'intro'});
	&send_done();
}
elsif ($arg{'action'} eq "create" && $modules{'create'}) {
	# 'module' should be set from previous form, but just in
	#   case, set to make sure menu button appears 'active'
	$arg{'module'} = "create";
	&create_list($arg{'list'},$arg{'description'},
		$arg{'passwd'},$arg{'owner'});
	&send_done();
}
elsif ($arg{'action'} eq "rename" && $modules{'rename'}) {
	# 'module' should be set from previous form, but just in
	#   case, set to make sure menu button appears 'active'
	$arg{'module'} = "rename";
	&rename_list($arg{'list'},$arg{'newlist'},$arg{'passwd'});
	&send_done();
}
elsif ($arg{'action'} eq "delete" && $modules{'delete'}) {
	# 'module' should be set from previous form, but just in
	#   case, set to make sure menu button appears 'active'
	$arg{'module'} = "delete";
	&delete_list($arg{'list'},$arg{'passwd'});
	&send_done();
}
elsif ($arg{'action'} eq "help") {
	&send_help();
	&send_done();
}
# action=about or catch-all for unsupported functions....
else {
	# Context & buttons has been undone in the ABOUT case above.
	# Not going to worry about the catch-all case....
	&send_about();
	&send_done();
}

### END OF MAIN BODY ###

###############################################################################
# MAJORDOMO ACTIONS
###############################################################################

#-----------------------------------------------
# Build a list of commands for subscribing and
# unsubscribing based on changes made on the
# user subscription administration page.
#-----------------------------------------------
sub do_subunsub {
	local($address,@lists) = @_;
	#
	&send_error("No E-Mail Address Specified.") unless $address;
	local(@commands);
	foreach (@lists) {
		# a subscribe selection
		if ($arg{"is_$_"} eq "SUBSCRIBED") {
			# subscribe since not already
			push(@commands,"subscribe $_\n")
				unless $user_status{$_};
		}
		# an unsubscribe selection
		else {
			# unsubscribe apropos addr(s)
			if ($user_status{$_}) {
				foreach $a (split('\n', $user_status{$_})) {
					push(@commands, "unsubscribe $_ $a\n");
				}
			}
		}
	}
	if (@commands) {
		if ($opt_subverify) {
			&majordomo_deferred("Subscription Update", "",
				$address,@commands);
		}
		else {
			&majordomo_command("Subscription Update", "",
				$address,@commands);
		}
	}
	else {
		&send_error("No Changes Submitted.");
	}
}

#-----------------------------------------------
# Act upon the approval queue: delete or post.
#-----------------------------------------------
sub do_queue {
	local($list,$passwd) = @_;
	#
	&send_error("No List Specified.") unless $list;
	&send_error("List <$list> Is Not A Valid List.")
		unless &valid_list($listdir,$list);
	&get_config($listdir, $list) unless &cf_ck_bool($list, '', 1);
	&send_error("Incorrect Password For List <$list>") 
		unless &valid_passwd($listdir, $list, $passwd) ||
		($config_opts{$list,"approve_passwd"} ne '' &&
			$config_opts{$list,"approve_passwd"} eq $passwd);
	# This MODIFY action that will accept the Moderator
	# password in addition to the Admin password.

	&send_error("Approval Queue Not Supported In This Majordomo Version") 
		unless defined($config'known_keys{'bounce_action'});
	&send_error("Unsafe Queue Access! No Length Defined")
		unless $arg{'queuelen'} > 0;

	# allow global bouncedir to be set in majordomo.cf
	# if not, use the per-list archive area
	#
	local($bouncepre) = "${list}.bounce";
	unless ($bouncedir) {
		$bouncedir = "$filedir/$list$filedir_suffix";
		$bouncepre = "bounce";	# list ref in directory name
	}
	# something is not set up right! default to TMP area
	unless (-d "$bouncedir") {
		$bouncedir = "$TMPDIR";
		$bouncepre = "${list}.bounce";
	}

	&send_header(1, "Approval Queue Processing For List <$list>");
	print <<"EOT";
	$tbl_start
	<TR><TD VALIGN=TOP ALIGN=LEFT>
	<OL>
EOT
	local($msgnum) = 0;
	while ($msgnum++ < $arg{'queuelen'}) {
		local($fname) = "msg${msgnum}";
		local($msgid) = "$arg{$fname}";
		unless ($msgid) {
			&log("MODIFY queue $list error: no msgid");
			next;
		}
		$file = "$bouncedir/${bouncepre}.$msgid";
		unless (-f "$file") {
			&log("MODIFY queue $list error: invalid $msgid");
			print "<LI>$msgid: File does not exist.\n";
			next;
		}
		local($action) = "$fname"."_action";
		$action = "$arg{$action}";
		if ($action eq "hold") {
			&log("MODIFY queue $list $action <$msgid>");
			print "<LI>Skipped &lt;$msgid&gt;.\n";
		}
		elsif ($action eq "delete") {
			&send_error("Could Not Delete Message File: $!")
				unless unlink($file);
			&log("MODIFY queue $list $action <$msgid>");
			print "<LI>Removed &lt;$msgid&gt; from queue.\n";
		}
		elsif ($action eq "reject") {
			local($msgfrom) = (&queue_parse($file))[0];
			&set_mailer($bounce_mailer ? $bounce_mailer : $mailer);
			&set_mail_from("owner-$list\@$whereami");
			&set_mail_sender("owner-$list\@$whereami");
			&send_error("Could Not Open Message File: $!")
				unless &lopen(MSG, "<", "$file");
			&resendmail(MAIL, "$msgfrom", 
				"Your Posting to $list was Rejected");
			print MAIL <<"EOT";
	Your posting to $list\@$whereami was not approved by
	the list owner/moderator. This could be for a number of reasons,
	but is most frequently because:

	The content or format was inappropriate.
	   or
	You are not a member of the list.
	   or
	You are a member, but did not post from a subscribed address.

	Please check in the the body of the message below for further
	clarification/explanations (if any) from the list owner/moderator.


EOT
			while (<MSG>) { print MAIL $_; }
			&lclose(MAIL); &lclose(MSG);
			&send_error("Could Not Delete Message File: $!")
				unless unlink($file);
			&log("MODIFY queue $list $action <$msgid>");
			print "<LI>Rejected &lt;$msgid&gt; and removed from queue.\n";
		}
		elsif ($action eq "approve") {
			&set_mailer($bounce_mailer ? $bounce_mailer : $mailer);
			&set_mail_from("owner-$list");
			&set_mail_sender("owner-$list");
			&send_error("Could Not Open Message File: $!")
				unless &lopen(MSG, "<", "$file");
			&resendmail(MAIL, "$list\@$whereami",
				"Approved Posting");
			print MAIL "Approved: $passwd\n";
			while (<MSG>) { print MAIL $_; }
			&lclose(MAIL); &lclose(MSG);
			&send_error("Could Not Delete Message File: $!")
				unless unlink($file);
			&log("MODIFY queue $list $action <$msgid>");
			print "<LI>Approved &lt;$msgid&gt; and removed from queue.\n";
		}
		else {
			&log("MODIFY queue $list error: <$msgid> '$action' action");
			print "<LI>Invalid action '$action' for &lt;$msgid&gt;.\n";
		}
	}
	print <<"EOT";
	</OL>
	</TD></TR>
	$tbl_end
EOT
	&send_footer();
}

#-----------------------------------------------
# Replace raw message in the queue.
#-----------------------------------------------
sub do_queuemsg {
	local($list,$passwd,$msgid) = @_;
	#
	&send_error("No List Specified.") unless $list;
	&send_error("List <$list> Is Not A Valid List.")
		unless &valid_list($listdir,$list);
	&get_config($listdir, $list) unless &cf_ck_bool($list, '', 1);
	&send_error("Incorrect Password For List <$list>") 
		unless &valid_passwd($listdir, $list, $passwd) ||
		($config_opts{$list,"approve_passwd"} ne '' &&
			$config_opts{$list,"approve_passwd"} eq $passwd);
	# This MODIFY action will accept the Moderator
	# password in addition to the Admin password.

	# allow global bouncedir to be set in majordomo.cf
	# if not, use the per-list archive area
	#
	local($bouncepre) = "${list}.bounce";
	unless ($bouncedir) {
		$bouncedir = "$filedir/$list$filedir_suffix";
		$bouncepre = "bounce";	# list ref in directory name
	}
	# something is not set up right! default to TMP area
	unless (-d "$bouncedir") {
		$bouncedir = "$TMPDIR";
		$bouncepre = "${list}.bounce";
	}

	&send_error("Invalid Message-ID")
		unless -f "$bouncedir/${bouncepre}.$msgid";
	&send_error("Unable To Open Message: $!")
		unless &lopen(MSG, ">", "$bouncedir/${bouncepre}.$msgid");
	print MSG $arg{header}, "\n", $arg{body};
	&lclose(MSG);

	&log("MODIFY queue $list update <$msgid>");
	&send_queue_form($list,$passwd);
}

#-----------------------------------------------
# Diff the new subscriber list with old (in temp 
# file) to get additions & deletions.
#-----------------------------------------------
sub do_approve {
	local($list,$passwd,$who) = @_;
	#
	&send_error("No List Specified.") unless $list;
	&send_error("List <$list> Is Not A Valid List.")
		unless &valid_list($listdir,$list);
	&send_error("Unsafe Usage! Timestamp For <$list> Not Found.")
		unless $arg{'whotime'};
	&send_error("Unsafe Usage! Snapshot For <$list> Not Found.")
		unless -f "COOL_TMPDIR/$list.$arg{'whotime'}";
	unless ($arg{sizeaction} eq "append") {
		@oldlist = &get_who("COOL_TMPDIR/$list.$arg{'whotime'}",
			0, $arg{'subset'});	# sorting doesn't matter
	}
	foreach (split(/[\r\n]+/,$who)) {
		next if /^\s*$/;
		push(@newlist,$_);
	}
	# diff the list; changes will appear as unsub/sub pairing,
	#   so the order is important!
	#
	# find all deletions
	local(%member) = ();
	foreach (@newlist) { $member{$_}++; }
	foreach (@oldlist) {
		push(@commands, "approve $passwd unsubscribe $list $_\n")
			if ! $member{$_};
	}
	# find all additions
	local(%member) = ();
	foreach (@oldlist) { $member{$_}++; }
	foreach (@newlist) {
		push(@commands, "approve $passwd subscribe $list $_\n")
			if ! $member{$_};
	}
	# we don't need the snapshot file anymore
	unlink("COOL_TMPDIR/$list.$arg{'whotime'}");
	if (@commands) {
		&majordomo_command("List Subscriber Update", "",
			$arg{'submit_as'},
			@commands);
	}
	else {
		&send_error("No Changes Submitted.");
	}
}

#-----------------------------------------------
# Make a digest of pending posts.
#-----------------------------------------------
sub do_mkdigest {
	local($list,$passwd) = @_;
	#
	&send_error("No List Specified.") unless $list;
	&send_error("List <$list> Is Not A Valid List.")
		unless &valid_list($listdir,$list);
	&get_config($listdir, $list) unless &cf_ck_bool($list, '', 1);
	&send_error("Incorrect Password For List <$list>") 
		unless &valid_passwd($listdir, $list, $passwd);
	$arg{'submit_as'} = (defined($config_opts{$list,owner}) ? 
		$config_opts{$list,owner} : "owner-$list\@$whereami")
			unless ($arg{'submit_as'});
	&majordomo_command("Digest Creation", "",
		$arg{'submit_as'},
		"mkdigest $list $passwd\n");
}

#-----------------------------------------------
# Mail full config file to the user.
#-----------------------------------------------
sub do_config {
	local($list,$passwd) = @_;
	#
	&send_error("No List Specified.") unless $list;
	&send_error("List <$list> Is Not A Valid List.")
		unless &valid_list($listdir,$list);
	&get_config($listdir, $list) unless &cf_ck_bool($list, '', 1);
	&send_error("Incorrect Password For List <$list>") 
		unless &valid_passwd($listdir, $list, $passwd);
	$arg{'submit_as'} = (defined($config_opts{$list,owner}) ? 
		$config_opts{$list,owner} : "owner-$list\@$whereami")
			unless ($arg{'submit_as'});
	&majordomo_command("List Configuration Send", "",
		$arg{'submit_as'},
		"config $list $passwd\n");
}

#-----------------------------------------------
# Process changes from configuration form.
#-----------------------------------------------
sub do_newconfig {
	local($list,$passwd,@keywords) = @_;
	#
	&send_error("No List Specified.") unless $list;
	&send_error("List <$list> Is Not A Valid List.")
		unless &valid_list($listdir,$list);
	&get_config($listdir, $list) unless &cf_ck_bool($list, '', 1);
	&send_error("Incorrect Password For List <$list>") 
		unless &valid_passwd($listdir, $list, $passwd);
	local(@lines);
	foreach (@keywords) {
		# skip bogus keywords
		next unless defined($config'known_keys{$_});
		# don't change keys not specified
		next unless defined($arg{"cf_$_"});
		# multiline entries are separated by ^A
		$arg{"cf_$_"} =~ s/\n/\001/go;
		# set the value
		$config_opts{$list,$_} = $arg{"cf_$_"};
	}
	# print out the keys & values
	foreach (sort keys(%config'known_keys)) {
		local($type) = $config'parse_function{$_};
		$type  =~ s/^grab_//o;
		local($lval) = $config_opts{$list,$_};
		$lval = ("no","yes")[$lval] 
			if $type eq "bool" && $lval =~ /\d+/o;

		# this code snip taken from Majordomo verbatim
		if ($type =~ "array") {
			# handle the - escapes. We have to be careful about ordering
			# the rules so that we don't accidently trigger a substitution
			# if there is a - at the beginning of an entry, double it
			# so that the doubled - can be stripped when read in later
			#$lval =~ s/^-/--/go;			# start with -'ed line
			#$lval =~ s/\001-/\001--/go;		# embedded line starting with -

			# In standard form, empty lines are lines that have only
			# a '-' on the line.
			$lval =~ s/^\001/-\001/go;		# start with blank line
			$lval =~ s/\001\001/\001-\001/go;	# embedded blank line

			# if there is space, protect it with a -
			$lval =~ s/^(\s)/-$1/g;			# the first line
			$lval =~ s/\001(\s)/\001-$1/g;		# embedded lines

			# now that all of the escapes are processed, get it ready
			# to be printed.
			$lval =~ s/\001/\n/go;

			push(@lines, "$_ << END\n");
			push(@lines, "$lval\n");
			push(@lines, "END\n");
		}
		else {
			push(@lines, "$_ = $lval\n");
		}
	}
	# all commands sent as owner-list unless overridden
	$arg{'submit_as'} = (defined($config_opts{$list,owner}) ? 
		$config_opts{$list,owner} : "owner-$list\@$whereami")
			unless ($arg{'submit_as'});
	# newconfig w/o comments (using supplied passwd)
	# writeconfig to force comments (using config passwd)
	# 	[since there may be a passwd change involved]
	&majordomo_command("List Configuration Update", "",
		$arg{'submit_as'},
		"newconfig $list $passwd\n",
		@lines,
		"\nEOF\n",
		($opt_cfcomments 
		  ? "writeconfig $list $config_opts{$list,admin_passwd}\n"
		  : ""));
}

#-----------------------------------------------
# Process password change information.
# (If done separate from config field.)
#-----------------------------------------------
sub do_passwd {
	local($list,$old_passwd,$new_passwd1,$new_passwd2) = @_;
	#
	&send_error("No List Specified.") unless $list;
	&send_error("List <$list> Is Not A Valid List.")
		unless &valid_list($listdir,$list);
	&get_config($listdir, $list) unless &cf_ck_bool($list, '', 1);
	&send_error("Old Password Is Not Correct.") 
		unless &valid_passwd($listdir, $list, $old_passwd);
	&send_error("New Password Cannot Contain Whitespace.") 
		if ($new_passwd1 =~ /\s+/o);
	&send_error("New Password Verification Does Not Match.") 
		unless ($new_passwd1 eq $new_passwd2);
	$arg{'submit_as'} = (defined($config_opts{$list,owner}) ? 
		$config_opts{$list,owner} : "owner-$list\@$whereami")
			unless ($arg{'submit_as'});
	######## old MASTER.PASSWD dependent way
	## using file droppings: must use passwd command to change
	##  (unless the Dave Wolfe master password technique is used)
	#if (-w "$listdir/$list.passwd" && ! -f "$listdir/MASTER.PASSWD") {
	#	&majordomo_command("List Password Update", "",
	#		$arg{'submit_as'},
	#		"passwd $list $old_passwd $new_passwd1\n");
	#}
	## using passwd contained in config file: issue newconfig
	#else {
	#	&do_newconfig($list, $old_passwd, "admin_passwd");
	#}
	######## new more Majordomo-ish way
	# if passwd file is write-protected, use config entry
	if (-e "$listdir/$list.passwd" && ! -w "$listdir/$list.passwd") {
		&do_newconfig($list, $old_passwd, "admin_passwd");
	}
	# else use passwd command to create file
	else {
		&majordomo_command("List Password Update", "",
			$arg{'submit_as'},
			"passwd $list $old_passwd $new_passwd1\n");
	}
}

#-----------------------------------------------
# Process new info file.
#-----------------------------------------------
sub do_newinfo {
	local($list,$passwd,$info) = @_;
	#
	&send_error("No List Specified.") unless $list;
	&send_error("List <$list> Is Not A Valid List.")
		unless &valid_list($listdir,$list);
	&get_config($listdir, $list) unless &cf_ck_bool($list, '', 1);
	&send_error("Incorrect Password For List <$list>") 
		unless &valid_passwd($listdir, $list, $passwd);
	$arg{'submit_as'} = (defined($config_opts{$list,owner}) ? 
		$config_opts{$list,owner} : "owner-$list\@$whereami")
			unless ($arg{'submit_as'});
	@info = split('\r',$info);
	&majordomo_command("INFO File Update", "",
		$arg{'submit_as'},
		"newinfo $list $passwd\n",
		@info,
		"\nEOF\n");
}

#-----------------------------------------------
# Process new intro file.
#-----------------------------------------------
sub do_newintro {
	local($list,$passwd,$intro) = @_;
	#
	&send_error("No List Specified.") unless $list;
	&send_error("List <$list> Is Not A Valid List.")
		unless &valid_list($listdir,$list);
	&get_config($listdir, $list) unless &cf_ck_bool($list, '', 1);
	&send_error("Incorrect Password For List <$list>") 
		unless &valid_passwd($listdir, $list, $passwd);
	$arg{'submit_as'} = (defined($config_opts{$list,owner}) ? 
		$config_opts{$list,owner} : "owner-$list\@$whereami")
			unless ($arg{'submit_as'});
	@intro = split('\r',$intro);
	&majordomo_command("INTRO File Update", "",
		$arg{'submit_as'},
		"newintro $list $passwd\n",
		@intro,
		"\nEOF\n");
}

#-----------------------------------------------
# Wholesale list replacement (no longer used).
#-----------------------------------------------
sub do_oldwho {
	local($list,$passwd,$who) = @_;
	#
	&send_error("No List Specified.") unless $list;
	&send_error("List <$list> Is Not A Valid List.")
		unless &valid_list($listdir,$list);
	&get_config($listdir, $list) unless &cf_ck_bool($list, '', 1);
	&send_error("Incorrect Password For List <$list>") 
		unless &valid_passwd($listdir, $list, $passwd);
	$arg{'submit_as'} = (defined($config_opts{$list,owner}) ? 
		$config_opts{$list,owner} : "owner-$list\@$whereami")
			unless ($arg{'submit_as'});
	foreach (split(/\r/,$who)) {
		next if /^\s*$/;
		push(@who,$_);
	}
	&majordomo_command("List Subscriber Update", "",
		$arg{'submit_as'},
		"newwho $list $passwd\n",
		@who,
		"\nEOF\n");
}

#-----------------------------------------------
# Create a list. Send mail to majordomo-owner to 
# ask, or call other program to do something.
#-----------------------------------------------
sub create_list {
	local($list,$desc,$passwd,$owner) = @_;
	#
	&send_error("Missing Data. Please Provide All Requested Information.") 
		unless $list && $desc && $passwd && $owner;
	&send_error("Listname Can Contain Alphanumeric and Hyphens Only.") 
		unless $list =~ /^[-\w]+$/o;
	&send_error("Password Cannot Contain Whitespace.") 
		if $passwd =~ /\s+/o;
	#
	# Besides just sending mail, I would like to be able to optionally:
	# a) provide a script (similar to approve/bounce) such that the
	#    majordomo-owner could pipe the mail to a program that would
	#    read the format and automatically create the list with approved
	#    attributes
	#	
	# b) for the really trustworthy sites, exec a command directly that
	#    would do all this without human intervention
	#
	local($subj) = "Majordomo list creation request ($list)";
	local($mesg);
	&log("CREATE $list");
	&send_header(1, "List Creation Request For <$list>");
	# no single quoting allowed
	$desc =~ s/'//go; $owner =~ s/'//go; $passwd =~ s/'//go;
	if (-f "$list_create_cmd" && -x "$list_create_cmd" && open(REQ, 
	  "$list_create_cmd -d '$desc' -o '$owner' -p '$passwd' $list 2>&1|")) {
		select((select(REQ), $|=1)[0]);		# unbuffered
		print "$tbl_start<TR><TD VALIGN=TOP ALIGN=LEFT>";
		print "<P>Here are the results from the list creation command:\n";
		print "<HR ALIGN=CENTER WIDTH=75%><PRE>";
		while (<REQ>) { print $_; }
		print "</PRE>";
		print "</TD></TR>\n$tbl_end";
		close(REQ);
		$subj = "FYI: $subj";
		$mesg = "No action is required on your part.";
	}
	else {
		print "<H3 ALIGN=CENTER>Request Sent to $whoami_owner</H3>";
		print "<H5 ALIGN=CENTER>You will be contacted later.</H5>";
	}
	# if everything's gone OK so far, always let the admin know. this
	# is either a full-fledged request (no list_create_cmd implemented)
	#   or an FYI after a successful list_create_cmd operation
	#
	if ($?>>8 == 0) {
		&set_mailer($bounce_mailer ? $bounce_mailer : $mailer);
		&set_mail_from($whoami_owner);
		&set_mail_sender($whoami_owner);
		&sendmail(MAIL, $whoami_owner, $subj);
		print MAIL <<"EOT";
"$owner" has requested that the following
Majordomo list be created:

	List: $list
	Description: $desc
	Password: $passwd
	Owner: $owner

$mesg
EOT
		close(MAIL);
	}
	&send_warm_fuzzy($owner);
	&send_footer();
}

#-----------------------------------------------
# Rename a list. Send request to majordomo-owner
# to ask, or call external program to do other
# action.
#-----------------------------------------------
sub rename_list {
	local($list,$newlist,$passwd) = @_;
	#
	&send_error("Missing Data. Please Provide All Requested Information.") 
		unless $list && $newlist && $passwd;
	&send_error("Listname Can Contain Alphanumeric and Hyphens Only.") 
		unless $list =~ /^[-\w]+$/o;
	&send_error("Listname Can Contain Alphanumeric and Hyphens Only.") 
		unless $newlist =~ /^[-\w]+$/o;
	&send_error("List <$list> Is Not A Valid List.")
		unless &valid_list($listdir,$list);
	&get_config($listdir, $list) unless &cf_ck_bool($list, '', 1);
	&send_error("Incorrect Password For List <$list>") 
		unless &valid_passwd($listdir, $list, $passwd);
	$owner = (defined($config_opts{$list,owner}) ? 
		$config_opts{$list,owner} : "owner-$list\@$whereami");
	local($subj) = "Majordomo list renaming request ($list)";
	local($mesg);
	&log("RENAME $list $newlist");
	&send_header(1, "List Renaming Request For <$list>");
	if (-f "$list_rename_cmd" && -x "$list_rename_cmd" && open(REQ, 
	  "$list_rename_cmd -o '$owner' -p '$passwd' $list $newlist 2>&1|")) {
		select((select(REQ), $|=1)[$[]);		# unbuffered
		print "$tbl_start<TR><TD VALIGN=TOP ALIGN=LEFT>";
		print "<P>Here are the results from the list renaming command:\n";
		print "<HR ALIGN=CENTER WIDTH=75%><PRE>";
		while (<REQ>) { print $_; }
		print "</PRE>";
		print "</TD></TR>\n$tbl_end";
		close(REQ);
		$subj = "FYI: $subj";
		$mesg = "No action is required on your part.";
	}
	else {
		print "<H3 ALIGN=CENTER>Request Sent to $whoami_owner</H3>";
		print "<H5 ALIGN=CENTER>You will be contacted later.</H5>";
	}
	# if everything's gone OK so far, always let the admin know. this
	# is either a full-fledged request (no list_rename_cmd implemented)
	#   or an FYI after a successful list_rename_cmd operation
	#
	if ($?>>8 == 0) {
		&set_mailer($bounce_mailer ? $bounce_mailer : $mailer);
		&set_mail_from($whoami_owner);
		&set_mail_sender($whoami_owner);
		&sendmail(MAIL, $whoami_owner, $subj);
		print MAIL <<"EOT";
It has been requested (and validated via password) that the
following Majordomo list be renamed:

	Old List: $list
	New List: $newlist
	Owner: $owner
	Password: $passwd

$mesg
EOT
		close(MAIL);
	}
	&send_warm_fuzzy($owner);
	&send_footer();
}

#-----------------------------------------------
# Delete a list. Send request to majordomo-owner
# to ask, or call external program to do other
# action.
#-----------------------------------------------
sub delete_list {
	local($list,$passwd) = @_;
	#
	&send_error("Missing Data. Please Provide All Requested Information.") 
		unless $list && $passwd;
	&send_error("List <$list> Is Not A Valid List.")
		unless &valid_list($listdir,$list);
	&get_config($listdir, $list) unless &cf_ck_bool($list, '', 1);
	&send_error("Incorrect Password For List <$list>") 
		unless &valid_passwd($listdir, $list, $passwd);
	$owner = (defined($config_opts{$list,owner}) ? 
		$config_opts{$list,owner} : "owner-$list\@$whereami");
	local($subj) = "Majordomo list deletion request ($list)";
	local($mesg);
	&log("DELETE $list");
	&send_header(1, "List Deletion Request For <$list>");
	if (-f "$list_delete_cmd" && -x "$list_delete_cmd" && open(REQ, 
	  "$list_delete_cmd -o '$owner' -p '$passwd' $list 2>&1|")) {
		select((select(REQ), $|=1)[$[]);		# unbuffered
		print "$tbl_start<TR><TD VALIGN=TOP ALIGN=LEFT>";
		print "<P>Here are the results from the list deletion command:\n";
		print "<HR ALIGN=CENTER WIDTH=75%><PRE>";
		while (<REQ>) { print $_; }
		print "</PRE>";
		print "</TD></TR>\n$tbl_end";
		close(REQ);
		$subj = "FYI: $subj";
		$mesg = "No action is required on your part.";
	}
	else {
		print "<H3 ALIGN=CENTER>Request Sent to $whoami_owner</H3>";
		print "<H5 ALIGN=CENTER>You will be contacted later.</H5>";
	}
	# if everything's gone OK so far, always let the admin know. this
	# is either a full-fledged request (no list_delete_cmd implemented)
	#   or an FYI after a successful list_delete_cmd operation
	#
	if ($?>>8 == 0) {
		&set_mailer($bounce_mailer ? $bounce_mailer : $mailer);
		&set_mail_from($whoami_owner);
		&set_mail_sender($whoami_owner);
		&sendmail(MAIL, $whoami_owner, $subj);
		print MAIL <<"EOT";
It has been requested (and validated via password) that the
following Majordomo list be deleted:

	List: $list
	Owner: $owner
	Password: $passwd

$mesg
EOT
		close(MAIL);
	}
	&send_warm_fuzzy($owner);
	&send_footer();
}

###############################################################################
# FORM OUTPUT SUBROUTINES
###############################################################################

#-----------------------------------------------
# Initial query form; could be menu mode or
# all modules on single screen.
#-----------------------------------------------
sub send_main_form {
	&send_header(0, "$sitename Mailing Lists");
	&send_browse_form if ! $prefs{GenMenuMode} ||
		($prefs{GenMenuMode} && $arg{'module'} eq "browse");
	&send_modify_form if ! $prefs{GenMenuMode} ||
		($prefs{GenMenuMode} && $arg{'module'} eq "modify");
	&send_create_form if ! $prefs{GenMenuMode} ||
		($prefs{GenMenuMode} && $arg{'module'} eq "create");
	&send_rename_form if ! $prefs{GenMenuMode} ||
		($prefs{GenMenuMode} && $arg{'module'} eq "rename");
	&send_delete_form if ! $prefs{GenMenuMode} ||
		($prefs{GenMenuMode} && $arg{'module'} eq "delete");
	&send_footer();
}

#-----------------------------------------------
# Query for email; show matching lists.
#-----------------------------------------------
sub send_browse_form {
	if ($modules{browse}) {
	# set some button defaults
	$arg{'criteria'} = "subscribed" unless $arg{'criteria'};
	local($listtypes);
	$listtypes .= 
		"<INPUT TYPE=radio NAME=criteria VALUE=subscribed".
		($arg{'criteria'} eq "subscribed" ? " CHECKED>" : ">").
		"Subscribed &nbsp;".
		"<INPUT TYPE=radio NAME=criteria VALUE=unsubscribed".
		($arg{'criteria'} eq "unsubscribed" ? " CHECKED>" : ">").
		"Unsubscribed &nbsp;".
		"<INPUT TYPE=radio NAME=criteria VALUE=available".
		($arg{'criteria'} eq "available" ? " CHECKED>" : ">").
		"All<BR>";
	if (defined($config'known_keys{owner})) {
		$listtypes .= 
			"<INPUT TYPE=radio NAME=criteria VALUE=owned".
			($arg{'criteria'} eq "owned" ? " CHECKED>" : ">").
			"My Owned Lists &nbsp;";
		$listtypes .= 
			"<INPUT TYPE=radio NAME=criteria VALUE=unconfigured".
			($arg{'criteria'} eq "unconfigured" ? " CHECKED>" : ">").
			"Owner-less<BR>";
	}
	$listtypes .= 
		"<INPUT TYPE=radio NAME=criteria VALUE=matched".
		($arg{'criteria'} eq "matched" ? " CHECKED>" : ">").
		"Find: ".
		"<INPUT TYPE=text SIZE=12 MAXLENGTH=32 NAME=list_match".
		$arg{'list_match'}.
		"> <INPUT TYPE=checkbox NAME=list_exact>Exact Match<BR>";
	local($submit) = "<INPUT TYPE=\"submit\" VALUE=\"Browse\">";
	$submit = "<INPUT TYPE=\"image\" NAME=\"submit\" "
		."ALT=\"[Apply Changes]\" "
		."SRC=\"WEB_IMGURL/mc_action_button.gif\" BORDER=0>"
		if $prefs{GenSubmitImages};
	local($submit_fmt) = "<TD VALIGN=TOP ALIGN=CENTER COLSPAN=2>";
	$submit_fmt = "<TD VALIGN=TOP ALIGN=RIGHT></TD>"
		."<TD VALIGN=TOP ALIGN=LEFT>"
		if $prefs{GenMenuMode};
	print "$tbl_start";
	print "<TR><TD ALIGN=CENTER VALIGN=TOP>";
	print &imgsrc(&img('browse','banner'),
		"ALT=\"[BROWSE LISTS]\" ALIGN=MIDDLE");
	print <<"EOT";
	<H4 ALIGN=CENTER>$tip{browse}</H4>
	</TD></TR>
	<TR><TD ALIGN=LEFT VALIGN=TOP>$siteaddr{browse}</TD></TR>
	$tbl_end
	$tbl_start
	<FORM NAME=\"browse\" METHOD=POST ACTION=\"$url\">
		<INPUT TYPE=\"hidden\" NAME=\"module\" VALUE=\"browse\">
		<INPUT TYPE=\"hidden\" NAME=\"action\" VALUE=\"status\">
		<TR><TD VALIGN=TOP ALIGN=RIGHT>
			$siteaddr{prompt}
		</TD>
		<TD VALIGN=TOP ALIGN=LEFT>
			<INPUT TYPE=\"text\" NAME=\"siteaddr\" 
				VALUE=\"$arg{siteaddr}\"
				SIZE=32 MAXLENGTH=64>
		</TD></TR>
		<TR><TD VALIGN=TOP ALIGN=RIGHT>
			Browse Which Lists?
		</TD>
		<TD VALIGN=TOP ALIGN=LEFT>
			$listtypes
		</TD></TR>
		<TR><TD VALIGN=TOP ALIGN=RIGHT>
			QuickView Mode?<BR>
		</TD>
		<TD VALIGN=TOP ALIGN=LEFT>
			<INPUT TYPE=\"checkbox\" NAME=\"quickview\">
			<FONT SIZE=-1>
			(No Subscription Tests For Faster Browsing)
			</FONT>
		</TD></TR>
		<TR><TD COLSPAN=2>&nbsp;</TD></TR>
		<TR>$submit_fmt
			$submit
		</TD></TR>
		$tbl_end
	</FORM>
EOT
	}
	else {
	}
}

#-----------------------------------------------
# Prepare to modify list config/info/list file.
#-----------------------------------------------
sub send_modify_form {
	if ($modules{modify}) {
	# set some button defaults
	$arg{'view'} = "list" unless $arg{'view'};
	local($listfield);
	if ($opt_hiddenlists) {
		$listfield = "<INPUT TYPE=\"text\" "
			."NAME=\"list\" VALUE=\"$arg{list}\" "
			."SIZE=32 MAXLENGTH=64>";
	}
	else {
		&load_cache();
		$listfield = "<SELECT NAME=\"list\" SIZE=1>\n";
		foreach (sort (keys(%cached_descr))) {
			$listfield .=" <OPTION VALUE=\"$_\" "
				.($arg{list} eq $_ ? "SELECTED" : "")
				.">$_\n";
		}
		$listfield .= "</SELECT>\n";
	}
	local($offer) =
		"<INPUT TYPE=radio NAME=view VALUE=list".
		($arg{'view'} eq "list" ? " CHECKED>" : ">")."\n".
		"List Subscribers<BR>".
		"<INPUT TYPE=radio NAME=view VALUE=config".
		($arg{'view'} eq "config" ? " CHECKED>" : ">")."\n".
		"Configuration Options<BR>".
		"<INPUT TYPE=radio NAME=view VALUE=info".
		($arg{'view'} eq "info" ? " CHECKED>" : ">")."\n".
		"List Info File<BR>";
	local($offer_intro) = 
		"<INPUT TYPE=radio NAME=view VALUE=intro".
		($arg{'view'} eq "intro" ? " CHECKED>" : ">")."\n".
		"List Intro File<BR>" 
		if defined($config'known_keys{'intro_access'});
	local($offer_queue) =
		"<INPUT TYPE=radio NAME=view VALUE=queue".
		($arg{'view'} eq "queue" ? " CHECKED>" : ">")."\n".
		"Approval Queue<BR>"
		if defined($config'known_keys{'bounce_action'});
	local($submit) = "<INPUT TYPE=\"submit\" VALUE=\"Modify\">";
	$submit = "<INPUT TYPE=\"image\" NAME=\"submit\" ".
		"ALT=\"[Apply Changes]\" ".
		"SRC=\"WEB_IMGURL/mc_action_button.gif\" BORDER=0>"
		if $prefs{GenSubmitImages};
	local($submit_fmt) = "<TD VALIGN=TOP ALIGN=CENTER COLSPAN=2>";
	$submit_fmt = "<TD VALIGN=TOP ALIGN=RIGHT></TD>".
		"<TD VALIGN=TOP ALIGN=LEFT>"
		if $prefs{GenMenuMode};
	print "$tbl_start";
	print "<TR><TD ALIGN=CENTER VALIGN=TOP COLSPAN=2>";
	print &imgsrc(&img('modify','banner'),
		"ALT=\"[MODIFY A LIST]\" ALIGN=MIDDLE");
	print <<"EOT";
	<H4 ALIGN=CENTER>$tip{modify}</H4>
	</TD></TR>
	<TR><TD ALIGN=LEFT VALIGN=TOP>$siteaddr{modify}</TD></TR>
	$tbl_end
	$tbl_start
	<FORM NAME=\"modify\" METHOD=POST ACTION=\"$url\">
		<INPUT TYPE=\"hidden\" NAME=\"module\" VALUE=\"modify\">
		<INPUT TYPE=\"hidden\" NAME=\"action\" VALUE=\"edit\">
		<TR><TD VALIGN=TOP ALIGN=RIGHT>
			List Name
		</TD>
		<TD VALIGN=TOP ALIGN=LEFT>
			$listfield
		</TD></TR>
		<TR><TD VALIGN=TOP ALIGN=RIGHT>
			Admin Password
		</TD>
		<TD VALIGN=TOP ALIGN=LEFT>
			<INPUT TYPE=\"password\" NAME=\"passwd\" 
				VALUE=\"$arg{passwd}\"
				SIZE=32 MAXLENGTH=64>
		</TD></TR>
		<TR><TD VALIGN=TOP ALIGN=RIGHT>
			Modify What?
		</TD>
		<TD VALIGN=TOP ALIGN=LEFT>
			$offer
			$offer_intro
			$offer_queue
		</TD></TR>
		<TR><TD COLSPAN=2>&nbsp;</TD></TR>
		<TR>$submit_fmt
			$submit
		</TD></TR>
		$tbl_end
	</FORM>
EOT
	}
	else {
	}
}

#-----------------------------------------------
# Query for list to create.
#-----------------------------------------------
sub send_create_form {
	if ($modules{create}) {
	local($submit) = "<INPUT TYPE=\"submit\" VALUE=\"Create\"></TD>";
	$submit = "<INPUT TYPE=\"image\" NAME=\"submit\" "
		."ALT=\"[Create It]\" "
		."SRC=\"WEB_IMGURL/mc_action_button.gif\" BORDER=0>"
		."</TD>"
		if $prefs{GenSubmitImages};
	local($submit_fmt) = "<TD VALIGN=TOP ALIGN=CENTER COLSPAN=2>";
	$submit_fmt = "<TD VALIGN=TOP ALIGN=RIGHT>"
		."<TD VALIGN=TOP ALIGN=LEFT>"
		if $prefs{GenMenuMode};
	print "$tbl_start";
	print "<TR><TD ALIGN=CENTER VALIGN=TOP COLSPAN=2>";
	print &imgsrc(&img('create','banner'),
		"ALT=\"[CREATE A LIST]\" ALIGN=MIDDLE");
	print <<"EOT";
	<H4 ALIGN=CENTER>$tip{create}</H4>
	</TD></TR>
	<TR><TD ALIGN=LEFT VALIGN=TOP>$siteaddr{create}</TD></TR>
	$tbl_end
	$tbl_start
	<FORM NAME=\"create\" METHOD=POST ACTION=\"$url\">
		<INPUT TYPE=\"hidden\" NAME=\"module\" VALUE=\"create\">
		<INPUT TYPE=\"hidden\" NAME=\"action\" VALUE=\"create\">
		<TR><TD VALIGN=TOP ALIGN=RIGHT>
			List Name
		</TD>
		<TD VALIGN=TOP ALIGN=LEFT>
			<INPUT TYPE=\"text\" NAME=\"list\" 
			VALUE=\"$arg{list}\"
			SIZE=32 MAXLENGTH=64>
		</TD></TR>
		<TR><TD VALIGN=TOP ALIGN=RIGHT>
			List Description
		</TD>
		<TD VALIGN=TOP ALIGN=LEFT>
			<INPUT TYPE=\"text\" NAME=\"description\"
				VALUE=\"$arg{description}\"
				SIZE=32 MAXLENGTH=64>
		</TD></TR>
		<TR><TD VALIGN=TOP ALIGN=RIGHT>
			Initial Admin Password
		</TD>
		<TD VALIGN=TOP ALIGN=LEFT>
			<INPUT TYPE=\"password\" NAME=\"passwd\" 
				VALUE=\"$arg{passwd}\"
				SIZE=32 MAXLENGTH=64>
		</TD></TR>
		<TR><TD VALIGN=TOP ALIGN=RIGHT>
			Owner's E-Mail Address
		</TD>
		<TD VALIGN=TOP ALIGN=LEFT>
			<INPUT TYPE=\"text\" NAME=\"owner\" 
				VALUE=\"$arg{owner}\"
				SIZE=32 MAXLENGTH=64>
		</TD></TR>
		<TR><TD COLSPAN=2>&nbsp;</TD></TR>
		<TR>$submit_fmt
			$submit
		</TD></TR>
		$tbl_end
	</FORM>
EOT
	}
	else {
	}
}

#-----------------------------------------------
# Query for list to rename.
#-----------------------------------------------
sub send_rename_form {
	if ($modules{rename}) {
	local($submit) = "<INPUT TYPE=\"submit\" VALUE=\"Rename\"></TD>";
	$submit = "<INPUT TYPE=\"image\" NAME=\"submit\" "
		."ALT=\"[Rename It]\" "
		."SRC=\"WEB_IMGURL/mc_action_button.gif\" BORDER=0>"
		."</TD>"
		if $prefs{GenSubmitImages};
	local($submit_fmt) = "<TD VALIGN=TOP ALIGN=CENTER COLSPAN=2>";
	$submit_fmt = "<TD VALIGN=TOP ALIGN=RIGHT>"
		."<TD VALIGN=TOP ALIGN=LEFT>"
		if $prefs{GenMenuMode};
	print "$tbl_start";
	print "<TR><TD ALIGN=CENTER VALIGN=TOP COLSPAN=2>";
	print &imgsrc(&img('rename','banner'),
		"ALT=\"[RENAME A LIST]\" ALIGN=MIDDLE");
	print <<"EOT";
	<H4 ALIGN=CENTER>$tip{rename}</H4>
	</TD></TR>
	<TR><TD ALIGN=LEFT VALIGN=TOP>$siteaddr{rename}</TD></TR>
	$tbl_end
	$tbl_start
	<FORM NAME=\"rename\" METHOD=POST ACTION=\"$url\">
		<INPUT TYPE=\"hidden\" NAME=\"module\" VALUE=\"rename\">
		<INPUT TYPE=\"hidden\" NAME=\"action\" VALUE=\"rename\">
		<TR><TD VALIGN=TOP ALIGN=RIGHT>
			Old List Name
		</TD>
		<TD VALIGN=TOP ALIGN=LEFT>
			<INPUT TYPE=\"text\" NAME=\"list\" 
			VALUE=\"$arg{list}\"
			SIZE=32 MAXLENGTH=64>
		</TD></TR>
		<TR><TD VALIGN=TOP ALIGN=RIGHT>
			New List Name
		</TD>
		<TD VALIGN=TOP ALIGN=LEFT>
			<INPUT TYPE=\"text\" NAME=\"newlist\" 
			VALUE=\"$arg{newlist}\"
			SIZE=32 MAXLENGTH=64>
		</TD></TR>
		<TR><TD VALIGN=TOP ALIGN=RIGHT>
			Admin Password
		</TD>
		<TD VALIGN=TOP ALIGN=LEFT>
			<INPUT TYPE=\"password\" NAME=\"passwd\" 
				VALUE=\"$arg{passwd}\"
				SIZE=32 MAXLENGTH=64>
		</TD></TR>
		<TR><TD COLSPAN=2>&nbsp;</TD></TR>
		<TR>$submit_fmt
			$submit
		</TD></TR>
		$tbl_end
	</FORM>
EOT
	}
	else {
	}
}

#-----------------------------------------------
# Query for list to delete.
#-----------------------------------------------
sub send_delete_form {
	if ($modules{'delete'}) {
	local($submit) = "<INPUT TYPE=\"submit\" VALUE=\"Delete\">";
	$submit = "<INPUT TYPE=\"image\" NAME=\"submit\" "
		."ALT=\"[Delete It]\" "
		."SRC=\"WEB_IMGURL/mc_action_button.gif\" BORDER=0>" 
		if $prefs{GenSubmitImages};
	local($submit_fmt) = "<TD VALIGN=TOP ALIGN=CENTER COLSPAN=2>";
	$submit_fmt = "<TD VALIGN=TOP ALIGN=RIGHT></TD>"
		."<TD VALIGN=TOP ALIGN=LEFT>"
		if $prefs{GenMenuMode};
	print "$tbl_start";
	print "<TR><TD ALIGN=CENTER VALIGN=TOP COLSPAN=2>";
	print &imgsrc(&img('delete','banner'),
		"ALT=\"[DELETE A LIST]\" ALIGN=MIDDLE");
	print <<"EOT";
	<H4 ALIGN=CENTER>$tip{delete}</H4>
	</TD></TR>
	<TR><TD ALIGN=LEFT VALIGN=TOP>$siteaddr{'delete'}</TD></TR>
	$tbl_end
	$tbl_start
	<FORM NAME=\"delete\" METHOD=POST ACTION=\"$url\">
		<INPUT TYPE=\"hidden\" NAME=\"module\" VALUE=\"delete\">
		<INPUT TYPE=\"hidden\" NAME=\"action\" VALUE=\"delete\">
		<TR><TD VALIGN=TOP ALIGN=RIGHT>
			List Name
		</TD>
		<TD VALIGN=TOP ALIGN=LEFT>
			<INPUT TYPE=\"text\" NAME=\"list\" 
				VALUE=\"$arg{list}\"
				SIZE=32 MAXLENGTH=64>
		</TD></TR>
		<TR><TD VALIGN=TOP ALIGN=RIGHT>
			Admin Password
		</TD>
		<TD VALIGN=TOP ALIGN=LEFT>
			<INPUT TYPE=\"password\" NAME=\"passwd\" 
				VALUE=\"$arg{passwd}\"
				SIZE=32 MAXLENGTH=64>
		</TD></TR>
		<TR><TD COLSPAN=2>&nbsp;</TD></TR>
		<TR>$submit_fmt
			$submit
		</TD></TR>
		$tbl_end
	</FORM>
EOT
	}
	else {
	}
}

#-----------------------------------------------
# Form to show subscription status and provide
# checkboxes for subscribing and unsubscribing.
#-----------------------------------------------
sub send_status_form {
	local($user,$address,$pattern,$criteria,$list_match,@lists) = @_;
	#
	local($show_toggle,$lock,$check_box,$hidden);
	local($person) = ($user ? "$user" : "$address");

	&send_error("No <$criteria> Lists For $person Found.")
		unless @lists;
	local($shown,$total);
	$shown = @lists;
	$total = keys(%cached_descr);

	local($submit);
	if ($arg{quickview}) {
	$notmsg = "<LI><IMG SRC=\"$icon_disabled\" $icon_size ALT=\"[X]\"> "
	    ."indicates that subscription changes cannot be made on "
	    ."this QuickView screen. You may change subscriptions at "
	    ."each individual List Detail view.";
	}
	else {
	$submit = "<INPUT TYPE=\"submit\" VALUE=\"Apply Changes\">"
		."<INPUT TYPE=\"reset\"  VALUE=\"(Undo)\">";
	$submit = "<INPUT TYPE=\"image\" NAME=\"submit\" "
		."ALT=\"[Apply Subscription Changes]\" "
		."SRC=\"WEB_IMGURL/mc_apply_button.gif\" BORDER=0>" 
		if $prefs{GenSubmitImages};
	$notmsg = "<LI><IMG SRC=\"$icon_disabled\" $icon_size ALT=\"[X]\"> "
		."indicates that a list cannot be subscribed to because "
		."it has no owner or has otherwise been excluded from "
		."Web access." 
		if (defined($config'known_keys{'owner'}) ||
		    defined($config'known_keys{'web_access'}));
	}

	&log("BROWSE lists $criteria" 
		.($criteria eq "matched" ? "=/$list_match/ " : " ")
		.($arg{quickview} ? "QuickView" : "")
		."(user='$user',address='$address',pattern='$pattern')");
	&send_header(1, "All <$criteria".($criteria eq "matched"
		? "=/$list_match/" : "")."> Lists For $person");
	print <<"EOT";
	$tbl_start
	<TR><TD VALIGN=TOP ALIGN=LEFT>
	<UL>
	<LI>$shown matched of $total total lists. Some lists may not be 
	"advertised" for your viewing.
	<LI>Click on the list name to view list details.
	<LI>Use check-boxes to subscribe/unsubscribe.
	$notmsg
	<LI><IMG SRC=\"$icon_locked\" $icon_size ALT=\"[L]\">
	    indicates the list is closed to certain subscribe/unsubscribe
	    requests. Your request will be sent to the list owner for approval.
	<LI>Your actions are logged. Please do not maliciously change the
	    subscriptions of others.
	</UL>
	</TD></TR>
	$tbl_end
	<BR>
	$tbl_start
	<TR><TD VALIGN=TOP ALIGN=LEFT COLSPAN=4>
	<FORM NAME=\"browse_lists\" METHOD=POST ACTION=\"$url\">
		<INPUT TYPE=\"hidden\" NAME=\"module\" VALUE=\"browse\">
		<INPUT TYPE=\"hidden\" NAME=\"action\" VALUE=\"do_subunsub\">
		<INPUT TYPE=\"hidden\" NAME=\"siteaddr\"
			VALUE=\"$arg{'siteaddr'}\">
		<INPUT TYPE=\"hidden\" NAME=\"criteria\" VALUE=\"$criteria\">
		<INPUT TYPE=\"hidden\" NAME=\"list_match\" VALUE=\"$list_match\">
		$submit
	</TD></TR>
EOT
	print &html_tbl_row("-", "<B>Sub?</B>",
		"<B>List Name</B>","<B>Description</B>");
	foreach $list (@lists) {
		$check_box = $checked = $subscribed = "";
		$lock = "&nbsp;";
		if ($user_status{$list}) {
			$checked = "CHECKED";
			$subscribed = "SUBSCRIBED";
		}
		unless ($user_advertised{$list}) {
			$hidden .="<INPUT TYPE=\"hidden\" "
				."NAME=\"is_$list\" VALUE=\"$subscribed\">\n";
			next;
		}
		$lock = "<IMG SRC=\"$icon_locked\" $icon_size ALT=\"[L]\">" if
			$cached_spolicy{$list} =~ /closed|locked/o ||
			$cached_upolicy{$list} =~ /closed/o;

		$show_toggle = 1;			# default checkbox
		$show_toggle = 0 if $arg{quickview};	# not in QuickView!

		# don't need get_config unless checking web_access!
		&get_config($listdir, $list) unless
			(! defined($config'known_keys{web_access}) ||
			&cf_ck_bool($list, '', 1));

		# access may be disabled by owner
		$show_toggle = 0 if &web_lockout($list, "browse");

		# list may have no owner
		$show_toggle = 0 if (defined($config'known_keys{owner}) &&
			! $cached_owner{$list});

		if ($show_toggle) {
			$check_box = "<INPUT TYPE=\"checkbox\" "
				."NAME=\"is_$list\" VALUE=\"SUBSCRIBED\" "
				."$checked>";
		}
		else {
			$check_box = "<IMG SRC=\"$icon_disabled\""
				." $icon_size ALT=\"[X]\">";
			$hidden .= "<INPUT TYPE=\"hidden\" "
				."NAME=\"is_$list\" VALUE=\"$subscribed\">\n";
		}

		local($descr) = ($cached_descr{$list}
			  ? "$cached_descr{$list}"
			  : "&nbsp;");
		# nested forms are not legal HTML, and no browser
		#   currently supports them. DO NOT TURN THIS ON!
		# keep the code around just in case...ya never know
		$opt_nestedforms = 0;	
		if ($opt_nestedforms) {
		local($address) = &url_encode($address);
		local($pattern) = &url_encode($pattern);
		local($browse) = "<INPUT TYPE=\"submit\" VALUE=\"Details\">";
		$browse = "<INPUT TYPE=\"image\" ALIGN=BOTTOM BORDER=0 "
			."ALT=\"[View Additional Details]\" "
			."SRC=\"WEB_IMGURL/mc_browse_hot.gif\">"
			if $prefs{GenSubmitImages};
		print <<"EOT";
			<TR>
			<TD ALIGN=LEFT VALIGN=TOP>$check_box</TD>
			<TD ALIGN=LEFT VALIGN=TOP>$lock</TD>
			<TD ALIGN=LEFT VALIGN=TOP>$list</TD>
			<TD ALIGN=LEFT VALIGN=TOP>$descr</TD>
			<TD ALIGN=LEFT VALIGN=TOP>
			<FORM NAME=\"go_details\" METHOD=POST ACTION=\"$url\">
				<INPUT TYPE=\"hidden\" 
					NAME=\"module\" VALUE=\"browse\">
				<INPUT TYPE=\"hidden\"
					NAME=\"action\" VALUE=\"view\">
				<INPUT TYPE=\"hidden\" 
					NAME=\"view\" VALUE=\"config\">
				<INPUT TYPE=\"hidden\" 
					NAME=\"list\" VALUE=\"$list\">
				<INPUT TYPE=\"hidden\" 
					NAME=\"siteaddr\" 
					VALUE=\"$arg{siteaddr}\">
				$browse
			</FORM>
			</TD>
			</TR>
EOT
		}
		else {
		local($javascript) =
			" onMouseOver=\"".
			  " msg('$list Details');".
			  " return true\"".
			" onMouseOut=\"".
			  " msg('');".
			  " return true\"";
		print &html_tbl_row("$check_box","$lock", 
			"<A HREF=\"$url?module=browse&action=view".
				"&view=config&list=$list".
				"&siteaddr=".&url_encode($arg{siteaddr}).
				"\"".
                		($prefs{GenJavaScript} ? $javascript : "").
				"><B>$list</B></A>",
			"$descr");
		}
	}
	print <<"EOT";
		$hidden
		<TR><TD VALIGN=TOP ALIGN=LEFT COLSPAN=4>
		$submit
		</TD></TR>
		$tbl_end
	</FORM>
EOT
	&send_footer();
}

#-----------------------------------------------
# Form for administration of subscribers.
#-----------------------------------------------
sub send_who_form {
	local($list,$passwd,$sizeaction) = @_;
	#
	local($members,$subset,$modifier);
	&send_error("No List Specified.") unless $list;
	&send_error("List <$list> Is Not A Valid List.")
		unless &valid_list($listdir,$list);
	&get_config($listdir, $list) unless &cf_ck_bool($list, '', 1);

	# access may be disabled by owner
	&send_error($disabled_msg) 
		if &web_lockout($list, "modify");

	&send_error("Incorrect Password For List <$list>") 
		unless &valid_passwd($listdir, $list, $passwd);

	if ($sizeaction eq "subset") {
		$subset = $arg{'subset'};
		$modifier = "[Subset=/$subset/]";
	}
	elsif ($sizeaction eq "append") {
		$modifier = "[Append]";
	}
	# save a timestamp for identification
	local($whotime) = (stat("$listdir/$list"))[9];
	# make a temp file to hold a snapshot of the list
	&send_error("Cannot Create Snapshot COOL_TMPDIR/$list.$whotime: $!") 
		unless &lopen(STATE, ">", "COOL_TMPDIR/$list.$whotime");
	# lock the list itself during the snapshot operation
	&send_error("Cannot Lock List <$list>: $!") 
		unless &lopen(LIST, "<", "$listdir/$list");
	while (<LIST>) { 
		print STATE if ($_ =~ /$subset/io || ! $subset);
	}
	# close the list and the snapshot
	&lclose(LIST); &lclose(STATE);
	if ($sizeaction ne "append") {
		# get members as of the time this snapshot was taken
		$members = join("\n", &get_who("COOL_TMPDIR/$list.$whotime",
				$prefs{GenListSorted}, $subset));
	}
	&send_subset_request_form(length($members)) 
		if (length($members) > $prefs{ModifyListMaxSize} &&
		$sizeaction ne "test");
	local($last) = &ctime((stat("$listdir/$list"))[9]); chop($last);
	local($submit) = "<INPUT TYPE=\"reset\"  VALUE=\"(Undo)\">"
		."<INPUT TYPE=\"submit\" VALUE=\"Apply Changes\">";
	$submit = "<INPUT TYPE=\"image\" NAME=\"submit\" "
		."ALT=\"[Apply Subscriber Changes]\" "
		."SRC=\"WEB_IMGURL/mc_apply_button.gif\" BORDER=0>"
		if $prefs{GenSubmitImages};
	local($submit_as) = (defined($config'known_keys{owner}) ? 
		$config_opts{$list,owner} : "owner-$list\@$whereami");

	&log("MODIFY who $list");
	&send_header(1, "Subscriber Administration For List <$list> $modifier");
	print <<"EOT";
	$tbl_start
	<TR><TD VALIGN=TOP ALIGN=LEFT>
	<UL>
	<LI>Last changed on $last.
	<LI>Addresses should be listed one-per-line. All addresses must
	    be fully-qualified <CODE>\@dom.ain</CODE> or else they will be
	    considered relative to the <CODE>$whereami</CODE> host.
	<LI>Subscriber changes are made as <B>approve</B>d
	    subscribe/unsubscribe actions. New subscribers will receive
	    notification and the optional <I>info/intro</I> file as usual.
	<LI>Change the <i>apply-as</i> field to have Majordomo results
		sent to other than the list owner.
	</UL>
	</TD></TR>
	<TR>
	<FORM NAME=\"modify_list\" METHOD=POST ACTION=\"$url\">
		<INPUT TYPE=\"hidden\" NAME=\"module\" VALUE=\"modify\">
		<INPUT TYPE=\"hidden\" NAME=\"action\" VALUE=\"do_approve\">
		<INPUT TYPE=\"hidden\" NAME=\"list\" VALUE=\"$list\">
		<INPUT TYPE=\"hidden\" NAME=\"passwd\" VALUE=\"$passwd\">
		<INPUT TYPE=\"hidden\" NAME=\"whotime\" VALUE=\"$whotime\">
		<INPUT TYPE=\"hidden\" NAME=\"sizeaction\" VALUE=\"$sizeaction\">
		<INPUT TYPE=\"hidden\" NAME=\"subset\" VALUE=\"$subset\">
	<TD VALIGN=TOP ALIGN=CENTER>
		<TEXTAREA NAME=\"who\" COLS=60 ROWS=10>$members</TEXTAREA>
	</TD></TR>
	<TR>
	<TD VALIGN=TOP ALIGN=CENTER>
		<BR>
		$submit
		as <INPUT TYPE=\"text\" NAME=\"submit_as\" VALUE=\"$submit_as\"
			SIZE=32 MAXLENGTH=64>
	</TD></TR>
	</FORM>
	$tbl_end
EOT
	&send_footer();
}

#-----------------------------------------------
# Form to edit/create info file.
#-----------------------------------------------
sub send_info_form {
	local($list,$passwd) = @_;
	#
	&send_error("No List Specified.") unless $list;
	&send_error("List <$list> Is Not A Valid List.")
		unless &valid_list($listdir,$list);
	&get_config($listdir, $list) unless &cf_ck_bool($list, '', 1);

	# access may be disabled by owner
	&send_error($disabled_msg) 
		if &web_lockout($list, "modify");

	&send_error("Incorrect Password For List <$list>") 
		unless &valid_passwd($listdir, $list, $passwd);

	local(@info) = &get_file("$listdir/$list.info");
	# remove any datestamp from file contents
	local($first) = shift(@info);
	unshift(@info,$first) unless 
		&cf_ck_bool($list,'date_info') || $first =~ /\[Last updated/o;
	local($info) = join("", @info);
	local($last) = &ctime((stat("$listdir/$list.info"))[9]); chop($last);
 	local($edit_opts) = "COLS=80 ROWS=10";
	$edit_opts .= " WRAP=YES WRAP=PHYSICAL" if $prefs{ModifyInfoWrap};
	local($offer_intro) =
		"<LI>An <B>Intro File</B> will be sent instead of the "
	    	."<B>Info File</B> if it exists.\n"
		if defined($config'known_keys{'intro_access'});
	local($offer_date);
	if (&cf_ck_bool($list,'date_info')) {
		$offer_date = 
		"<LI><CODE>date_info</CODE> is enabled for this list. "
		."A datestamp will be prepended to the text in this "
		."file, although it will not be displayed in the edit "
		."window itself.";
	}
	else {
		$offer_date = 
		"<LI><CODE>date_info</CODE> is not enabled for this list. "
		."A datestamp will not be prepended to the text in this "
		."file.";
	}
	local($submit) = "<INPUT TYPE=\"reset\"  VALUE=\"(Undo)\">"
		."<INPUT TYPE=\"submit\" VALUE=\"Apply Changes\">";
	$submit = "<INPUT TYPE=\"image\" NAME=\"submit\" "
		."ALT=\"[Apply Info File Changes]\" "
		."SRC=\"WEB_IMGURL/mc_apply_button.gif\" BORDER=0>"
		if $prefs{GenSubmitImages};
	local($submit_as) = (defined($config'known_keys{owner}) ? 
		$config_opts{$list,owner} : "owner-$list\@$whereami");

	&log("MODIFY info $list");
	&send_header(1, "Info File For List <$list>");
	print <<"EOT";
	$tbl_start
	<TR><TD VALIGN=TOP ALIGN=LEFT>
	<UL>
	<LI>Last changed on $last.
	<LI>The <B>Info File</B> is sent to all new subscribers,
	    or by request via the <i>info</i> command.
	$offer_intro
	$offer_date
	<LI>Change the <i>apply-as</i> field to have Majordomo results
	    sent to other than the list owner.
	</UL>
	</TD></TR>
	<TR>
	<FORM NAME=\"modify_info\" METHOD=POST ACTION=\"$url\">
		<INPUT TYPE=\"hidden\" NAME=\"module\" VALUE=\"modify\">
		<INPUT TYPE=\"hidden\" NAME=\"action\" VALUE=\"do_newinfo\">
		<INPUT TYPE=\"hidden\" NAME=\"list\" VALUE=\"$list\">
		<INPUT TYPE=\"hidden\" NAME=\"passwd\" VALUE=\"$passwd\">
	<TD VALIGN=TOP ALIGN=RIGHT>
		<TEXTAREA NAME=\"info\" $edit_opts>$info</TEXTAREA>
	</TD></TR>
	<TD VALIGN=TOP ALIGN=CENTER>
		$submit
		as <INPUT TYPE=\"text\" NAME=\"submit_as\" VALUE=\"$submit_as\"
			SIZE=32 MAXLENGTH=64>
	</TD></TR>
	</FORM>
	$tbl_end
EOT
	&send_footer();
}

#-----------------------------------------------
# Form to edit/create intro file.
#-----------------------------------------------
sub send_intro_form {
	local($list,$passwd) = @_;
	#
	&send_error("No List Specified.") unless $list;
	&send_error("List <$list> Is Not A Valid List.")
		unless &valid_list($listdir,$list);
	&get_config($listdir, $list) unless &cf_ck_bool($list, '', 1);

	# access may be disabled by owner
	&send_error($disabled_msg) 
		if &web_lockout($list, "modify");

	&send_error("Incorrect Password For List <$list>") 
		unless &valid_passwd($listdir, $list, $passwd);

	local(@intro) = &get_file("$listdir/$list.intro");
	# remove any datestamp from file contents
	local($first) = shift(@intro);
	unshift(@intro,$first) unless 
		&cf_ck_bool($list,'date_intro') || $first =~ /\[Last updated/o;
	local($intro) = join("", @intro);
	local($last) = &ctime((stat("$listdir/$list.intro"))[9]); chop($last);
 	local($edit_opts) = "COLS=80 ROWS=10";
	$edit_opts .= " WRAP=YES WRAP=PHYSICAL" if $prefs{ModifyInfoWrap};
	if (&cf_ck_bool($list,'date_intro')) {
		$offer_date = 
		"<LI><CODE>date_intro</CODE> is enabled for this list. "
		."A datestamp will be prepended to the text in this "
		."file, although it will not be displayed in the edit "
		."window itself.";
	}
	else {
		$offer_date = 
		"<LI><CODE>date_intro</CODE> is not enabled for this list.  "
		."A datestamp will not be prepended to the text in this "
		."file.";
	}
	local($submit) = "<INPUT TYPE=\"reset\"  VALUE=\"(Undo)\">"
		."<INPUT TYPE=\"submit\" VALUE=\"Apply Changes\">";
	$submit = "<INPUT TYPE=\"image\" NAME=\"submit\" "
		."ALT=\"[Apply Intro File Changes]\" "
		."SRC=\"WEB_IMGURL/mc_apply_button.gif\" BORDER=0>"
		if $prefs{GenSubmitImages};
	local($submit_as) = (defined($config'known_keys{owner}) ? 
		$config_opts{$list,owner} : "owner-$list\@$whereami");

	&log("MODIFY intro $list");
	&send_header(1, "Intro File For List <$list>");
	print <<"EOT";
	$tbl_start
	<TR><TD VALIGN=TOP ALIGN=LEFT>
	<UL>
	<LI>Last changed on $last.
	<LI>The <B>Intro File</B> is sent to all new subscribers,
	    or by request via the <i>intro</i> command.
	<LI>If the <B>Intro File</B> does not exist, the <B>Info File</B>
	    is used instead.
	$offer_date
	<LI>Change the <i>apply-as</i> field to have Majordomo results
	    sent to other than the list owner.
	</UL>
	</TD></TR>
	<TR>
	<FORM NAME=\"modify_intro\" METHOD=POST ACTION=\"$url\">
		<INPUT TYPE=\"hidden\" NAME=\"module\" VALUE=\"modify\">
		<INPUT TYPE=\"hidden\" NAME=\"action\" VALUE=\"do_newintro\">
		<INPUT TYPE=\"hidden\" NAME=\"list\" VALUE=\"$list\">
		<INPUT TYPE=\"hidden\" NAME=\"passwd\" VALUE=\"$passwd\">
	<TD VALIGN=TOP ALIGN=RIGHT>
		<TEXTAREA NAME=\"intro\" $edit_opts>$intro</TEXTAREA>
	</TD></TR>
	<TD VALIGN=TOP ALIGN=CENTER>
		$submit
		as <INPUT TYPE=\"text\" NAME=\"submit_as\" VALUE=\"$submit_as\"
			SIZE=32 MAXLENGTH=64>
	</TD></TR>
	</FORM>
	$tbl_end
EOT
	&send_footer();
}

#-----------------------------------------------
# Form to display the approval queue.
#-----------------------------------------------
sub send_queue_form {
	local($list,$passwd) = @_;
	#
	&send_error("No List Specified.") unless $list;
	&send_error("List <$list> Is Not A Valid List.")
		unless &valid_list($listdir,$list);
	&get_config($listdir, $list) unless &cf_ck_bool($list, '', 1);

	# access may be disabled by owner
	&send_error($disabled_msg) 
		if &web_lockout($list, "modify");

	&send_error("Incorrect Password For List <$list>") 
		unless &valid_passwd($listdir, $list, $passwd) ||
		($config_opts{$list,"approve_passwd"} ne '' &&
			$config_opts{$list,"approve_passwd"} eq $passwd);
	# This MODIFY action will accept the Moderator
	# password in addition to the Admin password.

	&send_error("Approval Queue Not Supported In This Majordomo Version") 
		unless defined($config'known_keys{'bounce_action'});

	# allow global bouncedir to be set in majordomo.cf
	# if not, use the per-list archive area
	#
	local($bouncepre) = "${list}.bounce";
	unless ($bouncedir) {
		$bouncedir = "$filedir/$list$filedir_suffix";
		$bouncepre = "bounce";	# list ref in directory name
	}
	# something is not set up right! default to TMP area
	unless (-d "$bouncedir") {
		$bouncedir = "$TMPDIR";
		$bouncepre = "${list}.bounce";
	}
	local(@queue) = &fileglob("$bouncedir", "^${bouncepre}\.");
	local($queuelen) = $#queue + 1;

	local($submit) = "<INPUT TYPE=\"reset\"  VALUE=\"(Undo)\">"
		."<INPUT TYPE=\"submit\" VALUE=\"Apply Changes\">";
	$submit = "<INPUT TYPE=\"image\" NAME=\"apply\" "
		."ALT=\"[Apply Actions To Messages]\" "
		."SRC=\"WEB_IMGURL/mc_apply_button.gif\" BORDER=0>"
		if $prefs{GenSubmitImages};
	local($submit_as) = (defined($config'known_keys{owner}) ? 
		$config_opts{$list,owner} : "owner-$list\@$whereami");

	&log("MODIFY queue $list");
	&send_header(1, "Approval Queue For List <$list>");
	if (@queue) {
		print <<"EOT";
		$tbl_start
		<TR><TD VALIGN=TOP ALIGN=LEFT>
		<UL>
		<LI>The following messages are awaiting approval. Select an action
		    for each pending message.
		    <UL>
		    <LI>HOLD will leave the message on the server.
		    <LI>DELETE will remove the message from the queue.
		    <LI>REJECT will return the message to sender and remove from the queue.
		    <LI>APPROVE will send the message and then remove it from the queue.
		    </UL>
		<LI>Use View to examine the message content.
		<LI>Use Edit to add moderator comments prior to Approval/Rejection.<BR>
		    WARNING: Large files will take a long time to load and may consume
		    considerable browser memory.
		</UL>
		</TD></TR>
		$tbl_end
		$tbl_start
		<TR><TD VALIGN=TOP ALIGN=LEFT COLSPAN=2>
		<FORM NAME=\"modify_queue\" METHOD=POST ACTION=\"$url\">
		<INPUT TYPE=\"hidden\" NAME=\"module\" VALUE=\"modify\">
		<INPUT TYPE=\"hidden\" NAME=\"action\" VALUE=\"do_queue\">
		<INPUT TYPE=\"hidden\" NAME=\"list\" VALUE=\"$list\">
		<INPUT TYPE=\"hidden\" NAME=\"passwd\" VALUE=\"$passwd\">
		<INPUT TYPE=\"hidden\" NAME=\"queuelen\" VALUE=\"$queuelen\">
		<P>
		</TD></TR>
EOT
		print &html_tbl_row("<B>Action</B>", "<B>Message</B>");
		local($msgnum) = 0;
		foreach $msgid (@queue) {
			$msgnum++;
			local($msgfrom,$msgsubj,$msgdate,$msgsize) =
				&queue_parse($msgid);
			$msgid =~ s|$bouncedir/$bouncepre\.||o;
			local($javascript_v) =
				" onMouseOver=\"".
				  " msg('View E-Mail Message');".
				  " return true\"".
				" onMouseOut=\"".
				  " msg('');".
				  " return true\"";
			local($javascript_e) =
				" onMouseOver=\"".
				  " msg('Edit E-Mail Message');".
				  " return true\"".
				" onMouseOut=\"".
				  " msg('');".
				  " return true\"";
			print &html_tbl_row(
				"\n<SELECT NAME=msg${msgnum}_action>".
				"<OPTION VALUE=hold SELECTED>Hold".
				"<OPTION VALUE=delete>Delete".
				"<OPTION VALUE=reject>Reject".
				"<OPTION VALUE=approve>Approve".
				"</SELECT>".
				"<INPUT TYPE=\"hidden\" NAME=\"msg${msgnum}\"".
				  " VALUE=\"$msgid\">",
				"<B>$msgid</B>");
			print &html_tbl_row("", 
				"From: ".&html_encode($msgfrom));
			print &html_tbl_row("", 
				"Subject: ".&html_encode($msgsubj));
			print &html_tbl_row("", 
				"Date: ".&html_encode($msgdate));
			print &html_tbl_row("", 
				"Size: ".&html_encode("$msgsize bytes"));
			print &html_tbl_row("",
				" <A HREF=\"$url?module=modify".
				  "&action=edit&view=queuemsg".
				  "&list=$list&passwd=$passwd".
				  "&msgid=".&url_encode($msgid).
				  "\"".
                		  ($prefs{GenJavaScript} ? $javascript_v : "").
				  "><B>[View]</B></A>".
				" <A HREF=\"$url?module=modify".
				  "&action=edit&view=queuemsg_edit".
				  "&list=$list&passwd=$passwd".
				  "&msgid=".&url_encode($msgid).
				  "\"".
                		  ($prefs{GenJavaScript} ? $javascript_e : "").
				  "><B>[Edit]</B></A>");
			print &html_tbl_row("-", "<P>&nbsp;<P>");
		}
		print <<"EOT";
		<TR><TD VALIGN=TOP ALIGN=LEFT COLSPAN=2>
		$submit
		</TD></TR>
		</FORM>
		$tbl_end
EOT
	}
	else {
		print "<H3 ALIGN=CENTER>No Pending Messages</H3>";
	}
	&send_footer();
}

#-----------------------------------------------
# Edit raw message in the queue.
#-----------------------------------------------
sub send_queuemsg_form {
	local($list,$passwd,$msgid) = @_;
	#
	&send_error("No List Specified.") unless $list;
	&send_error("List <$list> Is Not A Valid List.")
		unless &valid_list($listdir,$list);
	&get_config($listdir, $list) unless &cf_ck_bool($list, '', 1);
	&send_error("Incorrect Password For List <$list>") 
		unless &valid_passwd($listdir, $list, $passwd) ||
		($config_opts{$list,"approve_passwd"} ne '' &&
			$config_opts{$list,"approve_passwd"} eq $passwd);
	# This MODIFY action will accept the Moderator
	# password in addition to the Admin password.

	# allow global bouncedir to be set in majordomo.cf
	# if not, use the per-list archive area
	#
	local($bouncepre) = "${list}.bounce";
	unless ($bouncedir) {
		$bouncedir = "$filedir/$list$filedir_suffix";
		$bouncepre = "bounce";	# list ref in directory name
	}
	# something is not set up right! default to TMP area
	unless (-d "$bouncedir") {
		$bouncedir = "$TMPDIR";
		$bouncepre = "${list}.bounce";
	}

	&send_error("Invalid Message-ID")
		unless -f "$bouncedir/${bouncepre}.$msgid";
	&send_error("Unable To Open Message: $!")
		unless &lopen(MSG, "<", "$bouncedir/${bouncepre}.$msgid");
	local(@message) = <MSG>;	# snarf
	local($last) = &ctime((stat(MSG))[9]); chop($last);
	local($size) = -s MSG;
	local($warning) = "<B>WARNING: Large files may take a long time ".
		"to load and consume considerable browser memory.</B>" 
		if $size > 500000;
	&lclose(MSG);
	local($body,$header) = join("", @message);
	while (1) {
		$body =~ s/(.*\n)//; $header .= $1;
		last if $body =~ /^\n/;
	}
	$body =~ s/^\n//;
	#faster Perl5-ism
	#$header =~ s/\n\n.*$/\n/s; $body = s/$header\n//s;

	local($submit) = "<INPUT TYPE=\"reset\"  VALUE=\"(Undo)\">"
		."<INPUT TYPE=\"submit\" VALUE=\"Apply Changes\">";
	$submit = "<INPUT TYPE=\"image\" NAME=\"apply\" "
		."ALT=\"[Apply Changes To Headers/Message]\" "
		."SRC=\"WEB_IMGURL/mc_apply_button.gif\" BORDER=0>"
		if $prefs{GenSubmitImages};

	&log("MODIFY queue $list edit <$msgid>");
	&send_header(1, "Pending Message For List <$list>");
	print <<"EOT";
	$tbl_start
	<TR><TD VALIGN=TOP ALIGN=LEFT>
	<UL>
	<LI>If the message is to be rejected, adding the explanation may be
	    appropriate. Other comments &amp; corrections can be added as
	    necessary.
	<LI>Edits should be limited to the message body. Be very careful when
	    editing the header section.
	<LI>Last changed on $last.
	<LI>Message is $size bytes. $warning
	</UL>
	</TD></TR>
	$tbl_end
	$tbl_start
	<FORM NAME=\"modify_queuemsg\" METHOD=POST ACTION=\"$url\">
		<INPUT TYPE=\"hidden\" NAME=\"module\" VALUE=\"modify\">
		<INPUT TYPE=\"hidden\" NAME=\"action\" VALUE=\"do_queuemsg\">
		<INPUT TYPE=\"hidden\" NAME=\"list\" VALUE=\"$list\">
		<INPUT TYPE=\"hidden\" NAME=\"passwd\" VALUE=\"$passwd\">
		<INPUT TYPE=\"hidden\" NAME=\"msgid\" VALUE=\"$msgid\">
	<TR><TD VALIGN=TOP ALIGN=CENTER>
		<TEXTAREA NAME=\"header\" COLS=80 ROWS=2>$header</TEXTAREA>
	</TD></TR>
	<TR><TD VALIGN=TOP ALIGN=CENTER>
		<TEXTAREA NAME=\"body\" COLS=80 ROWS=25>$body</TEXTAREA>
	</TD></TR>
	<TR><TD VALIGN=TOP ALIGN=LEFT>
		$submit
	</TD></TR>
	</FORM>
	$tbl_end
EOT
	&send_footer();
}

#-----------------------------------------------
# Form for administration of config file fields.
#-----------------------------------------------
sub send_config_form {
	local($list,$passwd,$keyview) = @_;
	$keyview = "basic" unless $keyview;
	#
	&send_error("No List Specified.") unless $list;
	&send_error("List <$list> Is Not A Valid List.")
		unless &valid_list($listdir,$list);
	&get_config($listdir, $list) unless &cf_ck_bool($list, '', 1);

	# access may be disabled by owner
	&send_error($disabled_msg) 
		if &web_lockout($list, "modify");

	&send_error("Incorrect Password For List <$list>") 
		unless &valid_passwd($listdir, $list, $passwd);

	local($description) =
		&html_encode($config_opts{$list,'description'});
	local(@members) = &get_who("$listdir/$list");	# sorting doesn't matter
	local($member_count) = $#members+1;
	local($info_size) = -s "$listdir/$list.info" || 0;
	local($intro_size) = -s "$listdir/$list.intro" || 0;
	local($last) = &ctime((stat("$listdir/$list.config"))[9]); chop($last);
	local($browse) = "<INPUT TYPE=\"submit\" VALUE=\"Browse\">";
	$browse = "<INPUT TYPE=\"image\" ALIGN=BOTTOM BORDER=0 "
		."ALT=\"[View Other Attributes]\" "
		."SRC=\"WEB_IMGURL/mc_browse_hot.gif\">"
		if $prefs{GenSubmitImages};
	local($modify) = "<INPUT TYPE=\"submit\" VALUE=\"Modify\">";
	$modify = "<INPUT TYPE=\"image\" ALIGN=BOTTOM BORDER=0 "
		."ALT=\"[Modify Other Attributes]\" "
		."SRC=\"WEB_IMGURL/mc_modify_hot.gif\">"
		if $prefs{GenSubmitImages};
	local($submit) = "<INPUT TYPE=\"reset\"  VALUE=\"(Undo)\">"
		."<INPUT TYPE=\"submit\" VALUE=\"Apply Changes\">";
	$submit = "<INPUT TYPE=\"image\" NAME=\"submit\" "
		."ALT=\"[Apply Configuration Changes]\" "
		."SRC=\"WEB_IMGURL/mc_apply_button.gif\" BORDER=0>"
		if $prefs{GenSubmitImages};
	local($submit_as) = (defined($config'known_keys{owner}) ? 
		$config_opts{$list,owner} : "owner-$list\@$whereami");

	&log("MODIFY config $list ($keyview)");
	&send_header(1, "Configuration Options For List <$list>");
	print <<"EOT";
	$tbl_start
	<TR><TD VALIGN=TOP ALIGN=CENTER>
	<FORM NAME=\"modify_config_keyview\" METHOD=POST ACTION=\"$url\">
		<INPUT TYPE=\"hidden\" NAME=\"module\" VALUE=\"modify\">
		<INPUT TYPE=\"hidden\" NAME=\"action\" VALUE=\"edit\">
		<INPUT TYPE=\"hidden\" NAME=\"view\" VALUE=\"config\">
		<INPUT TYPE=\"hidden\" NAME=\"list\" VALUE=\"$list\">
		<INPUT TYPE=\"hidden\" NAME=\"passwd\" VALUE=\"$passwd\">
EOT
	print "<SELECT NAME=\"keyview\" ".
       		($prefs{GenJavaScript} 
		  ? "onChange='modify_config_keyview.submit(); return true;'"
		  : "").
		">";
	print "<OPTION VALUE=\"basic\"".
		($keyview eq "basic" ? " SELECTED>" : ">").
		"Basic Keywords\n";
	print "<OPTION VALUE=\"all\"".
		($keyview eq "all" ? " SELECTED>" : ">").
		"All Keywords\n";
	print "<OPTION VALUE=\"majordomo\"".
		($keyview eq "majordomo" ? " SELECTED>" : ">").
		"Majordomo Subsystem\n";
	print "<OPTION VALUE=\"resend\"".
		($keyview eq "resend" ? " SELECTED>" : ">").
		"Resend Subsystem\n";
	print "<OPTION VALUE=\"archive\"".
		($keyview eq "archive" ? " SELECTED>" : ">").
		"Archive Subsystem\n";
	print "<OPTION VALUE=\"digest\"".
		($keyview eq "digest" ? " SELECTED>" : ">").
		"Digest Subsystem\n";
	print "<OPTION VALUE=\"config\"".
		($keyview eq "config" ? " SELECTED>" : ">").
		"Configuration Subsystem\n";
	local($change) = "<INPUT TYPE=\"submit\" VALUE=\"Change View\">";
	$change = "<INPUT TYPE=\"image\" NAME=\"submit\" "
		."ALT=\"[Change View]\" ALIGN=bottom "
		."SRC=\"WEB_IMGURL/mc_action_button.gif\" BORDER=0>" 
		if $prefs{GenSubmitImages};
	print "</SELECT>\n";
	print "<NOSCRIPT>\n" if $prefs{GenJavaScript};
	print "$change\n";
	print "</NOSCRIPT>\n" if $prefs{GenJavaScript};
	print <<"EOT";
	</FORM>
	</TD></TR>
	<TR><TD VALIGN=TOP ALIGN=LEFT>
	<UL>
	<LI>Last changed on $last.
	<LI>"$keyview" keywords shown. Select alternate view above.
	<LI>Change the <i>apply-as</i> field to have Majordomo results
	    sent to other than the list owner.
	</UL>
	</TD></TR>
	$tbl_end
	$tbl_start_border
	<FORM NAME=\"modify_config\" METHOD=POST ACTION=\"$url\">
		<INPUT TYPE=\"hidden\" NAME=\"module\" VALUE=\"modify\">
		<INPUT TYPE=\"hidden\" NAME=\"action\" VALUE=\"do_newconfig\">
		<INPUT TYPE=\"hidden\" NAME=\"list\" VALUE=\"$list\">
		<INPUT TYPE=\"hidden\" NAME=\"passwd\" VALUE=\"$passwd\">
		<INPUT TYPE=\"hidden\" NAME=\"admin_passwd\" VALUE=\"$passwd\">
		<INPUT TYPE=\"hidden\" NAME=\"keyview\" VALUE=\"$keyview\">
EOT
	foreach (&get_keywords($keyview)) {
		next unless defined($config'known_keys{$_});
		local($label) = $_;
		$label =~ s/_/ /go;
		$label .= " ($config'subsystem{$_})"
			if $prefs{ModifyConfSubsys};
		next if $_ eq "admin_passwd" && $opt_hiddenpasswd;
		if ($prefs{ModifyConf2Column}) {
			print &html_tbl_row("<B>$label</B>",
				&html_mjkey($list,$_));
		}
		else {
			print &html_tbl_row("<B>$label</B>: ".
				&html_mjkey($list,$_));
		}
	}
	print <<"EOT";
	$tbl_end
	<BR>
	$tbl_start
	<TR><TD VALIGN=TOP ALIGN=RIGHT>
		$submit
	</TD>
	<TD VALIGN=TOP ALIGN=LEFT>
		as <INPUT TYPE=\"text\" NAME=\"submit_as\" VALUE=\"$submit_as\"
			SIZE=32 MAXLENGTH=64>
	</TD></TR>
	$tbl_end
	</FORM>
	<HR ALIGN=CENTER WIDTH=75%>
	<H3 ALIGN=CENTER>Other Modify Actions</H3>
	$tbl_start
	<FORM NAME=\"go_modify_choice\" METHOD=POST ACTION=\"$url\">
		<INPUT TYPE=\"hidden\" NAME=\"module\" VALUE=\"modify\">
		<INPUT TYPE=\"hidden\" NAME=\"action\" VALUE=\"edit\">
		<INPUT TYPE=\"hidden\" NAME=\"list\" VALUE=\"$list\">
		<INPUT TYPE=\"hidden\" NAME=\"passwd\" VALUE=\"$passwd\">
		<TR><TD VALIGN=TOP ALIGN=RIGHT>
			$modify
		</TD><TD VALIGN=TOP ALIGN=LEFT>
			<INPUT TYPE=\"radio\" NAME=\"view\" VALUE=\"list\"
			CHECKED>
			List Subscribers
			($member_count members)
		</TD></TR>
		<TR><TD></TD>
		<TD VALIGN=TOP ALIGN=LEFT>
			<INPUT TYPE=\"radio\" NAME=\"view\" VALUE=\"info\">
			'Info' File
			($info_size bytes)
		</TD></TR>
EOT
	print <<"EOT" if defined($config'known_keys{'intro_access'});
		<TR><TD></TD>
		<TD VALIGN=TOP ALIGN=LEFT>
			<INPUT TYPE=\"radio\" NAME=\"view\" VALUE=\"intro\">
			'Intro' File
			($intro_size bytes)
		</TD></TR>
EOT
	if (defined($config'known_keys{'bounce_action'})) {
		# allow global bouncedir to be set in majordomo.cf
		# if not, use the per-list archive area
		#
		local($bouncepre) = "${list}.bounce";
		unless ($bouncedir) {
			$bouncedir = "$filedir/$list$filedir_suffix";
			$bouncepre = "bounce";	# list ref in directory name
		}
		# something is not set up right! default to TMP area
		unless (-d "$bouncedir") {
			$bouncedir = "$TMPDIR";
			$bouncepre = "${list}.bounce";
		}
		local(@queue) = &fileglob("$bouncedir", "^${bouncepre}\.");
		local($queuelen) = $#queue + 1;

		print <<"EOT";
		<TR><TD></TD>
		<TD VALIGN=TOP ALIGN=LEFT>
			<INPUT TYPE=\"radio\" NAME=\"view\" VALUE=\"queue\">
			Approval Queue ($queuelen pending)
		</TD></TR>
EOT
	}
	if (-d "$filedir/$list$filedir_suffix") {
		print <<"EOT";
		<TR><TD></TD>
		<TD VALIGN=TOP ALIGN=LEFT>
			<INPUT TYPE=\"radio\" NAME=\"view\" VALUE=\"files\">
			File/Archive Area
			(not yet supported)
		</TD></TR>
EOT
	}
	print "</FORM>\n";
	print "</TD></TR>\n";
	print "$tbl_end";
	if ($opt_hiddenpasswd) {
	print <<"EOT";
	<HR ALIGN=CENTER WIDTH=75%>
	$tbl_start
	<TR><TD VALIGN=TOP ALIGN=LEFT>
	<H3 ALIGN=CENTER>Change List Password</H3>
	<UL>
	<LI>For security reasons, there is no provision for overriding
	    the <I>apply-as</I> here. This action will be performed as
	    (and results sent to) the defined list owner.
	</UL>
	</TD></TR>
	$tbl_end
	<BR>
	$tbl_start
	<FORM NAME=\"modify_passwd\" METHOD=POST ACTION=\"$url\">
		<INPUT TYPE=\"hidden\" NAME=\"module\" VALUE=\"modify\">
		<INPUT TYPE=\"hidden\" NAME=\"action\" VALUE=\"do_passwd\">
		<INPUT TYPE=\"hidden\" NAME=\"list\" VALUE=\"$list\">
		<INPUT TYPE=\"hidden\" NAME=\"do_passwd\" VALUE=\"$list\">
		<TR><TD VALIGN=TOP ALIGN=RIGHT>
			Old Password
		</TD><TD VALIGN=TOP ALIGN=LEFT>
			<INPUT TYPE=\"password\" SIZE=32 MAXLENGTH=64
			NAME=\"old_passwd\">
		</TD></TR>
		<TR><TD VALIGN=TOP ALIGN=RIGHT>
			New Password
		</TD><TD VALIGN=TOP ALIGN=LEFT>
			<INPUT TYPE=\"password\" SIZE=32 MAXLENGTH=64
			NAME=\"cf_admin_passwd\">
		</TD></TR>
		<TR><TD VALIGN=TOP ALIGN=RIGHT>
			New Password<BR><FONT SIZE=-1>(verify)</FONT>
		</TD><TD VALIGN=TOP ALIGN=LEFT>
			<INPUT TYPE=\"password\" SIZE=32 MAXLENGTH=64
			NAME=\"admin_passwd2\">
		</TD></TR>
		$tbl_end
		$tbl_start
		<TR><TD VALIGN=TOP ALIGN=CENTER>
		<BR>
		$submit
	</FORM>
	$tbl_end
EOT
	}
	if ($list =~ /-digest$/o) {
	local($send) = "<INPUT TYPE=\"submit\" VALUE=\"Initiate A Digest\">";
	$send = "<INPUT TYPE=\"image\" NAME=\"submit\" "
		."ALT=\"[Initiate A Digest]\" "
		."SRC=\"WEB_IMGURL/mc_apply_button.gif\" BORDER=0>"
		if $prefs{GenSubmitImages};
	print <<"EOT";
	<HR ALIGN=CENTER WIDTH=75%>
	<H3 ALIGN=CENTER>Initiate A Digest Mailing</H3>
	<FORM NAME=\"modify_mkdigest\" METHOD=POST ACTION=\"$url\">
		<INPUT TYPE=\"hidden\" NAME=\"module\" VALUE=\"modify\">
		<INPUT TYPE=\"hidden\" NAME=\"action\" VALUE=\"do_mkdigest\">
		<INPUT TYPE=\"hidden\" NAME=\"list\" VALUE=\"$list\">
		<INPUT TYPE=\"hidden\" NAME=\"passwd\" VALUE=\"$passwd\">
		$tbl_start
		<TR><TD VALIGN=TOP ALIGN=RIGHT>
			$send
		</TD>
		<TD VALIGN=TOP ALIGN=LEFT>
			as <INPUT TYPE=\"text\" NAME=\"submit_as\" 
				VALUE=\"$submit_as\"
				SIZE=32 MAXLENGTH=64><BR>
		</TD></TR>
		$tbl_end
	</FORM>
EOT
	}
	if ($opt_sendcf) {
	local($send) = "<INPUT TYPE=\"submit\" VALUE=\"Send Configuration File\">";
	$send = "<INPUT TYPE=\"image\" NAME=\"submit\" "
		."ALT=\"[Send Configuration File]\" "
		."SRC=\"WEB_IMGURL/mc_send_button.gif\" BORDER=0>"
		if $prefs{GenSubmitImages};
	print <<"EOT";
	<HR ALIGN=CENTER WIDTH=75%>
	<H3 ALIGN=CENTER>Send Configuration File by E-Mail</H3>
	<FORM NAME=\"mail_config\" METHOD=POST ACTION=\"$url\">
		<INPUT TYPE=\"hidden\" NAME=\"module\" VALUE=\"modify\">
		<INPUT TYPE=\"hidden\" NAME=\"action\" VALUE=\"do_config\">
		<INPUT TYPE=\"hidden\" NAME=\"list\" VALUE=\"$list\">
		<INPUT TYPE=\"hidden\" NAME=\"passwd\" VALUE=\"$passwd\">
		$tbl_start
		<TR><TD VALIGN=TOP ALIGN=RIGHT>
			$send
		</TD>
		<TD VALIGN=TOP ALIGN=LEFT>
			to <INPUT TYPE=\"text\" NAME=\"submit_as\" 
				VALUE=\"$submit_as\"
				SIZE=32 MAXLENGTH=64><BR>
		</TD></TR>
		$tbl_end
	</FORM>
EOT
	}
	&send_footer();
}

#-----------------------------------------------
# Show current Preferences; collect changes.
#-----------------------------------------------
sub send_prefs_form {
	&send_header(0, "$sitename MajorCool Preferences");
	local($state) = &url_encode($arg{state}) if ($opt_prefsreturn);
	local($submit) = "<INPUT TYPE=\"submit\" VALUE=\"Apply Changes\">"
		."<INPUT TYPE=\"reset\"  VALUE=\"(Undo)\">";
	$submit = "<INPUT TYPE=\"image\" NAME=\"submit\" "
		."ALT=\"[Apply Preference Changes]\" "
		."SRC=\"WEB_IMGURL/mc_apply_button.gif\" BORDER=0>" 
		if $prefs{GenSubmitImages};
	print "$tbl_start";
	print "<TR><TD ALIGN=CENTER VALIGN=TOP>";
	print &imgsrc(&img('prefs','banner'),
		"ALT=\"[MAJORCOOL PREFERENCES]\" ALIGN=MIDDLE");
	print <<"EOT";
	<H4 ALIGN=CENTER>$tip{prefs}</H4>
	</TD></TR>
	<TR><TD VALIGN=TOP ALIGN=LEFT>
	<UL>
	<LI>Preferences will only apply to <I>MajorCool</I> running on 
	this [<I>$ENV{'SERVER_NAME'}</I>] host with this 
	[<I>$ENV{'HTTP_USER_AGENT'}</I>] browser. <I>MajorCool</I>
	instances on other hosts (and browser families) will have
	their own distinct preference controls.
	</UL>
	</TD></TR>
	$tbl_end
	<BR>
	$tbl_start
	<FORM NAME=\"prefs\" METHOD=POST ACTION=\"$url\">
	<INPUT TYPE=\"hidden\" NAME=\"action\" VALUE=\"prefs\">
	<INPUT TYPE=\"hidden\" NAME=\"state\" VALUE=\"$state\">
	<TR><TD VALIGN=TOP ALIGN=LEFT>
		$submit
	</TD></TR>
	<TR><TD VALIGN=TOP ALIGN=LEFT>
EOT
	print "<B>General Options</B><BR>\n";
	foreach (sort(keys(%prefs))) {
		next if /_/;
		next unless /^Gen/;
		local($pref,$type,$text);
		eval "\$pref = 'pref_$_'";
		eval "\$type = $_.'_Type'";
		eval "\$text = $_.'_Text'";
		next unless $prefs{$type};
		$type = $prefs{$type};
		if ($type =~ /boolean/io) {
			print "<INPUT TYPE=\"checkbox\" NAME=\"$pref\"".
				" VALUE=\"1\"";
			print " CHECKED" if $prefs{$_};
			print "> ".&html_encode($prefs{$text})."<BR>\n";
		}
		elsif ($type =~ /choice/io) {
			local($choice,$item,@items);
			$choice = (split(/:/, $type))[1];
			@items = split(/,/, $choice);
			print &html_encode($prefs{$text}).": ";
			print "<SELECT NAME=\"$pref\" SIZE=1>\n";
			foreach $item (@items) { 
				$item = "(no pref)" unless $item;
				print "<OPTION VALUE=\"$item\"";
				print " SELECTED" if $item eq $prefs{$_};
				print ">$item</OPTION>"; 
			}
			print "</SELECT><BR>\n";
		}
		elsif ($type =~ /word/o) {
			local($size) = (split(/:/,$type))[1];
			print &html_encode($prefs{$text}).
				" <INPUT TYPE=\"string\" NAME=\"$pref\"".
				" SIZE=$size MAXLENGTH=$size".
				" VALUE=\"$prefs{$_}\">".
				"<BR>\n";
		}
		elsif ($type =~ /integer/o) {
			local($size) = (split(/:/,$type))[1];
			print &html_encode($prefs{$text}).
				" <INPUT TYPE=\"string\" NAME=\"$pref\"".
				" SIZE=$size MAXLENGTH=$size".
				" VALUE=\"$prefs{$_}\">".
				"<BR>\n";
		}
		else {
			print "?!?<INPUT TYPE=\"hidden\" ".
				"VALUE=\"$type\" NAME=\"$pref\">".
				&html_encode($prefs{$text}).
				"<BR>\n";
		}
	}
	print "<BR>\n";
	local($this_section,$prev_section);
	foreach (sort(keys(%prefs))) {
		next if /_/;
		next if /^Gen/;
		local($pref,$type,$text);
		eval "\$pref = 'pref_$_'";
		eval "\$type = $_.'_Type'";
		eval "\$text = $_.'_Text'";
		next unless $prefs{$type};
		$type = $prefs{$type};
		$prev_section = $this_section;
		$this_section = $_;
		$this_section =~ s/([A-Z]*[^A-Z0-9]*).*/$1/;
		$this_section =~ s/[A-Z][a-z]+// if m/[A-Z][A-Z]/o;
		print "<BR>" if
			($this_section ne $prev_section && $prev_section);
		print "<B>$this_section Options</B><BR>\n" if
			($this_section ne $prev_section);
		if ($type =~ /boolean/io) {
			print "<INPUT TYPE=\"checkbox\" NAME=\"$pref\"".
				" VALUE=\"1\"";
			print " CHECKED" if $prefs{$_};
			print "> ".&html_encode($prefs{$text})."<BR>\n";
		}
		elsif ($type =~ /choice/io) {
			local($choice,$item,@items);
			$choice = (split(/:/, $type))[1];
			@items = split(/,/, $choice);
			print &html_encode($prefs{$text}).": ";
			print "<SELECT NAME=\"$pref\" SIZE=1>\n";
			foreach $item (@items) { 
				print "<OPTION VALUE=\"$item\"";
				print " SELECTED" if $item eq $prefs{$_};
				$item = "(no pref)" unless $item;
				print ">$item</OPTION>"; 
			}
			print "</SELECT><BR>\n";
		}
		elsif ($type =~ /word/o) {
			local($size) = (split(/:/,$type))[1];
			print &html_encode($prefs{$text}).
				" <INPUT TYPE=\"string\" NAME=\"$pref\"".
				" SIZE=$size MAXLENGTH=$size".
				" VALUE=\"$prefs{$_}\">".
				"<BR>\n";
		}
		elsif ($type =~ /integer/o) {
			local($size) = (split(/:/,$type))[1];
			print &html_encode($prefs{$text}).
				" <INPUT TYPE=\"string\" NAME=\"$pref\"".
				" SIZE=$size MAXLENGTH=$size".
				" VALUE=\"$prefs{$_}\">".
				"<BR>\n";
		}
		else {
			print "?!?<INPUT TYPE=\"hidden\" ".
				"VALUE=\"$type\" NAME=\"$pref\">".
				&html_encode($prefs{$text})."<BR>\n";
		}
	}
	print <<"EOT";
	</TD></TR>
	<TR><TD VALIGN=TOP ALIGN=LEFT>
		$submit
	</TD></TR>
	</FORM>
	$tbl_end
EOT
	&send_footer();
}

sub send_subset_request_form {
	local($size) = @_;
	#
	local($submit) = "<INPUT TYPE=\"reset\"  VALUE=\"(Undo)\">"
		."<INPUT TYPE=\"submit\" VALUE=\"Modify Subset\">";
	$submit = "<INPUT TYPE=\"image\" NAME=\"submit\" "
		."ALT=\"[Modify Member Subset]\" "
		."SRC=\"WEB_IMGURL/mc_action_button.gif\" BORDER=0>"
		if $prefs{GenSubmitImages};
	local($otheritem) = "<LI>The subset you had previously specified".
		" (/<I>$arg{'subset'}</I>/) resulted in a list that".
		" was still too large to modify. Please try a more".
		" restrictive limit." if $arg{'sizeaction'} eq "subset";
	&send_header(0, "$sitename Mailing Lists");
	print <<"EOT";
	$tbl_start
	<TR><TD COLSPAN=2>
	<FORM NAME=\"prefs\" METHOD=POST ACTION=\"$url\">
	<INPUT TYPE=\"hidden\" NAME=\"module\" VALUE=\"modify\">
	<INPUT TYPE=\"hidden\" NAME=\"action\" VALUE=\"edit\">
	<INPUT TYPE=\"hidden\" NAME=\"view\" VALUE=\"list\">
	<INPUT TYPE=\"hidden\" NAME=\"list\" VALUE=\"$list\">
	<INPUT TYPE=\"hidden\" NAME=\"passwd\" VALUE=\"$passwd\">
	<P>The $size byte subscriber list you are attempting to modify
	is larger than your browser can support, as approximated by 
	the <I>Maximum Size Supported By Browser TextArea</I>
	setting in your Preferences. Please select one of the following
	actions and then press the action button below:
	</TD></TR>
	<TR><TD>
	<INPUT TYPE=\"radio\" NAME=\"sizeaction\" VALUE=\"test\" CHECKED>
	<B>You may test this limitation by forcing an edit of the
	file.</B> Be aware that you may experience an inability to
	add new addresses. If so, you will have to pick one of the
	other actions. Increasing the above Preferences value only 
	changes the watermark that causes this warning screen to
	trigger -- it cannot alter the inherent limitation of your 
	browser.
	</TD></TR>
	<TR><TD>
	<INPUT TYPE=\"radio\" NAME=\"sizeaction\" VALUE=\"append">
	<B>You may ignore this limitation if you want to add members
	rather than edit or delete.</B> You will be given an empty input
	field, and the addresses that you enter will be appended to the
	existing list file.
	</TD></TR>
	<TR><TD>
	<INPUT TYPE=\"radio\" NAME=\"sizeaction\" VALUE=\"subset\">
	<B>You may work around this limitation by modifying a <EM>subset</EM>
	of the subscriber list rather than the complete list.</B> To 
	modify a subset, enter a limiting word here: 
	<DL>
	<DT><DD><INPUT TYPE=\"string\" SIZE=32 MAXLENGTH=64 NAME=\"subset\">
	</DL>
	For example, specifying "edu" will select only the subscriber
	addresses in your list that contain the string <I>edu</I>.
	  <UL>
	  $otheritem
	  <LI>The goal is to select a match that produces a small
	  enough subset to fit within your browser limit. In no
	  way does the subset view affect the actual <I>Majordomo</I> 
	  list contents. Think of the subset as a window into part 
	  of your list.
	  <LI>Once a subset has been established, you are free to
	  make any changes to the list. You are not required to make
	  modifications that match your subset criteria (e.g., you 
	  can add <I>.com</I> addresses even if the selected subset
	  is <I>edu</I>).
	  <LI>Knowledgeable users can specify any complex <I>regular
	  expression</I> for the subset match.
	  </UL>
	</TD></TR>
	<TR><TD ALIGN=CENTER>
		$submit
	</TD></TR>
	</FORM>
	$tbl_end
EOT
	&send_footer();
	&send_done();
}

###############################################################################
# DATA DISPLAY SUBROUTINES
###############################################################################

#-----------------------------------------------
# Display list details (owner, policy, etc).
#-----------------------------------------------
sub send_details {
	local($list,$address,$pattern) = @_;
	#
	&send_error("List <$list> Is Not A Valid List.")
		unless &valid_list($listdir,$list);
	# don't need get_config unless checking web_access!
	if (defined($config'known_keys{web_access})) {
		&get_config($listdir, $list) 
			unless &cf_ck_bool($list, '', 1);
		&send_error($disabled_msg) 
			if &web_lockout($list, "browse");
	}
	local($sub,$email,$listowner,$lock);
	# get subscribed-as
	$sub = $email = &is_subscribed($list,$address,$pattern);
	$email = &html_encode($email) if $email;
	# set owner address
	if (defined($config'known_keys{owner})) {
		$listowner = "$cached_owner{$list}";
		$listowner = "owner-$list\@$whereami" if
			$listowner && ! $sub;
	}
	else {
		$listowner = "owner-$list\@$whereami";
	}
	$lock = "&nbsp;";
	$lock = "<IMG SRC=\"$icon_locked\" $icon_size ALT=\"[L]\">" if
		$cached_spolicy{$list} =~ /closed|locked/o ||
		$cached_upolicy{$list} =~ /closed/o;
	local(@members) = &get_who("$listdir/$list");	# sorting doesn't matter
	local($member_count) = $#members+1;
	local($info_size) = -s "$listdir/$list.info" || 0;
	local($intro_size) = -s "$listdir/$list.intro" || 0;
	local($browse) = "<INPUT TYPE=\"submit\" VALUE=\"Browse\">";
	$browse = "<INPUT TYPE=\"image\" ALIGN=BOTTOM BORDER=0 "
		."ALT=\"[View Other Attributes]\" "
		."SRC=\"WEB_IMGURL/mc_browse_hot.gif\">"
		if $prefs{GenSubmitImages};
	local($modify) = "<INPUT TYPE=\"submit\" VALUE=\"Modify\">";
	$modify = "<INPUT TYPE=\"image\" ALIGN=BOTTOM BORDER=0 "
		."ALT=\"[Modify This List]\" "
		."SRC=\"WEB_IMGURL/mc_modify_hot.gif\">"
		if $prefs{GenSubmitImages};
	&log("BROWSE details $list"
		. " (user='$user',address='$address',pattern='$pattern')");
	&send_header(1, "List Details For <$list>");
	print <<"EOT";
	$tbl_start
	<TR><TD VALIGN=TOP ALIGN=LEFT>
	<UL>
	<LI><IMG SRC=\"$icon_locked\" $icon_size ALT=\"[L]\">
	    indicates that certain subscribe/unsubscribe requests
	    may be forwarded to the list owner for approval.
	</UL>
	</TD></TR>
	$tbl_end
	<BR>
	$tbl_start
EOT
	local($javascript);
	print &html_tbl_row("<B>Name</B>&nbsp;$lock", "$list");
	print &html_tbl_row("<B>Description</B>", 
		"$cached_descr{$list}");
	$javascript =
		" onMouseOver=\"".
		  " msg('Send Mail to the List');".
		  " return true\"".
		" onMouseOut=\"".
		  " msg('');".
		  " return true\"";
	print &html_tbl_row("<B>List Address</B>",
		"<A HREF=\"mailto:$list\@$whereami\"".
       		  ($prefs{GenJavaScript} ? $javascript : "").
		  ">".
		"<CODE>$list\@$whereami</CODE>".
		"</A>");
	$javascript =
		" onMouseOver=\"".
		  " msg('Send Mail to the List Owner');".
		  " return true\"".
		" onMouseOut=\"".
		  " msg('');".
		  " return true\"";
	print &html_tbl_row("<B>List Owner Address</B>",
		($listowner
		  ? "<A HREF=\"mailto:$listowner\"".
       		    ($prefs{GenJavaScript} ? $javascript : "").
		    ">".
		    "<CODE>$listowner</CODE>".
		    "</A>"
		  : "<I>Not Configured</I>")
		);
	print &html_tbl_row("<B>Subscribe Policy</B>",
		"$cached_spolicy{$list}");
	print &html_tbl_row("<B>Unsubscribe Policy</B>",
		"$cached_upolicy{$list}") if $cached_upolicy{$list};
	print &html_tbl_row("<B>Subscribed-As</B>",
		"<CODE>".($email ? $email : "<I>Not Subscribed</I>")."</CODE>");

	# add sub/unsub button for quick action
	if ($cached_owner{$list} || ! defined($config'known_keys{owner})) {
		print &html_tbl_row(
			($sub
			  ? "Unsubscribe Now?"
			  : "Subscribe Now?"
			),
			"<FORM ACTION=\"$url\" METHOD=GET>\n".
			"<INPUT TYPE=hidden NAME=action VALUE=remote>\n".
			"<INPUT TYPE=hidden NAME=list VALUE=$list>\n".
			"<INPUT TYPE=hidden NAME=command VALUE=".
			  ($sub ? "unsubscribe" : "subscribe").">\n".
			"<INPUT TYPE=hidden NAME=siteaddr VALUE=\"".
			  ($sub ? "$sub" : "$arg{siteaddr}")."\">\n".
			($prefs{GenSubmitImages}
			  ? "<INPUT TYPE=\"image\" NAME=\"submit\" ".
			    "ALT=\"[Toggle Subscription]\" ".
			    "SRC=\"WEB_IMGURL/mc_apply_button.gif\" BORDER=0>"
			  : "<INPUT TYPE=submit VALUE=\"Yes\">\n").
			"</FORM>", ""
		);
	}

	print <<"EOT";
	<TR><TD COLSPAN=2><HR ALIGN=CENTER WIDTH=75%>
		<H3 ALIGN=CENTER>Other Details</H3></TD></TR>
	<FORM NAME=\"go_browse_choice\" METHOD=POST ACTION=\"$url\">
		<INPUT TYPE=\"hidden\" NAME=\"module\" VALUE=\"browse\">
		<INPUT TYPE=\"hidden\" NAME=\"action\" VALUE=\"view\">
		<INPUT TYPE=\"hidden\" NAME=\"list\" VALUE=\"$list\">
		<INPUT TYPE=\"hidden\" NAME=\"siteaddr\" 
			VALUE=\"$arg{siteaddr}\">
		<TR><TD VALIGN=TOP ALIGN=RIGHT>
			$browse
		</TD><TD VALIGN=TOP ALIGN=LEFT>
			<INPUT TYPE=\"radio\" NAME=\"view\" VALUE=\"list\"
			CHECKED>
			List Subscribers
			($member_count members)
		</TD></TR>
		<TR><TD></TD>
		<TD VALIGN=TOP ALIGN=LEFT>
			<INPUT TYPE=\"radio\" NAME=\"view\" VALUE=\"info\">
			'Info' File
			($info_size bytes)
		</TD></TR>
EOT
	print <<"EOT" if defined($config'known_keys{'intro_access'});
		<TR><TD></TD>
		<TD VALIGN=TOP ALIGN=LEFT>
			<INPUT TYPE=\"radio\" NAME=\"view\" VALUE=\"intro\">
			'Intro' File
			($intro_size bytes)
		</TD></TR>
EOT
	if (-d "$filedir/$list$filedir_suffix") {
		print <<"EOT";
		<TR><TD></TD>
		<TD VALIGN=TOP ALIGN=LEFT>
			<INPUT TYPE=\"radio\" NAME=\"view\" VALUE=\"files\">
			File/Archive Area
			(not yet supported)
		</TD></TR>
EOT
	}
	print "</FORM>\n";
	if ($modules{'modify'}) {
		print <<"EOT";
		<FORM NAME=\"go_modify_config\" METHOD=POST ACTION=\"$url\">
		<TR><TD COLSPAN=2><HR ALIGN=CENTER WIDTH=75%>
			<H3 ALIGN=CENTER>Modify This List</H3></TD></TR>
		<TR><TD VALIGN=TOP ALIGN=RIGHT>
			<INPUT TYPE=\"hidden\" NAME=\"module\" VALUE=\"modify\">
			<INPUT TYPE=\"hidden\" NAME=\"action\" VALUE=\"edit\">
			<INPUT TYPE=\"hidden\" NAME=\"view\" VALUE=\"config\">
			<INPUT TYPE=\"hidden\" NAME=\"list\" VALUE=\"$list\">
			$modify
		</TD><TD VALIGN=TOP ALIGN=LEFT>
			with
			<INPUT TYPE=\"password\" NAME=\"passwd\" VALUE=\"\" 
			SIZE=32 MAXLENGTH=64><BR>
			(Password required)
		</TD></TR>
		</FORM>
EOT
	}
	print "$tbl_end";
	&send_footer();
}

#-----------------------------------------------
# Display list subscriber file.
#-----------------------------------------------
sub send_who {
	local($list,$address,$pattern) = @_;
	###############################
	# Display list of subscribers #
	###############################
	&send_error("No List Specified.") unless $list;
	&send_error("List <$list> Is Not A Valid List.")
		unless &valid_list($listdir,$list);
	&get_config($listdir, $list) unless &cf_ck_bool($list, '', 1);
	local($member) = &is_subscribed($list,$address,$pattern);
	local($modify) = "<INPUT TYPE=\"submit\" VALUE=\"Modify\">";
	$modify = "<INPUT TYPE=\"image\" ALIGN=BOTTOM BORDER=0 "
		."ALT=\"[Modify This List]\" "
		."SRC=\"WEB_IMGURL/mc_modify_hot.gif\">"
		if $prefs{GenSubmitImages};
	&log("BROWSE who $list"
		. " (user='$user',address='$address',pattern='$pattern')");
	# 1.93 private_who: member, but not trusting --> use email
	# 1.94 who_access=list: member, but not trusting --> use email
	if ($member && ! $opt_trustident &&
	  (($config_opts{$list,'who_access'} eq "list") ||
	    &cf_ck_bool($list,'private_who'))) {
		&majordomo_command("Membership Inquiry", $private_msg,
			$member,
			"who $list\n");
		# move along, move along; nothing to see
		return;
	}
	&send_header(1, "Subscribers For List <$list>");
	# 1.94 who_access=closed: no access at all
	if ($config_opts{$list,'who_access'} eq "closed") {
		print "<H3 ALIGN=CENTER>$secret_msg</H3>";
	}
	# 1.93 private_who: not member --> sorry!
	# 1.94 who_access=list: not member --> sorry!
	elsif (! $member &&
	  ($config_opts{$list,'who_access'} eq "list" ||
	    &cf_ck_bool($list,'private_who'))) {
		print "<H3 ALIGN=CENTER>$member_msg</H3>";
	}
	# 1.9x not private or (private+trusted) --> let 'em see
	else {
		local($nested) = ($prefs{BrowseListNested} ?
			"as hyperlinks" : "in bold");
		print <<"EOT";
		$tbl_start
		<TR><TD VALIGN=TOP ALIGN=LEFT>
		<UL>
		<LI>Nested lists are indicated $nested.
		</UL>
		</TD></TR>
		$tbl_end
		<BR>
		$tbl_start
		<TR><TD VALIGN=TOP ALIGN=LEFT>
		<P>
		<OL>
EOT
		foreach (&get_who("$listdir/$list", $prefs{GenListSorted})) {
			local($who) = &html_encode($_);	# HTML-ize it
			local($addr) = $_;		# but also keep a copy
			$addr =~ s/\@$whereami//o;	# make it local
			$addr =~ tr/A-Z/a-z/;		# and lowercase
			if ($addr =~ /^[\-\w]+$/o && 
			  &valid_list($listdir,$addr)) {
				local($link) = ($prefs{BrowseListNestedWho} ?
					"list" : "config");
				local($javascript) =
					" onMouseOver=\"".
					  " msg('$addr ($link)');".
					  " return true\"".
					" onMouseOut=\"".
					  " msg('');".
					  " return true\"";
				$who = ($prefs{BrowseListNested} 
					? "<A HREF=\"$url?module=browse".
					  "&action=view&view=$link&list=$addr".
					  "&siteaddr=".&url_encode($arg{siteaddr}).
					  "\"".
                			  ($prefs{GenJavaScript} 
					    ? $javascript : "").
					  "><B>$who</B></A>"
					: "<B>$who</B>");
			}
			print ($prefs{BrowseListNumber} ? "<LI>" : "");
			print "<CODE>$who</CODE>";
			print ($prefs{BrowseListNumber} ? "" : "<BR>\n");
		}
		print <<"EOT";
		</OL>
		</TD></TR>
		$tbl_end
EOT
		print <<"EOT" if $modules{'modify'};
		<FORM NAME=\"go_modify_who\" METHOD=POST ACTION=\"$url\">
			$tbl_start
			<TR><TD COLSPAN=2><HR ALIGN=CENTER WIDTH=75%></TD></TR>
			<TR><TD VALIGN=TOP ALIGN=RIGHT>
				<INPUT TYPE=\"hidden\"
					NAME=\"module\" VALUE=\"modify\">
				<INPUT TYPE=\"hidden\"
					NAME=\"action\" VALUE=\"edit\">
				<INPUT TYPE=\"hidden\"
					NAME=\"view\" VALUE=\"list\">
				<INPUT TYPE=\"hidden\"
					NAME=\"list\" VALUE=\"$list\">
				$modify
			</TD><TD VALIGN=TOP ALIGN=LEFT>
				with
				<INPUT TYPE=\"password\" NAME=\"passwd\" VALUE=\"\" 
				SIZE=32 MAXLENGTH=64><BR>
				(Password required)
			</TD></TR>
			$tbl_end
		</FORM>
EOT
	}
	&send_footer();
}

#-----------------------------------------------
# Display list info file.
#-----------------------------------------------
sub send_info {
	local($list,$address,$pattern) = @_;
	#
	&send_error("No List Specified.") unless $list;
	&send_error("List <$list> Is Not A Valid List.")
		unless &valid_list($listdir,$list);
	&get_config($listdir, $list) unless &cf_ck_bool($list, '', 1);
	local($member) = &is_subscribed($list,$address,$pattern);
	local($modify) = "<INPUT TYPE=\"submit\" VALUE=\"Modify\">";
	$modify = "<INPUT TYPE=\"image\" ALIGN=BOTTOM BORDER=0 "
		."ALT=\"[Modify This List]\" "
		."SRC=\"WEB_IMGURL/mc_modify_hot.gif\">"
		if $prefs{GenSubmitImages};
	&log("BROWSE info $list"
		. " (user='$user',address='$address',pattern='$pattern')");
	# 1.93 private_info: member, but not trusting --> use email
	# 1.94 info_access=list: member, but not trusting --> use email
	if ($member && ! $opt_trustident &&
	  ($config_opts{$list,'info_access'} eq "list" ||
	    &cf_ck_bool($list,'private_info'))) {
		&majordomo_command("Membership Inquiry", $private_msg,
			$member,
			"info $list\n");
		# move along, move along; nothing to see
		return;
	}
	&send_header(1, "Info File For List <$list>");
	# 1.94 info_access=closed: no access at all
	if ($config_opts{$list,'info_access'} eq "closed") {
		print "<H3 ALIGN=CENTER>$secret_msg</H3>";
	}
	# 1.93 private_info: not member --> sorry!
	# 1.94 info_access=list: not member --> sorry!
	elsif (! $member &&
	  ($config_opts{$list,'info_access'} eq "list" ||
	   &cf_ck_bool($list,'private_info'))) {
		print "<H3 ALIGN=CENTER>$member_msg</H3>";
	}
	# 1.9x not private or (private+trusted) --> let 'em see
	else {
		local($info) = join("", &get_file("$listdir/$list.info"));
		$info = &html_encode($info);
		$info = "<CENTER>(none)</CENTER>" unless $info;
		print <<"EOT";
		$tbl_start
		<TR><TD VALIGN=TOP ALIGN=LEFT>$info</TD></TR>
		$tbl_end
EOT
		print <<"EOT" if $modules{'modify'};
		<FORM NAME=\"go_modify_info\" METHOD=POST ACTION=\"$url\">
			$tbl_start
			<TR><TD COLSPAN=2><HR ALIGN=CENTER WIDTH=75%></TD></TR>
			<TR><TD VALIGN=TOP ALIGN=RIGHT>
				<INPUT TYPE=\"hidden\"
					NAME=\"module\" VALUE=\"modify\">
				<INPUT TYPE=\"hidden\"
					NAME=\"action\" VALUE=\"edit\">
				<INPUT TYPE=\"hidden\"
					NAME=\"view\" VALUE=\"info\">
				<INPUT TYPE=\"hidden\"
					NAME=\"list\" VALUE=\"$list\">
				$modify
			</TD><TD VALIGN=TOP ALIGN=LEFT>
				with
				<INPUT TYPE=\"password\" NAME=\"passwd\" VALUE=\"\" 
				SIZE=32 MAXLENGTH=64><BR>
				(Password required)
			</TD></TR>
			$tbl_end
		</FORM>
EOT
	}
	&send_footer();
}

#-----------------------------------------------
# Display list intro file.
#-----------------------------------------------
sub send_intro {
	local($list,$address,$pattern) = @_;
	#
	&send_error("No List Specified.") unless $list;
	&send_error("List <$list> Is Not A Valid List.")
		unless &valid_list($listdir,$list);
	&get_config($listdir, $list) unless &cf_ck_bool($list, '', 1);
	local($member) = &is_subscribed($list,$address,$pattern);
	local($modify) = "<INPUT TYPE=\"submit\" VALUE=\"Modify\">";
	$modify = "<INPUT TYPE=\"image\" ALIGN=BOTTOM BORDER=0 "
		."ALT=\"[Modify This List]\" "
		."SRC=\"WEB_IMGURL/mc_modify_hot.gif\">"
		if $prefs{GenSubmitImages};
	&log("BROWSE intro $list"
		. " (user='$user',address='$address',pattern='$pattern')");
	# 1.94 intro_access=list: member, but not trusting --> use email
	if ($member && ! $opt_trustident &&
	  $config_opts{$list,'intro_access'} eq "list") {
		&majordomo_command("Membership Inquiry", $private_msg,
			$member,
			"intro $list\n");
		# move along, move along; nothing to see
		return;
	}
	&send_header(1, "Intro File For List <$list>");
	# 1.94 intro_access=closed: no access at all
	if ($config_opts{$list,'intro_access'} eq "closed") {
		print "<H3 ALIGN=CENTER>$secret_msg</H3>";
	}
	# 1.94 intro_access=list: not member --> sorry!
	elsif (! $member && $config_opts{$list,'intro_access'} eq "list") {
		print "<H3 ALIGN=CENTER>$member_msg</H3>";
	}
	# 1.9x not private or (private+trusted) --> let 'em see
	else {
		local($intro) = join("", &get_file("$listdir/$list.intro"));
		$intro = &html_encode($intro);
		$intro = "<CENTER>(none)</CENTER>" unless $intro;
		print <<"EOT";
		$tbl_start
		<TR><TD VALIGN=TOP ALIGN=LEFT>$intro</TD></TR>
		$tbl_end
EOT
		print <<"EOT" if $modules{'modify'};
		<FORM NAME=\"go_modify_intro\" METHOD=POST ACTION=\"$url\">
			$tbl_start
			<TR><TD COLSPAN=2><HR ALIGN=CENTER WIDTH=75%></TD></TR>
			<TR><TD VALIGN=TOP ALIGN=RIGHT>
				<INPUT TYPE=\"hidden\"
					NAME=\"module\" VALUE=\"modify\">
				<INPUT TYPE=\"hidden\"
					NAME=\"action\" VALUE=\"edit\">
				<INPUT TYPE=\"hidden\"
					NAME=\"view\" VALUE=\"intro\">
				<INPUT TYPE=\"hidden\"
					NAME=\"list\" VALUE=\"$list\">
				$modify
			</TD><TD VALIGN=TOP ALIGN=LEFT>
				with
				<INPUT TYPE=\"password\" NAME=\"passwd\" VALUE=\"\" 
				SIZE=32 MAXLENGTH=64><BR>
				(Password required)
			</TD></TR>
			$tbl_end
		</FORM>
EOT
	}
	&send_footer();
}

#-----------------------------------------------
# View a raw message in the queue. text/plain
#   is used to bypass any HTML format conflicts.
#-----------------------------------------------
sub send_queuemsg {
	local($list,$passwd,$msgid) = @_;
	#
	&send_error("No List Specified.") unless $list;
	&send_error("List <$list> Is Not A Valid List.")
		unless &valid_list($listdir,$list);
	&get_config($listdir, $list) unless &cf_ck_bool($list, '', 1);
	&send_error("Incorrect Password For List <$list>") 
		unless &valid_passwd($listdir, $list, $passwd) ||
		($config_opts{$list,"approve_passwd"} ne '' &&
			$config_opts{$list,"approve_passwd"} eq $passwd);
	# This MODIFY action will accept the Moderator
	# password in addition to the Admin password.

	# allow global bouncedir to be set in majordomo.cf
	# if not, use the per-list archive area
	#
	local($bouncepre) = "${list}.bounce";
	unless ($bouncedir) {
		$bouncedir = "$filedir/$list$filedir_suffix";
		$bouncepre = "bounce";	# list ref in directory name
	}
	# something is not set up right! default to TMP area
	unless (-d "$bouncedir") {
		$bouncedir = "$TMPDIR";
		$bouncepre = "${list}.bounce";
	}

	&send_error("Invalid Message-ID")
		unless -f "$bouncedir/${bouncepre}.$msgid";
	&send_error("Unable To Open Message: $!")
		unless &lopen(MSG, "<", "$bouncedir/${bouncepre}.$msgid");

	&log("MODIFY queue $list view <$msgid>");
	print "Content-type: text/plain\n\n";
	while (<MSG>) {
		print $_; 
	}
	&lclose(MSG);
}

#-----------------------------------------------
# Not a display command; a Majordomo pass-thru.
#-----------------------------------------------
sub send_subunsub {
	local($list,$address,$pattern,$command) = @_;
	#
	&send_error("No List Specified.") unless $list;
	&send_error("List <$list> Is Not A Valid List.")
		unless &valid_list($listdir,$list);
	local($member) = &is_subscribed($list,$address,$pattern);
	&send_error("You Are Already Subscribed To \"$list\" As $member")
		if ($command eq "subscribe" && $member);
	&send_error("You Are Not Currently Subscribed To \"$list\"")
		if ($command eq "unsubscribe" && ! $member);
	&log("REMOTE $command $list"
		. " (user='$user',address='$address',pattern='$pattern')");
	&majordomo_command("Subscription Update", "",
		($member ? $member : $address),
		"$command $list\n");
}

#-----------------------------------------------
# Display program 'ABOUT' info. Both local &
# built-in hooks are provided.
#-----------------------------------------------
sub send_about {
	&log("HELP about");
	&send_header(1, "MajorCool: A Web Interface to Majordomo");
	print <<"EOT";
	$tbl_start
	<TR><TD VALIGN=TOP ALIGN=CENTER>
	<I>Majordomo</I> is a mailing list manager developed by Brent
	Chapman and released (&copy; Great Circle Associates) to the public
	domain.  For additional information on <I>Majordomo</I>, see the 
	<A HREF="http://www.greatcircle.com/majordomo/"><I>Majordomo</I>
	archive site</A>.
	<P>
	<I>MajorCool</I> is a Web-based interface to the <I>Majordomo</I>
	list manager. It was developed by Bill Houle and has been made 
	<A HREF="http://ncrinfo.ncr.com/pub/contrib/unix/MajorCool/">
	available</A> to the <I>Majordomo</I> community (&copy; NCR
	Corporation).
	</TD></TR>
	$tbl_end
EOT
	&send_footer();
}

#-----------------------------------------------
# Call external HTML Help file.
#-----------------------------------------------
sub send_help {
	local($url) = "$scheme://$ENV{SERVER_NAME}:$ENV{SERVER_PORT}$helpfile";
	&log("HELP $helpfile");
	if ($arg{'module'}) {
		local($anchor) = $arg{'module'};
		$anchor =~ tr/a-z/A-Z/;		# anchors in u/c
		$url .= "#$anchor";
	}
	print "Location: $url\n\n";
}

#-----------------------------------------------
# Output an error for browser display.
#-----------------------------------------------
sub send_error {
	if (-f $log) {
		local($module) = "INIT";
		$module = $arg{module} if $arg{module};
		$module =~ tr/a-z/A-Z/;
		&log("$module ERROR: @_");
	}
	&send_header(0, "MajorCool Error");
	print "<H3 ALIGN=CENTER>";
	foreach (@_) { print &html_encode("$_\n"); }
	print "</H3>\n";
	# keep some debug output handy
	foreach (keys(%arg)) {
		print "<!-- key: $_	value: $arg{$_} -->\n";
	}
	&send_footer();
	&send_done();
}

#-----------------------------------------------
# Send a status message as a multi-part push.
# Each part begins with separator & MIME type.
#-----------------------------------------------
sub send_wait {
	local($msg) = join(" ", @_);
	# not all browsers will support this push
	return unless $InMultiPart;
	&send_header(0, "Processing...");
	print <<"EOT";
	$tbl_start
	<TR><TD ALIGN=CENTER VALIGN=TOP>
	<H1 ALIGN=CENTER>$msg</H1>
	<IMG SRC=\"WEB_IMGURL/mc_wait.gif\">
	<H2 ALIGN=CENTER>This may take a few moments...</H2>
	</TD></TR>
	$tbl_end
EOT
	&send_footer();
}

#-----------------------------------------------
# Initiate multi-part output if supported.
#-----------------------------------------------
sub send_multi {
	if ($opt_multipart) {
	print "Content-type: multipart/x-mixed-replace;boundary=MajorCool\n\n";
	$InMultiPart = 1;
	}
}

#-----------------------------------------------
# Done with all output, so exit the script.
# Terminate the multi-part if supported.
#-----------------------------------------------
sub send_done {
	print "--MajorCool--\n" if $InMultiPart;
	exit;
}

#-----------------------------------------------
# Generic feel-good phrase to follow all big
# 'modify' operations.
#-----------------------------------------------
sub send_warm_fuzzy {
	local($to) = shift(@_);
	$to = "<CODE>$to</CODE>" if $to;
	$to = "you" unless $to;
	print <<"EOT";
	$tbl_start
	<TR><TD VALIGN=TOP ALIGN=LEFT>
	<HR ALIGN=CENTER WIDTH=75%>
	<UL>
	<LI>Examine the results of the requested <I>Majordomo</I> command
	above. Any errors will appear between the lines.
	<LI>Assuming there were no obvious problems, $to will receive
	a confirmation from <I>Majordomo</I> via e-mail.
	<LI>Any list creation/ownership changes may have to wait for the
	system to be updated. Results of your submission may not be 
	immediately visible.
	</UL>
	</TD></TR>
	$tbl_end
EOT
}

###############################################################################
# MAJORDOMO 'GLUE' SUBROUTINES
###############################################################################

#-----------------------------------------------
# Create a table of cached keys for each list.
#-----------------------------------------------
sub load_cache {
	&send_error("Cannot Open File <$cache> -- $!")
		unless open(CACHE, "$cache");
	while (<CACHE>) {
		chop;					# remove the trailing \n
		next if /^\s*$/;			# remove blank lines
		local($list,$owner,$spolicy,$upolicy,$descr,$adv,$noadv)
			 = split(/\007/);
		# only add good lists to the cache
		if (&valid_list($listdir,$list)) {
			$cached_owner{$list} = $owner;
			$cached_spolicy{$list} = $spolicy;
			$cached_upolicy{$list} = $upolicy;
			$cached_descr{$list} = &html_encode($descr);
			$cached_adv{$list} = $adv; 
			$cached_noadv{$list} = $noadv;
		}
	}
	close(CACHE);
}

#-----------------------------------------------
# Determine subscription status and list
# visibility for any or all lists.
#-----------------------------------------------
sub get_status {
	local($address,$pattern,$single) = @_;
	local(@lists) = ($single ? $single : keys(%cached_descr));
	local($remain);
	#
	&send_error("Cannot Determine Access Privileges -- No E-Mail"
		." Address Data Available.") unless $address;
	$remain = @lists;

	# single list or no subscription checks are fast enough to skip msg
	unless ($arg{quickview} || $single) {
		if ($arg{'action'} eq "do_subunsub") {
			&send_wait("Validating Subscription Changes");
		}
		else {
			&send_wait("Checking Subscriptions ".
				"And Access Rights<BR>".
				"($remain Lists to Examine)");
		}
	}

	foreach $l (@lists) {
		unless ($arg{quickview}) {
			if ($opt_scansteps && ($remain % $opt_scansteps == 0)) {
				&send_wait("Checking ".
					"Subscriptions And ".
					"Access Rights<BR>".
					"($remain Lists Remaining)")
				unless ($arg{'action'} eq "do_subunsub");
			}
			$user_status{$l} = &is_subscribed($l,$address,$pattern)
		}
		$user_advertised{$l} = &is_advertised($l,$address);
		$remain--;
	}
}

#-----------------------------------------------
# Find all lists matching a certain criteria.
#-----------------------------------------------
sub get_lists {
	local($address,$pattern,$criteria,$list_match) = @_;
	local(@lists);
	#
	# all lists
	if ($criteria eq "available") {
		@lists = sort(keys(%cached_descr));
	}
	#
	# lists subscribed by this person
	elsif ($criteria eq "subscribed") {
		&send_error("You Must Enable Subscription Tests ".
			"For This Browse Function.") if $arg{quickview};
		foreach (sort(keys(%cached_descr))) {
			push(@lists,$_) if $user_status{$_};
		}
	}
	#
	# lists not subscribed by this person
	elsif ($criteria eq "unsubscribed") {
		&send_error("You Must Enable Subscription Tests ".
			"For This Browse Function.") if $arg{quickview};
		foreach (sort(keys(%cached_descr))) {
			push(@lists,$_) unless $user_status{$_};
		}
	}
	#
	# lists owned by this person
	elsif ($criteria eq "owned") {
		foreach (sort(keys(%cached_descr))) {
			if ($pattern) {
				push(@lists,$_) if
					$cached_owner{$_} =~ /$pattern/io;
			}
			else {
				push(@lists,$_) if
					&addr_match($cached_owner{$_},$address,
						undef);
			}
		}
	}
	#
	# lists not yet configured
	elsif ($criteria eq "unconfigured") {
		foreach (sort(keys(%cached_descr))) {
			push(@lists,$_) if &is_advertised($_,$address) &&
				! $cached_owner{$_};
		}
	}
	elsif ($criteria eq "matched") {
		&send_error("No Search Pattern Specified.")
			unless $list_match;
		foreach (sort(keys(%cached_descr))) {
			push(@lists,$_) if &is_advertised($_,$address) &&
				($_ =~ /$list_match/io ||
				 $cached_descr{$_} =~ /$list_match/io);
		}
	}
	return @lists;
}

#-----------------------------------------------
# Determine whether this list should be shown
# to this email address.
#-----------------------------------------------
sub is_advertised {
	local($list,$address) = @_;
	#
	return 1 if ($user_status{$list} && $opt_noadvertise);
	local($test);
	if ($cached_noadv{$list}) {
		foreach $re (split(/\001/,$cached_noadv{$list})) {
			$test = '($address' . " =~ $re)";
			return 0 if eval $test;
		}
	}
	if ($cached_adv{$list}) {
		foreach $re (split(/\001/,$cached_adv{$list})) {
			$test = '($address' . " =~ $re)";
			return 1 if eval $test;
		}
		return 0;
	}
	return 1;
}

#-----------------------------------------------
# Determine if the search string is found in the
# list's subscriber file. Returns all matching
# email addresses.
#-----------------------------------------------
sub is_subscribed {
	local($list,$address,$pattern) = @_;
	local($hit) = "";
	#
	&send_error("List <$list> Is Not A Valid List.")
		unless &valid_list($listdir,$list);
	&send_error("Could Not Open List <$list>: $!")
		unless open(LIST, "$listdir/$list");
	while (<LIST>) {
		chop;					# remove the trailing \n
		if ($pattern) {
			# scrub entry before comparison
			next if /^\s*\#|^\s*$/;		# remove comment, blanks
			s/^([^#]*)\#.*$/$1/;		# remove ending comments
			s/^\s*//;			# remove leading w-space
			$_ = join(", ",&ParseAddrs($_));# remove RFC comments
			#
			$hit .= "$_\n" if /$pattern/i;
				#
				# could be multiple matches with regexp,
				#   so search the entire list
		}
		else {
			# addr_match will do the scrubbing for us
			if (&addr_match($_,$address,undef)) {
				$hit = "$_\n";
				last;
				#
				# Majordomo will(should) not allow duplicate
				#   addresses, so one match is sufficient
				#   for the straight comparison case
			}
		}
	}
	close(LIST);
	chop($hit);					# remove trailing \n
	return $hit;
}

#-----------------------------------------------
# Create a list of all subscribers for a list.
#-----------------------------------------------
sub get_who {
	local($file,$sort,$subset) = @_;
	#
	local(@who);
	&send_error("List <$list> Is Not A Valid List.")
		unless &valid_list($listdir,$list);
	&send_error("Cannot Open File <$file> -- $!") 
		unless &lopen(LIST, "<", "$file");

	while (<LIST>) {
		chop;				# remove newline
		s/^\s*|\s*$//;			# remove lead/trailing w-space
		next if /^\s*\#|^\s*$/;		# remove comment,blank lines
		push(@who,$_)			# build our list
			if ($_ =~ /$subset/io || ! $subset);
	}
	&lclose(LIST);
	if ($sort) {
		if (defined(&by_siteaddr)) {
			return (sort by_siteaddr @who);
		}
		else {
			return (sort @who)
		}
	}
	else {
		return (@who);
	}
}

#-----------------------------------------------
# Get all lines from the requested Domo file.
#-----------------------------------------------
sub get_file {
	local($file) = @_;
	#
	local(@lines);
	if (&lopen(FILE, "<", "$file")) {
		while (<FILE>) { push (@lines,$_); }
		&lclose(FILE);
	}
	return (@lines);
}

#-----------------------------------------------
# Get all keywords for the specified level.
#-----------------------------------------------
sub get_keywords {
	local($keyview) = @_;
	#
	local(@keywords);
	if ($keyview eq "all") {
		@keywords = sort(keys(%config'known_keys));
	}
	elsif ($keyview eq "basic") {
	  	@keywords = @admin_keywords;
	}
	else {
		foreach (sort(keys(%config'subsystem))) {
			push(@keywords,$_) 
				if $config'subsystem{$_} =~ /,?$keyview,?/o;
		}
	}
	return @keywords;
}

#-----------------------------------------------
# Build command(s) in email and send to $engine.
#-----------------------------------------------
sub majordomo_command {
	local($mode,$msg,$from,@lines) = @_;
	#
	&majordomo_deferred($mode,$msg,$from,@lines) if $opt_cmdverify;
	# Normally, we'd run majordomo under the Majordomo wrapper.
	# But since the CGI is already "under wraps", it is not necessary.
	local($engine) = "$ENV{HOME}/majordomo";
	$engine = "cat" if $debug;
#	&debug_dump();
	delete($arg{'module'});		# undo any 'active' menu buttons
	local($identity) = "$from (MajorCool: $remote)";

	&send_error("Unable To Determine Submission Address.<BR>"
		."Make Sure The List Owner E-Mail Address Is Defined Or You "
		."Have Used The <I>apply-as</I> Field.") unless $from;

	&send_header(0, "Majordomo Results");
	print <<"EOT";
	<H2 ALIGN=CENTER>One Moment Please. Your Request
	  Is Being Processed...</H2>
	$tbl_start<TR><TD VALIGN=TOP ALIGN=LEFT>
	<P>$msg
	<P>Here are the results of your <I>Majordomo</I> $mode request:<BR>
	<FONT SIZE="-1">($remote)</FONT>
	<HR ALIGN=CENTER WIDTH=75%>
	<PRE>
EOT
	&send_error("Cannot Open Program <$engine> -- $!") 
		unless open(MAJORDOMO, "|$engine");
	select((select(MAJORDOMO), $|=1)[$[]);		# unbuffered
	print MAJORDOMO "From: $identity\n";
	print MAJORDOMO "To: $whoami\n\n";
	print MAJORDOMO @lines;
	close MAJORDOMO;
	print "</PRE>\n";
	print "</TD></TR>\n$tbl_end";
	&send_warm_fuzzy($from);
	&send_footer();
	&send_done();
}

#-----------------------------------------------
# Build command(s) in email and send to user.
#-----------------------------------------------
sub majordomo_deferred {
	local($mode,$msg,$from,@lines) = @_;
	#
	delete($arg{'module'});		# undo any 'active' menu buttons
	local($identity) = 
		"$from (MajorCool: $remote)";

	&send_error("Unable To Determine Submission Address.<BR>"
		."Make Sure The List Owner E-Mail Address Is Defined Or You "
		."Have Used The <I>apply-as</I> Field.") unless $from;

	&send_header(0, "Majordomo Results");
	&set_mailer($bounce_mailer ? $bounce_mailer : $mailer);
	&set_mail_from($whoami);
	&set_mail_sender($whoami_owner);
	&sendmail(MAIL, $from, $mode);
	print MAIL <<"EOT";
The following Majordomo commands were requested in your name
via the MajorCool Web interface.
	MajorCool Host: $cfbase/$whereami
	Web Requestor:  $remote
If this is incorrect, please delete this message.

However, if this is correct, you must return these commands to the
$whereami listserver to initiate the requested 
actions. Simply reply to this message and remove everything up 
to and including the break (--) below. Remove any quoting 
characters on the remaining lines.

Do not reply to this message for any other reasons. If you need
to talk with a person, please address all correspondence to
$whoami_owner.
--

EOT
	foreach (@lines) { print MAIL; }
	close(MAIL);
	print <<"EOT";
	<H2 ALIGN=CENTER>Your $mode Request Has Been Recieved</H2>
	<H3 ALIGN=CENTER>It Has Been Converted To The Equivalent
	Majordomo Command(s) And Is Being Sent To You ($from) In
	E-Mail. Please Follow The Directions Outlined In That Message
	To Initiate The Desired Actions.</H3>
	$tbl_start<TR><TD VALIGN=TOP ALIGN=LEFT>
	<P>$msg
	</TD></TR>\n$tbl_end
EOT
	&send_footer();
	&send_done();
}

###############################################################################
# INITIALIZATION FUNCTIONS
###############################################################################

#-----------------------------------------------
# Parse the input to capture all info passed on 
# the command line.
#-----------------------------------------------
sub init_args {
	local(@ARGV) = @_;
	#
	$cf = $cfbase = $ARGV[0]; shift(@ARGV);	# get MajorCool config filename
	$cfbase =~ s/MajorCool[_](\w+)\.cf/$1/io;# base config name
	while ($ARGV[0]) {			# loop through other args
		if ($ARGV[0] eq "-env") {
			local($key,$value);
			shift(@ARGV);
			($key,$value) = split(/=/,$ARGV[0],2);
			$ENV{$key} = &url_decode($value);
		}
		shift(@ARGV);
	}

	# This one was weird. Appeared to be a Cookie problem,
	# because once the Cookies.txt file was erased, the problem
	# disappeared.
	#
	if ($ENV{CONTENT_LENGTH} < 0) {
		&send_error("The Web Server Is Reporting A Negative"
		." Content-Length Header Resulting From Your Selection.<BR>",
		"This Could Be Caused By A Corrupted 'Cookies' File.");
	}

	# This is probably non-standard. Usually, you
	# don't support both GET and POST. It was done
	# done this way to allow both embedded FORM &
	# command-line args to be supported.
	#
	local($buffer) = "";
	if ($ENV{REQUEST_METHOD} eq "POST") {		# via POST
		read(STDIN, $buffer, $ENV{CONTENT_LENGTH});
	}
	if ($ENV{QUERY_STRING}) {			# via GET
		$buffer .= "&" if $buffer;
		$buffer .= "$ENV{QUERY_STRING}";
	}
	$buffer =~ s|^/||o;				# remove any leftover /
	foreach (split(/&/, $buffer)) {			# build array
		next unless /^([^=]+)=(.*)$/;
		$arg{$1} = &url_decode($2);
	}
	# Smash case on listnames
	$arg{'list'} =~ tr/A-Z/a-z/;
	# Check to make sure that certain $arg types are kosher
	&send_error("Invalid Format For List Name. [$arg{list}]") 
		if defined($arg{list}) 
		&& $arg{list} !~ /^[\w\-]+$/o;
	&send_error("Invalid Search Pattern Specified.") 
		if defined($arg{list_match}) 
		&& $arg{list_match} =~ /^[\?\+\*]|\|[\?\+\*]/o;
	&send_error("Invalid Search Pattern Specified.") 
		if defined($arg{subset}) 
		&& $arg{subset} =~ /^[\?\+\*]|\|[\?\+\*]/o;
}

#-----------------------------------------------
# Reconcile Form changes with Prefs settings.
# Prevent certain Prefs if conditions not met.
# Set/Reset Cookies in HTTP header.
#-----------------------------------------------
sub init_prefs {
	# Extract Cookie values to set/reset Preferences
	foreach (split(/;/, $ENV{HTTP_COOKIE})) {
		# wrapper does not preserve args, so I had
		# to URL-ify as a way to pass them through
		$_ = &url_decode($_);
		# MajorCool cookie or some other app's variable?
		if (/^\s*MajorCool[_](\S+)\s*=(.*)$/) {
			local($pref,$value) = ($1,$2);
			$value =~ s/^\s+|\s+$//o;	# whitespace
			eval "\$prefs{$pref} = \"$value\"";
		}
	}
	#
	# ...backwards compat with 1.0 Prefs
	#
	$prefs{ListSorted} = $prefs{SortedList} if $prefs{SortedList};
	$prefs{GenCache} = $prefs{HTTPCache} if $prefs{HTTPCache};
	$prefs{GenMenuMode} = $prefs{MenuMode} if $prefs{MenuMode};
	$prefs{GenMenuStart} = $prefs{MenuStart} if $prefs{MenuStart};
	#
	# ...backwards compat with 1.1 Prefs
	#
	$prefs{GenListSorted} = $prefs{ListSorted} if $prefs{ListSorted};
	$prefs{BrowseListNested} = $prefs{ListNested} if $prefs{ListNested};
	$prefs{BrowseListNumber} = $prefs{ListNumber} if $prefs{ListNumber};
	$prefs{ModifyConf2Column} = $prefs{Conf2Column} if $prefs{Conf2Column};
	$prefs{ModifyConfHelp} = $prefs{ConfHelp} if $prefs{ConfHelp};
	$prefs{ModifyConfSubsys} = $prefs{ConfSubsys} if $prefs{ConfSubsys};
	$prefs{ModifyInfoWrap} = $prefs{InfoWrap} if $prefs{InfoWrap};
	$prefs{ModifyListMaxSize} = $prefs{ListMaxSize} if $prefs{ListMaxSize};

	# Extract pref_xxx form settings and convert to %prefs value
	if ($arg{'action'} eq "prefs") {
		&log("PREFS set '$ENV{HTTP_USER_AGENT}'");
		foreach (keys(%prefs)) {
			next if m/_/;
			local($pref,$type);
			eval "\$pref = 'pref_'.$_";
			eval "\$type = $_.'_Type'";
			$type = $prefs{$type};
			if ($type =~ /boolean/io) {
				# Unchecked checkboxes are not sent.
				# Therefore, set to 0 if not present.
				$prefs{$_} = ($arg{$pref} ? 1 : 0);
			}
			elsif ($type =~/word/io) {
				$prefs{$_} = $arg{$pref} 
					if $arg{$pref} =~ /^\S+$/o;
			}
			elsif ($type =~/integer/io) {
				$prefs{$_} = $arg{$pref} 
					if $arg{$pref} > 0;
			}
			else {
				$prefs{$_} = $arg{$pref}
					if defined($arg{$pref});
			}
		}
	}

	# If module is disabled, don't show module-related prefs
	foreach (keys(%prefs)) {
		next if /^Gen/;		# always leave General prefs
		local($module) = $_;
		$module =~ s/([A-Z]*[^A-Z0-9]*).*/$1/;
		$module =~ s/[A-Z][a-z]+// if m/[A-Z][A-Z]/o;
		$module =~ tr/A-Z/a-z/;
		delete($prefs{$_}) if ! $modules{$module};
	}

	# Done manipulating Prefs array. Everything from here 
	#   on is only when action=prefs.
	#
	return unless ($arg{'action'} eq "prefs");

	# Set Cookies to store user preferences
	#
	# A Pref is in the array if it is legit, or if it was
	# picked up by processing HTTP_COOKIE. Need to save
	# legit values and unsave any that are no longer valid
	# keys.
	#
	foreach (keys(%prefs)) {
		next if /_/;
		local($type);
		eval "\$type = $_.'_Type'";
		if ($prefs{$type}) {
			# 'expire' is millenium for longevity
			# 'path' is root to allow script movement
			print "Set-Cookie: MajorCool"."_$_=$prefs{$_};"
			  ." expires=Mon, 01-Jan-2001 00:00:01 GMT; path=/\n";
		}
		else {
			# Can you guess the significance of this date?
			print "Set-Cookie: MajorCool"."_$_=$prefs{$_};"
			  ." expires=Fri, 16-Feb-1996 00:00:01 GMT; path=/\n";
		}
	}

	if ($opt_prefsreturn) {
		# When the Prefs module was selected, we saved the
		#   current state. Now we must re-create that state
		#  in order to return from where we left.
		#
		local($buffer) = &url_decode($arg{state});
		foreach (split(/!!/, $buffer)) {
			$arg{$1} = $2 if /^([^=]+)=(.*)$/;
		}
	}
}

#-----------------------------------------------
# Build page header/footer strings based on $cf
# (include files) and prefs (width, MousOvers).
#-----------------------------------------------
sub init_page {
	# so many ways to center on a page! this should cover all browsers
	$tbl_start =
		"\n<DIV ALIGN=CENTER><CENTER>\n\t".
		"<TABLE ALIGN=CENTER VALIGN=TOP".
		" WIDTH=\"$prefs{GenScreenWidth}\" BORDER=0>";
	$tbl_start_border =
		"\n<DIV ALIGN=CENTER>\n\t".
		"<TABLE ALIGN=CENTER VALIGN=TOP".
		" WIDTH=\"$prefs{GenScreenWidth}\" BORDER=1>";
	$tbl_end =
		"\n\t</TABLE>\n</CENTER></DIV>";
	$page_footer = 
		"$tbl_start".
		"<TR><TD VALIGN=TOP ALIGN=CENTER>".
		"<HR ALIGN=CENTER WIDTH=75%>".
		"<A HREF=\"$url?module=$arg{'module'}\"";
	#
	$page_footer .= 
		" onMouseOver=\"".
		  " img('cool',1);".
		  " msg('Restart MajorCool');".
		  ($prefs{GenToolTips} ? 
		    " tip('BOTTOM','$tip{restart}',1);" : "").
		  " return true\"".
		" onMouseOut=\"".
		  " img('cool',0);".
		  " msg('');".
		  ($prefs{GenToolTips} ? 
		    " tip('BOTTOM','',0);" : "").
		  " return true\"".
		" onClick=\"".
		  " img('cool',0);".
		  " msg('');".
		  ($prefs{GenToolTips} ? 
		    " tip('BOTTOM','',0);" : "").
		  " return true\""
		if $prefs{GenJavaScript};
	#
	$page_footer .= ">". &imgsrc(&img('cool','button'),
		"NAME=cool ALT=\"[RESTART]\" ALIGN=MIDDLE BORDER=0").
		"</A>";
	if (! $prefs{GenMenuMode}) {
		local($state);
		if ($opt_prefsreturn) {
			$state = &url_encode(&save_state());
			$state = "&state=$state" if $state;
		}
		$page_footer .= "<A HREF=\"$url?module=prefs$state\"";
		#
		$page_footer .=
			" onMouseOver=\"".
			  " img('prefs',1);".
			  " msg('Set MajorCool Preferences');".
			  ($prefs{GenToolTips} ?
			    " tip('BOTTOM','$tip{prefs}',1);" : "").
			  " return true\"".
			" onMouseOut=\"".
			  " img('prefs',0);".
			  " msg('');".
			  ($prefs{GenToolTips} ? 
			    " tip('BOTTOM','',0);" : "").
			  " return true\"".
			" onClick=\"".
			  " img('prefs',0);".
			  " msg('');".
			  ($prefs{GenToolTips} ? 
			    " tip('BOTTOM','',0);" : "").
			  " return true\""
			if $prefs{GenJavaScript};
		#
		$page_footer .= ">". &imgsrc(&img('prefs','button'),
	  		"NAME=prefs ALT=\"[PREFS]\" ALIGN=MIDDLE BORDER=0").
			"</A>";
	}
	$page_footer .= "<A HREF=\"$url?module=$arg{'module'}&action=help\"";
	#
	$page_footer .= 
		" onMouseOver=\"".
		  " img('help',1);".
		  " msg('Get Detailed Help For This ".
		    ($prefs{GenMenuMode} ? "Function" : "Program")."');".
		  ($prefs{GenToolTips} ?
		    " tip('BOTTOM','$tip{help}',1);" : "").
		  " return true\"".
		" onMouseOut=\"".
		  " img('help',0);".
		  " msg('');".
		  ($prefs{GenToolTips} ? 
		    " tip('BOTTOM','',0);" : "").
		  " return true\"".
		" onClick=\"".
		  " img('help',0);".
		  " img('banner',2);".
		  " msg('');".
		  ($prefs{GenToolTips} ? 
		    " tip('BOTTOM','',0);" : "").
		  " return true\""
		if $prefs{GenJavaScript};
	#
	$page_footer .= ">". &imgsrc(&img('help','button'),
		"NAME=help ALT=\"[HELP]\" ALIGN=MIDDLE BORDER=0").
		"</A>".
		"<A HREF=\"mailto:majordomo-owner\@$whereami\"";
	#
	$page_footer .=
		" onMouseOver=\"".
		  " img('mail',1);".
		  " msg('Send E-Mail To The majordomo-owner');".
		  ($prefs{GenToolTips} ? 
		    " tip('BOTTOM','$tip{mail}',1);" : "").
		  " return true\"".
		" onMouseOut=\"".
		  " img('mail',0);".
		  " msg('');".
		  ($prefs{GenToolTips} ? 
		    " tip('BOTTOM','',0);" : "").
		  " return true\"".
		" onClick=\"".
		  " img('mail',0);".
		  " msg('');".
		  ($prefs{GenToolTips} ? 
		    " tip('BOTTOM','',0);" : "").
		  " return true\""
		if $prefs{GenJavaScript};
	#
	$page_footer .= ">". &imgsrc(&img('mail','button'),
		"NAME=mail ALT=\"[MAIL]\" ALIGN=MIDDLE BORDER=0").
		"</A>".
		"<A HREF=\"$sitehome\"";
	#
	$page_footer .=
		" onMouseOver=\"".
		  " img('home',1);".
		  " msg('Return From Whence This Service Came');".
		  ($prefs{GenToolTips} ?
		    " tip('BOTTOM','$tip{home}',1);" : "").
		  " return true\"".
		" onMouseOut=\"".
		  " img('home',0);".
		  " msg('');".
		  ($prefs{GenToolTips} ? 
		    " tip('BOTTOM','',0);" : "").
		  " return true\"".
		" onClick=\"".
		  " img('home',0);".
		  " msg('');".
		  ($prefs{GenToolTips} ? 
		    " tip('BOTTOM','',0);" : "").
		  " return true\""
		if $prefs{GenJavaScript};
	#
	$page_footer .= ">". &imgsrc(&img('home','button'),
		"NAME=home ALT=\"[HOME]\" ALIGN=MIDDLE BORDER=0").
		"</A>".
		"<BR><P><FONT size=-1><I>MajorCool</I> version $version,".
		" &copy;1996-1998 NCR Corporation</FONT>".
		"<BR><FONT SIZE=-1>[$cfbase/$whereami]</FONT>".
		"</TD></TR>\n$tbl_end";
	#
	# NOTE: external files should provide: 
	#	<BODY> begin & end tags
	#	<HTML> end tag 
	#		(HTML begin tag and full <HEAD> section
	#		is always generated by this program)
	#
	$page_header .= (-f "$header" ? `cat -s "$header"` : 
		"<BODY TEXT=\"#000000\" BGCOLOR=\"#ffffff\">\n");
	$page_footer .= (-f "$footer" ? `cat -s "$footer"` : 
		"</BODY>\n</HTML>\n");
}

###############################################################################
# HTML FUNCTIONS
###############################################################################

#-----------------------------------------------
# Convert ASCII to URL characters.
#-----------------------------------------------
sub url_encode {
	local($_) = @_;
	#
	s/\=/%3D/g;
	s/\+/%2B/g;
	s/ /+/g;
	$_;
}

#-----------------------------------------------
# Convert URL characters to ASCII.
#-----------------------------------------------
sub url_decode {
	local($_) = @_;
	#
	s/\+/ /g;
	s/%0[Dd]%0[Aa]/%0A/g;
	s/%([a-fA-F0-9][a-fA-F0-9])/pack("c", hex($1))/ge;
	$_;
}

#-----------------------------------------------
# Convert entity characters to Web format.
#-----------------------------------------------
sub html_encode {
	local($_) = @_;
	#
	s/&/&amp;/g;				# special entitites
	s/"/&quot;/g;
	s/</&lt;/g;
	s/>/&gt;/g;
	s/^\n$/<P>/g;				# blank line is paragraph
	s/\n\n/<P>/g;				# blank line is paragraph
	s/\n/<BR>/g;				# end of line is break
	$_;
}

#-----------------------------------------------
# Build multicolumn table row based on arglist.
#-----------------------------------------------
sub html_tbl_row {
	local($str) = "<TR>";
	local($span) = 1;
	foreach (@_) {
		if ($_ eq '-') {
			$span++; next;
		}
		$str .= "<TD VALIGN=TOP ALIGN=LEFT COLSPAN=$span>$_</TD>";
		$span = 1 unless $_ eq '-';		# reset span if spanned
	}
	$str .=	"</TR>\n";
}

#-----------------------------------------------
# Display Majordomo keyword as HTML form field.
#-----------------------------------------------
sub html_mjkey {
	local($list,$key) = @_;
	local($type,$value,$str,$def);
	$type = $config'parse_function{$key};
	$value = $config_opts{$list, $key};
	$type =~ s/^grab_//o;

	# do if HELP ON?
	if ($prefs{ModifyConfHelp}) {
		$str = $config'comments{$key};
		# don't break on CR
		$str =~ s/[\r\n]/ /go;
		$str = &html_encode($str);
		$str = "<FONT SIZE=-1>$str</FONT><P>";
	}

	if ($type eq "bool") {
		$value = (&cf_ck_bool($list,$key) ? 1 : 0);
		$str .= "<INPUT TYPE=\"radio\" NAME=\"cf_$key\""
			." VALUE=\"yes\" ";
		$str .= "CHECKED" if $value;
		$str .= ">yes ";
		$str .= "<INPUT TYPE=\"radio\" NAME=\"cf_$key\""
			." VALUE=\"no\" ";
		$str .= "CHECKED" unless $value;
		$str .= ">no ";
	}
	elsif ($type eq "enum") {
		local(@enum);
		@enum = split(/\001/, $config'known_keys{$key});
		$def = pop(@enum); $value = $def unless $value;
		foreach (@enum) {
			$str .= "<INPUT TYPE=\"radio\""
				." NAME=\"cf_$key\" VALUE=\"$_\" ";
			$str .= "CHECKED" if ($value eq $_);
			$str .= ">$_ ";
		}
	}
	elsif ($type eq "integer") {
		$str .= "<INPUT TYPE=\"text\" NAME=\"cf_$key\"";
		$str .= " onFocus=\"cf_$key.select()\"" 
			if $prefs{GenJavaScript};
		$str .=	" VALUE=\"$value\" SIZE=8 MAXLENGTH=8>";
	}
	elsif ($type eq "word") {
		$str .= "<INPUT TYPE=\"text\" NAME=\"cf_$key\"";
		$str .= " onFocus=\"cf_$key.select()\"" 
			if $prefs{GenJavaScript};
		$str .= " VALUE=\"$value\" SIZE=32 MAXLENGTH=64>";
	}
	elsif ($type eq "string") {
		$str .= "<INPUT TYPE=\"text\" NAME=\"cf_$key\"";
		$str .= " onFocus=\"cf_$key.select()\"" 
			if $prefs{GenJavaScript};
		$str .= " VALUE=\"$value\" SIZE=64 MAXLENGTH=128>";
	}
	elsif ($type eq "absolute_dir") {
		$str .= "<INPUT TYPE=\"text\" NAME=\"cf_$key\"";
		$str .= " onFocus=\"cf_$key.select()\"" 
			if $prefs{GenJavaScript};
		$str .= " VALUE=\"$value\" SIZE=64 MAXLENGTH=128>";
	}
	elsif ($type eq "absolute_file") {
		$str .= "<INPUT TYPE=\"text\" NAME=\"cf_$key\"";
		$str .= " onFocus=\"cf_$key.select()\"" 
			if $prefs{GenJavaScript};
		$str .= " VALUE=\"$value\" SIZE=64 MAXLENGTH=128>";
	}
	elsif ($type eq "restrict_post") {
		$str .= "<INPUT TYPE=\"text\" NAME=\"cf_$key\"";
		$str .= " onFocus=\"cf_$key.select()\"" 
			if $prefs{GenJavaScript};
		$str .= " VALUE=\"$value\" SIZE=64 MAXLENGTH=128>";
	}
	# this snip taken verbatim form Majordomo
	elsif ($type =~ "array") {
		# handle the - escapes. We have to be careful about ordering
		# the rules so that we don't accidently trigger a substitution
		# if there is a - at the beginning of an entry, double it
		# so that the doubled - can be stripped when read in later
		$value =~ s/^-/--/go;			# start with -'ed line
		$value =~ s/\001-/\001--/go;		# embedded line starting with -

		# In standard form, empty lines are lines that have only
		# a '-' on the line.
		$value =~ s/^\001/-\001/go;		# start with blank line
		$value =~ s/\001\001/\001-\001/go;	# embedded blank line

		# if there is space, protect it with a -
		$value =~ s/^(\s)/-$1/g;		# the first line
		$value =~ s/\001(\s)/\001-$1/g;		# embedded lines

		# now that all of the escapes are processed, get it ready
		# to be printed.
		$value =~ s/\001/\n/go;
		$str .= "<TEXTAREA NAME=\"cf_$key\""
			." ROWS=3 COLS=80 WRAP=none>$value</TEXTAREA>";
	}
	else {
		$str = "$type: unknown or unsupported field type";
	}
	return "$str\n";
}

#-----------------------------------------------
# Set MIME content. Build HTML TITLE header.
# Buttonbar if menu-mode, JavaScript if prefs.
#-----------------------------------------------
sub send_header {
	local($inline) = shift;
	$wtitle = join(" ", @_);
	$title = ($inline ? &html_encode($wtitle) : "");
	local($http_cache) = ($prefs{GenCache} ? "" :
		"<META HTTP-EQUIV=\"Pragma\" CONTENT=\"no-cache\">\n");
	local($http_robot) = ($opt_robots ? "" :
		"<META NAME=\"ROBOTS\" CONTENT=\"NOINDEX, NOFOLLOW\">\n");
	local($img) = &imgsrc("WEB_IMGURL/mc_cool_banner.gif", "NAME=banner ".
		"ALT=\"[MajorCool: A Web Interface to Majordomo]\" BORDER=0");
	print "--MajorCool\n" if $InMultiPart;
	print "Content-type: text/html\n\n";
	print "<HTML>\n<HEAD>\n";
	print <<"EOT" if $prefs{GenFontCSS};
	<STYLE type="text/css">
	<!-- Hide Sheet from Ill-Behaved Browsers
	BODY,P,TABLE,TD,PRE,FORM,INPUT,TEXTAREA,SELECT,OPTION { 
		font: $prefs{GenFontSize}pt '$prefs{GenFontFace}',sans-serif; 
	}
	H5	{ font: 110% '$prefs{GenFontFace}',sans-serif;	}
	H4	{ font: 120% '$prefs{GenFontFace}',sans-serif;	}
	H3	{ font: 130% '$prefs{GenFontFace}',sans-serif;	}
	H2	{ font: 140% '$prefs{GenFontFace}',sans-serif;	}
	H1	{ font: 150% '$prefs{GenFontFace}',sans-serif;	}
	B	{ font-weight: bold;				}
	I	{ font-style: italic;				}
	U	{ font-variant: small-caps;			}
	-->
	</STYLE>
EOT
	print <<"EOT" if $prefs{GenJavaScript};
	<SCRIPT LANGUAGE=\"JavaScript\">
	<!-- Hide Script from Ill-Behaved Browsers

	//====================================================================
	// Active Images: dynamic mouseOver/mouseOut graphics.
	//
	//  o	You must explicitly set NAME=XXX in all <IMG> tags in
	//	order to take advantage of this feature.
	//
	//  o	Each image named XXX can have a complement grouping:
	//		XXX_normal -- the static image
	//		XXX_active -- the active image
	//		XXX_clicked-- the clicked image
	//
	//  o	Harmless to non-compliant browsers (eg, Win MSIE 3.x).
	//
	//--------------------------------------------------------------------
	// Bill Houle 				NCR Corporation
	// bhoule\@conveyanced.com		bill.houle\@sandiegoca.ncr.com
	//--------------------------------------------------------------------

	//--------------------------------------------------------------------
	// First, we build the complement grouping by pre-loading all
	// the images (if the browser can handle it)
	//
	if (document.images) {
		banner_normal		= new Image();
		banner_normal.src	= "WEB_IMGURL/mc_cool_banner.gif";
		banner_clicked		= new Image();
		banner_clicked.src	= "WEB_IMGURL/mc_help_banner.gif";
		browse_normal		= new Image();
		browse_normal.src	= "WEB_IMGURL/mc_browse_button.gif";
		browse_active		= new Image();
		browse_active.src	= "WEB_IMGURL/mc_browse_hot.gif";
		modify_normal		= new Image();
		modify_normal.src	= "WEB_IMGURL/mc_modify_button.gif";
		modify_active		= new Image();
		modify_active.src	= "WEB_IMGURL/mc_modify_hot.gif";
		create_normal		= new Image();
		create_normal.src	= "WEB_IMGURL/mc_create_button.gif";
		create_active		= new Image();
		create_active.src	= "WEB_IMGURL/mc_create_hot.gif";
		rename_normal		= new Image();
		rename_normal.src	= "WEB_IMGURL/mc_rename_button.gif";
		rename_active		= new Image();
		rename_active.src	= "WEB_IMGURL/mc_rename_hot.gif";
		delete_normal		= new Image();
		delete_normal.src	= "WEB_IMGURL/mc_delete_button.gif";
		delete_active		= new Image();
		delete_active.src	= "WEB_IMGURL/mc_delete_hot.gif";
		prefs_normal		= new Image();
		prefs_normal.src	= "WEB_IMGURL/mc_prefs_button.gif";
		prefs_active		= new Image();
		prefs_active.src	= "WEB_IMGURL/mc_prefs_hot.gif";
		cool_normal		= new Image();
		cool_normal.src		= "WEB_IMGURL/mc_cool_button.gif";
		cool_active		= new Image();
		cool_active.src		= "WEB_IMGURL/mc_cool_hot.gif";
		help_normal		= new Image();
		help_normal.src		= "WEB_IMGURL/mc_help_button.gif";
		help_active		= new Image();
		help_active.src		= "WEB_IMGURL/mc_help_hot.gif";
		mail_normal		= new Image();
		mail_normal.src		= "WEB_IMGURL/mc_mail_button.gif";
		mail_active		= new Image();
		mail_active.src		= "WEB_IMGURL/mc_mail_hot.gif";
		home_normal		= new Image();
		home_normal.src		= "WEB_IMGURL/mc_home_button.gif";
		home_active		= new Image();
		home_active.src		= "WEB_IMGURL/mc_home_hot.gif";
	}

	//--------------------------------------------------------------------
	// img(imgName,imgEvent)
	//	imgName -- string:	basename of the image group
	//	imgEvent -- integer:	0=normal, 1=active, 2=clicked
	//
	// Usage:
	//	onClick='img(foo,2); return true;'
	// 	onMouseOver='img(foo,1); return true;'
	//	onMouseOut='img(foo,0); return true;'
	//
	//   o	Toggle the named image between event-specific values
	//	(normal, mouseOver, and onClick).  Uses pre-loaded
	//	images from above.
	//
	function img(imgName,imgEvent) {
		if (document.images) {
			if      (imgEvent == 2)	imgVal = 'clicked';
			else if (imgEvent == 1)	imgVal = 'active';
			else if (imgEvent == 0)	imgVal = 'normal';
			else return;
			imgObj = eval(imgName + '_' + imgVal + ".src");
			// image may not exist in the grouping...
			if (document.images[imgName]) 
     	       			document.images[imgName].src = imgObj; 
		}
	}

	//====================================================================

	//--------------------------------------------------------------------
	// msg(msgText)
	//	msgText -- string:	text to display on browser Status line
	//				null string to clear Status
	//
	// Usage:
	// 	onMouseOver='msg("Hit me!"); return true;'
	//	onMouseOut='msg("Maybe next time?"); return true;'
	//	onClick='msg("Ouch!"); return true;'
	//
	//
	function msg(msgText) {
		window.status = msgText;
	}

	//====================================================================

	//--------------------------------------------------------------------
	// Tool Tip/Balloon Help: dynamic mouseOver/mouseOut windowing
	//
	//  o	Shows/hides text in pop-up window.
	//
	//  o	Delayed open to only pop those who pause for help.
	//
	//  o	mouseOut->mouseOver will re-use open window rather than
	//	than close & reopen.
	//
	//  o	Window closes after timeout. Timeout varies with amount
	//	of text displayed. More text, larger timeout.
	//
	//  o	Harmless to non-compliant browsers.
	//
	// Known bugs:
	//
	//   o	No control of window.open() placement.
	//
	//   o	In Win MSIE3.x: if tipWin is closed manually, the object
	//	is still valid but the window is not open. The timeout
	//	of the tip will result in 'scripting error 80010012'.
	//	Luckily, other browsers support onMouseOut, so the problem
	//	is not widespread.
	//	
	//--------------------------------------------------------------------
	// Bill Houle 				NCR Corporation
	// bhoule\@conveyanced.com		bill.houle\@sandiegoca.ncr.com
	//--------------------------------------------------------------------

	// Global settings; tweak as required
	//
	tipTitle = "MajorCool ToolTip";
	tipColor = "#FFFF80";			// yellow background
	tipWidth = 300;
	tipHeight = 100;
	// Timing
	tipOpen = 1500;				// open msecs after mouseover
	tipClose = 500;				// close msecs after mouseout
	tipFactor = 20;				// est: chars read in 1 msec
	// Null defaults
	tipWin = null;
	tipOTime = null; tipOFlag = 0;
	tipCTime = null; tipCFlag = 0;

	//--------------------------------------------------------------------
	// tip(tipLocation, tipText, later)
	//	tipLocation -- handle:	a Window name
	//	tipText -- string:	the message to display in tipLocation
	//				null string to close tipLocation
	//	later -- boolean:	show text immediately or after pause
	//
	// Usage:
	// 	onMouseOver='tip(win,"This is descriptive.",1); return true;'
	//	onMouseOut='tip(win,"",0); return true;'
	//	onClick='tip(win,"",0); return true;'
	//
	//   o	Don't forget the onClick(), or else the tip window may pop
	//	up while you are off doing whatever the click action was.
	//
	function tip(tipLocation, tipText, later) {
		//
		// Clear any pending window opens or closes. Please note:
		//   uninitialized "timeout id" in NN3.x is untestable 'opaque'
		//   object, while "timeout id" in MSIE *must* be tested before
		//   clearTimeout(). Therefore, use global integers to bypass
		//   this Catch-22 situation.
		//
		if (tipOFlag != 0) { clearTimeout(tipOTime); tipOFlag = 0; }
		if (tipCFlag != 0) { clearTimeout(tipCTime); tipCFlag = 0; }
		//
		// Remove the tip from display
		//
		if (tipText == '') {
			if (tipWin == null) return;	// already gone
			//
			// Close window in the future (extra delay is to
			//   avoid popping down & up if there is a subsequent
			//   mouseOver).
			//
			tipCTime = window.setTimeout(
				"if (tipWin != null) {tipWin.close(); tipWin=null;}",
				tipClose);
			tipCFlag = 1;
		}
		//
		// Display the tip
		//
		else {
			//
			// If not yet open and requesting delayed open...
			//
			if (tipWin == null && later > 0) {
				tipOTime = window.setTimeout(
					"tip('"+tipLocation+"','"+tipText+"',0);",
					tipOpen);
				tipOFlag = 1;
				return;
			}
			//
			// MSIE requires explicit options on open(). Also,
			//   contrary to appearances, this double open is not
			//   for the known NN2 open() bug (which ignores the
			//   location URL), but rather for an IE3 problem that
			//   creates a window object without a valid document
			//   object within.
			// The 'dependent' attribute is Netscapism to indicate
			//   a window child relationship.
			//
			tipWin = window.open("",tipLocation,
				"width="+tipWidth+",height="+tipHeight+","+
				"toolbar=0,menubar=0,scrollbars=0,"+
				"location=0,status=0,resizable=0,"+
				"directories=0,dependent=1");
			tipWin = window.open("",tipLocation,
				"width="+tipWidth+",height="+tipHeight+","+
				"toolbar=0,menubar=0,scrollbars=0,"+
				"location=0,status=0,resizable=0,"+
				"directories=0,dependent=1");
			if (tipWin == null) return; 	// whoops!
			// bring window to front
			if (parseInt(navigator.appVersion) > 2) tipWin.focus();
			if (tipWin.document) {
				tipWin.document.open('text/html');
				tipWin.document.write("<HEAD><TITLE>");
				tipWin.document.write(tipTitle);
				tipWin.document.write("</TITLE></HEAD>");
				tipWin.document.write("<BODY BGCOLOR='"+tipColor+"'>");
				tipWin.document.write("<P><FONT SIZE=2>");
				tipWin.document.write(tipText.bold());
				tipWin.document.write("</FONT></BODY>");
				tipWin.document.close();
			}
			else if (tipWin) {
				// open window, but still no document?!?
				tipWin.close();
				return;
			}
			//
			// Estimate how long it takes to read this much text;
			//   blank the tip text after a suitable delay for
			//   reading.
			//
			tipRead = Math.round(tipText.length/tipFactor) * 1000;
			tipOTime = window.setTimeout(
				"tip('"+tipLocation+"','',0);",
				tipRead);
			tipOFlag = 1;
		}
	}

	//====================================================================
	// End of JavaScript -->
	</SCRIPT>
EOT
	print <<"EOT";
	<TITLE>$wtitle</TITLE>
	$http_cache$http_robot
	<META NAME="KEYWORDS" CONTENT="COOL_SITENAME, MajorCool, Majordomo, mailing list">
	<META NAME="DESCRIPTION" CONTENT="MajorCool: a Web interface to the Majordomo mailing list manager run by COOL_SITENAME.">
	</HEAD>
	$page_header
	$tbl_start
	<TR><TD VALIGN=MIDDLE ALIGN=CENTER>
EOT
	print "<A HREF=\"$url?action=about\"";
	print " onMouseOver=\"".
		  " msg('About MajorCool');".
		  ($prefs{GenToolTips} ?
		    " tip('TOP','$tip{about}',1);" : "").
		  " return true\"".
		" onMouseOut=\"".
		  " msg('');".
		  ($prefs{GenToolTips} ? 
		    " tip('TOP','',0);" : "").
		  " return true\"".
		" onClick=\"".
		  " img('banner',2);".
		  " msg('');".
		  ($prefs{GenToolTips} ? 
		    " tip('TOP','',0);" : "").
		  " return true\""
		if $prefs{GenJavaScript};
	print ">$img</A>";
	print "</TD></TR>\n";
	if ($prefs{GenMenuMode}) {
		print "<TR><TD VALIGN=MIDDLE ALIGN=CENTER>";
		# using list rather than (keys(%modules)) so the
		# presentation order is controlled
		foreach ('browse','modify','create','rename','delete','prefs') {
			next unless $modules{$_};
			local($alt) = $_; $alt =~ tr/a-z/A-Z/;
			local($msg,$state);
			$msg = "View Lists and/or Change Subscription Status"
				if ($_ eq 'browse');
			$msg = "Administer An Existing List" if $_ eq 'modify';
			$msg = "Create A New List" if $_ eq 'create';
			$msg = "Rename An Existing List" if $_ eq 'rename';
			$msg = "Delete An Existing List" if $_ eq 'delete';
			if ($_ eq 'prefs') {
				$msg = "Set MajorCool Preferences";
				if ($opt_prefsreturn) {
					$state = &url_encode(&save_state());
					$state = "&state=$state" if $state;
				}
			}
			if ($arg{'module'} eq $_) {
				print &imgsrc(&img($_, 'active'), 
				  "NAME=$_ ALT=[$alt] ALIGN=MIDDLE BORDER=0");
			}
			else {
				print "<A HREF=\"$url?module=$_$state\"";
				print " onMouseOver=\"".
					  " img('$_',1);".
					  " msg('$msg');".
					  ($prefs{GenToolTips} ?
					    " tip('TOP','$tip{$_}',1);" : "").
					  " return true\"".
					" onMouseOut=\"".
					  " img('$_',0);".
					  " msg('');".
					  ($prefs{GenToolTips} ? 
					    " tip('TOP','',0);" : "").
					  " return true\"".
					" onClick=\"".
					  " img('$_',0);".
					  " msg('');".
					  ($prefs{GenToolTips} ? 
					    " tip('TOP','',0);" : "").
					  " return true\""
					if $prefs{GenJavaScript};
				print ">";
				print &imgsrc(&img($_,'button'),
					  "NAME=$_ ALT=[$alt] ".
					  "ALIGN=MIDDLE BORDER=0")."</A>";
			}
		}
		print "</TD></TR>\n";
	}
	print "<TR><TD VALIGN=MIDDLE ALIGN=CENTER>";
	print "<HR ALIGN=CENTER WIDTH=75%>" if $prefs{GenMenuMode};
	print "<H2 ALIGN=CENTER>$title</H2>" if $title;
	print "</TD></TR>\n";
	print "$tbl_end\n";
}

#-----------------------------------------------
# Print HTML footer.
#-----------------------------------------------
sub send_footer {
	print "$page_footer";
}

###############################################################################
# MISC FUNCTIONS
###############################################################################

#-----------------------------------------------
# With optional keyword, Web may be disabled.
#-----------------------------------------------
sub web_lockout {
	local($list,$mode) = @_;
	#
	return (defined($config'known_keys{web_access}) &&
		$config_opts{$list,web_access} !~ /$mode/o);
}

#-----------------------------------------------
# Dump debugging info for isolating problems.
#-----------------------------------------------
sub debug_dump {
	local($list) = @_;
	#
	print "--MajorCool--\n" if $InMultiPart;
	print "Content-type: text/html\n\n";
	print "<H1><BLINK>Debugging a Problem! ";
	print "Please Try Again Later!</BLINK></H1>\n";
	print "<PRE>\n";
	print "<hr>\n";
	print "<h3>\@ARGV</h3>\n";
	while ($ARGV[0]) {
		print "$ARGV[0]\n";
		shift(@ARGV);
	}
	print "<hr>\n";
	print "<h3>&ENV</h3>\n";
	foreach (sort(keys(%ENV))) {
		print "ENV{$_} = $ENV{$_}\n";
	}
	print "<hr>\n";
	print "<h3>%Prefs</h3>\n";
	foreach (sort(keys(%prefs))) {
		print "prefs{$_} = $prefs{$_}\n";
	}
	print "<hr>\n";
	print "<h3>%Arg</h3>\n";
	print"DEBUG: $debug_str\n" if $debug_str;
	foreach (sort(keys(%arg))) {
		print"$_ = $arg{$_}\n";
	}
	print "<hr>\n";
	if ( $list ) {
		&load_cache();
		print "Descr  :\t$cached_descr{$list}\n";
		print "Owner  :\t$cached_owner{$list}\n";
		print "SPolicy:\t$cached_spolicy{$list}\n";
		print "UPolicy:\t$cached_upolicy{$list}\n";
		print "E-Mail :\t$user_status{$list}\n";
		print "<hr>\n";
	}
	print "</PRE>\n";
}

#-----------------------------------------------
# Automatically calculate GIF/JPEG image size.
# (Builds <IMG SRC=...> HTML.)
#-----------------------------------------------
sub imgsrc {
	# $url is assumed to be local & unqualified!
	local($url,@rest) = @_; $rest = join(' ', @rest);
	local($file) = $url; $file =~ s|WEB_IMGURL|WEB_IMGDIR|o;
	local($size);
	if (open(IMAGE, "<$file")) {
		if ($file =~ /.gif/io) {
			local($type,$s);
			read(IMAGE,$type,6);
			if (($type =~ /GIF8[79]/o) && (read(IMAGE,$s,4) == 4)) {
				local($a,$b,$c,$d) = unpack("C4",$s);
				$size = join("", 
				  ' WIDTH=', $b<<8|$a,
				  ' HEIGHT=', $d<<8|$c);
			}
		}
		elsif ($file =~ /.jpg/io || $file =~ /.jpeg/io) {
			local($c1,$c2,$ch,$s,$length,$junk);
			# Originally by Andrew Tong, werdna@ugcs.caltech.edu
			read(IMAGE, $c1, 1); read(IMAGE, $c2, 1);
			# valid JPEG?
			break if !((ord($c1) == 0xFF) && (ord($c2) == 0xD8));
			while (ord($ch) != 0xDA) {
				# Find next marker (markers begin with 0xFF)
				while (ord($ch) != 0xFF) {
					read(IMAGE, $ch, 1); 
				}
				# markers can be padded with unlimited 0xFF's
				while (ord($ch) == 0xFF) {
					read(IMAGE, $ch, 1); 
				}
				# Now, $ch contains the value of the marker
				if ((ord($ch) >= 0xC0) && (ord($ch) <= 0xC3)) {
					read(IMAGE, $junk, 3);
					read(IMAGE, $s, 4);
					local($a,$b,$c,$d) = unpack("C4",$s);
					$size = join("", 
					  ' HEIGHT=', $a<<8|$b,
					  ' WIDTH=', $c<<8|$d);
				}
				else {
					# skip variables, since FF's within
					# variable names are NOT valid markers
					read(IMAGE, $s, 2);
					($c1, $c2) = unpack("C2",$s);
					$length = $c1<<8|$c2;
					# bad marker length?
					break if $length < 2;
					read(IMAGE, $junk, $length-2);
				}
			}
		}
		elsif ($file =~ /.xbm/io) {
			$_ = <IMAGE>; $_ .= <IMAGE>;
			$size = join("",
			  ' WIDTH=', $1,
			  ' HEIGHT=', $2) if
				/#define\s*\S*\s*(\d*)\s*\n#define\s*\S*\s*(\d*)\s*\n/i;
		}
		close(IMAGE);
	}
	return "<IMG SRC=\"$url\" $size $rest>";
}

#-----------------------------------------------
# Display image in one of 4 modes:
#   button (default display)
#   hot (eg, MouseOver button)
#   active (eg, selected button)
#   banner
#-----------------------------------------------
sub img {
	local($type) = shift(@_);
	local($mode) = shift(@_);
	eval "\$img = 'WEB_IMGURL/mc_${type}_${mode}.gif'";
	return $img;
}

#-----------------------------------------------
# Save current state of $arg list. Returned
#   value is suitable for use in a 'GET' URL.
#-----------------------------------------------
sub save_state {
	# don't save if we just came from a Majordomo submit
	return if $arg{'action'} =~ /^do_/o;
	local($state);
	foreach (keys(%arg)) {
		next if $_ eq "state";
		next if /^pref_|^cf_/;
		$state .= "$_=$arg{$_}!!";
	}
	chop($state); chop($state);
	return $state;
}

#-----------------------------------------------
# Read message from approval queue and extract
#   certain header info.
#-----------------------------------------------
sub queue_parse {
	local($file) = shift;
	#
	&lopen(Q, "<", "$file") || return ($!, undef);
	local($from,$subj,$date,$size);
	$size = -s Q;
	while (<Q>) {
		last if /^$/;
		$from = $1 if (/^from: (.*)/i);
		$subj = $1 if (/^subject: (.*)/i);
		$date = $1 if (/^date: (.*)/i);
	}
	&lclose(Q);
	return ($from,$subj,$date,$size);
}

#-----------------------------------------------
# Send mail using configured Majordomo mailer.
#   This is identical to the sendmail function,
#   but was needed in cases where we did not
#   want the '--' prepended in the message.
#-----------------------------------------------
sub resendmail { #''
	local($MAIL) = shift;
	local($to) = shift;
	local($subject) = shift;
	local($from) = $Majordomo'mail_from;
	local($sender) = $Majordomo'mail_sender;
	# The following eval expands embedded variables like $sender
	local($mail_cmd) = eval qq/"$Majordomo'mail_prog"/;
	local($isParent);
	if ($#_ >= $[) { $from = shift; }
	if ($#_ >= $[) { $sender = shift; }

	# force unqualified filehandles into caller's package
	local($package) = caller;
	$MAIL =~ s/^[^':]+$/$package'$&/;

	# clean up the addresses, for use on the mailer command line
	local(@to) = &main'ParseAddrs($to);
	for (@to) {
		$_ = join(", ", &main'ParseAddrs($_));
	}
	$to = join(", ", @to);  #';

	print STDERR "$0: resendmail:  To $to, Subject $subject, From $from\n" 
		if $DEBUG;
	print STDERR "$0: resendmail:  Sender $sender, mail_cmd = $mail_cmd\n"
		if $DEBUG;

	# open the process
	if (defined($isParent = open($MAIL, "|-"))) {
 		&main'do_exec_sendmail(split(' ', $mail_cmd))
			unless ($isParent);
	} else {
		&main'abort("Failed to fork prior to mailer exec");
	}

	# Generate the header.  Note the line beginning with "-"; this keeps
	# this message from being reprocessed by Majordomo if some misbegotten
	# mailer out there bounces it back.
	print $MAIL <<"EOT";
To: $to
From: $from
Subject: $subject
Reply-To: $from

EOT
	return;
}

