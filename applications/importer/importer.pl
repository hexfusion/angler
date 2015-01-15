#!/usr/bin/env perl

use warnings;
use strict;

use YAML;

my $h = Load(<<'...');
---
importer:
    options:
        product_prefix: "WB"
        join_string: "-"
        filters:
            Attribute:
                name:
                    - lc
                    - special2underscore
            AttributeValue:
                value:
                    - lc
                    - special2underscore
            Navigation:
                uri:
                    - lc
                    - special2underscore
                    - space2dash
            Product:
                sku:
                    - lc
                    - space2dash
                uri:
                    - lc
                    - special2underscore
                    - space2dash
    manufacturers:
        hatch:
            prefix: "HA"
            full_name: "Hatch Outdoors"
            short_name: "Hatch"
        loomis:
            prefix: "LO"
            full_name: "Loomis"
        orvis:
            prefix: "OR"
            full_name: "The Orvis Company"
            short_name: "Orvis"
        patagonia:
            prefix: "PA"
            full_name: "Patagonia"
            fields:
                navigation:
                    - "xl:Category1"
                    - "xl:Category2"
                    - "xl:Category3"
                all:
                    price: "xl:Retail"
                    gtin: "UPC"
                canonical:
                    sku:
                        - "product_prefix"
                        - "prefix"
                        - "xl:Item"
                    name:
                        - "full_name"
                        - "xl:Product"
                    uri:
                        - "full_name"
                        - "xl:Product"
                variant:
                    attributes:
                        color: "xl:Color"
                        size: "xl:Size"
                    sku:
                        - "product_prefix"
                        - "prefix"
                        - "xl:Item"
                        - "xl:Color_Code"
                        - "xl:Size"
                    name:
                        - "full_name"
                        - "xl:Product"
                        - "xl:Color_Code"
                    uri:
                        - "full_name"
                        - "xl:Product"
                        - "xl:Color_Code"
                        - "xl:Size"
            navigation_alias:
                filters:
                    -
                        field: "Product"
                        regexp: "^M's"
                        result: "men"
                    -
                        field: "Product"
                        regexp: "^W's"
                        result: "women"
                clothing:
                    - men:
                        - outerwear:
                            - "alpine/shell-tops"
                            - "alpine/shell-bottoms"
                            - "alpine/insulation-tops"
                            - "trail-running/tops"
                            - "trail-running/bottoms"
                            - "trail-running/outerwear"
                    - women:
                        - outerwear:
                            - "alpine/shell-tops"
                            - "alpine/shell-bottoms"
                            - "alpine/insulation-tops"
                            - "trail-running/tops"
                            - "trail-running/bottoms"
                            - "trail-running/outerwear"
                    - kids:
                
        sage:
            prefix: "SA"
            full_name: "Sage"
        scott:
            prefix: "SC"
            full_name: "Scott"
        simms:
            prefix: "SI"
            full_name: "Simms Fishing Products"
            short_name: "Simms"
...

use Data::Dumper::Concise;
print Dumper($h);
