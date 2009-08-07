<?php
session_start();
header("Pragma: no-cache");
/*
include ("body.php");
include ("functions.php");
include ("f_secure.php");
include ("config.php");
*/

//$username = $_SESSION['username_logged'];
//$filename = "$username.jpg";
$filename = $_REQUEST['filename'];

// Set a maximum height and width

$width = 125;
$height = 125;

$size = getimagesize("$filename");

if ($size[2] == 2) {
$ext2 = "jpeg"; } elseif ($size[2] == 1) {
$ext2 = "gif"; }

// Content type
header('Content-type: image/$ext2');

//header("Content-type: " . image_type_to_mime_type());


// Get new dimensions
list($width_orig, $height_orig) = getimagesize($filename);

if ($width && ($width_orig < $height_orig)) {
   $width = ($height / $height_orig) * $width_orig;
} else {
   $height = ($width / $width_orig) * $height_orig;
   
//   echo "$width is width / $width_orig is width_orig) * $height_orig is height_orig";
}

// Resample
$image_p = imagecreatetruecolor($width, $height);

if ($ext2 == "jpeg") {
$image = imagecreatefromjpeg($filename); } elseif ($ext2 == "gif") {
$image = imagecreatefromgif($filename); }


//$image = imagecreatefromjpeg($filename);
imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);

// Output
if ($ext2 == "jpeg") {
imagejpeg($image_p, null, 100); } elseif ($ext2 == "gif") {
imagegif($image_p, null, 100); }

//imagejpeg($image_p, null, 100);
?>