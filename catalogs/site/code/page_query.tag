UserTag page_query Order page_id
UserTag page_query AddAttr
UserTag page_query Routine <<EOR
sub {
	my ($page_id, $opt) = @_;
        my (@conds, %data, $qtd, $query);

	%data = %{$opt->{hash} || {}};

        # discontinued condition
        if ($data{discontinued}) {
		push (@conds, q{merchandising = 'discontinued'});
        }
        else {
		push (@conds, q{merchandising <> 'discontinued'});
        }

        # select conditions
        for my $name (qw/gender prod_group category manufacturer/) {
        	if ($data{$name}) {
			$qtd = $Db{pages}->quote($data{$name});
                     	push (@conds, qq{$name = $qtd});
                }
        }

        # bounce condition
        push (@conds, q{bounce = 'none'});

        $query = q{select * from products where } 
		. join(' AND ', @conds)
		. ' order by length(price), price desc';

        return $query; 
}
EOR

