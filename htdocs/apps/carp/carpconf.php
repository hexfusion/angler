<?php
/*
If you wish to change CaRP's default configuration values, we recommeng doing
it in this file rather than modifying carp.php. That way, when you upgrade to
a new version, you won't need to copy your override settings into the new
version.

See the online documentation for details.
http://carp.docs.geckotribe.com/
*/
function MyCarpConfReset($theme='') {
	global $carpconf;
	CarpConfReset();
	
	// Add configuration code that applies to all themes here
	
	
	
	switch ($theme) {
	case 'podcast-heavy': CarpLoadTheme('podcast-heavy.php'); break;
	case 'podcast-lite': CarpLoadTheme('podcast-lite.php'); break;
	case 'ul': CarpLoadTheme('ul.php'); break;
	
	// Add new themes below here
	
	
	
	// Add new themes above here
	
	default: // Enter code for your default theme here
		break;
	}
}
MyCarpConfReset(isset($GLOBALS['carptheme'])?$GLOBALS['carptheme']:'');

function MyForceConf() {
	// Add any configuration code that you don't want ever to be overriden here.
	// This code will be executed whenever CaRP parses a feed.
	// Be aware that in CaRP Evolution, plugins could still override some settings in their callback code.
	
}

// Define constants that must not be changed -- if they've been pre-defined, output an error and terminate immediately
if (!define('carp_banned_tags','script,embed,object,applet,iframe'))
	exit('<br /><b>ERROR: "carp_banned_tags" was pre-defined. This is a potential security violation. Terminating now.</b><br />');

return;
?>