package Angler::Populate::Attribute;

use strict;
use warnings;

use Moo;
use MooX::Types::MooseLike::Base qw(:all);

=head1 NAME

Angler::Populate::Attribute

=head1 DESCRIPTION

This module provides population capabilities for the Attribute class.

=head1 SYNOPSIS


my @color_values = ['Red', 'Blue', 'Green'];

# or array of hash

my @size_values = (
    { value => 'xs', title => 'Extra Small', priority => '1'},
    { value => 's', title => 'Small', priority => '2'},
);

my $attributes = Angler::Populate::Attribute->new(
    schema => shop_schema,
    name   => 'color',
    title  => 'Color',
    values =>  @color_values
);

$attributes->add;

=cut

=head1 Attributes

=head2 name

Returns attribute name

=cut

has name => (
    is => 'ro',
    required => 1,
);

=head2 title

Returns attribute display title

=cut

has title => (
    is => 'ro',
    required => 1,
);

=head2 schema

L<Interchange6::Schema> object.

=cut

has schema => (
    is => 'ro',
    required => 1,
);

=head2 values

Return attribute values

=cut

has values => (
    is => 'ro',
    isa => ArrayRef,
    required => 1
);
=head2 add;

=cut

sub add {
    my ($self) = @_;
    my $schema = $self->schema;

    my $attribute = $schema->resultset('Attribute')->find_or_create(
        {
            name  => &clean_attribute_value($self->name),
            title => $self->title,
            type  => 'variant',
        },
        {
            key => 'attributes_name_type'
        }
    );

    foreach my $attribute_value (@{$self->values}) {
        my ($value, $title, $priority);

        if (ref($attribute_value) eq "HASH") {
            $value = $attribute_value->{value};
            $title = $attribute_value->{title};
            $priority = $attribute_value->{priority};
        }
        elsif (ref($attribute_value) eq "") {
            $value = $attribute_value;
            $title = ucfirst($attribute_value);
            $priority = '0';
        }
        else {
            die "Attribute values must be passed as an array or array of hashes";
        }

       $schema->resultset('AttributeValue')->find_or_create(
            {
                attributes_id => $attribute->attributes_id,
                value         => &clean_attribute_value($value),
                title         => $title,
                priority      => $priority
            },
            {
                key => 'attribute_values_attributes_id_value'
            }
        );
    }
}

=head2 clean_attribute_value($value)

return a cleaned up value for AttributeValue value column

=cut

sub clean_attribute_value {
    my $value = lc(shift);
    $value =~ s/\s+/_/g;
    return $value;
}

1;
