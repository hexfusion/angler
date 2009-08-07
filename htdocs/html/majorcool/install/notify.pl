##!PERLBIN
#

# $1: Majordomo.cf file
# $2: Majordomo $homedir
# $3: MajorCool 'personality' name (eg, "default")

$domocf = shift(@ARGV);
$homedir = shift(@ARGV);
$instance = shift(@ARGV);
$shell_file = "$instance.sh";
$config_file = "$homedir/majorcool_$instance.cf";
$registration_file = "$homedir/.majorcool_$instance";

exit if -e $registration_file;
chop($system = (`uname -a` || `arch` || "unknown\n"));
push(@INC, $homedir);

# load the configs
#
require "$domocf" || die "couldn't open $domocf, $!";
require "majordomo.pl";
require "majordomo_version.pl";
require "$config_file" || die "couldn't open $config_file, $!";

print <<"ZOT";

Purely out of curiosity, the author would like to know who is using
this software. Would you like to let $author 
know that you have completed a successful installation by sending
the cf file? This is only for "census" purposes, to get an idea of
where and how the program is being used. There will be no unnecessary
snooping, follow-up emails, or other solicitations; and you will not
be advertised as a proud user in any documentation or propaganda 
unless you specifically request it.

So...do you want to proclaim your victory?
ZOT

# just a 'touch'
open(RF,">$registration_file") || die "couldn't create $registration_file, $!";
close RF;

# ask
print "[yes] ";
if ( <STDIN> =~ /n/i) {
	print <<"ZOT";

Bummer...Oh well...If you ever change your mind, just delete the
$homedir/$registration_file file and Configure will
notice the next time it is run. Thanks for trying MajorCool...

ZOT
	exit;
}

# prepare the mail
$sendmail_command = "/usr/lib/sendmail" 
	unless defined $sendmail_command;
$bounce_mailer = "$sendmail_command -f\$sender -t" 
	unless defined $bounce_mailer;
&set_abort_addr($whoami_owner);
&set_mail_from($whoami_owner); $x = $whoami; # Keeps -w happy
&set_mail_sender($whoami_owner);
&set_mailer($bounce_mailer);

# do it
&sendmail(NOTIFY, "$register", "MajorCool $version Success!");
print NOTIFY <<"ZOT";
MajorCool $version was just successfully installed for 
Majordomo $majordomo_version at $whoami on
System "$system".

Here is how this particular installation was implemented:

ZOT

# open & send the file
#
open(SH,"<$shell_file");
while (<SH>) { print NOTIFY; } 
close SH;
close NOTIFY;

print <<"ZOT";

Thanks for your trust. And thanks for trying MajorCool...

PS: Drop $author a separate email if you ever
feel comfortable enough to have your site listed in the MajorCool Web 
documents as an exemplary user. Greater visibility will encourage more
people to try the product....

ZOT

