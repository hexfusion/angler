package Angler::Routes::Photo;

use Cache::FileCache;
use Dancer ':syntax';
use Dancer::Plugin::Database;
use File::Type;
use HTTP::Date qw(time2str);
use Imager;

our $VERSION = '0.1';

prefix '/photo';

# a photo with the photo name included in the file name

get qr{
       (?<dir>  .*?)
    /  (?<id>   \d+?) _.+?
    \. (?<type> \w+?) $
}x => sub {
    my $c = captures;
    my $path = path( "/photo", $$c{dir}, $$c{id} . '.' . $$c{type} );
    forward $path;
};

# maxheight set

get '/maxheight/:y/:file' => sub {
    return pass if params->{y} !~ /^\d+$/;
    forward "/photo/" . params->{file}, { y => params->{y} };
};

# look for a size

get '/:size/:file' => sub {

    my ( $x, $y, $thumb ) = ( 0, 0, 0);

    my %sizes = (
        small  => 250,
        medium => 400,
        large  => 600,
    );

    if ( params->{size} eq 'thumb' ) {
        $thumb = 1;
    }
    elsif ( $sizes{ params->{size} } ) {

        # a named size
        $x = $y = $sizes{ params->{size} };
    }
    elsif ( params->{size} =~ /^\d+$/ ) {

        # pixel limit on both x and y
        $x = $y = params->{size};
    }
    elsif ( params->{size} =~ /^(\d+)x(\d+)$/ ) {

        # pixel limit woth x and y supplied
        ( $x, $y ) = ( $1, $2 );
    }
    else {

        # anything else is invalid
        return pass;
    }

    forward "/photo/" . params->{file}, { x => $x, y => $y, thumb => $thumb };
};

# just the image (may have been called from another patch with params)

get '/*.*' => sub {
    my ( $id, $type ) = splat;

    # fail if id is not an integer
    return pass if $id !~ /^\d+$/;

    # x and y default to 0 if not set

    my $x = params->{x} ? params->{x} : 0;
    my $y = params->{y} ? params->{y} : 0;
    my $thumb = params->{thumb} ? params->{thumb} : 0;

    # setup our image cache

    my $cache = Cache::FileCache->new(
        {
            namespace  => '/photo',
            cache_root => path( config->{appdir}, "data/cache" ),
        }
    );

    # construct the key, check the cache and send the image back if we got it

    my $key  = "$id|$type|$x|$y|$thumb";
    my $data = $cache->get($key);

    if ($data) {
        debug "Photo found in cache with key $key";
        &send_image( $type, $data );
    }

    # we got this far so no cached image exists - check for id in db 1st

    my $photo =
      database->quick_select( 'photos', { id => $id, type => $type } );

    return pass unless $photo;

    # the original image file

    my $file = "/data/image_archive/$id.$type";

    return pass unless -r $file;

    my $img = Imager->new();
    $img->read( file => $file ) or return pass;

    # do we need to resize?

    if ( $thumb ) {
        $img = $img->scale( ypixels => 84, xpixels => 112 );
        $img = $img->crop( left => 0, top => 0, bottom => 84, right => 112 );
    }
    elsif ( $x || $y ) {

        my $width  = $img->getwidth;
        my $height = $img->getheight;

        if (( not $x ) && ( $y < $height )) {
            $img = $img->scale( ypixels => $y );
        }
        elsif (( not $y ) && ( $x < $width )) {
            $img = $img->scale( xpixels => $x );
        }
        else {
            my $ratio    = $width / $height;
            my $newratio = $x / $y;
            if ( $ratio > $newratio && $width > $x ) {
                $img = $img->scale( xpixels => $x );
            }
            elsif ( $height > $y ) {
                $img = $img->scale( ypixels => $y );
            }
        }
    }

    # do we need to add copyright, etc?

    if ( $img->getwidth > 165 ) {

        $photo->{mydate} =~ m/^(\d{4,}?)\-/;
        my $year = $1;
        return pass unless $year;

        my $copy = chr(169) . $year;

        my $photographer =
          database->quick_select( 'photographers',
            { id => $photo->{photographer} },
          );

        if ( $photographer->{name} ) {
            if ( $photographer->{name} eq "Bruce Stidston" ) {
                $copy = '';
            }
            else {
                $copy .= " " . $photographer->{name};
            }
        }

        my $colour = Imager::Color->new("#FFFFFF");
        my $font =
          Imager::Font->new(
            file => '/usr/share/fonts/truetype/msttcorefonts/Verdana.ttf' );
        $img->align_string(
            font   => $font,
            text   => $copy,
            x      => $img->getwidth / 2,
            y      => $img->getheight() - 3,
            halign => 'center',
            valign => 'bottom',
            size   => 9,
            color  => $colour,
        );
        $img->align_string(
            font   => $font,
            text   => 'www.midiphotobank.com',
            x      => $img->getwidth / 2,
            y      => 3,
            halign => 'center',
            valign => 'top',
            size   => 10,
            color  => $colour,
        );
    }

    # write the image back into the $data buffer

    if ( $photo->{type} eq 'jpg' ) {

        # got a jpeg - don't use normal 75% quality setting

        $img->write(
            data        => \$data,
            type        => 'jpeg',
            jpegquality => 90
        );
    }
    else {
        $img->write( data => \$data, type => $type );
    }

    # cache and send out the image PUT THIS BACK IN
    $cache->set( $key => $data );
    &send_image( $type, $data );
};

sub send_image {
    my ( $type, $data ) = @_;

    my $maxage = 60 * 60 * 24;    # one day

    content_type mime->for_name($type);

    headers
    #'Cache-Control' => "max-age=$maxage, must-revalidate",
      'Cache-Control' => "max-age=$maxage",
      'Expires'       => time2str( time() + $maxage );

    return $data;
}

true;

