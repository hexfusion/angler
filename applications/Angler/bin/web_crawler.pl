#!/usr/bin/env perl
use 5.010;
use open qw(:locale);
use strict;
use utf8;
use warnings qw(all);

use Mojo::UserAgent;

# FIFO queue
my @urls = map { Mojo::URL->new($_) } qw(
    http://www.simmsfishing.com/shop/waders.html
    http://www.simmsfishing.com/shop/footwear.html
    http://www.simmsfishing.com/shop/footwear.html?p=2
);

# Limit parallel connections to 4
my $max_conn = 4;

# User agent following up to 5 redirects
my $ua = Mojo::UserAgent->new(max_redirects => 5);
$ua->proxy->detect;

# Keep track of active connections
my $active = 0;

Mojo::IOLoop->recurring(
    0 => sub {
        for ($active + 1 .. $max_conn) {

            # Dequeue or halt if there are no active crawlers anymore
            return ($active or Mojo::IOLoop->stop)
                unless my $url = shift @urls;

            # Fetch non-blocking just by adding
            # a callback and marking as active
            ++$active;
            $ua->get($url => \&get_callback);
        }
    }
);

# Start event loop if necessary
Mojo::IOLoop->start unless Mojo::IOLoop->is_running;

sub get_callback {
    my (undef, $tx) = @_;

    # Deactivate
    --$active;

    # Parse only OK HTML responses
    return
        if not $tx->res->is_status_class(200)
        or $tx->res->headers->content_type !~ m{^text/html\b}ix;

    # Request URL
    my $url = $tx->req->url;

    say $url;
    parse_html($url, $tx);

    return;
}

sub parse_html {
    my ($url, $tx) = @_;

    say $tx->res->dom->at('html title')->text;

    # Extract and enqueue URLs
    for my $e ($tx->res->dom('a[href]')->each) {

        # Validate href attribute
        my $link = Mojo::URL->new($e->{href});
        next if 'Mojo::URL' ne ref $link;

        # "normalize" link
        $link = $link->to_abs($tx->req->url)->fragment(undef);
        next unless grep { $link->protocol eq $_ } qw(http https);

        # Don't go deeper than /a/b/c
        next if @{$link->path->parts} > 3;

        # Access every link only once
        state $uniq = {};
        ++$uniq->{$url->to_string};
        next if ++$uniq->{$link->to_string} > 1;

        # Don't visit other hosts
        next if $link->host ne $url->host;

        push @urls, $link;
        say " -> $link";
    }
    say '';

    return;
}
__DATA__
Featured at:
http://blogs.perl.org/users/stas/2013/01/web-scraping-with-modern-perl-part-1.html
