#!/usr/bin/env perl

use utf8;
use strict;
use warnings;
use Dancer ':script';
use Amazon::MWS::Uploader;
use Data::Dumper;
use Getopt::Long;

binmode STDOUT, ':utf8';
binmode STDERR, ':utf8';

my $confsection = '';

my %conf = (access_key_id => config->{aws}->{developer_id},
            secret_key => config->{aws}->{developer_key},
            marketplace_id => config->{aws}->{marketplace_id},
            endpoint => config->{aws}->{endpoint},
            merchant_id => config->{aws}->{seller_id},
            feed_dir => 'amazon/feeds',
            schema_dir => 'amazon/schema',
        );

my $uploader = Amazon::MWS::Uploader->new(%conf);

foreach my $ean (@ARGV) {
    print Dumper($uploader->get_product_categories($ean));
    print Dumper($uploader->get_product_category_names($ean));
    print Dumper($uploader->get_product_category_data($ean));
}
