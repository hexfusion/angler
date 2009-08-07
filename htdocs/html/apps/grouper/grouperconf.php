<?php
/*
If you wish to change Grouper's default configuration values, we recommeng doing
it in this file rather than modifying grouper.php. That way, when you upgrade to
a new version, you won't need to copy your override settings into the new
version.

See the online documentation for details.
http://www.geckotribe.com/rss/grouper/manual/
*/
function MyGrouperConfReset($set='default') {
	global $grouperconf;
	GrouperConfReset();
	
	// Override any settings you wish to change here
	
	
	
	// Create alternative configuration sets here
	if ($set=='default') {
		GrouperConf('source','google');
	} else if ($set=='style1') {
		
	}
}
MyGrouperConfReset();
?>