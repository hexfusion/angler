# Copyright 2002 Interchange Development Group (http://www.icdevgroup.org/)
# Licensed under the GNU GPL v2. See file LICENSE for details.
# $Id: image.tag,v 1.10 2004/10/16 17:59:20 docelic Exp $

UserTag image Order src
UserTag image AddAttr
UserTag image Routine <<EOR
sub {
	my ($src, $opt) = @_;
	my ($image, $path, $secure, $sku);
	my ($imagedircurrent, $imagedir, $imagedirsecure);

	my @descriptionfields = grep /\S/, split /\s+/,
		$opt->{descriptionfields} || $::Variable->{DESCRIPTIONFIELDS};
	@descriptionfields = qw( description ) if ! @descriptionfields;

	my @imagefields = qw( image );
	my @imagesuffixes = qw( jpg gif png jpeg );
	my $filere = qr/\.\w{2,4}$/;
	my $absurlre = qr/^(?i:https?)/;

	if ($opt->{ui}) {
		# unless no image dir specified, add locale string
		my $locale = $Scratch->{mv_locale} ? $Scratch->{mv_locale} : 'en_US';
		$imagedir		= $::Variable->{UI_IMAGE_DIR}
						|| $Global::Variable->{UI_IMAGE_DIR};
		$imagedirsecure	= $::Variable->{UI_IMAGE_DIR}
						|| $Global::Variable->{UI_IMAGE_DIR};
		for ($imagedir, $imagedirsecure) {
			if ($_) {
				$_ .= '/' if substr($_, -1, 1) ne '/';
				$_ .= $locale . '/';
			}
		}
	} else {
		$imagedir		= $Vend::Cfg->{ImageDir};
		$imagedirsecure	= $Vend::Cfg->{ImageDirSecure} || $imagedir ;
	}

	# make sure there's a trailing slash on directories
	for ($imagedir, $imagedirsecure) {
		$_ .= '/' if $_ and substr($_, -1, 1) ne '/';
	}

	if (defined $opt->{secure}) {
		$secure = $opt->{secure} ? 1 : 0;
	} else {
		$secure = $CGI::secure;
	}

	$imagedircurrent = $secure ? $imagedirsecure : $imagedir;

	return $imagedircurrent if $opt->{dir_only};

	$opt->{getsize} = 1 unless defined $opt->{getsize};
	$opt->{imagesubdir} ||= $::Scratch->{mv_imagesubdir}
		if defined $::Scratch->{mv_imagesubdir};
	$opt->{default} ||= $::Scratch->{mv_imagedefault}
		if defined $::Scratch->{mv_imagedefault};

	if ($opt->{sku}) {
		$sku = $opt->{sku};
	} else {
		# assume src option is a sku if it doesn't look like a filename
		if ($src !~ /$filere/) {
			$sku = $src;
			undef $src;
		}
	}

	if($opt->{name_only} and $src) {
		return $src =~ /$absurlre/ ? $src : "$imagedircurrent$src";
	}

	if ($src =~ /$absurlre/) {
		# we have no way to check validity or create/read sizes of full URLs,
		# so we just assume they're good
		$image = $src;
	} else {

		my @srclist;
		push @srclist, $src if $src;
		if ($sku) {
			# check all products tables for image fields
			for ( @{$Vend::Cfg->{ProductFiles}} ) {
				my $db = Vend::Data::database_exists_ref($_)
					or die "Bad database $_?";
				$db = $db->ref();
				my $view = $db->row_hash($sku)
					if $db->record_exists($sku);
				if (ref $view eq 'HASH') {
					for (@imagefields) {
						push @srclist, $view->{$_} if $view->{$_};
					}
					# grab product description for alt attribute
					unless (defined $opt->{alt}) {
						for (@descriptionfields) {
							($opt->{alt} = $view->{$_}, last)
								if $view->{$_};
						}
					}
				}
			}
		}
		push @srclist, $sku if $sku;
		push @srclist, $opt->{default} if $opt->{default};

		if ($opt->{imagesubdir}) {
			$opt->{imagesubdir} .= '/' unless $opt->{imagesubdir} =~ m:/$:;
		}
		my $dr = $::Variable->{DOCROOT};
		my $id = $imagedircurrent;
		$id =~ s:/+$::;
		$id =~ s:/~[^/]+::;

		IMAGE_EXISTS:
		for my $try (@srclist) {
			($image = $try, last) if $try =~ /$absurlre/;
			$try = $opt->{imagesubdir} . $try;
			my @trylist;
			if ($try and $try !~ /$filere/) {
				@trylist = map { "$try.$_" } @imagesuffixes;
			} else {
				@trylist = ($try);
			}
			for (@trylist) {
				if ($id and m{^[^/]}) {
					if ($opt->{force} or ($dr and -f "$dr$id/$_")) {
						$image = $_;
						$path = "$dr$id/$_";
					}
				} elsif (m{^/}) {
					if ($opt->{force} or ($dr and -f "$dr/$_")) {
						$image = $_;
						$path = "$dr/$_";
					}
				}
				last IMAGE_EXISTS if $image;
			}
		}

		return unless $image;
		return 1 if $opt->{exists_only};

		my $mask;

		if($opt->{makesize} and $path) {
			my $dir = $path;
			$dir =~ s:/([^/]+$)::;
			my $fn = $1;
			my $siz = $opt->{makesize};
			MOGIT: {
				$siz =~ s/\W+//g;
				$siz =~ m{^\d+x\d+$}
					or do {
						logError("%s: Unable to make image with bad size '%s'", 'image tag', $siz);
						last MOGIT;
					};

				$dir .= "/$siz";
				
				my $newpath = "$dir/$fn";
				if(-f $newpath) {
					$image =~ s:(/?)([^/]+$):$1$siz/$2:;
					$path = $newpath;
					last MOGIT;
				}

				$mask = umask(02);

				unless(-d $dir) {
					File::Path::mkpath($dir);
				}

				my $mgkpath = $newpath;
				my $ext;
				$mgkpath =~ s/\.(\w+)$/.mgk/
					and $ext = $1;

				File::Copy::copy($path, $newpath)
					or do {
						logError("%s: Unable to create image '%s'", 'image tag', $newpath);
						last MOGIT;
					};
				my $exec = $Global::Variable->{IMAGE_MOGRIFY};
				if(! $exec) {
					my @dirs = split /:/, "/usr/X11R6/bin:$ENV{PATH}";
					for(@dirs) {
						next unless -x "$_/mogrify";
						 $exec = "$_/mogrify";
						 $Global::Variable->{IMAGE_MOGRIFY} = $exec;
						last;
					}
				}
				last MOGIT unless $exec;
				system "$exec -geometry $siz $newpath";
				if($?) {
					logError("%s: Unable to mogrify image '%s'", 'image tag', $newpath);
					last MOGIT;
				}

				if(-f $mgkpath) {
					rename $mgkpath, $newpath
						or die "Could not overwrite image with new one!";
				}
				$image =~ s:(/?)([^/]+$):$1$siz/$2:;
				$path = $newpath;
			}
		}

		umask($mask) if defined $mask;

		if ($opt->{getsize} and $path) {
			eval {
				require Image::Size;
				my ($width, $height) = Image::Size::imgsize($path);
				($opt->{width}, $opt->{height}) = ($width, $height)
					if $width and $height;
			};
		}
	}

	$image = $imagedircurrent . $image unless
		$image =~ /$absurlre/ or substr($image, 0, 1) eq '/';

	return $image if $opt->{src_only};

	$opt->{title} = $opt->{alt} if ! defined $opt->{title} and $opt->{alt};

	my $opts = '';
	for (qw: width height alt title border hspace vspace align :) {
		if (defined $opt->{$_}) {
			my $val = $opt->{$_};
			$val = HTML::Entities::encode($val) if $val =~ /\W/;
			$opts .= qq{ $_="$val"};
		}
	}
	if($opt->{extra}) {
		$opts .= " $opt->{extra}";
	}
	$image =~ s/"/&quot;/g;
	return qq{<img src="$image"$opts>};
}
EOR

