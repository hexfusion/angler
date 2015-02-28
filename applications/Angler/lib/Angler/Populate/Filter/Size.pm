#!/usr/bin/env perl
use strict;
use Carp;
use Data::Dumper;

my %sizemapping = (

    'xxs' => 'xxs',
    'xx-sm' => 'xxs',
    'xx-sml' => 'xxs',
    'xx-small' => 'xxs',
    'xx_sm' => 'xxs',
    'xx_sml' => 'xxs',
    'xx_small' => 'xxs',
    'xxsm' => 'xxs',
    'xxsml' => 'xxs',
    'xxsmall' => 'xxs',
    'extra_extra_small' => 'xxs',
    'extra-extra-small'  => 'xxs',
    'extraextrasmall'  => 'xxs',
    'extra_extrasmall'  => 'xxs',
    'xs' => 'xs',
    'x-sm' => 'xs',
    'x-sml' => 'xs',
    'x-small' => 'xs',
    'x_sm' => 'xs',
    'x_sml' => 'xs',
    'x_small' => 'xs',
    'xsm' => 'xs',
    'xsml' => 'xs',
    'xsmall' => 'xs',
    's' => 's',
    'sm' => 's',
    'sml' => 's',
    'small' => 's',
    'm' => 'm',
    'med' => 'm',
    'md' => 'm',
    'medium' => 'm',
    'l' => 'l',
    'lg' => 'l',
    'large' => 'l',
    'lge' => 'l',
    'xl' => 'xl',
    'extra_large' => 'xl',
    'x_large' => 'xl',
    'x-large' => 'xl',
    'xlarge' => 'xl',
    'xlg' => 'xl',
    'xxl' => 'xxl',
    'extraextralarge' => 'xxl',
    'extra_extra_large' => 'xxl',
    'xx_large' => 'xxl',
    'xx-large' => 'xxl',
    'xxlarge' => 'xxl',
    'xxlg' => 'xxl',  
    '2xl' => 'xxl',
    '2_xl' => 'xxl',
    '2-xl' => 'xxl',
    'xxxl' => 'xxxl',
    'extraextraextralarge' => 'xxxl',
    'extra_extra_extra_large' => 'xxxl',
    'xxx_large' => 'xxxl',
    'xxx-large' => 'xxxl',
    'xxxlarge' => 'xxxl',
    'xxxlg' => 'xxxl',
    '3xl' => 'xxxl',
    'xxxxl' => 'xxxxl',
    'extraextraextraextralarge' => 'xxxxl',
    'extra_extra_extra_extra_large' => 'xxxxl',
    'xxxx_large' => 'xxxxl',
    'xxxx-large' => 'xxxxl',
    'xxxxlarge' => 'xxxxl',
    'xxxxlg' => 'xxxxl',
    '4xl' => 'xxxxl',
  );
  
my @array_of_hashs=(
{ name => "size", value => "S" },
{ name => "size", value => "SM" },
{ name => "size", value => "S30W" },
{ name => "size", value => "M34W" },
{ name => "size", value => "L38W" },
{ name => "size", value => "XS24W" },
{ name => "size", value => "Small" },
{ name => "size", value => "N - should not map"},
);

for (@array_of_hashs) {
    $_->{value} =~ m/([[:alpha:]]+)((\d+)W)?/ or carp "no match for " . $_->{value};
    if( exists $sizemapping{$1}) {
        $_->{value} = $sizemapping{$1};
        $_->{waist} = $3 if $2;
        } else {
        carp "no mapping for " . $1;
        }
    }

print Dumper( \@array_of_hashs);    
    
