UserTag image-store Order
UserTag image-store addAttr
UserTag image-store Documentation <<EOD
#=head1 NAME
#
#[image-store]
#
#=head1 SYNOPSIS
#
#  [image-store
#  		outfile="filepath"
#		cgi=1*
#		bgcolor=("white"|"#ffffff")
#		align=("NorthWest"|"North"|"NorthEast"|"West"|"Center"|"East"|"SouthWest"|"South"|"SouthEast")*
#
#		imgblob=BLOB
#		imgfile="filepath"*
#		imgwidth=N*
#		imgheight=N*
#		imgbordercolor=("black"|"#000000")*
#		imgborderwidth=N*
#		imgborderheight=N*
#
#		logofile="filepath"*
#		logoblob=BLOB*
#		logoalign=("NorthWest"|"North"|"NorthEast"|"West"|"Center"|"East"|"SouthWest"|"South"|"SouthEast")*
#		logocompose=("Over"|"In"|"Out"|"Atop"|"Xor"|"Plus"|"Minus"|"Add"|"Subtract"|"Difference"|"Multiply"|"Bumpmap"|"Copy"|"CopyRed"|"CopyGreen"|"CopyBlue"|"CopyMatte"|"Dissolve"|"Clear"|"Displace"|"Modulate"|"Threshold")*
#
#		tmbfile="filepath"*
#		tmbwidth=N*
#		tmbheight=N*
#		tmbbordercolor=("black"|"#000000")*
#		tmbborderwidth=N*
#		tmbborderheight=N*
#
#		umask='022'*
#	]
#
#=head1 DESCRIPTION
#
# The [image-store] tag takes an image (file or blob) and 
# manipulates it to generate an image of specified width, height, 
# or both (maintaining aspect ratio). It also allows a logo to be 
# placed over this image (again file or blob). In all cases, a
# supplied blob input takes precidence over a supplied file path.
# 
# If the input image is a different shape to the output one,
# [image-store] creates a new image of the appropriate size, and 
# background color, and then places the input image on it with
# alignment according to 'align'.
# 
# If required, it generates a thumbnail (without logo) of defined 
# width, or height, or both (maintaining aspect ratio). The same 
# alignment rules as the image are applied to the thumbnail.
#
# It is also possible to specify a border color and width to be 
# placed around both the image and/or the thumb. You can specify
# the width (and/or height) in pixels, and the color (defaults to 
# black).
#
# The output file name's file extension will define the image type.
#
# All file paths are relative to the Interchange catalog root.
#
EOD

UserTag image-store Routine <<EOR
sub {
	use Image::Magick;

	my ($opt) = @_;

	my ($width, $height, $inwidth, $inheight, $err, $tmb);

	my @mapdirect = qw/
			bgcolor
			gravity
			imgblob
			imgfile
			imgwidth
			imgheight
			imgbordercolor
			imgborderwidth
			imgborderheight
			logofile
			logoblob
			logoalign
			logocompose
			tmbfile
			tmbwidth
			tmbheight
			tmbbordercolor
			tmbborderwidth
			tmbborderheight
			umask
			yes
			no
	/;

	if($opt->{cgi}) {
		for(@mapdirect) {
			next if ! defined $CGI->{$_};
			$opt->{$_} = $CGI->{$_};
		}
	}

	$opt->{yes} = $opt->{yes} || 1;
	$opt->{no} = $opt->{no} || '';
	$opt->{bgcolor} = $opt->{bgcolor} || 'white';
	$opt->{align} = $opt->{align} || 'center';
	$opt->{umask} = $opt->{umask} || '022';
	

	if (($opt->{imgheight} < 1) || ($opt->{imgwidth} < 1)) {
		logError("[image-store] error: silly imgwidth or imgheight: (%s x %s)", $opt->{imgwidth}, $opt->{imgheight});
		return $opt->{no};
	}

	my $image_in = Image::Magick->new;
	my $image_out = Image::Magick->new;

	# accept input of image data.
	if ($opt->{imgblob}) {
		$err = $image_in->BlobToImage($opt->{imgblob});
		if ($err) {
			logError("[image-store] Image::Magick error: %s", $err);
			return $opt->{no};
		}
	} elsif ($opt->{imgfile}) {
		my $fn = $opt->{imgfile};
		$fn =~ s/\.\.//g;
		$fn = "$Config->{VendRoot}/$fn";
		$err = $image_in->Read($fn);
		if ($err) {
			logError("[image-store] Image::Magick error: %s", $err);
			return $opt->{no};
		}
	} else {
		logError("[image-store] error: no input file or blob.");
		return $opt->{no};
	}

	($inwidth, $inheight) = $image_in->Get('width', 'height');
	if (($inwidth < 1) || ($inheight < 1)) {
		logError("error accepting image: width=%s, height=%s", $width, $height);
		return $opt->{no};
	}

	# make a copy in case we may need it for the thumb..
	my $img_copy = $image_in->Clone();

	# if an imgwidth and imgheight are both specified, we create the image exactly that size..
	if (($opt->{imgheight} > 0) && ($opt->{imgwidth} > 0)) {
		$err = $image_out->Set(size=>"$opt->{imgwidth}x$opt->{imgheight}");
		if ($err) {
			logError("[image-store] Image::Magick error: %s", $err);
			return $opt->{no};
		}
		$err = $image_out->Read("xc:$opt->{bgcolor}");
		if ($err) {
			logError("[image-store] Image::Magick error: %s", $err);
			return $opt->{no};
		}
		$err = $image_in->Set(background=>"$opt->{bgcolor}");
		if ($err) {
			logError("[image-store] Image::Magick error: %s", $err);
			return $opt->{no};
		}

		my $xratio = $inwidth / $opt->{imgwidth};
		my $yratio = $inheight / $opt->{imgheight};

		if (($xratio > 1) && ($xratio > $yratio)){
			# scale width to fit..
			$height = $inheight / $xratio;
			$err = $image_in->Scale(	width=>"$opt->{imgwidth}",
							height=>"$height");
			if ($err) {
				logError("[image-store] Image::Magick error: %s", $err);
				return $opt->{no};
			}
		} elsif (($yratio > 1) && ($yratio > $xratio)){
			# scale height to fit..
			$width = $inwidth / $yratio;
			$err = $image_in->Scale(	height=>"$opt->{imgheight}",
							width=>"$width");
			if ($err) {
				logError("[image-store] Image::Magick error: %s", $err);
				return $opt->{no};
			}
		}

		$err = $image_out->Composite(	image=>$image_in, 
						gravity=>"$opt->{align}");
		if ($err) {
			logError("[image-store] Image::Magick error: %s", $err);
			return $opt->{no};
		}

	} else {

		if ($opt->{imgwidth} > 0) {
			my $xratio = $inwidth / $opt->{imgwidth};
			$height = $inheight / $xratio;
			$err = $image_in->Scale(	width=>"$opt->{imgwidth}",
							height=>"$height");
			if ($err) {
				logError("[image-store] Image::Magick error: %s", $err);
				return $opt->{no};
			}
		} elsif ($opt->{imgheight} > 0) {
			my $yratio = $inheight / $opt->{imgheight};
			$width = $inwidth / $yratio;
			$err = $image_in->Scale(	height=>"$opt->{imgheight}",
							width=>"$width");
			if ($err) {
				logError("[image-store] Image::Magick error: %s", $err);
				return $opt->{no};
			}
		}
		$err = $image_out->Set('magick' => $image_in->Get('magick'));
		if ($err) {
			logError("[image-store] Image::Magick error: %s", $err);
			return $opt->{no};
		}
	}

	# now we create a thumbnail if we were asked to..
	if ($opt->{tmbfile}) {
		$opt->{tmbwidth} = $opt->{tmbwidth} || 96;
		$opt->{tmbheight} = $opt->{tmbheight} || (($opt->{imgheight} / $opt->{imgwidth}) * $opt->{tmbwidth});

		($width, $height) = $image_out->Get('width', 'height');

		# if the input image is smaller than the output one, 
		# we use the input one so the content of the thumb is bigger..

		$tmb = Image::Magick->new;

		$err = $tmb->Set(size=>"$opt->{tmbwidth}x$opt->{tmbheight}");
		if ($err) {
			logError("[image-store] Image::Magick error: %s", $err);
			return $opt->{no};
		}
		$err = $tmb->Read("xc:$opt->{bgcolor}");
		if ($err) {
			logError("[image-store] Image::Magick error: %s", $err);
			return $opt->{no};
		}
		$err = $tmb->Set(background=>"$opt->{bgcolor}");
		if ($err) {
			logError("[image-store] Image::Magick error: %s", $err);
			return $opt->{no};
		}

		my $xratio = $inwidth / $opt->{tmbwidth};
		my $yratio = $inheight / $opt->{tmbheight};

		if (($xratio >= 1) && ($xratio >= $yratio)){
			# scale width to fit..
			$height = $inheight / $xratio;
			$err = $img_copy->Scale(	height=>"$height",
							width=>"$opt->{tmbwidth}");
			if ($err) {
				logError("[image-store] Image::Magick error: %s", $err);
				return $opt->{no};
			}
		} elsif (($yratio > 1) && ($yratio > $xratio)){
			# scale height to fit..
			$width = $inwidth / $yratio;
			$err = $img_copy->Scale(	height=>"$opt->{tmbheight}",
							width=>"$width");
			if ($err) {
				logError("[image-store] Image::Magick error: %s", $err);
				return $opt->{no};
			}
		}

		$err = $tmb->Composite(	image=>$img_copy, 
					gravity=>"$opt->{align}");
		if ($err) {
			logError("[image-store] Image::Magick error: %s", $err);
			return $opt->{no};
		}

		# add a border..
		if ($opt->{tmbborderwidth}){
			$opt->{tmbbordercolor} = $opt->{tmbbordercolor} || '#000000';
			$opt->{tmbborderheight} = $opt->{tmbborderheight} || $opt->{tmbborderwidth};
			$err = $tmb->Border(	width=>"$opt->{tmbborderwidth}",
						height=>"$opt->{tmbborderheight}",
						fill=>"$opt->{tmbbordercolor}",
						);
			if ($err) {
				logError("[image-store] Image::Magick error: %s", $err);
				return $opt->{no};
			}
		}
		my $fn = $opt->{tmbfile};
		$fn =~ s/\.\.//g;
		$fn = "$Config->{VendRoot}/$fn";
		$opt->{umask} = umask oct($opt->{umask});
		$err = $tmb->Write($fn);
		$opt->{umask} = umask oct($opt->{umask});
		if ($err) {
			logError("[image-store] Image::Magick error: %s", $err);
			return $opt->{no};
		}
	}

	# now superimpose a logo on the main image if we were asked to..
	if ($opt->{logofile} || $opt->{logoblob}){
		$opt->{logocompose} = $opt->{logocompose} || 'over';
		$opt->{logoalign} = $opt->{logoalign} || 'SouthEast';
		my $logo = Image::Magick->new;

		if ($opt->{logoblob}) {
			$err = $logo->BlobToImage($opt->{logoblob});
			if ($err) {
				logError("[image-store] Image::Magick error: %s", $err);
				return $opt->{no};
			}
		} elsif ($opt->{logofile}) {
			my $fn = $opt->{logofile};
			$fn =~ s/\.\.//g;
			$fn = "$Config->{VendRoot}/$fn";
			$err = $logo->Read($fn);
			if ($err) {
				logError("[image-store] Image::Magick error: %s", $err);
				return $opt->{no};
			}
		}

		# superimpose on the main image..
		$err = $image_out->Composite(	image=>$logo,
						compose=>"$opt->{logocompose}",
						gravity=>"$opt->{logoalign}",
						);
		if ($err) {
			logError("[image-store] Image::Magick error: %s", $err);
			return $opt->{no};
		}
	}

	# add a border..
	if ($opt->{imgborderwidth}){
		$opt->{imgbordercolor} = $opt->{imgbordercolor} || '#000000';
		$opt->{imgborderheight} = $opt->{imgborderheight} || $opt->{imgborderwidth};
		$err = $image_out->Border(	width=>"$opt->{imgborderwidth}",
						height=>"$opt->{imgborderheight}",
						fill=>"$opt->{imgbordercolor}",
						);
		if ($err) {
			logError("[image-store] Image::Magick error: %s", $err);
			return $opt->{no};
		}
	}

	# finally, write the main image to file..
	my $fn = $opt->{outfile};
	$fn =~ s/\.\.//g;
	$fn = "$Config->{VendRoot}/$fn";
	$opt->{umask} = umask oct($opt->{umask});
	$err = $image_out->Write($fn);
	$opt->{umask} = umask oct($opt->{umask});
	if ($err) {
		logError("[image-store] Image::Magick error: %s", $err);
		return $opt->{no};
	}

	return $opt->{yes};
}
EOR
