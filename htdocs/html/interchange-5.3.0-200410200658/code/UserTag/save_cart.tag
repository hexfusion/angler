# Copyright 2002 Interchange Development Group (http://www.icdevgroup.org/)
# Licensed under the GNU GPL v2. See file LICENSE for details.
# $Id: save_cart.tag,v 1.2 2004/10/16 21:50:43 docelic Exp $

UserTag save_cart Order nickname recurring
UserTag save_cart AttrAlias name nickname
UserTag save_cart Routine <<EOR
sub {
	my($nickname,$recurring) = @_;

	my $add = 0;
	my %names = ();

	$nickname =~ s/://g;
	$recurring = ($recurring?"r":"c");

	foreach(split("\n",$Tag->value('carts'))) {
		my($n,$t,$r) = split(':',$_);
		$names{$n} = $r;
		if($r eq $recurring) {
			if($n eq $nickname) {
				#$Tag->userdb({function => 'delete_cart', nickname => $_});
				$add = 1;
			}
		}
	}
	if($add) {
		while($names{"$nickname,$add"} eq $recurring) {
			$add++;
		}
		$nickname .= ",$add";
	}

	my $nn = join(':',$nickname,time(),$recurring);

	$Tag->userdb({function => 'set_cart', nickname => $nn});

	$Carts->{main} = [];

	return '';
}
EOR
