use utf8;
use open ':std', ':encoding(UTF-8)';
use Dancer ':syntax';

use WWW::Mechanize;
use XML::Twig;
use Dancer::Plugin::DBIC qw(schema resultset rset);
use Database::Schema;
use Try::Tiny; 
use YAML qw(DumpFile LoadFile freeze thaw);
use XML::Twig;
sub trim { my $s = shift; $s =~ s/^\s+|\s+$//g; return $s }
sub printt { $|++; print localtime().' | '.shift."\n" }

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

my (@podjetja, $url, $response, $content, $last);
my $podjetje_link_base = 'http://www.simmsfishing.com/catalogsearch/result/?q=a';
my $total_added = 0;
# Main
fetch();

sub fetch {
	my $page = 1;
	while(true){
		# URL hunt
		print "Page  $page:";
		$url = "http://www.simmsfishing.com/catalogsearch/result/index/?p=$page&q=a";
		try {
			$mech->get( $url );
		}
		catch {
			$last = 1;
		};
		
		if($last){
			$last = undef;
			last;
		}
		
		my $added = 0;
		my $link_no = 0;
		my $skipped = 0;
		
		for my $link ($mech->links){
			my ( $url, $text, $name, $tag, $base, $attr_href ) = @{$link};
			
			# Skip non company links
			next unless ($attr_href->{class} and $attr_href->{class} eq 'product-image');
			
			$link_no++;
			my $id = substr $url, 28, -5;
			
			if ($id and !$schema->resultset('SimmsProduct')->find($id)){
				$schema->resultset('SimmsProduct')->create({
					id => $id,  
				});
				$added++;
			}
			else {
				$skipped++;
				#printt("$letter$page $link_no - $url skipped");
			}
		}	 
		last if $skipped == 3 and !$added;
		$page++;
		$total_added += $added;
		print "$added added and $skipped skipped\n";
	}	
	printt "All done. $total_added added.";
}

1;