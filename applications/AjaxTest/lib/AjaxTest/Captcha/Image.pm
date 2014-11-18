package AjaxTest::Captcha::Image;

use Moo;
use AjaxTest::Types;
use namespace::clean;

=head2 index

The image index.

=cut

has index => (
    is => 'rw',
    isa => Int,
);

=head2 name 

The image name. 

=cut

has name => (
    is => 'rw',
    isa => AllOf [ Defined, NotEmpty, VarChar [255] ],
    required => 1,
);

=head2 name

The image name.

=cut

has path => (
    is => 'rw',
    isa => AllOf [ Defined, NotEmpty, VarChar [255] ],
    required => 1,
);

=head2 value

The image value is an SHA1(12) representation of the name.

=cut

has value => (
    is => 'rw',
    isa => AllOf [ Defined, NotEmpty, VarChar [255] ],
    required => 1,
);

1;
