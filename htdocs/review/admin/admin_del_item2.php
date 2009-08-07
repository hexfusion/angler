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

$sel_record = $_POST['sel_record'];

		if (!$sel_record) {
header("Location: http://$url$directory/admin_del_item1.php?".sid);
			exit;
		}
		
			$sql = "SELECT * FROM review_items WHERE item_id='$sel_record'";
		
					$sql_result = mysql_query($sql)
		or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));


		if (!$sql_result) {  
   
			echo "<P>Couldn't get item!";

		} else {
			 while ($row = mysql_fetch_array($sql_result)) {
	$item_name = stripslashes($row["item_name"]);
	$category = stripslashes($row["category"]);
}			

BodyHeader("Delete Review!");
?>
<center>
  <h1><font face="Verdana, Arial, Helvetica, sans-serif" size="3">You have selected 
    the following Item to DELETE:</font></h1>
</center>

<?php
	function getCategoryName($catId){
		$sql='Select * from review_category_list where cat_id_cloud = ' . $catId;
		$sql_result = mysql_query($sql);
		while ($row = mysql_fetch_array($sql_result)) {
			return stripslashes($row["category"]);	
		}	
	}
?>

<FORM method="POST" action="admin_del_item3.php?<?php echo htmlspecialchars(SID); ?>">
  <input type="hidden" name="sel_record" value="<?php echo $_POST['sel_record']; ?>">
  <table cellspacing=5 cellpadding=5>
    <tr> 
      <td valign=top><strong>Item Name:</strong></td>
      <td valign=top> <?php echo "$item_name" ?> 
      </td>
    </tr>
    <tr> 
      <td valign=top><strong>Category:</strong></td>
      <td valign=top><?php echo getCategoryName($category) ?></td>
      </td>
    </tr>
 
    
    <tr> 
      <td align=center colspan=2>   
	  <input type=hidden name="category" value="<?php echo htmlspecialchars($category); ?>">
  <input type=hidden name="item_name" value="<?php echo htmlspecialchars($item_name); ?>">
  <INPUT type="submit" value="Delete Item"> 
      </td>
    </tr>
  </table>
</FORM>

  <div align="center">Back to <a href="admin_menu.php?<?php echo htmlspecialchars(SID); ?>">Menu</a></div>
  <br />
</center>
<?php
BodyFooter(); 
}
?>
