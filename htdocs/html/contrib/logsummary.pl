#!/usr/local/bin/perl5.003
#
# Print various statistics about the Log file
#
# Todo: summarize admin commands
#
# Paul Close, April 1994
# Bill Houle, Nov 1997 (getopt, support for MajorCool entries)
#

require "getopts.pl";

die("Usage: $0 [-egisuw]") unless &Getopts("egisuw");

while (<>) {
	if (($mon,$day,$time,$who,$cmd) =
	/([A-Za-z]+) (\d+) ([\d:]+)\s+\S+\s+majordomo\[\d+\]\s+{(.*)} (.*)/) {
		@f = split(' ',$cmd);
		$cmd = $f[0];
		$f[1] =~ s/[<>]//g;
		$f[2] =~ s/[<>]//g;

		# help
		# lists
		# which [address]
		if ($cmd eq "help" ||
		    $cmd eq "lists" ||
		    $cmd eq "which") {
			$count{$cmd}++;
			$global{$cmd}++;
		}

		# approve PASSWD ...
		elsif ($cmd eq "approve" ||
		       $cmd eq "send_confirm") {
			$count{$cmd}++;
			${$cmd}++;
		}

		# index list
		# info list
		# who list
		elsif ($cmd eq "index" ||
		       $cmd eq "info" ||
		       $cmd eq "intro" ||
		       $cmd eq "who") {
			if ($#f == 1) {
				$count{$cmd}++;
				$lists{$f[1]}++;
				$f[1] =~ s/-//g;
				${$f[1]}{$cmd}++;
			}
			else {
				$bad{$cmd}++;
			}
		}

		# get list file
		# newinfo list passwd
		# config list passwd
		# newconfig list passwd
		# writeconfig list passwd
		elsif ($cmd eq "get" ||
		       $cmd eq "newinfo" ||
		       $cmd eq "newintro" ||
		       $cmd eq "mkdigest" ||
		       $cmd eq "config" ||
		       $cmd eq "newconfig" ||
		       $cmd eq "writeconfig") {
			if ($#f == 2) {
				$count{$cmd}++;
				$lists{$f[1]}++;
				$f[1] =~ s/-//g;
				${$f[1]}{$cmd}++;
				if ($cmd eq "get") {
					$req = &ParseAddrs($who);
					$long{$req} = $who;
					$getcount{$req}++;
				}
			} 
			else {
				$bad{$cmd}++;
			}
		}

		# subscribe list [address]
		# unsubscribe list [address]
		elsif ($cmd eq "subscribe" ||
		       $cmd eq "unsubscribe") {
			if ($#f >= 1) {
				$count{$cmd}++;
				$lists{$f[1]}++;
				$f[1] =~ s/-//g;
				${$f[1]}{$cmd}++;
			} 
			else {
				$bad{$cmd}++;
			}
		}

		# request cmd list subscribe (for approval)
		elsif ($cmd eq "request") {
			if ($#f >= 2) {
				$count{$cmd}++;
				$lists{$f[2]}++;
				$f[2] =~ s/-//g;
				${$f[2]}{$cmd}++;
			}
			else {
				$bad{$cmd}++;
			}
		}

		elsif ($cmd eq "ABORT" ||
		       $cmd eq "FAILED" ||
		       $cmd eq "DUPLICATE" ||
		       $cmd eq "WARNING") {
			$errors{$cmd}++;
		}
		else {
			$unrecognized{$cmd}++;
		}
	}
	elsif (($mon,$day,$time,$process,$who,$action) =
	m|([A-Za-z]+) (\d+) ([\d:]+)\s+\S+\s+(/[^\[]+)\[\d+\]\s+{(.*)} (.*)|) {
		@f = split(' ',$action);
		$module = shift(@f);
		$action = $f[0];

		# PREFS get
		# PREFS set
		# HELP about
		# HELP helpfile
		if ($module eq "PREFS" ||
		    $module eq "HELP") {
			$mode{"$module $action"}++;
		}

		# CREATE list
		# RENAME list
		# DELETE list
		elsif ($module eq "CREATE" ||
		       $module eq "RENAME" ||
		       $module eq "DELETE") {
			$list = $action;
			$mode{"$module"}++;
			$weblists{$list}++;
			${$list}{"$module"}++;
		}

		elsif ($module eq "BROWSE" ||
		       $module eq "MODIFY") {

			# BROWSE who list
			# MODIFY who list
			# etc
			if ($action eq "who" ||
			    $action eq "info" ||
			    $action eq "details" ||
			    $action eq "config") {
				$action = "config" if $action eq "details";
				$mode{"$module $action"}++;
				$weblists{$f[1]}++;
				${$f[1]}{"$module $action"}++;
			}

			# BROWSE lists criteria (address)
			elsif ($action eq "lists") {
				$mode{"$module $action"}++;
			}

			elsif ($action eq "ERROR:") {
				$weberr{"$module $action"}++;
			}

			else {
				$webbad{"$module $action"}++;
			}

		}
		else {
			$webunrecognized{"$module $action"}++;
		}
	} else {
		warn "line $. didn't match!\n" if !/^$/;
	}
}

if ($opt_s && defined(%count)) {
	print "Majordomo Command Summary:\n";
	foreach $cmd (sort keys %count) {
		printf "      %-15s %4d\n", $cmd, $count{$cmd};
	}
	print "\n";
}

if ($opt_g && defined(%global)) {
	print "Global Commands:\n";
	foreach $cmd (sort keys %global) {
		printf "      %-15s %4d\n", $cmd, $global{$cmd};
	}
	print "\n";
}

if ($opt_u && defined(%unrecognized)) {
	print "Unrecognized Commands:\n";
	foreach $cmd (sort keys %unrecognized) {
		printf "      %-15s %4d\n", $cmd, $unrecognized{$cmd};
	}
	print "\n";
}

if ($opt_i && defined(%bad)) {
	print "Incomplete Commands:\n";
	foreach $cmd (sort keys %bad) {
		printf "      %-15s %4d\n", $cmd, $bad{$cmd};
	}
	print "\n";
}

if ($opt_e && defined(%errors)) {
	print "Errors & Warnings:\n";
	foreach $cmd (sort keys %errors) {
		printf "      %-15s %4d\n", $cmd, $errors{$cmd};
	}
	print "\n";
}

@reqs = sort {$getcount{$b}<=>$getcount{$a};} keys %getcount;
if ($#reqs >= 0) {
	print "Top 'GET' Requestors:\n";
	for ($i=0; $i < 5; $i++) {
		printf "     %5d  %s\n", $getcount{$reqs[$i]}, $long{$reqs[$i]};
		last if ($i == $#reqs);
	}
	print "\n";
}

if (defined(%lists)) {
	printf "%-20s %5s %5s %5s %5s %5s %5s %7s %7s %7s\n", "List",
		"sub", "unsub",
		"info", "who", 
		"index", "get",
		"approve",
		"config",
		"newconf";
	foreach $list (sort keys %lists) {
		printf "%-20s", substr($list,0,20);
		$list =~ s/-//g;
		%l = %{$list};
		printf " %5d %5d %5d %5d %5d %5d %7d %7d %7d\n",
			$l{subscribe}, $l{unsubscribe},
			$l{info}, $l{who},
			$l{index}, $l{get},
			$l{approve},
			$l{config},
			$l{newconfig};
	}
	print "\n";
}

if ($opt_w) {
	if ($opt_s && defined(%mode)) {
		print "MajorCool Command Summary:\n";
		foreach $mode (sort keys %mode) {
			printf "      %-15s %4d\n", $mode, $mode{$mode};
		}
		print "\n";
	}

	if ($opt_u && defined(%webunrecognized)) {
		print "Unrecognized Web Commands:\n";
		foreach $cmd (sort keys %webunrecognized) {
			printf "      %-15s %4d\n", $cmd, $webunrecognized{$cmd};
		}
		print "\n";
	}

	if ($opt_i && defined(%webbad)) {
		print "Incomplete Commands:\n";
		foreach $cmd (sort keys %webbad) {
			printf "      %-15s %4d\n", $cmd, $webbad{$cmd};
		}
		print "\n";
	}

	if ($opt_e && defined(%weberr)) {
		print "Errors & Warnings:\n";
		foreach $cmd (sort keys %weberr) {
			printf "      %-15s %4d\n", $cmd, $weberr{$cmd};
		}
		print "\n";
	}

	if (defined(%weblists)) {
		printf "%-20s %17s %17s %7s %7s %7s\n",
			"", "BROWSE", "MODIFY", 
			"CREATE", "RENAME", "DELETE";
		printf "%-20s %5s %5s %5s %5s %5s %5s %7s %7s %7s\n", "List",
			"who",  "info", "conf",
			"who",  "info", "conf",
			"------", "------", "------";
		foreach $list (sort keys %weblists) {
			printf "%-20s", substr($list,0,20);
			%l = %{$list};
			printf " %5d %5d %5d %5d %5d %5d %7s %7d %7d\n",
				$l{"BROWSE who"}, 
				$l{"BROWSE info"},
				$l{"BROWSE config"},
				$l{"MODIFY who"},
				$l{"MODIFY info"},
				$l{"MODIFY config"},
				$l{"CREATE"},
				$l{"RENAME"},
				$l{"DELETE"};
		}
		print "\n";
	}
}

# from majordomo.pl, modified to work on a single address
# $addrs = &ParseAddrs($addr_list)
sub ParseAddrs {
	local($_) = shift;
	1 while s/\([^\(\)]*\)//g; 		# strip comments
	1 while s/"[^"]*"//g;		# strip comments
	1 while s/.*<(.*)>.*/\1/;
	s/^\s+//;
	s/\s+$//;
	$_;
}

