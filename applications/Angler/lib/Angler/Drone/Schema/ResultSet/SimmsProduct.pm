use utf8;

package Angler::Drone::Schema::ResultSet::SimmsProduct;

=head1 NAME

Interchange6::Schema::ResultSet::Product

=cut

use open ':std', ':encoding(UTF-8)';
use WWW::Mechanize;
use XML::Twig;
use Try::Tiny;
use YAML qw(DumpFile LoadFile freeze thaw);
use XML::Twig;

=head1 SYNOPSIS

Provides extra accessor methods for L<Angler::Drone::Schema::ResultSet::SimmsProduct>

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

    my (@podjetja, $url, $response, $content, $last);
    my ($mech, $twig) = $self->_init_mech();
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

            if ($id and !$self->find($id)){
                $self->create({
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
    Dancer::debug "All done. $total_added added.";
}


=head2 fetch_data

=cut

sub fetch_data {
    my $self = shift;
    my ($mech, $twig) = $self->_init_mech();

    my $unfetched = $self->search(); #{html => undef}

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
            $p->name(  trim $n->text );
            last
        }
        # SKU
        for my $n ($twig->find_nodes('//[@class="product-ids"]')){
            $p->sku( substr  trim($n->text), 7 );
            last;
        }
        # Price
        for my $n ($twig->find_nodes('//[@class="price"]')){
            $p->price( substr  trim($n->text), 1 );
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
        Dancer::debug "$no ".$p->id." fetched";
        $no++;
    }
}

=head2 parse_data

=cut

sub parse_data {
    my $self = shift;
    my ($mech, $twig) = _init_mech();

    my $fetched = $self->search({html => { '-not' => undef}, description => undef});

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
        Dancer::debug "$no ".$p->id." updated";
        $no++;
    }

    Dancer::debug "All done";

}

1;
