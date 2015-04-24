use utf8;
use Dancer ':syntax';

use WWW::Mechanize;
use Dancer::Plugin::DBIC qw(schema resultset rset);
use Database::Schema;
use Try::Tiny; 
use YAML qw(DumpFile LoadFile freeze thaw);
use XML::Twig;
use Encode qw(encode decode_utf8);
use HTML::Entities;
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

parse();

sub parse {
	 
	my $fetched = $schema->resultset('PatagoniaProduct')->search({html => { '-not' => undef}, long_description => undef}); #description => undef
	my @error;
	my $no = 1;
	while(my $p = $fetched->next){
		my $html = $p->html;
		$html =~ s/(\s)([0-9]+)"/$1$2&#34;/g; # Fix inch " encoding
		$html =~ s/(\s)([0-9]+)(\\)"/$1$2&#34;/g; # Fix inch " encoding
		
		open(my $fh, '>', 'html.html');
		print $fh $html;
		close $fh;		
		$html = encode("iso-8859-1", decode_utf8($html));
		$html =~ s/[^\x00-\x7f]//g;
		my $error_parsing; 
		try{
			$twig->parse_html($html);
		} catch {
			
		    warn "caught error: $_"; # not $@
		    push @error, $p->id;
		    $error_parsing = 1;
			
	    };
		next if $error_parsing;
		# Name		
		for my $n ($twig->find_nodes('//h1[@itemprop="name"]')){
			$p->name( trim $n->text );
			last;
		}
		
		# Price		
		for my $n ($twig->find_nodes('//span[@itemprop="price"]')){
			$p->price( trim $n->att('content') );
			last;
		}
		
		# Images	
		my @images;	
		for my $n ($twig->find_nodes('//div[@id="alt-views"]/a')){
			my $src = $n->att('src');
			next unless $src;		
			$src =~ s/wid=50&hei=50//g;	
			push @images, $src if $src;
		}
		unless(@images){
			for my $n ($twig->find_nodes('//img[@itemprop="image"]')){
				my $src = $n->att('src');	
				$src =~ s/wid=750&hei=750&bgcolor=FFFFFF&//g;		
				push @images, $src;
				last;
			}
		}
		$p->img( join ' ', @images ) if @images;
		
		# Sizes	
		my @sizes;	
		for my $n ($twig->find_nodes('//div[@id="size-inner"]/div[@class="size"]')){
			my $size = $n->att('data-size-basic');		
			push @sizes, $size;
		}		
		$p->sizes( join ' ', @sizes );
		
		# Desc		
		for my $n ($twig->find_nodes('//p[@itemprop="description"]')){
			$p->description( trim $n->text );
			last;
		}

		# Fit		
		for my $n ($twig->find_nodes('//div[@id="fittype"]//a')){
			$p->fit( trim $n->text );
			last;
		}

		# Color name		
		for my $n ($twig->find_nodes('//span[@id="currently-selected-color"]')){
			$p->color_name( trim $n->text );
			last;
		}

		# Rating		
		for my $n ($twig->find_nodes('//a[@class="review-cta"]')){
			my ($rating) = split ' ', trim $n->att('title') if $n->att('title');
			$p->rating( $rating );
			last;
		}

		# Video	
		my @videos;	
		for my $n ($twig->find_nodes('//iframe[@id="youtubevid"]')){
			push @videos, trim $n->att('src');						
		}
		$p->videos(join ' ', @videos);

		# Similar	
		#my $similar_js = $p->similar_js;
		#my $desc = '"id": "';
		#my $tag  = '",   "name"';			
		#my @similar = ($similar_js =~ /$desc(.*?)$tag/sg);			
		#$p->similar(join ' ', @similar);
		
		# Long Desc		
		my @dscs = $twig->find_nodes('//div[@id="learn-more-panel"]//div[@class="col-md-6"]');
		if(@dscs){
			$p->long_description( (shift @dscs)->text );
			$p->features( (shift @dscs)->xml_string );
		}
		
		

		
		
		$p->update;
		printt "$no ".$p->id." updated";
		$no++;
	}	
	
	printt "All done";
	printt "Error parsing: ".join ', ', @error;
}


1;