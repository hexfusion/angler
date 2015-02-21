package Angler::Drone;

use strict;
use warnings;

use utf8;
use Dancer ':syntax';

use WWW::Mechanize;
use XML::Twig;
use Try::Tiny; 
use YAML qw(DumpFile LoadFile freeze thaw);
use XML::Twig;

my $mech = WWW::Mechanize->new();
my $twig= XML::Twig->new();
$twig->output_encoding('UTF-8');

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
sub img_value { my $s = trim shift; return substr $s, -5, 1}

=head2 fetch_simms

=cut

sub fetch_simms {
    my ($drone) = @_;

    my $unfetched = $drone->resultset('SimmsProduct')->search(); #{html => undef}
    
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

sub fetch_id {
    my ($drone) = @_;

    my (@podjetja, $url, $response, $content, $last);
    my $podjetje_link_base = 'http://www.simmsfishing.com/catalogsearch/result/?q=a';
    my $total_added = 0;

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
            
            if ($id and !$drone->resultset('SimmsProduct')->find($id)){
                $drone->resultset('SimmsProduct')->create({
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
        printt "$added added and $skipped skipped\n";
    }   
    printt "All done. $total_added added.";
}

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
