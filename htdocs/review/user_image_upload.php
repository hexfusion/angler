<?php
session_start();
//header("Pragma: no-cache");

include ('body.php');
include ('functions.php');
include ('f_secure.php');
include ('config.php');

if ($_GET['review_id']) {
$review_id = $_GET['review_id'];
$_SESSION['review_id'] = "$review_id";
}

if(!is_numeric($_SESSION['review_id'])) {
BodyHeader("Invalid item","","");
echo "The item you are attempting to upload is not valid.";
BodyFooter();
exit;
}

$item_id=@$_REQUEST['item_id'];
			$item_id = htmlspecialchars($item_id, ENT_QUOTES);
			$item_id = makeStringSafe($item_id);
			if($item_id!=""){
				if(! is_numeric($item_id)) $item_id="";
			}
			

BodyHeader("Upload Image","Upload an image for your review","upload");

if ($_SERVER['REQUEST_METHOD'] == "POST")
{
$path = "images/user_upload/";
$max_size = 200000;

//get extension of image
$ext = strtolower(strrchr($_FILES['userfile']['name'], "."));

if (!isset($_FILES['userfile'])) exit;

if (is_uploaded_file($_FILES['userfile']['tmp_name'])) {

if ($_FILES['userfile']['size']>$max_size) { echo "The file is too big<br />\n"; exit; }
if (($_FILES['userfile']['type']=="image/pjpeg") || ($_FILES['userfile']['type']=="image/jpeg")  || ($_FILES['userfile']['type']=="image/gif")) {

//if user uploaded another image and wants to replace it with this one it needs to be deleted.
$file_delete = $path . $_SESSION['review_id'] . ".gif";

 if (file_exists($file_delete)) {                                  
                       @unlink($file_delete);                
                   }      

$file_delete = $path . $_SESSION['review_id'] . ".jpg";

 if (file_exists($file_delete)) {                                  
                       @unlink($file_delete);                
                   }      

//rename and move the file
$res = @move_uploaded_file($_FILES['userfile']['tmp_name'],$path . $_SESSION['review_id'] . $ext);

if (!$res) { echo "upload failed!<br />\n"; exit; 
} else { echo "<b>Upload Sucessful</b><br />\n"; }

//insert name of file into database
$user_image = $_SESSION['review_id'] . "$ext";
$review_id = $_SESSION['review_id'];
	$sql = "UPDATE review
	SET 
user_image='$user_image'
WHERE
id = '$review_id'
	";


$result = @mysql_query($sql,$connection)
	or die(sprintf("Couldn't execute sql_count, %s: %s", db_errno(), db_error()));


echo "File Name: ". $_SESSION['review_id'] . $ext."<br />\n";
echo "File Size: ".$_FILES['userfile']['size']." bytes<br />\n";
echo "File Type: ".$_FILES['userfile']['type']."<br />\n";
print("<img src=\"$path". $_SESSION['review_id'] ."$ext\">");

if ($mail_profile == "y") {
//email a notification to webmaster that photo has been uploaded
$msg = "Hello,\n\n";
$msg .= "$username just uploaded an image to be displayed with his/her review.\n\n";
$msg .= "You can view it here:\n";
$msg .= "$url$directory/images/user_upload/$path$review_id$ext\n\n";
$msg .= "\n\n";


$to = "$admin";
$subject = "Review Photo Uploaded - $sitename";
$mailheaders = "From: $sitename <$admin>\n";
$mailheaders .= "Reply-To: $admin\n\n";

mail($to, $subject, $msg, $mailheaders);
} //end mail profile

} else { echo "Wrong file type<br />You can only upload a .jpg file.\n"; exit; }

}
}//end if request
?><br />
<br />
<br />

 <FORM ENCTYPE="multipart/form-data" ACTION="<?php echo $_SERVER['PHP_SELF']; ?>" METHOD="POST">
<img src="<?php echo "$directory"; ?>/images/upload.gif" alt="Upload Image" width="16" height="16"> Upload Image: 
  <INPUT TYPE="file" NAME="userfile">
  <input name="item_id" type="hidden" value="<?php echo "$item_id"; ?>" />
<INPUT TYPE="submit" VALUE="Upload">
<br />
<font size="1">Click browse to upload a local file. JPG images are the only allowed format. </font>
</FORM><br />
<br />
<br />
<br />
<br />
<br />

 <div align="center">
     <br />
Back to <a href="<?php echo "$directory/index2.php?item_id=$item_id"; ?>">Reviews</a> </p>
</div>
   <?php //show navigation links on the bottom
BodyFooter(); ?> 