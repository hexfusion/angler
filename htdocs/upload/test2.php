<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title>Untitled Document</title>
</head>
<!-- The data encoding type, enctype, MUST be specified as below -->
<form enctype="multipart/form-data" action="" method="POST">
    <!-- MAX_FILE_SIZE must precede the file input field -->
    <input type="hidden" name="MAX_FILE_SIZE" value="30000000" />
    <!-- Name of input element determines name in $_FILES array -->
    Send this file: <input name="userfile" type="file" />
    <input type="submit" value="Send File" />
</form>

<?php
// In PHP versions earlier than 4.1.0, $HTTP_POST_FILES should be used instead
// of $_FILES.

$uploaddir = '/home/virtual/site12/fst/var/www/html/upload/';
$upload_thumb_dir = '/home/virtual/site12/fst/var/www/html/upload/thumbs/';
$upload_image_dir = '/home/virtual/site12/fst/var/www/html/upload/image/';
$uploadfile = $uploaddir . basename($_FILES['userfile']['name']);
$image = $_FILES['userfile']['name'];
//$uploadfile = $_FILES['imagefile']['name']['error'];

//Thumb
 system("convert ".$uploaddir.$image." -resize x70 ".$upload_thumb_dir.$image."  2>&1");



echo '<pre>';
if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
    echo "File is valid, and was successfully uploaded.\n";
} else {
    echo "Possible file upload attack!\n";
}

echo 'Here is some more debugging info:';
print_r($_FILES);

print "</pre>";




?>

<body>
</body>
</html>
