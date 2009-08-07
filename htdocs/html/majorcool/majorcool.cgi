#!PERLBIN
#

# MajorCool is designed to run under Majordomo wrapper control,
# but we need access to some of the httpd/CGI environment
# variables. The sole purpose of this CGI shim is to pass the
# needed variables on the wrapper command line.
# 
# This shim should be installed (or symlinked) in the server's
# cgi-bin. majorcool.pl is the actual Web interface that is invoked
# (which does all the work).

# Both of these are likely preset by wrapper, but needed here also.
#   $HOME is used in the processing of the Majordomo config file.
#   $MAJORDOMO_CF is to extract $homedir for the wrapper location
$ENV{HOME}		= "DOMO_DIR";
$ENV{MAJORDOMO_CF}	= "DOMO_CONF";

# CGI env-vars needed to pass
@cgi_envs = ( 
	'REMOTE_ADDR',
	'REMOTE_HOST',
	'SERVER_NAME',
	'SERVER_PORT',
	'SCRIPT_NAME',
	'REQUEST_METHOD',
	'QUERY_STRING',
	'CONTENT_LENGTH',
	'HTTP_COOKIE',
	'HTTP_USER_AGENT',
	'HTTP_REFERER',
	'MAJORDOMO_CF',	# pass this just in case
);

# Read and execute the Majordomo .cf file
$cf = $ENV{MAJORDOMO_CF}; 
die("$cf not readable; stopped") unless -r $cf;
die("inclusion of $cf failed $@") unless require "$cf"; 

# check to see if the cf file is valid
die("listdir not defined. Is majordomo.cf being included correctly?")
	unless defined($listdir);

# Build the environ string
#   'wrapper' does not honor arg separators, so we need to hide
#   any embedded whitespace and shell chars with URL-style encoding....
foreach (@cgi_envs) {
	local($value) = $ENV{$_};
	next unless $value;
	$value =~ s/\"/%22/g;		# quote to hex val
	$value =~ s/\'/%27/g;		# apos to hex val
	$value =~ s/\+/%2B/g;		# plus to hex val
	$value =~ s/\;/%3B/g;		# semi to hex val
	$value =~ s/\</%3C/g;		# lt to hex val
	$value =~ s/\>/%3E/g;		# gt to hex val
	$value =~ s/ /+/g;		# ' ' to plus encoding
	$env .= " -env $_=$value";
}

#DEBUG
#print "Content-type: text/plain\n\n";
#print "$homedir/wrapper majorcool.pl majorcool_COOL_CF.cf $env";
#system("$homedir/wrapper majorcool.pl majorcool_COOL_CF.cf $env");

#OLD STYLE
# This works, but involves /bin/sh for arg processing.
#exec("$homedir/wrapper majorcool.pl majorcool_COOL_CF.cf $env");

#NEW STYLE
# No /bin/sh processing, and thus potentially safer.
@args = split(' ', "majorcool.pl majorcool_COOL_CF.cf $env");
exec("$homedir/wrapper", @args);

