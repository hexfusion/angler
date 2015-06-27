use utf8;

package Angler::Interchange6::Schema::Result::ProductRedirect;

=head1 NAME

Angler::Interchange6::Schema::Result::ProductRedirect

=cut

use Interchange6::Schema::Candy -components => [qw(InflateColumn::DateTime TimeStamp)];

=head1 DESCRIPTION

The ProductRedirect class facilitates a mapping of uri to $product redirects.
This is useful in the case of cleaning up SEO, dead links, etc.

=head1 ACCESSORS

=head2 product_redirect_id

Primary key.

=cut

primary_column product_redirect_id => {
    data_type         => "integer",
    is_auto_increment => 1,
    sequence          => "product_redirect_product_redirect_id_seq",
};

=head2 uri

The uri used to match a product redirect

=cut

unique_column uri => {
    data_type     => "varchar",
    size          => 255
};

=head2 sku

Foreign constraint on L<Interchange6::Schema::Result::Product/sku>
via L</product> relationship.

=cut

column sku => {
    data_type      => "varchar",
    is_foreign_key => 1,
    size           => 64
};

=head2 status_code

Integer representing the http status code
ie '301' - Moved Permanently

=cut

column status_code => {
    data_type     => "integer",
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

=head1 RELATIONS

=head2 product

Type: belongs_to

Related object: L<Interchange6::Schema::Result::Product>

=cut

belongs_to
  product => "Interchange6::Schema::Result::Product",
  "sku",
  { is_deferrable => 1, on_delete => "CASCADE", on_update => "CASCADE" };

1;
