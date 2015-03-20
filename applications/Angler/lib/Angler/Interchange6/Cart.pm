package Angler::Interchange6::Cart;

use Moo;
extends 'Dancer::Plugin::Interchange6::Cart';
use Dancer qw/debug error warning/;
use Dancer::Plugin::DBIC;

=head1 NAME

Angler::Interchange6::Cart - extends Dancer::Plugin::Interchange6::Cart

=head1 METHODS

=head2 set_navigation_weight( $cart_product );

Called in L</add> and L</BUILD> modifier to lookup weight via navigation
when product has undef weight.

=cut

sub set_navigation_weight {
    my $cart_product = shift;

    my $sku =
        $cart_product->canonical_sku
      ? $cart_product->canonical_sku
      : $cart_product->sku;

    eval {
        my $weight =
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

        if ( defined $weight ) {
            $cart_product->set_weight($weight);
        }
        else {
            warning "No navigation weight found for $sku";
        }
    };
    if ($@) {
        warning "KABOOM! in Cart set_navigation_weight: ", $@;
    }
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
                &set_navigation_weight($cart_product);
            }
        }
    }
    return $ret;
};

=head2 BUILD

Add an C<after> method modifier to C<BUILD> to add weights from nav when
product weight is undef.

=cut

after 'BUILD' => sub {
    my $self = shift;

    foreach my $cart_product ( $self->products_array ) {
        if ( !defined $cart_product->weight ) {
            &set_navigation_weight($cart_product);
        }
    }
};

1;
