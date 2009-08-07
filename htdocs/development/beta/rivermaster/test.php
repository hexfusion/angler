<?php
require_once "/home/virtual/site12/fst/var/www/html/development/beta/rivermaster/grouper.php";
Header("Content-Type: application/rss+xml");
GrouperShow("search terms","name_of_cache_file");
?>
