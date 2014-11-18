package AjaxTest::Captcha;

use Moo;
use AjaxTest::Types;
use MooX::HandlesVia;
use Scalar::Util 'blessed';
use namespace::clean;

=head2 sessions_id

The session ID for the captcha.

=cut

has sessions_id => (
    is => 'rw',
);

=head2 name

The captcha name. Default is 'captcha'.  Multiple captchas on same page require differnt names

=cut

has name => (
    is => 'rw',
    isa => AllOf [ Defined, NotEmpty, VarChar [255] ],
    default => 'captcha',
    required => 1,
);

=head2 asset_path

The assets_path is the public location that media for the captcha is located. 
By default, it will be ./assets

=cut

has assets_path => (
    is => 'rw',
    isa => AllOf [ NotEmpty, VarChar [255] ],
    default => '/assets',
    required => 1,
);

=head2 default_images

default_images is optional. Defaults to the array inside ./images.json.
The path is relative to ./assets/images/

=cut

has default_images => (
    is => 'rw',
    isa => AllOf [ NotEmpty, VarChar [255] ],
    default => '/images.json',
    required => 1,
);

=head2 default_audios

default_audios is optional. Defaults to the array inside ./audios.json.
The path is relative to ./assets/audios/

=cut

has default_audios => (
    is => 'rw',
    isa => AllOf [ NotEmpty, VarChar [255] ],
    default => '/audios.json',
    required => 1,
);

=head2 image_options

=cut

has image_options => (
    is => 'rwp',
    isa => ArrayRef [ InstanceOf ['AjaxTest::Captcha::Image'] ],
    default => sub { [] },
    handles_via => 'Array',
    handles => {
        clear => 'clear',
        count => 'count',
        image_get => 'get',
        _image_set => 'set',
        _image_push => 'push',
    },
    init_arg => undef,
);

=head2 add($image)

Add image to the captcha. Returns image object in case of success.

The image is an L<AjaxTest::Captcha::Image> or a hash (reference) of image attributes that would be passed to AjaxTest::Captcha::Image->new().

=cut

sub add {
    my $self    = shift;
    my $image = $_[0];

    if ( blessed($image) ) {
        die "image argument is not an AjaxTest::Captcha::Image"
          unless ( $image->isa('AjaxTest::Captcha::Image') );
    }
    else {

        # we got a hash(ref) rather than an Image

        my %args;

        if ( is_HashRef($image) ) {

            # copy args
            %args = %{$image};
        }
        else {

            %args = @_;
        }

        $image = 'AjaxTest::Captcha::Image'->new(%args);

        unless ( blessed($image)
            && $image->isa('AjaxTest::Captcha::Image') )
        {
            die "failed to create image.";
        }
    }
    # add image
    $self->_image_push($image);

    return $image;
}

1;
