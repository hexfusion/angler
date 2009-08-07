#  Configuration file for MajorCool
#  These should only be modified through the use of the Configure script
#  For yes/no values, 0=no, 1=yes

# SYSTEM DETAILS
# How to start a perl script
	PERLBIN="/usr/bin/perl"
# The cpset command for installations
	CPSET="install/cpset"

# MAJORDOMO DETAILS
# Home directory
	DOMO_DIR="/home/virtual/site12/fst/usr/lib/majordomo"
# Configuration file location
	DOMO_CONF="/home/virtual/site12/fst/etc/majordomo.cf"
# Version number
	DOMO_VERSION="1.94.5"

# WEB SERVER DETAILS
# Server scheme (http, shttp, https)
	WEB_SCHEME="http"
# Server root directory
	WEB_ROOT="/home/virtual/site12/fst/var/www/html"
# CGI directory
	WEB_CGIDIR="/home/virtual/site12/fst/var/www/cgi-bin"
# CGI URL
	WEB_CGIURL="/cgi-bin"
# Images directory
	WEB_IMGDIR="/home/virtual/site12/fst/var/www/html/images"
# Images URL
	WEB_IMGURL="/images"
# Root for served documents
	WEB_DOCROOT="/home/virtual/site12/fst/var/www/html"
# Header & footer for common site look
	WEB_HEADER=""
	WEB_FOOTER=""

# MAJORCOOL CONFIG
# The name of this site
	COOL_SITENAME="West Branch Angler"
# The URL of this site's home page
	COOL_SITEHOME="www.westbranchangler.com"
# TMP directory
	COOL_TMPDIR="/home/virtual/site12/fst/tmp"
# Location of key cache file
	COOL_CACHE="/home/virtual/site12/fst/usr/lib/majordomo/.majordomo_keys"
# CGI program name
	COOL_NAME="majordomo2"
# UNIX user/group-id owners
	COOL_USERID="admin12"
	COOL_GROUPID="admin12"
# Help file for MajorCool
	COOL_HELPFILE="mc_default_help.htm"
# The address lookup function to use
	COOL_SITEADDR="simple"
# The programs to invoke on CREATE/RENAME/DELETE
	COOL_CREATECMD=""
	COOL_RENAMECMD=""
	COOL_DELETECMD=""

# MAJORCOOL OPTIONS
# Module access
	OPT_ALLOWBROWSE="1"
	OPT_ALLOWMODIFY="1"
	OPT_ALLOWCREATE="1"
	OPT_ALLOWRENAME="1"
	OPT_ALLOWDELETE="1"
# Hide list of lists from admins?
	OPT_HIDDENLISTS="0"
# Admin passwd not in regular config?
	OPT_HIDDENPASSWD="0"
# Members see noadvertised lists?
	OPT_NOADVERTISED="0"
# Trust the address that users enter?
	OPT_TRUSTIDENT="0"
# Use paranoia mode? Sends commands in e-mail...
	OPT_CMDVERIFY="0"
# Send sub/unsub commands in e-mail for user confirmation?
	OPT_SUBVERIFY="0"
# Allow admins to request emailed config?
	OPT_SENDCF="1"
# Save comments in list config file?
	OPT_COMMENTEDCF="1"
# Use MIME screen update feature if supported?
	OPT_MULTIPART="1"
# Return to prior screen after Prefs session?
	OPT_PREFSRETURN="0"
# Allow Internet spiders to index this CGI?
	OPT_ROBOTS="1"
# Update interval during list membership scan.
	OPT_SCANSTEPS="50"

# SITEADDR OPTIONS (may be empty)
