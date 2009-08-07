<?php
session_start();
//header("Pragma: no-cache");

include ("../../body.php");
//include ("../../f_secure.php");
//include ("../../functions.php");
include ("../../config.php");

$username = $_SESSION['username_logged'];
$max_size = '200000';

BodyHeader("Upload Image","Upload an image for your review profile","upload");

if ($_SERVER['REQUEST_METHOD'] == "POST")
{
$path = "images/";


//get extension of image
$ext = strtolower(strrchr($_FILES['userfile']['name'], "."));

if (!isset($_FILES['userfile'])) exit;

if (is_uploaded_file($_FILES['userfile']['tmp_name'])) {

if ($_FILES['userfile']['size']>$max_size) { echo "The file is too big<br />\n"; exit; }
if (($_FILES['userfile']['type']=="image/pjpeg") || ($_FILES['userfile']['type']=="image/jpeg")) {

//rename and move the file
$res = @move_uploaded_file($_FILES['userfile']['tmp_name'],$path . $_SESSION['username_logged'] . $ext);

if (!$res) { echo "upload failed!<br />\n"; exit; } else { echo "<b>Upload Sucessful</b><br />\n"; }

echo "File Name: ". $_SESSION['username_logged'] . $ext."<br />\n";
echo "File Size: ".$_FILES['userfile']['size']." bytes<br />\n";
echo "File Type: ".$_FILES['userfile']['type']."<br />\n";
print("<img src=\"$path$username$ext\">");

if ($mail_profile == "y") {
//email a notification to webmaster that photo has been uploaded
$msg = "Hello,\n\n";
$msg .= "$username just uploaded an image.\n\n";
$msg .= "You can view it here:\n";
$msg .= "$url$directory/usercp/upload/$path$username$ext\n\n";
$msg .= "\n\n";


$to = "$admin";
$subject = "Photo Uploaded - $sitename";
$mailheaders = "From: $sitename <$admin>\n";
$mailheaders .= "Reply-To: $admin\n\n";

mail($to, $subject, $msg, $mailheaders);
} //end mail profile

} else { echo "Wrong file type<br />You can only upload a .jpg file.\n"; exit; }

}
}//end if request
?>
 <FORM ENCTYPE="multipart/form-data" ACTION="<?php echo $_SERVER['PHP_SELF']; ?>" METHOD="POST">
<img src="../../images/upload.gif" alt="Upload Image" width="16" height="16"> Upload Image: 
  <INPUT TYPE="file" NAME="userfile">
<INPUT TYPE="submit" VALUE="Upload">
<br />
<font size="1">Click browse to upload a local file. JPG images are the only allowed format. Max file size is <?php $kbsize = $max_size/1024; echo round($kbsize, 1); ?> kilobytes</font>
 </FORM>
<BR><BR><BR><BR>
        <?php //show navigation links on the bottom
include("../user_cp_links.php"); 
BodyFooter(); ?> 
