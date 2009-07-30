UserTag create-pdf Routine <<EOR
sub {
    my $infile = catfile($Vend::Cfg->{ScratchDir}, "$Vend::Session->{id}.pdf");
    my $outfile = catfile($Vend::Cfg->{ScratchDir}, "$Vend::Session->{id}.pdf");
    open PAGE, "> $infile"
        or die "Cannot create pagefile $infile: $!";
    for(@Vend::Output) {
        print PAGE $$_;
    }
    close PAGE;
    my $cmd = "htmldoc --continuous -t pdf14 -f $outfile  $infile";
#::logDebug("ready to run htmldoc command=$cmd");
    system $cmd;
    if(-f $outfile) {
        $Tag->deliver( { type => 'application/pdf', file => $outfile });
        unlink $outfile;
    }
    else {
        my $msg = errmsg("htmldoc failed to produce output with command: %s", $cmd);
        logError($msg);
    }
    unlink $infile;
    return;
}
EOR
