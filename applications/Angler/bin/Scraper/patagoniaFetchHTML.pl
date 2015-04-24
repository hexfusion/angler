use utf8;
use Dancer ':syntax';

use WWW::Mechanize;
use XML::Twig;
use Dancer::Plugin::DBIC qw(schema resultset rset);
use Database::Schema;
use Try::Tiny; 
use Encode;
use YAML qw(DumpFile LoadFile freeze thaw);
use XML::Twig;
use LWP::Simple;
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
	my $unfetched = $schema->resultset('PatagoniaProduct')->search({html => undef}); #{html => undef}
	
	my $no = 1;
	while(my $p = $unfetched->next){
		
		# Fetch
		$mech->get( "http://www.patagonia.com/us/product/?p=".$p->sku );
		
		# Cleanup html
		my $raw_html = $mech->content();		   
		my $desc = 'container-fixed">';
		my $tag  = "<footer";
		
		$raw_html =~ /$desc(.*?)$tag/s;
		my $html = '<div>'.$1;
		
		$p->html( $html );

=Fetch similar - for some reason not working. Browser returns different contetnt.
		# Fetch
		#$mech->get( "http://recs.richrelevance.com/rrserver/p13n_generated.js?a=efd98ad8406be54e&ts=1429779979550&p=".$p->id."&re=true&pt=%7Citem_page.responsive_content_top%7Citem_page.responsive_content_bottom&u=1429688842054&s=1429688842054&cts=http%3A%2F%2Fwww.patagonia.com%2Fus&flo=en&rid=WWW.PATAGONIA.COM&flv=17.0.0&rcs=eF4Ny7ENgEAIBdDmKkcxIeEOEP4GrnGBmFjYqfPr619rT-4JGSyc1O0QUjFQFBc5TCRYzSeW673PWqNT1wH3AG_Q-AvxBzv9EB0&l=1" );
		#my $html = $mech->content();
		# Create the fake browser (user agent).
	    my $ua = LWP::UserAgent->new();
	    
	    # Accept cookies. You don't need to supply
	    # any options to new() here, but just for
	    # kicks we'll save the cookies to a file.
	    my $cookies = HTTP::Cookies->new(
	        file => "cookies.txt",
	        autosave => 1,
	    );
	    
	    $ua->cookie_jar($cookies);
	    
	    # Pretend to be Internet Explorer.
	    $ua->agent("Windows IE 7");
	    # or maybe .... $ua->agent("Mozilla/8.0");
	    
	    # Get some HTML.
	    my $response = $ua->get('http://news.bbc.co.uk');
		
		
		my $html = $ua->get("http://recs.richrelevance.com/rrserver/p13n_generated.js?a=efd98ad8406be54e&ts=1429779979550&p=".$p->id."&re=true&pt=%7Citem_page.responsive_content_top%7Citem_page.responsive_content_bottom&u=1429688842054&s=1429688842054&cts=http%3A%2F%2Fwww.patagonia.com%2Fus&flo=en&rid=WWW.PATAGONIA.COM&flv=17.0.0&rcs=eF4Ny7ENgEAIBdDmKkcxIeEOEP4GrnGBmFjYqfPr619rT-4JGSyc1O0QUjFQFBc5TCRYzSeW673PWqNT1wH3AG_Q-AvxBzv9EB0&l=1");	
		# Cleanup html				
		$p->similar_js( $html );
=cut		
		$p->update;
		printt "$no ".$p->id." fetched";
		sleep rand 10;
		$no++;
	}
}

1;