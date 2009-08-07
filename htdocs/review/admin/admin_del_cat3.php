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

$category = $_POST['category'];

	if (!$category) {
header("Location: http://$url$directory/admin/admin_del_cat1.php?".sid);
			exit;
		}
	
	$sql = "DELETE FROM review_category_list WHERE cat_id_cloud = " .$category;
        $sql_result = mysql_query($sql) 
            or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error())); 

	if (!$sql_result) {  
   
		echo "<P>Couldn't delete record!";

	} 
//remove from the review_items table
$sql2 = "UPDATE review_items
	SET
	CATEGORY = '' 
	WHERE category_id = " . $category;

        $sql_result2 = mysql_query($sql2) 
            or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error())); 

	if (!$sql_result2) {  
   
		echo "<P>Couldn't delete record from review_items!";

	} 
else {
		BodyHeader("$sitename:  Delete A Category"); 
        ?>
<h1><font face="Verdana, Arial, Helvetica, sans-serif" size="3">You have deleted 
  <?php echo stripslashes($_POST['category_name']); ?> from the Category Database</font></h1>
<P> 
<table align="center">
  <tr> 
    <td valign=top><strong>Category:</strong></td>
    <td valign=top> <?php echo stripslashes($_POST['category_name']); ?> 
    </td>
  </tr>
  
</table>
<div align="center">
  <p><br />
    Back to <a href="admin_menu.php?<?php echo htmlspecialchars(SID); ?>">Menu</a><br />  </p>
</div>
    <?php 
				
        BodyFooter(); 
 }
 
?>

