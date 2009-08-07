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
BodyHeader("Upload an Image");
?>
<H1>Upload an Image</H1>
<FORM METHOD="POST" ACTION="admin_upload_form2.php?<?php echo htmlspecialchars(SID); ?>&item_id=<?php echo "$item_id"; ?>" ENCTYPE="multipart/form-data">
<p><strong>Image to Upload (.jpg only):</strong><br />
<INPUT TYPE="file" NAME="img1" SIZE="30"></P>
<P><INPUT TYPE="submit" NAME="submit" VALUE="Upload File"></P>
</FORM>
<div align="center">Back to <a href="admin_menu.php?<?php echo htmlspecialchars(SID); ?>">Menu</a><br /></div>

<?php
BodyFooter();
?>
