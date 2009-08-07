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


$sel_record = makeStringSafe($_POST["sel_record"]);

	if (!$sel_record) {
header("Location: http://$url$directory/admin/admin_del_comment1.php?".sid);
			exit;
		}
	
	
	$sql = "DELETE FROM review_comment WHERE id = \"$sel_record\" LIMIT 1";

        $sql_result = mysql_query($sql) 
            or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error())); 

	if (!$sql_result) {  
   
		echo "<P>Couldn't delete record!";

	} else {
		BodyHeader("$sitename:  Delete A Comment","",""); 
        ?>
<h1><font face="Verdana, Arial, Helvetica, sans-serif" size="3">The comment you selected has been deleted</font></h1>
<P> 


</P>
<div align="center">Back to <a href="admin_menu.php?<?php echo htmlspecialchars(SID); ?>">Menu</a><br /></div>
  <?php 
				
        BodyFooter(); 
 }
 
?>

