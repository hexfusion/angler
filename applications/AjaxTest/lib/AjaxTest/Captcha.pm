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
        map_images    => 'map',
        images_array => 'elements',
        image_clear => 'clear',
        image_count => 'count',
        image_get => 'get',
        _image_set => 'set',
        _image_push => 'push',
    },
    init_arg => undef,
);

=head2 answer

This is the front end data that is passed back to the captcha 

=cut

has answer => (
    is => 'rwp',
    isa => ArrayRef [ InstanceOf ['AjaxTest::Captcha::Answer'] ],
    default => sub { [] },
    handles_via => 'Array',
    handles => {
        answer_clear => 'clear',
        answer_count => 'count',
        answer_get => 'get',
        _answer_set => 'set',
        _answer_push => 'push',
    },
    init_arg => undef,
);

=head2 add_image($data)

Add image to the captcha. Returns image object in case of success.

The image is an L<AjaxTest::Captcha::Image> or a hash (reference) of image attributes that would be passed to AjaxTest::Captcha::Image->new().

=cut

sub add_image {
    my $self    = shift;
    my $image = $_[0];

    if ( blessed($image) ) {
        die "data argument is not a AjaxTest::Captcha::Image"
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

=head2 add_answer($data)

Add image to the captcha. Returns image object in case of success.

The image is an L<AjaxTest::Captcha::Image> or a hash (reference) of image attributes that would be passed to AjaxTest::Captcha::Image->new().

=cut

sub add_answer {
    my $self    = shift;
    my $answer = $_[0];

    if ( blessed($answer) ) {
        die "answer argument is not a AjaxTest::Captcha::Answer"
          unless ( $answer->isa('AjaxTest::Captcha::Answer') );
    }
    else {

        # we got a hash(ref) rather than an Image

        my %args;

        if ( is_HashRef($answer) ) {

            # copy args
            %args = %{$answer};
        }
        else {

            %args = @_;
        }

        $answer = 'AjaxTest::Captcha::Answer'->new(%args);

        unless ( blessed($answer)
            && $answer->isa('AjaxTest::Captcha::Answer') )
        {
            die "failed to create answer.";
        }
    }
    # add image
    $self->_answer_push($answer);

    return $answer;
}

sub front_end_data {
    my $self    = shift;
    my @images;

    push @images , $self->map_images( sub { $_->value } ); 
    my %data = (
        values => \@images,
        imageName => $self->answer_get(0)->image_name,
        imageFieldName => $self->answer_get(0)->image_field_name,
        audioFieldName => $self->answer_get(0)->audio_field_name,
        
    );

    return \%data;
};

1;
