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

include ("../body.php");
include ("../functions.php");
include ("../f_secure.php");
include ("../config.php");

$item_id = $_GET["item_id"];


$sel_com2 = "select item_image from review_items
where
item_id = '$item_id'
";

$sResult2 = mysql_query("$sel_com2")
		or die(sprintf("Couldn't execute sql_count, %s: %s", db_errno(), db_error()));

while($row2 = mysql_fetch_array($sResult2)) {
$item_image = stripslashes($row2['item_image']);
} //while

$file = "../images/items/$item_image";

if(file_exists($file))
unlink($file);


		BodyHeader("$sitename:  Delete An Item Image"); 
        ?>
<h1><font face="Verdana, Arial, Helvetica, sans-serif" size="3">You have deleted 
  the items image</font><font size="3" face="Verdana, Arial, Helvetica, sans-serif">.</font></h1>
<P> 
<div align="center">Back to <a href="admin_menu.php?<?php echo htmlspecialchars(SID); ?>">Menu</a><br /></div>
  <?php 
				
        BodyFooter(); 
?>

