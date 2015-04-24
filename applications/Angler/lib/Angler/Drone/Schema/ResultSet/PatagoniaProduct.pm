use utf8;

package Angler::Drone::Schema::ResultSet::PatagoniaProduct;

=head1 NAME

Angler::Drone::Schema::ResultSet::PatagoniaProduct

=cut

use open ':std', ':encoding(UTF-8)';
use WWW::Mechanize;
use XML::Twig;
use Try::Tiny;
use YAML qw(DumpFile LoadFile freeze thaw);
use XML::Twig;

=head1 SYNOPSIS

Provides extra accessor methods for L<Angler::Drone::Schema::Result::PatagoniaProduct>

to scrape the patagonia.com site for new data run the methods in this order.

1.) fetch_pages: 

2.) fetch_data

3.) parse_data

=cut

use strict;
use warnings;
use constant { true => 1, false => 0};

use parent 'Angler::Drone::Schema::ResultSet';

=head1 METHODS

=cut

sub trim {
    my $self = shift;
    $self =~ s/^\s+|\s+$//g;
    return $self
}

sub number {
    my $self = shift;
    return undef if $self eq 'N/A';
    $self =~ s/,//g;
    return $self
}

sub compact {
    my $self = shift;
    my @parts = split '\n', trim $self;
    $self = join ', ', map {trim $_} @parts;
    return $self
}

sub compact_address {
    my $self = shift;
    my @parts = split ' ', trim $self;
    $self = join ' ', map {trim $_} @parts;
    return $self;
}

sub img_value {
    my $self = trim shift;
    return substr $self, -5, 1
}

=head2 _init_mech

=cut

sub _init_mech {
    my $mech = WWW::Mechanize->new();
    my $twig= XML::Twig->new();
    $twig->output_encoding('UTF-8');

    return $mech, $twig;
}
=head2 fetch_pages

=cut

sub fetch_pages {
    my $self = shift;
    my ($mech, $twig) = $self->_init_mech();

    my $unfetched = $self->search({html => undef}); #{html => undef}
    
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

        $p->update;
        print "$no ".$p->id." fetched";
        sleep rand 10;
        $no++;
    }
    Dancer::debug "All done. $no added.";
}

=head2 fetch_data

=cut

sub fetch_data {
    my $self = shift;
    my ($url, $response, $content, $last);
    my ($mech, $twig) = $self->_init_mech();

    my $page = 1;
    for my $category ( reverse $self->categories() ){
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

            my $product = $self->find($sku);
            if ($id and !$product){
                $self->create({
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
        print "$added added and $skipped skipped\n";
    }   
}

=head2 parse_data

=cut

sub parse_data {
    my $self = shift;
    my ($mech, $twig) = _init_mech();

     
    my $fetched = $self->search({html => { '-not' => undef}, long_description => undef}); #description => undef
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
        print "$no ".$p->id." updated";
        $no++;
    }   
    
    print "All done";
    print "Error parsing: ".join ', ', @error;
}

=head2 categories

=cut

sub categories {
    my $self = shift;
    my ($mech, $twig) = _init_mech();

    my $shop_link = 'http://www.patagonia.com/us/shop/';
    my $url = "http://www.patagonia.com/us/home";

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

1;
