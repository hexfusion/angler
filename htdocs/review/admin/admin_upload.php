<?php
//if a session does not yet exist for this user, start one
session_start();

//make sure user has been logged in.
$validU="";

if (isset($_SESSION['valid_user']) && $_SESSION['valid_user']!="")$validU=$_SESSION['valid_user']; 

if ($validU=="")
	{
	// User not logged in, redirect to login page
	Header("Location: index.php");
	}
	
@ini_set('max_execution_time', 3600);

ini_set( "post_max_size", "10M" );

header("Pragma: no-cache");

//make sure user has been logged in.
if (!$_SESSION['valid_user'])
	{
	// User not logged in, redirect to login page
	Header("Location: index.php");
	}

include ("../body.php");
include ("../functions.php");
include ("../f_secure.php");
include ("../config.php");

$item_id = $_GET['item_id'];

BodyHeader("Upload Image","","");
$max_size = 8388608;
if ($_SERVER['REQUEST_METHOD'] == "POST")
{
$path = "../images/items/";


//get extension of image
$ext = strtolower(strrchr($_FILES['userfile']['name'], "."));

if (!isset($_FILES['userfile'])) exit;

if (is_uploaded_file($_FILES['userfile']['tmp_name'])) {

if ($_FILES['userfile']['size']>$max_size) { echo "The file is too big<br />\n"; exit; }
if (($_FILES['userfile']['type']=="image/pjpeg") || ($_FILES['userfile']['type']=="image/jpeg")  || ($_FILES['userfile']['type']=="image/gif") || ($_FILES['userfile']['type']=="audio/mpeg") || ($_FILES['userfile']['type']=="audio/mp3")) {

//rename and move the file
$res = @move_uploaded_file($_FILES['userfile']['tmp_name'],$path . $_GET['item_id'] . $ext);

if (!$res) { echo "upload failed!<br />\n"; exit; } else { echo "<b>Upload Sucessful</b><br />\n"; }

//insert name of file into database
$item_image = "$item_id$ext";
	$sql = "UPDATE review_items
	SET 
item_image='$item_image'
WHERE
item_id = '$item_id'
	";

$result = @mysql_query($sql,$connection)
	or die(sprintf("Couldn't execute sql_count, %s: %s", db_errno(), db_error()));

//output image info to browser
echo "File Name: ". $_GET['item_id'] . $ext."<br />\n";
echo "File Size: ".$_FILES['userfile']['size']." bytes<br />\n";
echo "File Type: ".$_FILES['userfile']['type']."<br />\n";
//display the uploaded photo
$type = $_FILES['userfile']['type'];
if ($type == "audio/mpeg") {
echo "<img src=$directory/images/music.gif>"; } else {
print("<img src=\"$path$item_id$ext\">"); } ?>


    <br /><?php echo "<a href=$url$directory/index2.php?item_id=$item_id>$url$directory/index2.php?item_id=$item_id</a>"; ?><br /><br />

When you are happy with the image you have uploaded, return to the <a href="admin_menu.php?<?php echo htmlspecialchars(SID); ?>">Menu</a>.
<?php 

BodyFooter();
exit;
} else { echo "Wrong file type<br />\n"; exit; }

}
}//end if request
?>
<FORM ENCTYPE="multipart/form-data" ACTION="admin_upload.php?item_id=<?php echo "$item_id"; ?>&<?php echo htmlspecialchars(SID); ?>" METHOD="POST">
<input type="hidden" name="MAX_FILE_SIZE" value="8388608" />

Upload Image: 
  <INPUT TYPE="file" NAME="userfile">
<INPUT TYPE="submit" VALUE="Upload">
<br />
<font size="1">Click browse to upload a local file. JPG, GIF, MPG and MP3 are the only allowed format.  Max file size is <?php $kbsize = $max_size/1024; echo round($kbsize, 1); ?> kilobytes</font>
</FORM>
<BR><BR><BR><BR>
        <?php 
BodyFooter(); ?> 
