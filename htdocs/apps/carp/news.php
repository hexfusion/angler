<?php
require_once '/home/virtual/site12/fst/var/www/html/apps/carp/carp.php';
/*** Setup Code ***/
CarpConf('cache-method','mysql');
CarpConf('mysql-connect',1);
CarpConf('mysql-database','test_site2');
CarpConf('mysql-username','root');
CarpConf('mysql-password','s1a2mayfly');
$carpconf['mysql-tables'][0]='carp_aggregatecache';
$carpconf['mysql-tables'][1]='carp_manualcache';
$carpconf['mysql-tables'][2]='carp_autocache';
/*** End Setup Code ***/
// Add any desired configuration settings before CarpCacheShow
// using "CarpConf" and other functions

CarpCacheShow('http://www.westbranchresort.com/blog/feed.xml');
?>
