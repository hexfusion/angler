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

parse();

sub parse {
	 
	my $fetched = $schema->resultset('SimmsProduct')->search({html => { '-not' => undef}, description => undef});
	
	my $no = 1;
	while(my $p = $fetched->next){
		
		$twig->parse_html($p->html);

		# Headings
		my @headings;
		my $content;
		for my $n ($twig->find_nodes('//[@id="tab-content-1"]//h3')){
			push @headings, $n->text_only 			
		}
		for my $n ($twig->find_nodes('//[@id="tab-content-1"]/div')){
			$content = $n->sprint; 			
			last;
		}
		my $data;
		for my $part (split '<h3>', $content){
			my ($title, $con) = split '</h3>',$part;
			$title = lc $title;
			$p->$title(trim $con) if $con;
		}
		
		# Videos
		my @videos;
		for my $n ($twig->find_nodes('//iframe')){
			next unless defined $n->att('allowfullscreen');
			push @videos, $n->att('src');		
		}
		$p->videos(join ', ', @videos);
		
		$p->update;
		printt "$no ".$p->id." updated";
		$no++;
	}	
	
	printt "All done";

}


1;