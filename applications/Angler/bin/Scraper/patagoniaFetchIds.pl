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

my ($url, $response, $content, $last);
my $total_added = 0;
my $shop_link = 'http://www.patagonia.com/us/shop/';
my @categories = categories();

# Main
fetch();

sub categories {
	$url = "http://www.patagonia.com/us/home";
	try {
		$mech->get( $url );
	}
	catch {
		die $_;
	};
	
	try {$twig->parse_html($mech->content())}
	catch{
		warn 'Couldnt parse categories';
		next;
	};
	my @cats;		
	for my $n ($twig->find_nodes('//[@class="shop menu"]//li/a'), $twig->find_nodes('//[@class="shop menu"]//LI/A')){
		my $cat = trim $n->att('href');
		next if (index $cat, $shop_link) == -1;
		#$cat .= '&ps=all';
		$cat = substr $cat, length $shop_link;
		my ($name, $url) = split '\?', $cat; 		
		push @cats, {name => $name, id => substr $url, 2};
	}
	
	return @cats;
	
}

sub fetch {
	my $page = 1;
	for my $category ( reverse @categories){
		# URL hunt
		print "Category ".$category->{name}.":";
		my $cat_url = "http://www.patagonia.com/us/shop/a?k=".$category->{id}."&ps=all";
		try {
			$mech->get( $cat_url );
		}
		catch {
			$last = 1;
		};	
		
		my $added = 0;
		my $link_no = 0;
		my $skipped = 0;
		my @links = $mech->links;
		
		for my $link (@links){
			my ( $url, $text, $name, $tag, $uri, $attr_href ) = @{$link};
			# Skip non product links
			next if $url eq '' or ((index $url, '/us/product/') == -1);
			
			my($full_path, $sku) = split '\?', $url;
			next unless $full_path and $sku;
			
			# ID
			$sku = substr $sku, 2;
			my ($id, $size, $color) = split '-', $sku;
			
			
			my @path_parts = split '/', $full_path;
			my $path = pop @path_parts;
			
			$link_no++;			
			
			my $product = $schema->resultset('PatagoniaProduct')->find($sku);
			if ($id and !$product){
				$schema->resultset('PatagoniaProduct')->create({
					id => $id,  
					path => $path,
					sku => $sku,
					color => $color,
					size => $size,
					category_name => $category->{name},
					category_id => $category->{id},
				});
				$added++;
			}
			else {
				
				
					$skipped++ ;
				
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