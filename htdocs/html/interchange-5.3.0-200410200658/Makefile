# This Makefile is for the Interchange extension to perl.
#
# It was generated automatically by MakeMaker version
# 5.45 (Revision: 1.222) from the contents of
# Makefile.PL. Don't edit this file, edit Makefile.PL instead.
#
#	ANY CHANGES MADE HERE WILL BE LOST!
#
#   MakeMaker ARGV: ()
#
#   MakeMaker Parameters:

#	CONFIGURE => CODE(...)
#	DISTNAME => q[interchange]
#	EXE_FILES => []
#	NAME => q[Interchange]
#	VERSION_FROM => q[scripts/interchange.PL]
#	clean => { FILES=>q[lib/IniConf.pm _uid _db_storable _session_storable lib/File/CounterFile.pm scripts/initp.pl scripts/compile_link scripts/config_prog scripts/configdump scripts/dump scripts/expire scripts/localize scripts/expireall scripts/makecat scripts/ic_mod_perl scripts/interchange scripts/offline scripts/restart scripts/update] }
#	dist => { DIST_DEFAULT=>q[all tardist], COMPRESS=>q[gzip -9f], ZIP_FLAGS=>q[-pr9], CI=>q[ci -l -t-Initial], SUFFIX=>q[.gz] }

# --- MakeMaker post_initialize section:


# --- MakeMaker const_config section:

# These definitions are from config.sh (via /usr/lib/perl5/5.6.1/i386-linux/Config.pm)

# They may have been overridden via Makefile.PL or on the command line
AR = ar
CC = gcc
CCCDLFLAGS = -fPIC
CCDLFLAGS = -rdynamic
DLEXT = so
DLSRC = dl_dlopen.xs
LD = gcc
LDDLFLAGS = -shared -L/usr/local/lib
LDFLAGS =  -L/usr/local/lib
LIBC = /lib/libc-2.2.5.so
LIB_EXT = .a
OBJ_EXT = .o
OSNAME = linux
OSVERS = 2.4.17-0.13smp
RANLIB = :
SO = so
EXE_EXT = 
FULL_AR = /usr/bin/ar


# --- MakeMaker constants section:
AR_STATIC_ARGS = cr
NAME = Interchange
DISTNAME = interchange
NAME_SYM = Interchange
VERSION = 5.3.0-200410200658
VERSION_SYM = 5_3_0_200410200658
XS_VERSION = 5.3.0-200410200658
INST_BIN = blib/bin
INST_EXE = blib/script
INST_LIB = blib/lib
INST_ARCHLIB = blib/arch
INST_SCRIPT = blib/script
PREFIX = /usr
SITEPREFIX = $(PREFIX)
VENDORPREFIX = $(PREFIX)
INSTALLDIRS = perl
INSTALLPRIVLIB = /usr/local/interchange/lib
INSTALLARCHLIB = /usr/local/interchange
INSTALLSITELIB = $(SITEPREFIX)/lib/perl5/site_perl/5.6.1
INSTALLSITEARCH = $(SITEPREFIX)/lib/perl5/site_perl/5.6.1/i386-linux
INSTALLVENDORLIB = $(VENDORPREFIX)/lib/perl5/vendor_perl/5.6.1
INSTALLVENDORARCH = $(VENDORPREFIX)/lib/perl5/vendor_perl/5.6.1/i386-linux
INSTALLBIN = $(PREFIX)/local/interchange/bin
INSTALLSCRIPT = $(PREFIX)/local/interchange/bin
INSTALLSITEBIN = $(SITEPREFIX)/bin
PERL_LIB = /usr/lib/perl5/5.6.1
PERL_ARCHLIB = /usr/lib/perl5/5.6.1/i386-linux
SITELIBEXP = /usr/lib/perl5/site_perl/5.6.1
SITEARCHEXP = /usr/lib/perl5/site_perl/5.6.1/i386-linux
LIBPERL_A = libperl.a
FIRST_MAKEFILE = Makefile
MAKE_APERL_FILE = Makefile.aperl
PERLMAINCC = $(CC)
PERL_INC = /usr/lib/perl5/5.6.1/i386-linux/CORE
PERL = /usr/bin/perl
FULLPERL = /usr/bin/perl
FULL_AR = /usr/bin/ar

VERSION_MACRO = VERSION
DEFINE_VERSION = -D$(VERSION_MACRO)=\"$(VERSION)\"
XS_VERSION_MACRO = XS_VERSION
XS_DEFINE_VERSION = -D$(XS_VERSION_MACRO)=\"$(XS_VERSION)\"
PERL_MALLOC_DEF = -DPERL_EXTMALLOC_DEF -Dmalloc=Perl_malloc -Dfree=Perl_mfree -Drealloc=Perl_realloc -Dcalloc=Perl_calloc

MAKEMAKER = /usr/lib/perl5/5.6.1/ExtUtils/MakeMaker.pm
MM_VERSION = 5.45

# FULLEXT = Pathname for extension directory (eg Foo/Bar/Oracle).
# BASEEXT = Basename part of FULLEXT. May be just equal FULLEXT. (eg Oracle)
# ROOTEXT = Directory part of FULLEXT with leading slash (eg /DBD)  !!! Deprecated from MM 5.32  !!!
# PARENT_NAME = NAME without BASEEXT and no trailing :: (eg Foo::Bar)
# DLBASE  = Basename part of dynamic library. May be just equal BASEEXT.
FULLEXT = Interchange
BASEEXT = Interchange
DLBASE = $(BASEEXT)
VERSION_FROM = scripts/interchange.PL
OBJECT = 
LDFROM = $(OBJECT)
LINKTYPE = dynamic

# Handy lists of source code files:
XS_FILES= 
C_FILES = 
O_FILES = 
H_FILES = 
HTMLLIBPODS    = 
HTMLSCRIPTPODS = 
MAN1PODS = scripts/compile_link \
	scripts/config_prog \
	scripts/configdump \
	scripts/dump \
	scripts/expire \
	scripts/expireall \
	scripts/findtags \
	scripts/ic_mod_perl \
	scripts/interchange \
	scripts/localize \
	scripts/makecat \
	scripts/offline \
	scripts/restart \
	scripts/update
MAN3PODS = lib/Business/Fedex.pm \
	lib/IniConf.pm \
	lib/Vend/Accounting/SQL_Ledger.pm \
	lib/Vend/CounterFile.pm \
	lib/Vend/Form.pm \
	lib/Vend/Interpolate.pm \
	lib/Vend/Options/Matrix.pm \
	lib/Vend/Options/Old48.pm \
	lib/Vend/Options/Simple.pm \
	lib/Vend/Parser.pm \
	lib/Vend/Payment/AuthorizeNet.pm \
	lib/Vend/Payment/BoA.pm \
	lib/Vend/Payment/CyberCash.pm \
	lib/Vend/Payment/ECHO.pm \
	lib/Vend/Payment/EFSNet.pm \
	lib/Vend/Payment/Linkpoint.pm \
	lib/Vend/Payment/MCVE.pm \
	lib/Vend/Payment/PRI.pm \
	lib/Vend/Payment/PSiGate.pm \
	lib/Vend/Payment/Signio.pm \
	lib/Vend/Payment/Skipjack.pm \
	lib/Vend/Payment/TCLink.pm \
	lib/Vend/Payment/TestPayment.pm \
	lib/Vend/Payment/WellsFargo.pm \
	lib/Vend/Payment/iTransact.pm \
	lib/Vend/SOAP/Transport.pm \
	lib/Vend/SQL_Parser.pm \
	lib/Vend/Ship.pm \
	lib/Vend/Ship/Postal.pm \
	lib/Vend/Ship/QueryUPS.pm \
	lib/Vend/Table/Editor.pm \
	lib/Vend/UserControl.pm \
	lib/Vend/UserDB.pm
HTMLEXT = html
INST_MAN1DIR = blib/man1
INSTALLMAN1DIR = /usr/local/interchange/man
INSTALLSITEMAN1DIR = $(SITEPREFIX)/share/man/man1
INSTALLVENDORMAN1DIR = $(VENDORPREFIX)/share/man/man1
MAN1EXT = 1
INST_MAN3DIR = blib/man3
INSTALLMAN3DIR = /usr/local/interchange/man
INSTALLSITEMAN3DIR = $(SITEPREFIX)/share/man/man3
INSTALLVENDORMAN3DIR = $(VENDORPREFIX)/share/man/man3
MAN3EXT = 3pm
PERM_RW = 644
PERM_RWX = 755

# work around a famous dec-osf make(1) feature(?):
makemakerdflt: all

.SUFFIXES: .xs .c .C .cpp .cxx .cc $(OBJ_EXT)

# Nick wanted to get rid of .PRECIOUS. I don't remember why. I seem to recall, that
# some make implementations will delete the Makefile when we rebuild it. Because
# we call false(1) when we rebuild it. So make(1) is not completely wrong when it
# does so. Our milage may vary.
# .PRECIOUS: Makefile    # seems to be not necessary anymore

.PHONY: all config static dynamic test linkext manifest

# Where is the Config information that we are using/depend on
CONFIGDEP = $(PERL_ARCHLIB)/Config.pm $(PERL_INC)/config.h

# Where to put things:
INST_LIBDIR      = $(INST_LIB)
INST_ARCHLIBDIR  = $(INST_ARCHLIB)

INST_AUTODIR     = $(INST_LIB)/auto/$(FULLEXT)
INST_ARCHAUTODIR = $(INST_ARCHLIB)/auto/$(FULLEXT)

INST_STATIC  =
INST_DYNAMIC =
INST_BOOT    =

EXPORT_LIST = 

PERL_ARCHIVE = 

PERL_ARCHIVE_AFTER = 

TO_INST_PM = hints.pl \
	install_lsb.pl \
	lib/Business/Fedex.pm \
	lib/IniConf.pm \
	lib/Vend/Accounting.pm \
	lib/Vend/Accounting/SQL_Ledger.pm \
	lib/Vend/Cart.pm \
	lib/Vend/Config.pm \
	lib/Vend/Control.pm \
	lib/Vend/CounterFile.pm \
	lib/Vend/Data.pm \
	lib/Vend/DbSearch.pm \
	lib/Vend/Dispatch.pm \
	lib/Vend/Document.pm \
	lib/Vend/Error.pm \
	lib/Vend/External.pm \
	lib/Vend/File.pm \
	lib/Vend/Form.pm \
	lib/Vend/Glimpse.pm \
	lib/Vend/Imagemap.pm \
	lib/Vend/Interpolate.pm \
	lib/Vend/MakeCat.pm \
	lib/Vend/Menu.pm \
	lib/Vend/ModPerl.pm \
	lib/Vend/Options.pm \
	lib/Vend/Options/Matrix.pm \
	lib/Vend/Options/Old48.pm \
	lib/Vend/Options/Simple.pm \
	lib/Vend/Order.pm \
	lib/Vend/Page.pm \
	lib/Vend/Parse.pm \
	lib/Vend/Parser.pm \
	lib/Vend/Payment.pm \
	lib/Vend/Payment/AuthorizeNet.pm \
	lib/Vend/Payment/BoA.pm \
	lib/Vend/Payment/CyberCash.pm \
	lib/Vend/Payment/ECHO.pm \
	lib/Vend/Payment/EFSNet.pm \
	lib/Vend/Payment/Linkpoint.pm \
	lib/Vend/Payment/MCVE.pm \
	lib/Vend/Payment/PRI.pm \
	lib/Vend/Payment/PSiGate.pm \
	lib/Vend/Payment/Signio.pm \
	lib/Vend/Payment/Skipjack.pm \
	lib/Vend/Payment/TCLink.pm \
	lib/Vend/Payment/TestPayment.pm \
	lib/Vend/Payment/WellsFargo.pm \
	lib/Vend/Payment/iTransact.pm \
	lib/Vend/RefSearch.pm \
	lib/Vend/SOAP.pm \
	lib/Vend/SOAP/Transport.pm \
	lib/Vend/SQL_Parser.pm \
	lib/Vend/Scan.pm \
	lib/Vend/Search.pm \
	lib/Vend/Server.pm \
	lib/Vend/Session.pm \
	lib/Vend/SessionDB.pm \
	lib/Vend/SessionFile.pm \
	lib/Vend/Ship.pm \
	lib/Vend/Ship/Postal.pm \
	lib/Vend/Ship/QueryUPS.pm \
	lib/Vend/Swish.pm \
	lib/Vend/Table/Common.pm \
	lib/Vend/Table/DBI.pm \
	lib/Vend/Table/DBI_CompositeKey.pm \
	lib/Vend/Table/DB_File.pm \
	lib/Vend/Table/Editor.pm \
	lib/Vend/Table/GDBM.pm \
	lib/Vend/Table/InMemory.pm \
	lib/Vend/Table/LDAP.pm \
	lib/Vend/Table/SDBM.pm \
	lib/Vend/Table/Shadow.pm \
	lib/Vend/Tags.pm \
	lib/Vend/TextSearch.pm \
	lib/Vend/Track.pm \
	lib/Vend/UserControl.pm \
	lib/Vend/UserDB.pm \
	lib/Vend/Util.pm \
	relocate.pl

PM_TO_BLIB = lib/Vend/Table/Editor.pm \
	$(INST_LIB)/Vend/Table/Editor.pm \
	lib/Vend/Payment/TestPayment.pm \
	$(INST_LIB)/Vend/Payment/TestPayment.pm \
	lib/Vend/Payment.pm \
	$(INST_LIB)/Vend/Payment.pm \
	lib/Vend/Payment/iTransact.pm \
	$(INST_LIB)/Vend/Payment/iTransact.pm \
	lib/Vend/Menu.pm \
	$(INST_LIB)/Vend/Menu.pm \
	relocate.pl \
	$(INST_LIBDIR)/relocate.pl \
	lib/Vend/Table/Common.pm \
	$(INST_LIB)/Vend/Table/Common.pm \
	lib/Vend/RefSearch.pm \
	$(INST_LIB)/Vend/RefSearch.pm \
	lib/Vend/Payment/AuthorizeNet.pm \
	$(INST_LIB)/Vend/Payment/AuthorizeNet.pm \
	lib/Vend/Payment/PRI.pm \
	$(INST_LIB)/Vend/Payment/PRI.pm \
	lib/Vend/Tags.pm \
	$(INST_LIB)/Vend/Tags.pm \
	hints.pl \
	$(INST_LIBDIR)/hints.pl \
	lib/Vend/Server.pm \
	$(INST_LIB)/Vend/Server.pm \
	lib/Vend/Table/GDBM.pm \
	$(INST_LIB)/Vend/Table/GDBM.pm \
	lib/Vend/SQL_Parser.pm \
	$(INST_LIB)/Vend/SQL_Parser.pm \
	lib/Vend/Table/DBI.pm \
	$(INST_LIB)/Vend/Table/DBI.pm \
	lib/Vend/Parse.pm \
	$(INST_LIB)/Vend/Parse.pm \
	lib/Vend/Options/Simple.pm \
	$(INST_LIB)/Vend/Options/Simple.pm \
	lib/Vend/Payment/PSiGate.pm \
	$(INST_LIB)/Vend/Payment/PSiGate.pm \
	lib/Vend/Accounting/SQL_Ledger.pm \
	$(INST_LIB)/Vend/Accounting/SQL_Ledger.pm \
	lib/Vend/Imagemap.pm \
	$(INST_LIB)/Vend/Imagemap.pm \
	lib/Vend/Session.pm \
	$(INST_LIB)/Vend/Session.pm \
	lib/Vend/Payment/CyberCash.pm \
	$(INST_LIB)/Vend/Payment/CyberCash.pm \
	lib/Vend/Payment/WellsFargo.pm \
	$(INST_LIB)/Vend/Payment/WellsFargo.pm \
	lib/Vend/Parser.pm \
	$(INST_LIB)/Vend/Parser.pm \
	lib/Vend/Glimpse.pm \
	$(INST_LIB)/Vend/Glimpse.pm \
	lib/Vend/Payment/BoA.pm \
	$(INST_LIB)/Vend/Payment/BoA.pm \
	lib/Vend/Search.pm \
	$(INST_LIB)/Vend/Search.pm \
	lib/Vend/External.pm \
	$(INST_LIB)/Vend/External.pm \
	lib/Vend/SOAP.pm \
	$(INST_LIB)/Vend/SOAP.pm \
	install_lsb.pl \
	$(INST_LIBDIR)/install_lsb.pl \
	lib/Vend/Cart.pm \
	$(INST_LIB)/Vend/Cart.pm \
	lib/Vend/TextSearch.pm \
	$(INST_LIB)/Vend/TextSearch.pm \
	lib/Vend/SessionFile.pm \
	$(INST_LIB)/Vend/SessionFile.pm \
	lib/Vend/Error.pm \
	$(INST_LIB)/Vend/Error.pm \
	lib/Vend/Page.pm \
	$(INST_LIB)/Vend/Page.pm \
	lib/Vend/UserControl.pm \
	$(INST_LIB)/Vend/UserControl.pm \
	lib/Vend/Table/InMemory.pm \
	$(INST_LIB)/Vend/Table/InMemory.pm \
	lib/Vend/File.pm \
	$(INST_LIB)/Vend/File.pm \
	lib/Vend/Options/Matrix.pm \
	$(INST_LIB)/Vend/Options/Matrix.pm \
	lib/Vend/Options/Old48.pm \
	$(INST_LIB)/Vend/Options/Old48.pm \
	lib/Vend/Scan.pm \
	$(INST_LIB)/Vend/Scan.pm \
	lib/Vend/Swish.pm \
	$(INST_LIB)/Vend/Swish.pm \
	lib/IniConf.pm \
	$(INST_LIB)/IniConf.pm \
	lib/Vend/Order.pm \
	$(INST_LIB)/Vend/Order.pm \
	lib/Vend/Payment/EFSNet.pm \
	$(INST_LIB)/Vend/Payment/EFSNet.pm \
	lib/Vend/Payment/Skipjack.pm \
	$(INST_LIB)/Vend/Payment/Skipjack.pm \
	lib/Vend/Accounting.pm \
	$(INST_LIB)/Vend/Accounting.pm \
	lib/Vend/MakeCat.pm \
	$(INST_LIB)/Vend/MakeCat.pm \
	lib/Vend/SessionDB.pm \
	$(INST_LIB)/Vend/SessionDB.pm \
	lib/Vend/Options.pm \
	$(INST_LIB)/Vend/Options.pm \
	lib/Vend/Payment/MCVE.pm \
	$(INST_LIB)/Vend/Payment/MCVE.pm \
	lib/Vend/Track.pm \
	$(INST_LIB)/Vend/Track.pm \
	lib/Vend/UserDB.pm \
	$(INST_LIB)/Vend/UserDB.pm \
	lib/Vend/Ship/QueryUPS.pm \
	$(INST_LIB)/Vend/Ship/QueryUPS.pm \
	lib/Vend/Table/Shadow.pm \
	$(INST_LIB)/Vend/Table/Shadow.pm \
	lib/Vend/Data.pm \
	$(INST_LIB)/Vend/Data.pm \
	lib/Vend/Table/LDAP.pm \
	$(INST_LIB)/Vend/Table/LDAP.pm \
	lib/Vend/Interpolate.pm \
	$(INST_LIB)/Vend/Interpolate.pm \
	lib/Vend/ModPerl.pm \
	$(INST_LIB)/Vend/ModPerl.pm \
	lib/Vend/Form.pm \
	$(INST_LIB)/Vend/Form.pm \
	lib/Vend/Table/DB_File.pm \
	$(INST_LIB)/Vend/Table/DB_File.pm \
	lib/Vend/Dispatch.pm \
	$(INST_LIB)/Vend/Dispatch.pm \
	lib/Vend/Table/SDBM.pm \
	$(INST_LIB)/Vend/Table/SDBM.pm \
	lib/Vend/Control.pm \
	$(INST_LIB)/Vend/Control.pm \
	lib/Vend/CounterFile.pm \
	$(INST_LIB)/Vend/CounterFile.pm \
	lib/Vend/Payment/Signio.pm \
	$(INST_LIB)/Vend/Payment/Signio.pm \
	lib/Vend/SOAP/Transport.pm \
	$(INST_LIB)/Vend/SOAP/Transport.pm \
	lib/Business/Fedex.pm \
	$(INST_LIB)/Business/Fedex.pm \
	lib/Vend/Payment/TCLink.pm \
	$(INST_LIB)/Vend/Payment/TCLink.pm \
	lib/Vend/Ship/Postal.pm \
	$(INST_LIB)/Vend/Ship/Postal.pm \
	lib/Vend/Ship.pm \
	$(INST_LIB)/Vend/Ship.pm \
	lib/Vend/Table/DBI_CompositeKey.pm \
	$(INST_LIB)/Vend/Table/DBI_CompositeKey.pm \
	lib/Vend/DbSearch.pm \
	$(INST_LIB)/Vend/DbSearch.pm \
	lib/Vend/Util.pm \
	$(INST_LIB)/Vend/Util.pm \
	lib/Vend/Document.pm \
	$(INST_LIB)/Vend/Document.pm \
	lib/Vend/Payment/Linkpoint.pm \
	$(INST_LIB)/Vend/Payment/Linkpoint.pm \
	lib/Vend/Payment/ECHO.pm \
	$(INST_LIB)/Vend/Payment/ECHO.pm \
	lib/Vend/Config.pm \
	$(INST_LIB)/Vend/Config.pm


# --- MakeMaker tool_autosplit section:

# Usage: $(AUTOSPLITFILE) FileToSplit AutoDirToSplitInto
AUTOSPLITFILE = $(PERL) "-I$(PERL_ARCHLIB)" "-I$(PERL_LIB)" -e 'use AutoSplit;autosplit($$ARGV[0], $$ARGV[1], 0, 1, 1) ;'


# --- MakeMaker tool_xsubpp section:


# --- MakeMaker tools_other section:

SHELL = /bin/sh
CHMOD = chmod
CP = cp
LD = gcc
MV = mv
NOOP = $(SHELL) -c true
RM_F = rm -f
RM_RF = rm -rf
TEST_F = test -f
TOUCH = touch
UMASK_NULL = umask 0
DEV_NULL = > /dev/null 2>&1

# The following is a portable way to say mkdir -p
# To see which directories are created, change the if 0 to if 1
MKPATH = $(PERL) -I$(PERL_ARCHLIB) -I$(PERL_LIB) -MExtUtils::Command -e mkpath

# This helps us to minimize the effect of the .exists files A yet
# better solution would be to have a stable file in the perl
# distribution with a timestamp of zero. But this solution doesn't
# need any changes to the core distribution and works with older perls
EQUALIZE_TIMESTAMP = $(PERL) -I$(PERL_ARCHLIB) -I$(PERL_LIB) -MExtUtils::Command -e eqtime

# Here we warn users that an old packlist file was found somewhere,
# and that they should call some uninstall routine
WARN_IF_OLD_PACKLIST = $(PERL) -we 'exit unless -f $$ARGV[0];' \
-e 'print "WARNING: I have found an old package in\n";' \
-e 'print "\t$$ARGV[0].\n";' \
-e 'print "Please make sure the two installations are not conflicting\n";'

UNINST=0
VERBINST=0

MOD_INSTALL = $(PERL) -I$(INST_LIB) -I$(PERL_LIB) -MExtUtils::Install \
-e "install({@ARGV},'$(VERBINST)',0,'$(UNINST)');"

DOC_INSTALL = $(PERL) -e '$$\="\n\n";' \
-e 'print "=head2 ", scalar(localtime), ": C<", shift, ">", " L<", $$arg=shift, "|", $$arg, ">";' \
-e 'print "=over 4";' \
-e 'while (defined($$key = shift) and defined($$val = shift)){print "=item *";print "C<$$key: $$val>";}' \
-e 'print "=back";'

UNINSTALL =   $(PERL) -MExtUtils::Install \
-e 'uninstall($$ARGV[0],1,1); print "\nUninstall is deprecated. Please check the";' \
-e 'print " packlist above carefully.\n  There may be errors. Remove the";' \
-e 'print " appropriate files manually.\n  Sorry for the inconveniences.\n"'


# --- MakeMaker dist section:

DISTVNAME = $(DISTNAME)-$(VERSION)
TAR  = tar
TARFLAGS = cvf
ZIP  = zip
ZIPFLAGS = -r
COMPRESS = gzip -9f
SUFFIX = .gz
SHAR = shar
PREOP = @$(NOOP)
POSTOP = @$(NOOP)
TO_UNIX = @$(NOOP)
CI = ci -l -t-Initial
RCS_LABEL = rcs -Nv$(VERSION_SYM): -q
DIST_CP = best
DIST_DEFAULT = all tardist


# --- MakeMaker macro section:


# --- MakeMaker depend section:


# --- MakeMaker cflags section:


# --- MakeMaker const_loadlibs section:


# --- MakeMaker const_cccmd section:


# --- MakeMaker post_constants section:


# --- MakeMaker pasthru section:

PASTHRU = LIB="$(LIB)"\
	LIBPERL_A="$(LIBPERL_A)"\
	LINKTYPE="$(LINKTYPE)"\
	PREFIX="$(PREFIX)"\
	OPTIMIZE="$(OPTIMIZE)"


# --- MakeMaker c_o section:


# --- MakeMaker xs_c section:


# --- MakeMaker xs_o section:


# --- MakeMaker top_targets section:

#all ::	config $(INST_PM) subdirs linkext manifypods

all :: pure_all htmlifypods manifypods
	@$(NOOP)

pure_all :: config pm_to_blib subdirs linkext
	@$(NOOP)

subdirs :: $(MYEXTLIB)
	@$(NOOP)

config :: Makefile $(INST_LIBDIR)/.exists
	@$(NOOP)

config :: $(INST_ARCHAUTODIR)/.exists
	@$(NOOP)

config :: $(INST_AUTODIR)/.exists
	@$(NOOP)

$(INST_AUTODIR)/.exists :: /usr/lib/perl5/5.6.1/i386-linux/CORE/perl.h
	@$(MKPATH) $(INST_AUTODIR)
	@$(EQUALIZE_TIMESTAMP) /usr/lib/perl5/5.6.1/i386-linux/CORE/perl.h $(INST_AUTODIR)/.exists

	-@$(CHMOD) $(PERM_RWX) $(INST_AUTODIR)

$(INST_LIBDIR)/.exists :: /usr/lib/perl5/5.6.1/i386-linux/CORE/perl.h
	@$(MKPATH) $(INST_LIBDIR)
	@$(EQUALIZE_TIMESTAMP) /usr/lib/perl5/5.6.1/i386-linux/CORE/perl.h $(INST_LIBDIR)/.exists

	-@$(CHMOD) $(PERM_RWX) $(INST_LIBDIR)

$(INST_ARCHAUTODIR)/.exists :: /usr/lib/perl5/5.6.1/i386-linux/CORE/perl.h
	@$(MKPATH) $(INST_ARCHAUTODIR)
	@$(EQUALIZE_TIMESTAMP) /usr/lib/perl5/5.6.1/i386-linux/CORE/perl.h $(INST_ARCHAUTODIR)/.exists

	-@$(CHMOD) $(PERM_RWX) $(INST_ARCHAUTODIR)

config :: $(INST_MAN1DIR)/.exists
	@$(NOOP)


$(INST_MAN1DIR)/.exists :: /usr/lib/perl5/5.6.1/i386-linux/CORE/perl.h
	@$(MKPATH) $(INST_MAN1DIR)
	@$(EQUALIZE_TIMESTAMP) /usr/lib/perl5/5.6.1/i386-linux/CORE/perl.h $(INST_MAN1DIR)/.exists

	-@$(CHMOD) $(PERM_RWX) $(INST_MAN1DIR)

config :: $(INST_MAN3DIR)/.exists
	@$(NOOP)


$(INST_MAN3DIR)/.exists :: /usr/lib/perl5/5.6.1/i386-linux/CORE/perl.h
	@$(MKPATH) $(INST_MAN3DIR)
	@$(EQUALIZE_TIMESTAMP) /usr/lib/perl5/5.6.1/i386-linux/CORE/perl.h $(INST_MAN3DIR)/.exists

	-@$(CHMOD) $(PERM_RWX) $(INST_MAN3DIR)

help:
	perldoc ExtUtils::MakeMaker

Version_check:
	@$(PERL) -I$(PERL_ARCHLIB) -I$(PERL_LIB) \
		-MExtUtils::MakeMaker=Version_check \
		-e "Version_check('$(MM_VERSION)')"


# --- MakeMaker linkext section:

linkext :: $(LINKTYPE)
	@$(NOOP)


# --- MakeMaker dlsyms section:


# --- MakeMaker dynamic section:

## $(INST_PM) has been moved to the all: target.
## It remains here for awhile to allow for old usage: "make dynamic"
#dynamic :: Makefile $(INST_DYNAMIC) $(INST_BOOT) $(INST_PM)
dynamic :: Makefile $(INST_DYNAMIC) $(INST_BOOT)
	@$(NOOP)


# --- MakeMaker dynamic_bs section:

BOOTSTRAP =


# --- MakeMaker dynamic_lib section:


# --- MakeMaker static section:

## $(INST_PM) has been moved to the all: target.
## It remains here for awhile to allow for old usage: "make static"
#static :: Makefile $(INST_STATIC) $(INST_PM)
static :: Makefile $(INST_STATIC)
	@$(NOOP)


# --- MakeMaker static_lib section:


# --- MakeMaker htmlifypods section:

htmlifypods : pure_all
	@$(NOOP)


# --- MakeMaker manifypods section:
POD2MAN_EXE = /usr/bin/pod2man
POD2MAN = $(PERL) -we '%m=@ARGV;for (keys %m){' \
-e 'next if -e $$m{$$_} && -M $$m{$$_} < -M $$_ && -M $$m{$$_} < -M "Makefile";' \
-e 'print "Manifying $$m{$$_}\n";' \
-e 'system(qq[$$^X ].q["-I$(PERL_ARCHLIB)" "-I$(PERL_LIB)" $(POD2MAN_EXE) ].qq[$$_>$$m{$$_}])==0 or warn "Couldn\047t install $$m{$$_}\n";' \
-e 'chmod(oct($(PERM_RW))), $$m{$$_} or warn "chmod $(PERM_RW) $$m{$$_}: $$!\n";}'

manifypods : pure_all scripts/makecat \
	scripts/dump \
	scripts/interchange \
	scripts/expireall \
	scripts/config_prog \
	scripts/localize \
	scripts/configdump \
	scripts/expire \
	scripts/update \
	scripts/ic_mod_perl \
	scripts/compile_link \
	scripts/findtags \
	scripts/restart \
	scripts/offline \
	lib/Vend/Table/Editor.pm \
	lib/Vend/Payment/TestPayment.pm \
	lib/Vend/Options/Matrix.pm \
	lib/Vend/Payment/iTransact.pm \
	lib/Vend/Options/Old48.pm \
	lib/IniConf.pm \
	lib/Vend/Payment/Skipjack.pm \
	lib/Vend/Payment/EFSNet.pm \
	lib/Vend/Payment/AuthorizeNet.pm \
	lib/Vend/Payment/PRI.pm \
	lib/Vend/Payment/MCVE.pm \
	lib/Vend/SQL_Parser.pm \
	lib/Vend/Ship/QueryUPS.pm \
	lib/Vend/UserDB.pm \
	lib/Vend/Options/Simple.pm \
	lib/Vend/Payment/PSiGate.pm \
	lib/Vend/Interpolate.pm \
	lib/Vend/Accounting/SQL_Ledger.pm \
	lib/Vend/Payment/CyberCash.pm \
	lib/Vend/Form.pm \
	lib/Vend/Payment/WellsFargo.pm \
	lib/Vend/Parser.pm \
	lib/Vend/CounterFile.pm \
	lib/Vend/Payment/Signio.pm \
	lib/Vend/Payment/BoA.pm \
	lib/Vend/SOAP/Transport.pm \
	lib/Business/Fedex.pm \
	lib/Vend/Payment/TCLink.pm \
	lib/Vend/Ship/Postal.pm \
	lib/Vend/Ship.pm \
	lib/Vend/Payment/Linkpoint.pm \
	lib/Vend/Payment/ECHO.pm \
	lib/Vend/UserControl.pm
	@$(POD2MAN) \
	scripts/makecat \
	$(INST_MAN1DIR)/makecat.$(MAN1EXT) \
	scripts/dump \
	$(INST_MAN1DIR)/dump.$(MAN1EXT) \
	scripts/interchange \
	$(INST_MAN1DIR)/interchange.$(MAN1EXT) \
	scripts/expireall \
	$(INST_MAN1DIR)/expireall.$(MAN1EXT) \
	scripts/config_prog \
	$(INST_MAN1DIR)/config_prog.$(MAN1EXT) \
	scripts/localize \
	$(INST_MAN1DIR)/localize.$(MAN1EXT) \
	scripts/configdump \
	$(INST_MAN1DIR)/configdump.$(MAN1EXT) \
	scripts/expire \
	$(INST_MAN1DIR)/expire.$(MAN1EXT) \
	scripts/update \
	$(INST_MAN1DIR)/update.$(MAN1EXT) \
	scripts/ic_mod_perl \
	$(INST_MAN1DIR)/ic_mod_perl.$(MAN1EXT) \
	scripts/compile_link \
	$(INST_MAN1DIR)/compile_link.$(MAN1EXT) \
	scripts/findtags \
	$(INST_MAN1DIR)/findtags.$(MAN1EXT) \
	scripts/restart \
	$(INST_MAN1DIR)/restart.$(MAN1EXT) \
	scripts/offline \
	$(INST_MAN1DIR)/offline.$(MAN1EXT) \
	lib/Vend/Table/Editor.pm \
	$(INST_MAN3DIR)/Vend::Table::Editor.$(MAN3EXT) \
	lib/Vend/Payment/TestPayment.pm \
	$(INST_MAN3DIR)/Vend::Payment::TestPayment.$(MAN3EXT) \
	lib/Vend/Options/Matrix.pm \
	$(INST_MAN3DIR)/Vend::Options::Matrix.$(MAN3EXT) \
	lib/Vend/Payment/iTransact.pm \
	$(INST_MAN3DIR)/Vend::Payment::iTransact.$(MAN3EXT) \
	lib/Vend/Options/Old48.pm \
	$(INST_MAN3DIR)/Vend::Options::Old48.$(MAN3EXT) \
	lib/IniConf.pm \
	$(INST_MAN3DIR)/IniConf.$(MAN3EXT) \
	lib/Vend/Payment/Skipjack.pm \
	$(INST_MAN3DIR)/Vend::Payment::Skipjack.$(MAN3EXT) \
	lib/Vend/Payment/EFSNet.pm \
	$(INST_MAN3DIR)/Vend::Payment::EFSNet.$(MAN3EXT) \
	lib/Vend/Payment/AuthorizeNet.pm \
	$(INST_MAN3DIR)/Vend::Payment::AuthorizeNet.$(MAN3EXT) \
	lib/Vend/Payment/PRI.pm \
	$(INST_MAN3DIR)/Vend::Payment::PRI.$(MAN3EXT) \
	lib/Vend/Payment/MCVE.pm \
	$(INST_MAN3DIR)/Vend::Payment::MCVE.$(MAN3EXT) \
	lib/Vend/SQL_Parser.pm \
	$(INST_MAN3DIR)/Vend::SQL_Parser.$(MAN3EXT) \
	lib/Vend/Ship/QueryUPS.pm \
	$(INST_MAN3DIR)/Vend::Ship::QueryUPS.$(MAN3EXT) \
	lib/Vend/UserDB.pm \
	$(INST_MAN3DIR)/Vend::UserDB.$(MAN3EXT) \
	lib/Vend/Options/Simple.pm \
	$(INST_MAN3DIR)/Vend::Options::Simple.$(MAN3EXT) \
	lib/Vend/Payment/PSiGate.pm \
	$(INST_MAN3DIR)/Vend::Payment::PSiGate.$(MAN3EXT) \
	lib/Vend/Interpolate.pm \
	$(INST_MAN3DIR)/Vend::Interpolate.$(MAN3EXT) \
	lib/Vend/Accounting/SQL_Ledger.pm \
	$(INST_MAN3DIR)/Vend::Accounting::SQL_Ledger.$(MAN3EXT) \
	lib/Vend/Payment/CyberCash.pm \
	$(INST_MAN3DIR)/Vend::Payment::CyberCash.$(MAN3EXT) \
	lib/Vend/Form.pm \
	$(INST_MAN3DIR)/Vend::Form.$(MAN3EXT) \
	lib/Vend/Payment/WellsFargo.pm \
	$(INST_MAN3DIR)/Vend::Payment::WellsFargo.$(MAN3EXT) \
	lib/Vend/Parser.pm \
	$(INST_MAN3DIR)/Vend::Parser.$(MAN3EXT) \
	lib/Vend/CounterFile.pm \
	$(INST_MAN3DIR)/Vend::CounterFile.$(MAN3EXT) \
	lib/Vend/Payment/Signio.pm \
	$(INST_MAN3DIR)/Vend::Payment::Signio.$(MAN3EXT) \
	lib/Vend/Payment/BoA.pm \
	$(INST_MAN3DIR)/Vend::Payment::BoA.$(MAN3EXT) \
	lib/Vend/SOAP/Transport.pm \
	$(INST_MAN3DIR)/Vend::SOAP::Transport.$(MAN3EXT) \
	lib/Business/Fedex.pm \
	$(INST_MAN3DIR)/Business::Fedex.$(MAN3EXT) \
	lib/Vend/Payment/TCLink.pm \
	$(INST_MAN3DIR)/Vend::Payment::TCLink.$(MAN3EXT) \
	lib/Vend/Ship/Postal.pm \
	$(INST_MAN3DIR)/Vend::Ship::Postal.$(MAN3EXT) \
	lib/Vend/Ship.pm \
	$(INST_MAN3DIR)/Vend::Ship.$(MAN3EXT) \
	lib/Vend/Payment/Linkpoint.pm \
	$(INST_MAN3DIR)/Vend::Payment::Linkpoint.$(MAN3EXT) \
	lib/Vend/Payment/ECHO.pm \
	$(INST_MAN3DIR)/Vend::Payment::ECHO.$(MAN3EXT) \
	lib/Vend/UserControl.pm \
	$(INST_MAN3DIR)/Vend::UserControl.$(MAN3EXT)

# --- MakeMaker processPL section:

all :: scripts/compile_link
	@$(NOOP)

scripts/compile_link :: relocate.pl
	$(PERL) -I$(INST_ARCHLIB) -I$(INST_LIB) -I$(PERL_ARCHLIB) -I$(PERL_LIB) relocate.pl scripts/compile_link

all :: scripts/config_prog
	@$(NOOP)

scripts/config_prog :: relocate.pl
	$(PERL) -I$(INST_ARCHLIB) -I$(INST_LIB) -I$(PERL_ARCHLIB) -I$(PERL_LIB) relocate.pl scripts/config_prog

all :: scripts/configdump
	@$(NOOP)

scripts/configdump :: relocate.pl
	$(PERL) -I$(INST_ARCHLIB) -I$(INST_LIB) -I$(PERL_ARCHLIB) -I$(PERL_LIB) relocate.pl scripts/configdump

all :: scripts/dump
	@$(NOOP)

scripts/dump :: relocate.pl
	$(PERL) -I$(INST_ARCHLIB) -I$(INST_LIB) -I$(PERL_ARCHLIB) -I$(PERL_LIB) relocate.pl scripts/dump

all :: scripts/expire
	@$(NOOP)

scripts/expire :: relocate.pl
	$(PERL) -I$(INST_ARCHLIB) -I$(INST_LIB) -I$(PERL_ARCHLIB) -I$(PERL_LIB) relocate.pl scripts/expire

all :: scripts/expireall
	@$(NOOP)

scripts/expireall :: relocate.pl
	$(PERL) -I$(INST_ARCHLIB) -I$(INST_LIB) -I$(PERL_ARCHLIB) -I$(PERL_LIB) relocate.pl scripts/expireall

all :: scripts/findtags
	@$(NOOP)

scripts/findtags :: relocate.pl
	$(PERL) -I$(INST_ARCHLIB) -I$(INST_LIB) -I$(PERL_ARCHLIB) -I$(PERL_LIB) relocate.pl scripts/findtags

all :: scripts/ic_mod_perl
	@$(NOOP)

scripts/ic_mod_perl :: relocate.pl
	$(PERL) -I$(INST_ARCHLIB) -I$(INST_LIB) -I$(PERL_ARCHLIB) -I$(PERL_LIB) relocate.pl scripts/ic_mod_perl

all :: scripts/interchange
	@$(NOOP)

scripts/interchange :: relocate.pl
	$(PERL) -I$(INST_ARCHLIB) -I$(INST_LIB) -I$(PERL_ARCHLIB) -I$(PERL_LIB) relocate.pl scripts/interchange

all :: scripts/localize
	@$(NOOP)

scripts/localize :: relocate.pl
	$(PERL) -I$(INST_ARCHLIB) -I$(INST_LIB) -I$(PERL_ARCHLIB) -I$(PERL_LIB) relocate.pl scripts/localize

all :: scripts/makecat
	@$(NOOP)

scripts/makecat :: relocate.pl
	$(PERL) -I$(INST_ARCHLIB) -I$(INST_LIB) -I$(PERL_ARCHLIB) -I$(PERL_LIB) relocate.pl scripts/makecat

all :: scripts/offline
	@$(NOOP)

scripts/offline :: relocate.pl
	$(PERL) -I$(INST_ARCHLIB) -I$(INST_LIB) -I$(PERL_ARCHLIB) -I$(PERL_LIB) relocate.pl scripts/offline

all :: scripts/restart
	@$(NOOP)

scripts/restart :: relocate.pl
	$(PERL) -I$(INST_ARCHLIB) -I$(INST_LIB) -I$(PERL_ARCHLIB) -I$(PERL_LIB) relocate.pl scripts/restart

all :: scripts/update
	@$(NOOP)

scripts/update :: relocate.pl
	$(PERL) -I$(INST_ARCHLIB) -I$(INST_LIB) -I$(PERL_ARCHLIB) -I$(PERL_LIB) relocate.pl scripts/update


# --- MakeMaker installbin section:

$(INST_SCRIPT)/.exists :: /usr/lib/perl5/5.6.1/i386-linux/CORE/perl.h
	@$(MKPATH) $(INST_SCRIPT)
	@$(EQUALIZE_TIMESTAMP) /usr/lib/perl5/5.6.1/i386-linux/CORE/perl.h $(INST_SCRIPT)/.exists

	-@$(CHMOD) $(PERM_RWX) $(INST_SCRIPT)

EXE_FILES = scripts/compile_link scripts/config_prog scripts/configdump scripts/dump scripts/expire scripts/expireall scripts/findtags scripts/ic_mod_perl scripts/interchange scripts/localize scripts/makecat scripts/offline scripts/restart scripts/update

FIXIN = $(PERL) -I$(PERL_ARCHLIB) -I$(PERL_LIB) -MExtUtils::MakeMaker \
    -e "MY->fixin(shift)"

pure_all :: $(INST_SCRIPT)/makecat $(INST_SCRIPT)/dump $(INST_SCRIPT)/interchange $(INST_SCRIPT)/expireall $(INST_SCRIPT)/config_prog $(INST_SCRIPT)/localize $(INST_SCRIPT)/configdump $(INST_SCRIPT)/expire $(INST_SCRIPT)/update $(INST_SCRIPT)/ic_mod_perl $(INST_SCRIPT)/compile_link $(INST_SCRIPT)/findtags $(INST_SCRIPT)/restart $(INST_SCRIPT)/offline
	@$(NOOP)

realclean ::
	rm -f $(INST_SCRIPT)/makecat $(INST_SCRIPT)/dump $(INST_SCRIPT)/interchange $(INST_SCRIPT)/expireall $(INST_SCRIPT)/config_prog $(INST_SCRIPT)/localize $(INST_SCRIPT)/configdump $(INST_SCRIPT)/expire $(INST_SCRIPT)/update $(INST_SCRIPT)/ic_mod_perl $(INST_SCRIPT)/compile_link $(INST_SCRIPT)/findtags $(INST_SCRIPT)/restart $(INST_SCRIPT)/offline

$(INST_SCRIPT)/makecat: scripts/makecat Makefile $(INST_SCRIPT)/.exists
	@rm -f $(INST_SCRIPT)/makecat
	cp scripts/makecat $(INST_SCRIPT)/makecat
	$(FIXIN) $(INST_SCRIPT)/makecat
	-@$(CHMOD) $(PERM_RWX) $(INST_SCRIPT)/makecat

$(INST_SCRIPT)/dump: scripts/dump Makefile $(INST_SCRIPT)/.exists
	@rm -f $(INST_SCRIPT)/dump
	cp scripts/dump $(INST_SCRIPT)/dump
	$(FIXIN) $(INST_SCRIPT)/dump
	-@$(CHMOD) $(PERM_RWX) $(INST_SCRIPT)/dump

$(INST_SCRIPT)/interchange: scripts/interchange Makefile $(INST_SCRIPT)/.exists
	@rm -f $(INST_SCRIPT)/interchange
	cp scripts/interchange $(INST_SCRIPT)/interchange
	$(FIXIN) $(INST_SCRIPT)/interchange
	-@$(CHMOD) $(PERM_RWX) $(INST_SCRIPT)/interchange

$(INST_SCRIPT)/expireall: scripts/expireall Makefile $(INST_SCRIPT)/.exists
	@rm -f $(INST_SCRIPT)/expireall
	cp scripts/expireall $(INST_SCRIPT)/expireall
	$(FIXIN) $(INST_SCRIPT)/expireall
	-@$(CHMOD) $(PERM_RWX) $(INST_SCRIPT)/expireall

$(INST_SCRIPT)/config_prog: scripts/config_prog Makefile $(INST_SCRIPT)/.exists
	@rm -f $(INST_SCRIPT)/config_prog
	cp scripts/config_prog $(INST_SCRIPT)/config_prog
	$(FIXIN) $(INST_SCRIPT)/config_prog
	-@$(CHMOD) $(PERM_RWX) $(INST_SCRIPT)/config_prog

$(INST_SCRIPT)/localize: scripts/localize Makefile $(INST_SCRIPT)/.exists
	@rm -f $(INST_SCRIPT)/localize
	cp scripts/localize $(INST_SCRIPT)/localize
	$(FIXIN) $(INST_SCRIPT)/localize
	-@$(CHMOD) $(PERM_RWX) $(INST_SCRIPT)/localize

$(INST_SCRIPT)/configdump: scripts/configdump Makefile $(INST_SCRIPT)/.exists
	@rm -f $(INST_SCRIPT)/configdump
	cp scripts/configdump $(INST_SCRIPT)/configdump
	$(FIXIN) $(INST_SCRIPT)/configdump
	-@$(CHMOD) $(PERM_RWX) $(INST_SCRIPT)/configdump

$(INST_SCRIPT)/expire: scripts/expire Makefile $(INST_SCRIPT)/.exists
	@rm -f $(INST_SCRIPT)/expire
	cp scripts/expire $(INST_SCRIPT)/expire
	$(FIXIN) $(INST_SCRIPT)/expire
	-@$(CHMOD) $(PERM_RWX) $(INST_SCRIPT)/expire

$(INST_SCRIPT)/update: scripts/update Makefile $(INST_SCRIPT)/.exists
	@rm -f $(INST_SCRIPT)/update
	cp scripts/update $(INST_SCRIPT)/update
	$(FIXIN) $(INST_SCRIPT)/update
	-@$(CHMOD) $(PERM_RWX) $(INST_SCRIPT)/update

$(INST_SCRIPT)/ic_mod_perl: scripts/ic_mod_perl Makefile $(INST_SCRIPT)/.exists
	@rm -f $(INST_SCRIPT)/ic_mod_perl
	cp scripts/ic_mod_perl $(INST_SCRIPT)/ic_mod_perl
	$(FIXIN) $(INST_SCRIPT)/ic_mod_perl
	-@$(CHMOD) $(PERM_RWX) $(INST_SCRIPT)/ic_mod_perl

$(INST_SCRIPT)/compile_link: scripts/compile_link Makefile $(INST_SCRIPT)/.exists
	@rm -f $(INST_SCRIPT)/compile_link
	cp scripts/compile_link $(INST_SCRIPT)/compile_link
	$(FIXIN) $(INST_SCRIPT)/compile_link
	-@$(CHMOD) $(PERM_RWX) $(INST_SCRIPT)/compile_link

$(INST_SCRIPT)/findtags: scripts/findtags Makefile $(INST_SCRIPT)/.exists
	@rm -f $(INST_SCRIPT)/findtags
	cp scripts/findtags $(INST_SCRIPT)/findtags
	$(FIXIN) $(INST_SCRIPT)/findtags
	-@$(CHMOD) $(PERM_RWX) $(INST_SCRIPT)/findtags

$(INST_SCRIPT)/restart: scripts/restart Makefile $(INST_SCRIPT)/.exists
	@rm -f $(INST_SCRIPT)/restart
	cp scripts/restart $(INST_SCRIPT)/restart
	$(FIXIN) $(INST_SCRIPT)/restart
	-@$(CHMOD) $(PERM_RWX) $(INST_SCRIPT)/restart

$(INST_SCRIPT)/offline: scripts/offline Makefile $(INST_SCRIPT)/.exists
	@rm -f $(INST_SCRIPT)/offline
	cp scripts/offline $(INST_SCRIPT)/offline
	$(FIXIN) $(INST_SCRIPT)/offline
	-@$(CHMOD) $(PERM_RWX) $(INST_SCRIPT)/offline


# --- MakeMaker subdirs section:

# none

# --- MakeMaker clean section:

# Delete temporary files but do not touch installed files. We don't delete
# the Makefile here so a later make realclean still has a makefile to use.

clean ::
	-rm -rf lib/IniConf.pm _uid _db_storable _session_storable lib/File/CounterFile.pm scripts/initp.pl scripts/compile_link scripts/config_prog scripts/configdump scripts/dump scripts/expire scripts/localize scripts/expireall scripts/makecat scripts/ic_mod_perl scripts/interchange scripts/offline scripts/restart scripts/update ./blib $(MAKE_APERL_FILE) $(INST_ARCHAUTODIR)/extralibs.all perlmain.c mon.out core core.*perl.*.? *perl.core so_locations pm_to_blib *$(OBJ_EXT) *$(LIB_EXT) perl.exe $(BOOTSTRAP) $(BASEEXT).bso $(BASEEXT).def $(BASEEXT).exp
	-mv Makefile Makefile.old $(DEV_NULL)


# --- MakeMaker realclean section:

# Delete temporary files (via clean) and also delete installed files
realclean purge ::  clean
	rm -rf $(INST_AUTODIR) $(INST_ARCHAUTODIR)
	rm -f  $(INST_LIB)/Vend/Table/Editor.pm $(INST_LIB)/Vend/Payment/TestPayment.pm
	rm -f $(INST_LIB)/Vend/Payment.pm $(INST_LIB)/Vend/Payment/iTransact.pm
	rm -f $(INST_LIB)/Vend/Menu.pm $(INST_LIBDIR)/relocate.pl
	rm -f $(INST_LIB)/Vend/Table/Common.pm $(INST_LIB)/Vend/RefSearch.pm
	rm -f $(INST_LIB)/Vend/Payment/AuthorizeNet.pm $(INST_LIB)/Vend/Payment/PRI.pm
	rm -f $(INST_LIB)/Vend/Tags.pm $(INST_LIBDIR)/hints.pl $(INST_LIB)/Vend/Server.pm
	rm -f $(INST_LIB)/Vend/Table/GDBM.pm $(INST_LIB)/Vend/SQL_Parser.pm
	rm -f $(INST_LIB)/Vend/Table/DBI.pm $(INST_LIB)/Vend/Parse.pm
	rm -f $(INST_LIB)/Vend/Options/Simple.pm $(INST_LIB)/Vend/Payment/PSiGate.pm
	rm -f $(INST_LIB)/Vend/Accounting/SQL_Ledger.pm $(INST_LIB)/Vend/Imagemap.pm
	rm -f $(INST_LIB)/Vend/Session.pm $(INST_LIB)/Vend/Payment/CyberCash.pm
	rm -f $(INST_LIB)/Vend/Payment/WellsFargo.pm $(INST_LIB)/Vend/Parser.pm
	rm -f $(INST_LIB)/Vend/Glimpse.pm $(INST_LIB)/Vend/Payment/BoA.pm
	rm -f $(INST_LIB)/Vend/Search.pm $(INST_LIB)/Vend/External.pm $(INST_LIB)/Vend/SOAP.pm
	rm -f $(INST_LIBDIR)/install_lsb.pl $(INST_LIB)/Vend/Cart.pm
	rm -f $(INST_LIB)/Vend/TextSearch.pm $(INST_LIB)/Vend/SessionFile.pm
	rm -f $(INST_LIB)/Vend/Error.pm $(INST_LIB)/Vend/Page.pm
	rm -f $(INST_LIB)/Vend/UserControl.pm $(INST_LIB)/Vend/Table/InMemory.pm
	rm -f $(INST_LIB)/Vend/File.pm $(INST_LIB)/Vend/Options/Matrix.pm
	rm -f $(INST_LIB)/Vend/Options/Old48.pm $(INST_LIB)/Vend/Scan.pm
	rm -f $(INST_LIB)/Vend/Swish.pm $(INST_LIB)/IniConf.pm $(INST_LIB)/Vend/Order.pm
	rm -f $(INST_LIB)/Vend/Payment/EFSNet.pm $(INST_LIB)/Vend/Payment/Skipjack.pm
	rm -f $(INST_LIB)/Vend/Accounting.pm $(INST_LIB)/Vend/MakeCat.pm
	rm -f $(INST_LIB)/Vend/SessionDB.pm $(INST_LIB)/Vend/Options.pm
	rm -f $(INST_LIB)/Vend/Payment/MCVE.pm $(INST_LIB)/Vend/Track.pm
	rm -f $(INST_LIB)/Vend/UserDB.pm $(INST_LIB)/Vend/Ship/QueryUPS.pm
	rm -f $(INST_LIB)/Vend/Table/Shadow.pm $(INST_LIB)/Vend/Data.pm
	rm -f $(INST_LIB)/Vend/Table/LDAP.pm $(INST_LIB)/Vend/Interpolate.pm
	rm -f $(INST_LIB)/Vend/ModPerl.pm $(INST_LIB)/Vend/Form.pm
	rm -f $(INST_LIB)/Vend/Table/DB_File.pm $(INST_LIB)/Vend/Dispatch.pm
	rm -f $(INST_LIB)/Vend/Table/SDBM.pm $(INST_LIB)/Vend/Control.pm
	rm -f $(INST_LIB)/Vend/CounterFile.pm $(INST_LIB)/Vend/Payment/Signio.pm
	rm -f $(INST_LIB)/Vend/SOAP/Transport.pm $(INST_LIB)/Business/Fedex.pm
	rm -f $(INST_LIB)/Vend/Payment/TCLink.pm $(INST_LIB)/Vend/Ship/Postal.pm
	rm -f $(INST_LIB)/Vend/Ship.pm $(INST_LIB)/Vend/Table/DBI_CompositeKey.pm
	rm -f $(INST_LIB)/Vend/DbSearch.pm $(INST_LIB)/Vend/Util.pm
	rm -f $(INST_LIB)/Vend/Document.pm $(INST_LIB)/Vend/Payment/Linkpoint.pm
	rm -f $(INST_LIB)/Vend/Payment/ECHO.pm $(INST_LIB)/Vend/Config.pm
	rm -rf Makefile Makefile.old


# --- MakeMaker dist_basics section:

distclean :: realclean distcheck

distcheck :
	$(PERL) -I$(PERL_ARCHLIB) -I$(PERL_LIB) -MExtUtils::Manifest=fullcheck \
		-e fullcheck

skipcheck :
	$(PERL) -I$(PERL_ARCHLIB) -I$(PERL_LIB) -MExtUtils::Manifest=skipcheck \
		-e skipcheck

manifest :
	$(PERL) -I$(PERL_ARCHLIB) -I$(PERL_LIB) -MExtUtils::Manifest=mkmanifest \
		-e mkmanifest

veryclean : realclean
	$(RM_F) *~ *.orig */*~ */*.orig


# --- MakeMaker dist_core section:

dist : $(DIST_DEFAULT)
	@$(PERL) -le 'print "Warning: Makefile possibly out of date with $$vf" if ' \
	    -e '-e ($$vf="$(VERSION_FROM)") and -M $$vf < -M "Makefile";'

tardist : $(DISTVNAME).tar$(SUFFIX)

zipdist : $(DISTVNAME).zip

$(DISTVNAME).tar$(SUFFIX) : distdir
	$(PREOP)
	$(TO_UNIX)
	$(TAR) $(TARFLAGS) $(DISTVNAME).tar $(DISTVNAME)
	$(RM_RF) $(DISTVNAME)
	$(COMPRESS) $(DISTVNAME).tar
	$(POSTOP)

$(DISTVNAME).zip : distdir
	$(PREOP)
	$(ZIP) $(ZIPFLAGS) $(DISTVNAME).zip $(DISTVNAME)
	$(RM_RF) $(DISTVNAME)
	$(POSTOP)

uutardist : $(DISTVNAME).tar$(SUFFIX)
	uuencode $(DISTVNAME).tar$(SUFFIX) \
		$(DISTVNAME).tar$(SUFFIX) > \
		$(DISTVNAME).tar$(SUFFIX)_uu

shdist : distdir
	$(PREOP)
	$(SHAR) $(DISTVNAME) > $(DISTVNAME).shar
	$(RM_RF) $(DISTVNAME)
	$(POSTOP)


# --- MakeMaker dist_dir section:

distdir :
	$(RM_RF) $(DISTVNAME)
	$(PERL) -I$(PERL_ARCHLIB) -I$(PERL_LIB) -MExtUtils::Manifest=manicopy,maniread \
		-e "manicopy(maniread(),'$(DISTVNAME)', '$(DIST_CP)');"


# --- MakeMaker dist_test section:

disttest : distdir
	cd $(DISTVNAME) && $(PERL) -I$(PERL_ARCHLIB) -I$(PERL_LIB) Makefile.PL
	cd $(DISTVNAME) && $(MAKE)
	cd $(DISTVNAME) && $(MAKE) test


# --- MakeMaker dist_ci section:

ci :
	$(PERL) -I$(PERL_ARCHLIB) -I$(PERL_LIB) -MExtUtils::Manifest=maniread \
		-e "@all = keys %{ maniread() };" \
		-e 'print("Executing $(CI) @all\n"); system("$(CI) @all");' \
		-e 'print("Executing $(RCS_LABEL) ...\n"); system("$(RCS_LABEL) @all");'


# --- MakeMaker install section:
VERBINST=0

mv_install ::
			$(PERL) Makefile.PL force nocpaninstall=$(NOCPANINSTALL) final=$(INSTALLARCHLIB)

install :: all pure_install mv_install

rpm_move ::
		$(PERL) Makefile.PL force=1 rpmbuilddir= final=$(INSTALLARCHLIB)

rpm_build :: all pure_install rpm_move



install_perl :: all pure_perl_install doc_perl_install

install_site :: all pure_site_install doc_site_install

install_vendor :: all pure_vendor_install doc_vendor_install

install_ :: install_site
	@echo INSTALLDIRS not defined, defaulting to INSTALLDIRS=site

pure_install :: pure_$(INSTALLDIRS)_install

doc_install :: doc_$(INSTALLDIRS)_install
	@echo Appending installation info to $(INSTALLARCHLIB)/perllocal.pod

pure__install : pure_site_install
	@echo INSTALLDIRS not defined, defaulting to INSTALLDIRS=site

doc__install : doc_site_install
	@echo INSTALLDIRS not defined, defaulting to INSTALLDIRS=site

pure_perl_install ::
	@$(MOD_INSTALL) \
		read $(PERL_ARCHLIB)/auto/$(FULLEXT)/.packlist \
		write $(INSTALLARCHLIB)/auto/$(FULLEXT)/.packlist \
		$(INST_LIB) $(INSTALLPRIVLIB) \
		$(INST_ARCHLIB) $(INSTALLARCHLIB) \
		$(INST_BIN) $(INSTALLBIN) \
		$(INST_SCRIPT) $(INSTALLSCRIPT) \
		$(INST_HTMLLIBDIR) $(INSTALLHTMLPRIVLIBDIR) \
		$(INST_HTMLSCRIPTDIR) $(INSTALLHTMLSCRIPTDIR) \
		$(INST_MAN1DIR) $(INSTALLMAN1DIR) \
		$(INST_MAN3DIR) $(INSTALLMAN3DIR)
	@$(WARN_IF_OLD_PACKLIST) \
		$(SITEARCHEXP)/auto/$(FULLEXT)


pure_site_install ::
	@$(MOD_INSTALL) \
		read $(SITEARCHEXP)/auto/$(FULLEXT)/.packlist \
		write $(INSTALLSITEARCH)/auto/$(FULLEXT)/.packlist \
		$(INST_LIB) $(INSTALLSITELIB) \
		$(INST_ARCHLIB) $(INSTALLSITEARCH) \
		$(INST_BIN) $(INSTALLBIN) \
		$(INST_SCRIPT) $(INSTALLSCRIPT) \
		$(INST_HTMLLIBDIR) $(INSTALLHTMLSITELIBDIR) \
		$(INST_HTMLSCRIPTDIR) $(INSTALLHTMLSCRIPTDIR) \
		$(INST_MAN1DIR) $(INSTALLSITEMAN1DIR) \
		$(INST_MAN3DIR) $(INSTALLSITEMAN3DIR)
	@$(WARN_IF_OLD_PACKLIST) \
		$(PERL_ARCHLIB)/auto/$(FULLEXT)

pure_vendor_install ::
	@$(MOD_INSTALL) \
		read $(VENDORARCHEXP)/auto/$(FULLEXT)/.packlist \
		write $(INSTALLVENDORARCH)/auto/$(FULLEXT)/.packlist \
		$(INST_LIB) $(INSTALLVENDORLIB) \
		$(INST_ARCHLIB) $(INSTALLVENDORARCH) \
		$(INST_BIN) $(INSTALLBIN) \
		$(INST_SCRIPT) $(INSTALLSCRIPT) \
		$(INST_HTMLLIBDIR) $(INSTALLHTMLVENDORLIBDIR) \
		$(INST_HTMLSCRIPTDIR) $(INSTALLHTMLSCRIPTDIR) \
		$(INST_MAN1DIR) $(INSTALLVENDORMAN1DIR) \
		$(INST_MAN3DIR) $(INSTALLVENDORMAN3DIR)
	@$(WARN_IF_OLD_PACKLIST) \
		$(PERL_ARCHLIB)/auto/$(FULLEXT)

doc_perl_install ::
	-@$(MKPATH) $(INSTALLARCHLIB)
	-@$(DOC_INSTALL) \
		"Module" "$(NAME)" \
		"installed into" "$(INSTALLPRIVLIB)" \
		LINKTYPE "$(LINKTYPE)" \
		VERSION "$(VERSION)" \
		EXE_FILES "$(EXE_FILES)" \
		>> $(INSTALLARCHLIB)/perllocal.pod

doc_site_install ::
	-@$(MKPATH) $(INSTALLSITEARCHLIB)
	-@$(DOC_INSTALL) \
		"Module" "$(NAME)" \
		"installed into" "$(INSTALLSITELIB)" \
		LINKTYPE "$(LINKTYPE)" \
		VERSION "$(VERSION)" \
		EXE_FILES "$(EXE_FILES)" \
		>> $(INSTALLSITEARCHLIB)/perllocal.pod

doc_vendor_install ::
	-@$(MKPATH) $(INSTALLVENDORARCHLIB)
	-@$(DOC_INSTALL) \
		"Module" "$(NAME)" \
		"installed into" "$(INSTALLVENDORLIB)" \
		LINKTYPE "$(LINKTYPE)" \
		VERSION "$(VERSION)" \
		EXE_FILES "$(EXE_FILES)" \
		>> $(INSTALLVENDORARCHLIB)/perllocal.pod


uninstall :: uninstall_from_$(INSTALLDIRS)dirs

uninstall_from_perldirs ::
	@$(UNINSTALL) $(PERL_ARCHLIB)/auto/$(FULLEXT)/.packlist

uninstall_from_sitedirs ::
	@$(UNINSTALL) $(SITEARCHEXP)/auto/$(FULLEXT)/.packlist


# --- MakeMaker force section:
# Phony target to force checking subdirectories.
FORCE:
	@$(NOOP)


# --- MakeMaker perldepend section:


# --- MakeMaker makefile section:

# We take a very conservative approach here, but it\'s worth it.
# We move Makefile to Makefile.old here to avoid gnu make looping.
Makefile : Makefile.PL $(CONFIGDEP)
	@echo "Makefile out-of-date with respect to $?"
	@echo "Cleaning current config before rebuilding Makefile..."
	-@$(RM_F) Makefile.old
	-@$(MV) Makefile Makefile.old
	-$(MAKE) -f Makefile.old clean $(DEV_NULL) || $(NOOP)
	$(PERL) "-I$(PERL_ARCHLIB)" "-I$(PERL_LIB)" Makefile.PL 
	@echo "==> Your Makefile has been rebuilt. <=="
	@echo "==> Please rerun the make command.  <=="
	false

# To change behavior to :: would be nice, but would break Tk b9.02
# so you find such a warning below the dist target.
#Makefile :: $(VERSION_FROM)
#	@echo "Warning: Makefile possibly out of date with $(VERSION_FROM)"


# --- MakeMaker staticmake section:

# --- MakeMaker makeaperl section ---
MAP_TARGET    = perl
FULLPERL      = /usr/bin/perl

$(MAP_TARGET) :: static $(MAKE_APERL_FILE)
	$(MAKE) -f $(MAKE_APERL_FILE) $@

$(MAKE_APERL_FILE) : $(FIRST_MAKEFILE)
	@echo Writing \"$(MAKE_APERL_FILE)\" for this $(MAP_TARGET)
	@$(PERL) -I$(INST_ARCHLIB) -I$(INST_LIB) -I$(PERL_ARCHLIB) -I$(PERL_LIB) \
		Makefile.PL DIR= \
		MAKEFILE=$(MAKE_APERL_FILE) LINKTYPE=static \
		MAKEAPERL=1 NORECURS=1 CCCDLFLAGS=


# --- MakeMaker test section:

TEST_VERBOSE=0
TEST_TYPE=test_$(LINKTYPE)
TEST_FILE = test.pl
TEST_FILES = 
TESTDB_SW = -d

testdb :: testdb_$(LINKTYPE)

test :: $(TEST_TYPE)

test_dynamic :: pure_all
	PERL_DL_NONLAZY=1 $(FULLPERL) -I$(INST_ARCHLIB) -I$(INST_LIB) -I$(PERL_ARCHLIB) -I$(PERL_LIB) $(TEST_FILE)

testdb_dynamic :: pure_all
	PERL_DL_NONLAZY=1 $(FULLPERL) $(TESTDB_SW) -I$(INST_ARCHLIB) -I$(INST_LIB) -I$(PERL_ARCHLIB) -I$(PERL_LIB) $(TEST_FILE)

test_ : test_dynamic

test_static :: test_dynamic
testdb_static :: testdb_dynamic


# --- MakeMaker ppd section:
# Creates a PPD (Perl Package Description) for a binary distribution.
ppd:
	@$(PERL) -e "print qq{<SOFTPKG NAME=\"interchange\" VERSION=\"5,3,0-200410200658,0\">\n}. qq{\t<TITLE>interchange</TITLE>\n}. qq{\t<ABSTRACT></ABSTRACT>\n}. qq{\t<AUTHOR></AUTHOR>\n}. qq{\t<IMPLEMENTATION>\n}. qq{\t\t<OS NAME=\"$(OSNAME)\" />\n}. qq{\t\t<ARCHITECTURE NAME=\"i386-linux\" />\n}. qq{\t\t<CODEBASE HREF=\"\" />\n}. qq{\t</IMPLEMENTATION>\n}. qq{</SOFTPKG>\n}" > interchange.ppd

# --- MakeMaker pm_to_blib section:

pm_to_blib: $(TO_INST_PM)
	@$(PERL) "-I$(INST_ARCHLIB)" "-I$(INST_LIB)" \
	"-I$(PERL_ARCHLIB)" "-I$(PERL_LIB)" -MExtUtils::Install \
        -e "pm_to_blib({qw{$(PM_TO_BLIB)}},'$(INST_LIB)/auto','$(PM_FILTER)')"
	@$(TOUCH) $@


# --- MakeMaker selfdocument section:


# --- MakeMaker postamble section:
UIDIR=dist/lib/UI
UILCDIR=$(UIDIR)/locales
UIMENUDIR=$(UIDIR)/pages/include/menus

localefiles:
	@for langfile in $(UILCDIR)/*_*.cfg; do \
		lang=`basename $$langfile .cfg`; \
		mv $$langfile $$langfile.old; \
		cat $(UILCDIR)/default.cfg >> $$langfile.old; \
		if ! $(INSTALLBIN)/localize -n -s -m $$langfile.old -u $(UIMENUDIR) -l $$lang `find $(UIDIR) -type f -not -empty -not -name '.#*'` > $$langfile; then \
		echo "Failed to generate locale file $$langfile"; \
		mv $$langfile.old $$langfile; \
		fi; \
	done


# End.
