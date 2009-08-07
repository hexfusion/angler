<?php
$filename = '../../config.php';
if (is_writable($filename)) {
   echo 'OKAY...config.php is writable<br /><br />';
} else {
   echo 'config.php is not writable.  Please set the file permission to 666.<br /><br />';
}

$filename = '../../functions.php';
if (is_writable($filename)) {
   echo 'OKAY...functions.php is writable<br /><br />';
} else {
   echo 'functions.php is not writable.  Please set the file permission to 666.<br /><br />';
}

$filename = '../images_uploaded';
if (is_writable($filename)) {
   echo 'OKAY...images_uploaded is writable<br /><br />';
} else {
   echo 'images_uploaded is not writable.  Please set the folder permission to 777.<br /><br />';
}

$filename = '../../usercp/upload/images';
if (is_writable($filename)) {
   echo 'OKAY...usercp/upload/images is writable<br /><br />';
} else {
   echo 'usercp/upload/images is not writable.  Please set the folder permission to 777.<br /><br />';
}

$filename = '../../images/user_upload';
if (is_writable($filename)) {
   echo 'OKAY...images/user_upload is writable<br /><br />';
} else {
   echo 'images/user_upload is not writable.  Please set the folder permission to 777.<br /><br />';
}

$filename = '../../images/items';
if (is_writable($filename)) {
   echo 'OKAY...images/items is writable<br /><br />';
} else {
   echo 'images/items is not writable.  Please set the folder permission to 777.<br /><br />';
}

$filename = '../../spell_checker/personal_dictionary/personal_dictionary.txt';
if (is_writable($filename)) {
   echo 'OKAY...spell_checker/personal_dictionary/personal_dictionary.txt is writable<br /><br />';
} else {
   echo 'spell_checker/personal_dictionary/personal_dictionary.txt is not writable.  Please set the file permission to 646.<br /><br />';
}

?>