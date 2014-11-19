package AjaxTest::Captcha::Answer;

use Moo;
use AjaxTest::Types;
use namespace::clean;

=head2 image_name

This is the name of the image answer

=cut

has image_name => (
    is => 'rw',
    isa => AllOf [ Defined, NotEmpty, VarChar [255] ],
    required => 1,
);

=head2 image_field_name

SHA1 name of the image field to pass to the form

=cut

has image_field_name => (
    is => 'rw',
    isa => AllOf [ Defined, NotEmpty, VarChar [255] ],
    required => 1,
);

=head2 audio_field_name

SHA1 name of the audio field to pass to the form

=cut

has audio_field_name => (
    is => 'rw',
    isa => AllOf [ Defined, NotEmpty, VarChar [255] ],
    required => 1,
);

1;
