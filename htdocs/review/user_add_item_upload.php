<?php
session_start();
//header("Pragma: no-cache");

include ('body.php');
include ('functions.php');
include ('f_secure.php');
include ('config.php');

if ($_GET['item_id']) {
$_SESSION['item_id'] = $_GET['item_id'];
}


$max_size = '200000';

if(!is_numeric($_SESSION['item_id'])) {
BodyHeader("Invalid item","","");
echo "The item you are attempting to upload is not valid.";
BodyFooter();
exit;
}
$item_id = $_SESSION['item_id'];

BodyHeader("Upload Image","Upload an image for your review","upload");

if ($_SERVER['REQUEST_METHOD'] == "POST")
{
$path = 'images/items/';


//get extension of image
$ext = strtolower(strrchr($_FILES['userfile']['name'], "."));

if (!isset($_FILES['userfile'])) exit;

if (is_uploaded_file($_FILES['userfile']['tmp_name'])) {

if ($_FILES['userfile']['size']>$max_size) { echo "The file is too big<br />\n"; exit; }
if (($_FILES['userfile']['type']=="image/pjpeg") || ($_FILES['userfile']['type']=="image/jpeg")  || ($_FILES['userfile']['type']=="image/gif")) {

//if user uploaded another image and wants to replace it with this one it needs to be deleted.
$file_delete = $path . "00" . $_SESSION['item_id'] . ".gif";

 if (file_exists($file_delete)) {                                  
                       @unlink($file_delete);                
                   }      

$file_delete = $path . "00" . $_SESSION['item_id'] . ".jpg";

 if (file_exists($file_delete)) {                                  
                       @unlink($file_delete);                
                   }      

$newname = "00" .$_SESSION['item_id'];
//rename and move the file
$res = @move_uploaded_file($_FILES['userfile']['tmp_name'],$path . $newname . $ext);

if (!$res) { echo "upload failed!<br />\n"; exit; 
} else { echo "<b>Upload Sucessful</b><br />\n"; }




//insert name of file into database
	$sql = "UPDATE review_items_user
	SET 
item_image='$newname$ext'
WHERE
item_id='" . mysql_real_escape_string($item_id) . "'
	";

$result = @mysql_query($sql,$connection)
	or die(sprintf("Couldn't execute sql_count, %s: %s", db_errno(), db_error()));


echo "File Name: ". $newname . $ext."<br />\n";
echo "File Size: ".$_FILES['userfile']['size']." bytes<br />\n";
echo "File Type: ".$_FILES['userfile']['type']."<br />\n";
print("<img src=\"$path$newname$ext\">");

if ($mail_profile == "y") {
//email a notification to webmaster that photo has been uploaded
$msg = "Hello,\n\n";
$msg .= "$username just uploaded an image to be displayed with his/her review.\n\n";
$msg .= "You can view it here:\n";
$msg .= "$url$directory/images/user_upload/$path$item_id$ext\n\n";
$msg .= "\n\n";


$to = "$admin";
$subject = "Review Photo Uploaded - $sitename";
$mailheaders = "From: $sitename <$admin>\n";
$mailheaders .= "Reply-To: $admin\n\n";

mail($to, $subject, $msg, $mailheaders);
} //end mail profile

} else { echo "Wrong file type<br />You can only upload a .jpg or .gif file.\n"; exit; }

}
}//end if request
?><br />
<br />
<br />
<br />

 <FORM ENCTYPE="multipart/form-data" ACTION="<?php echo $_SERVER['PHP_SELF']; ?>" METHOD="POST">
<img src="<?php echo "$directory"; ?>/images/upload.gif" alt="Upload Image" width="16" height="16"> Upload Image: 
  <INPUT TYPE="file" NAME="userfile">
<INPUT TYPE="submit" VALUE="Upload">

<br />
<font size="1">Click browse to upload a local file. JPG and GIF images are the only allowed format. Max file size is <?php $kbsize = $max_size/1024; echo round($kbsize, 1); ?> kilobytes</font>

<input name="item_id" type="hidden" value="<?php echo $_GET['item_id']; ?>" />
</FORM>
 <div align="center"><br />
<br />
<br />
<br />
<br />
<br />

Back to <a href="<?php echo "$directory"; ?>">Reviews</a> </div>

   <?php //show navigation links on the bottom
BodyFooter(); ?> 
