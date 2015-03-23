package Angler::Interchange6::Cart;

use Moo;
extends 'Dancer::Plugin::Interchange6::Cart';
use Dancer qw/debug error warning/;
use Dancer::Plugin::DBIC;

=head1 NAME

Angler::Interchange6::Cart - extends Dancer::Plugin::Interchange6::Cart

=head1 METHODS

=head2 get_navigation_weight( $sku );

Called in L</add> and L</seed> modifiers to lookup weight via navigation
when product has undef weight.

=cut

sub get_navigation_weight {
    my $sku = shift;

    my $weight;

    eval {
        $weight =
          schema->resultset('Product')->find($sku)->search_related(
            'navigation_products',
            {
                'attribute.name' => 'weight',
                'attribute.type' => 'navigation',
            },
            {
                columns    => [],
                '+columns' => { weight => 'attribute_value.value' },
                join       => {
                    navigation => {
                        navigation_attributes => [
                            'attribute',
                            {
                                navigation_attribute_values =>
                                  'attribute_value'
                            }
                        ]
                    }
                },
                order_by => { -desc => 'navigation.priority' },
                rows     => 1,
            }
          )->single->get_column('weight');
    };
    if ($@) {
        warning "KABOOM! in Cart set_navigation_weight: ", $@;
    }
    warning "No navigation weight found for $sku" unless defined $weight;
    return $weight;
}

=head2 add

Add an C<around> method modifier to C<add> to add weights from nav when
product weight is undef.

=cut

around 'add' => sub {
    my $orig = shift;
    my $ret  = $orig->(@_);
    if ( ref($ret) eq 'ARRAY' ) {
        foreach my $cart_product (@$ret) {
            if ( !defined $cart_product->weight ) {

                my $sku =
                    $cart_product->canonical_sku
                  ? $cart_product->canonical_sku
                  : $cart_product->sku;

                $cart_product->set_weight( &get_navigation_weight($sku) );
            }
        }
    }
    return $ret;
};

=head2 seed

Add a C<before> method modifier to C<seed> to add weights from nav when
product weight is undef.

=cut

before 'seed' => sub {
    my ( $self, $products ) = @_;
    if ( $products && ref($products) eq 'ARRAY' ) {
        foreach my $product ( @$products ) {
                my $sku =
                    $product->{canonical_sku}
                  ? $product->{canonical_sku}
                  : $product->{sku};

            $product->{weight} = &get_navigation_weight($sku);
            use Data::Dumper::Concise;
            print Dumper( $product );
        }
    }
};

1;
