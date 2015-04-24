use utf8;
package Angler::Drone::Schema::Result::PatagoniaProduct;

=head1 NAME

Angler::Drone::Schema::Result::PatagoniaProduct

=cut

use DateTime;

use Angler::Drone::Schema::Candy -components =>
  [qw(InflateColumn::DateTime TimeStamp)];

=head2 sku

Primary key.

=cut

primary_column sku => {
    data_type     => "varchar",
    size          => 90,
};

=head2 id

Product id

=cut

column id => {
    data_type     => "varchar",
    is_nullable => 1,
    size          => 90,
};

=head2 size

Product size

=cut

column size => {
    data_type     => "varchar",
    is_nullable => 1,
    size          => 90,
};

=head2 color

Product color

=cut

column color => {
    data_type     => "varchar",
    is_nullable => 1,
    size          => 90,
};

=head2 color_name

Product color_name

=cut

column color_name => {
    data_type     => "varchar",
    is_nullable => 1,
    size          => 90,
};

=head2 path

Product path

=cut

column path => {
    data_type     => "varchar",
    is_nullable => 1,
    size          => 90,
};

=head2 category_name

Product category_name

=cut

column category_name => {
    data_type     => "varchar",
    is_nullable => 1,
    size          => 90,
};

=head2 category_id

Product category_id

=cut

column category_id => {
    data_type     => "varchar",
    is_nullable => 1,
    size          => 90,
};

=head2 name

Product name.

=cut

column name => {
    data_type     => "varchar",
    is_nullable => 1,
    size          => 90,
};

=head2 fit

Product fit.

=cut

column fit => {
    data_type     => "varchar",
    is_nullable => 1,
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
    is_nullable      => 1
};

=head2 sizes

Product sizes.

=cut

column sizes => {
    data_type     => "text",
    is_nullable => 1
};

=head2 description

Intro product description.

=cut

column description => {
    data_type     => "text",
    is_nullable      => 1
};

=head2 long_description

Long product description.

=cut

column long_description => {
    data_type     => "text",
    is_nullable      => 1
};


=head2 features

Product features.

=cut

column features => {
    data_type   => "text",
    is_nullable => 1
};

=head2 videos

Product videos

=cut

column videos => {
    data_type     => "text",
    is_nullable      => 1
};

=head2 html

Product html

=cut

column html => {
    data_type     => "text",
    is_nullable      => 1
};

=head2 similar

Product similar

=cut

column similar => {
    data_type     => "text",
    is_nullable      => 1
};

=head2 similar_js

Product similar_js

=cut

column similar_js => {
    data_type     => "text",
    is_nullable      => 1
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

iDate and time when this record was last modified returned as L<DateTime> object.
Value is auto-set on insert and update.

=cut

column last_modified => {
    data_type     => "datetime",
    set_on_create => 1,
    set_on_update => 1
};

=head1 METHODS

=head2 add_color

=cut

sub add_color {
    my ($self, $color) = @_;
    return 0 unless $color;
    my @colors = $self->_colors ? split '\|', $self->_colors : ();
    my %color_hash = map {$_ => 1} @colors;
    unless ($color_hash{$color}){ 
        push @colors, $color;
        $self->_colors(join '|', @colors);
        return 1;
    }
    return 0;
}

=head2 colors

=cut

sub colors {
    my ($self) = @_;
    my @colors = $self->_colors ? split '|', $self->_colors : ();   
    return @colors;
}


1;
