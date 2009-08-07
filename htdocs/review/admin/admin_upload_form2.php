<?php
session_start();

//make sure user has been logged in.
$validU="";

if (isset($_SESSION['valid_user']) && $_SESSION['valid_user']!="")$validU=$_SESSION['valid_user']; 

if ($validU=="")
	{
	// User not logged in, redirect to login page
	Header("Location: index.php");
	}
	
$item_id = $_GET['item_id'];

include ("../body.php");
include ("../functions.php");
include ("../f_secure.php");
include ("../config.php");
/*
if (exif_imagetype("$_FILES[img1][tmp_name]") != IMAGETYPE_JPEG) {
    echo "The picture is not a jpg or jpeg";
}
*/
if (isset($_FILES['img1'])) {
$img = $_FILES['img1'];
}

if ($img != "") {

///Program Files/Apache Group/Apache2/htdocs/upload
	@copy($_FILES[img1][tmp_name],"./Program Files/Apache Group/Apache2/htdocs/review/admin/images_uploaded/".$_FILES[img1][name]) or die("Couldn't copy the file.");

} else {

	die("No input file specified");

}
//echo $_FILES[img1][name];
BodyHeader("Upload Successful");
?>
<H1>Success!</H1>

<P>You sent:<?php echo @$_FILES[img1][name]; ?>, a <?php echo @$_FILES[img1][size]; ?> byte file with a mime type of <?php echo @$_FILES[img1][type]; ?>.</P>

<?php
echo image_type_to_extension($_FILES[img1][name]);
?>

<div align="center">Back to <a href="admin_menu.php?<?php echo htmlspecialchars(SID); ?>">Menu</a><br /></div>

<?php
BodyFooter();
exit;
?>