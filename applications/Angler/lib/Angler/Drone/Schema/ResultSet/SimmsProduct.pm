use utf8;

package Angler::Drone::Schema::ResultSet::SimmsProduct;

=head1 NAME

Interchange6::Schema::ResultSet::Product

=cut

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

use parent 'Angler::Drone::Schema::ResultSet';


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

=head2 _init_mech

=cut

sub _init_mech {
    my $mech = WWW::Mechanize->new();
    my $twig= XML::Twig->new();
    $twig->output_encoding('UTF-8');

    return $mech, $twig;
}

=head2 fetch_simms

=cut

sub fetch_simms {
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

1;
