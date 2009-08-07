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
		
			$sql = "SELECT category,cat_id_cloud FROM review_category_list WHERE cat_id_cloud = " .$category;
					$sql_result = mysql_query($sql)
		or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));


		if (!$sql_result) {  
   
			echo "<P>Couldn't get item!";

		} else {

			 while ($row = mysql_fetch_array($sql_result)) {
	$category = stripslashes($row["category"]);
	$cat_id_cloud = stripslashes($row["cat_id_cloud"]);
}			

BodyHeader("$sitename: Delete Review!");
?>
<center>
  <h1>You have selected 
    the following category to DELETE:</h1>
</center>
<FORM method="POST" action="admin_del_cat3.php?<?php echo htmlspecialchars(SID); ?>">
  <input type="hidden" name="category" value="<?php echo "$cat_id_cloud"; ?>">
  <input type="hidden" name="category_name" value="<?php echo "$category"; ?>">
  <table cellspacing=5 cellpadding=5>
    <tr> 
      <td valign=top><strong>Category:</strong></td>
      <td valign=top> <?php echo "$category"; ?> 
      </td>
    </tr>

    <tr> 
      <td align=center colspan=2>   
  <INPUT type="submit" value="Delete Category"> 
      </td>
    </tr>
  </table>
</FORM>
<div align=center>Back to <a href=admin_menu.php?<?php echo htmlspecialchars(SID); ?>>Menu</a><br /></div>
<?php
BodyFooter(); 
}
?>
