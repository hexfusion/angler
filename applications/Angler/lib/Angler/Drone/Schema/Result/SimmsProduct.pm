use utf8;
package Angler::Drone::Schema::Result::SimmsProduct;

=head1 NAME

Angler::Drone::Schema::Result::SimmsProduct

=cut


use Angler::Drone::Schema::Candy -components =>
  [qw(InflateColumn::DateTime TimeStamp)];

=head2 id

Primary key.

=cut

primary_column id => {
    data_type     => "varchar",
    default_value => "",
    size          => 90,
};

=head2 name

Product name.

=cut

column name => {
    data_type     => "varchar",
    default_value => "",
    size          => 90,
};

=head2 sku

Product sku.

=cut

column sku => {
    data_type     => "varchar",
    default_value => "",
    size          => 90,
};

=head2 price

Product price.

=cut

column price => {
    data_type     => "numeric",
    default_value => "0.0",
    size          => [ 10, 2 ],
};

=head2 rating

Numeric average rating.

=cut

column rating => {
    data_type         => "numeric",
    default_value     => 0,
    size              => [ 4, 2 ],
};

=head2 img

Product image.

=cut

column img => {
    data_type     => "text",
    nullable      => 1
};

=head2 description

Full product description.

=cut

column description => {
    data_type     => "text",
    nullable      => 1
};

=head2 features

Product features.

=cut

column featuress => {
    data_type     => "text",
    nullable      => 1
};

=head2 product_care

Product care.

=cut

column product_care => {
    data_type     => "text",
    nullable      => 1
};

=head2 technologies

Product technologies

=cut

column technologies => {
    data_type     => "text",
    nullable      => 1
};

=head2 videos

Product videos

=cut

column videos => {
    data_type     => "text",
    nullable      => 1
};

=head2 html

Product html

=cut

column html => {
    data_type     => "text",
    nullable      => 1
};

=head2 created

Date and time when this record was created returned as L<DateTime> object.
Value is auto-set on insert.

=cut

column created => {
    data_type     => "datetime",
    set_on_create => 1
};

=head2 last_modified

Date and time when this record was last modified returned as L<DateTime> object.
Value is auto-set on insert and update.

=cut

column last_modified => {
    data_type     => "datetime",
    set_on_create => 1,
    set_on_update => 1
};

1;
