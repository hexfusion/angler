<?php
/*session_start();
header("Pragma: no-cache");

include ("../../body.php");
include ("../../f_secure.php");
include ("../../functions.php");
include ("../../config.php");

//$username = $_SESSION['username_logged'];
//$filename = "$username.jpg";
//$filename = $_REQUEST['filename'];
*/
$filename = $_GET['filename'];

    // If $file is not supplied or is not a file, warn the user and return false.
    if (is_null($filename) || !is_file($filename)) {
        echo '<p><b>Warning:</b> image_info() => images only.</p>';
        return false;
    }

// Set a maximum height and width
$width = 50;
$height = 50;

// Content type
header('Content-type: image/jpeg');

// Get new dimensions
list($width_orig, $height_orig) = getimagesize($filename);

if ($width && ($width_orig < $height_orig)) {
   $width = ($height / $height_orig) * $width_orig;
} else {
   $height = ($width / $width_orig) * $height_orig;
}

// Resample
$image_p = imagecreatetruecolor($width, $height);
$image = imagecreatefromjpeg($filename);
imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);

// Output
imagejpeg($image_p, null, 100);
?>