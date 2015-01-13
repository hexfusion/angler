package AjaxTest;
use Dancer ':syntax';
use Dancer::Plugin::Form;
use Dancer::Plugin::Interchange6;
use Dancer::Plugin::Interchange6::Routes;
use Dancer::Plugin::DBIC;
use Dancer::Plugin::Ajax;
use Dancer::Plugin::Auth::Extensible qw(
logged_in_user authenticate_user user_has_role require_role
require_login require_any_role
);

use Digest::MD5 qw(md5_hex);
use List::Util qw/shuffle/;
use File::Slurp qw(read_file write_file);
use JSON::XS;
use AjaxTest::Captcha;
use AjaxTest::Captcha::Image;
use AjaxTest::Captcha::Answer;
our $VERSION = '0.1';

set session_options => {schema => schema};

get '/' => sub {
    template 'index';
};

#get '/start/:how_many' => sub {
#    my $self = shift;
#    my $qty = params->{how_many};
#    debug "start how_many: ", $qty;

#    my %test;

#    return to_json {"values" => ["04c1516f014d6224ba90","3f7303f0c6068ec1b68d","620f6c1831415e0a41ff","0d7778eb970540dc4100","fe89ecfead566c8c1930"],
#                        "imageName"=>"Scissors",
#                        "imageFieldName" => "c3ba1d134026ca2cb62b",
#                        "audioFieldName" => "ae3cae8c83edf3e09d3d"};
#};

get '/start/:how_many' => sub {
    my $self = shift;
    my $n = params->{how_many};
    my %data;

    # define basic params or fallback to defaults
    my $audio_json = config->{captcha}{default_audios};
    my $image_json = config->{captcha}{default_images};
    my $assets_path = config->{captcha}{assets_path};

    # attach the session id to captcha
    $data{sessions_id} = session 'id';

    # FIXME lets do this with a nice loop

    if(defined($audio_json)) {
        $data{default_images} = $image_json;
    };

    if(defined($audio_json)) {
        $data{default_audios} = $audio_json;
    };
    
    if(defined($assets_path)) {
        $data{assets_path} = $assets_path;
    };

    my $captcha = AjaxTest::Captcha->new(\%data);
    my $public_dir = Dancer::FileUtils::path(setting('appdir'), 'public');

    my $filename = ($public_dir . '/' . $captcha->{assets_path} . '/' .  $captcha->{default_images});

    my $json = -e $filename ? read_file $filename : '{}';
    # debug "Json file : ", $json;

    my @decoded_json = decode_json($json);

    my @shuffled = shuffle(@{$decoded_json[0]});
    # debug "Shuffeled: ", \@shuffled;

    # remove current answer if one already exists

#    @shuffled = grep { $_->{name} != $answer{image_name} } @shuffled;

    # randomly select one of the n records as the answer
    my $random = int(rand($n)) +1;

    my %answer;

    $answer{audio_field_name} = substr(md5_hex(rand), 0, 20);
    $answer{image_field_name} = substr(md5_hex(rand), 0, 20);

    # populate random images from dataset
    for my $j (1 .. $n) {
        for my $aref (\@shuffled) {
            my $image_name = $aref->[$j]{name};

            # this is the answer!
            if ($j == $random) {
                $answer{image_name} = $image_name;
            }

            # add sha1_hex(20) name to image
            $aref->[$j]{value} = substr(md5_hex(rand), 0, 20);

            # create image object and add to captcha
            $captcha->add_image(\%{$aref->[$j]});
        }
    }

    # create answer object and add to captcha
    $captcha->add_answer(\%answer);

    # send data back to the front end
    return to_json $captcha->front_end_data;
};

get '/image/:index' => sub {
    my $self = shift;
    my $index = params->{index};
    my $public_dir = Dancer::FileUtils::path(setting('appdir'), 'public');
    my $filename = ($public_dir . '/assets/images/');
    # FIXME $image is hard coded
    my $image = 'foo.jpg';
    return send_file( config->{captcha}{assets_path} . '/images/' . $image );


    debug "image index: ", $index;
};

get '/:audio:r:type' => sub {
    my $self = shift;
    my $file_name = params->{r};
    my $type = params->{type} || undef;
    debug "audio file_name type: ", $file_name , $type;
};

true;
