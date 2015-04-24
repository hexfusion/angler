use utf8;
use Dancer ':syntax';

use WWW::Mechanize;
use XML::Twig;
use Dancer::Plugin::DBIC qw(schema resultset rset);
use Database::Schema;
use Try::Tiny; 
use YAML qw(DumpFile LoadFile freeze thaw);
use XML::Twig;
sub trim { my $s = shift; $s =~ s/^\s+|\s+$//g; return $s }
sub number { my $s = shift; return undef if $s eq 'N/A'; $s =~ s/,//g; return $s }
sub printt { $|++; print localtime().' '.shift."\n" }
sub compact { 
	my $s = shift; 
	my @parts = split '\n', trim $s;
	$s = join ', ', map {trim $_} @parts; 
	return $s 
}
sub compact_address {
	 my $s = shift;
	 my @parts = split ' ', trim $s;
	$s = join ' ', map {trim $_} @parts; 
	return $s;
}
sub img_value {	my $s = trim shift; return substr $s, -5, 1}

# Settings file
use YAML qw/LoadFile/;
my $global_settings_file = "config.yml";
my $global_settings = LoadFile($global_settings_file) if (-f $global_settings_file);
die 'No DB settings!' unless $global_settings->{dbi};

# Vars
my $schema = Database::Schema->connect('dbi:'.$global_settings->{dbi}.':dbname='.$global_settings->{dbname}.';host='.$global_settings->{host}.';port='.$global_settings->{port}, $global_settings->{user}, $global_settings->{pass}, $global_settings->{options});
my $mech = WWW::Mechanize->new();

my $twig= XML::Twig->new();
$twig->output_encoding('UTF-8');

# Main

fetch();

sub fetch {
	my $unfetched = $schema->resultset('SimmsProduct')->search(); #{html => undef}
	
	my $no = 1;
	while(my $p = $unfetched->next){
		# Fetch
		$mech->get( "http://www.simmsfishing.com/".$p->id.".html" );
		
		### Quick info parsing
		try {$twig->parse_html($mech->content())}
		catch{
			warn 'Couldnt parse '.$p->id;
			next;
		};
		
		# Name		
		for my $n ($twig->find_nodes('//[@class="product-name"]//h1')){
			$p->name( trim $n->text );
			last
		}
		# SKU		
		for my $n ($twig->find_nodes('//[@class="product-ids"]')){
			$p->sku( substr trim($n->text), 7 );
			last;
		}
		# Price		
		for my $n ($twig->find_nodes('//[@class="price"]')){
			$p->price( substr trim($n->text), 1 );
			last;
		}
		
		
		# Img		
		for my $n ($twig->find_nodes('//[@id="main-image"]')){
			$p->img( $n->att('href') );
			last;
		}
		# HTML		
		for my $n ($twig->find_nodes('//[@class="tab-wrapper"]')){
			$p->html( $n->sprint );
			last;
		}
		$p->update;
		printt "$no ".$p->id." fetched";
		$no++;
	}
}

1;