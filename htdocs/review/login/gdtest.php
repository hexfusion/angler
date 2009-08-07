<?php
if (function_exists('imagecreate')) {
	echo "GD Library is enabled <br />\r\n<pre>";
	var_dump(gd_info());
	echo "</pre>";
} else {
	echo 'Sorry, you need to enable GD library first.  Contact your webhost.';
}
?>