UserTag generate_password Interpolate
UserTag generate_password Routine <<EOR
sub {
  my $dict = '/usr/share/dict/words'; # path to dict file
  my $wordlen = 10;             # desired length of the password
  my $sublen = 4;               # length of the word chunks that create the password
  my $inline ;                  #
  my $sep = "\n" ;              # Line separator
  my @dict;

  $wordlen >= $sublen || die "Error: The word length should be equal or
larger than the length of the 'chunks'\n";

  $inline = ::readfile($dict) if $dict;
  my @dict = split /$sep/, $inline;

  my @sub = ();
  my $word = '';
  my $parts = int ($wordlen/$sublen);

  for (my $i=0;$i < $parts; $i++) {
    do {
      $sub[$i] = substr ($dict[int (rand @dict)], 0, $sublen);
    }
    until (length $sub[$i] == $sublen);
    $word .= lc $sub[$i];
  }

  my $left = $wordlen % $sublen;
  $word .= substr (int rand (10**($wordlen - 1)), 0, $left);

  return $word ;
}
EOR
