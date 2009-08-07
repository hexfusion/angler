<?php
echo "<b>{$_GET['dir']}</b><br>";
echo (is_dir($_GET['dir'])) ? "is valid directory" : "invalid directory";
echo "<br>";
echo (is_writable($_GET['dir'])) ? "writable" : "not writable";
?>

