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


	function getCategoryName($catId){
		$sql='Select * from review_category_list where cat_id_cloud = ' . $catId;
		$sql_result = mysql_query($sql);
		while ($row = mysql_fetch_array($sql_result)) {
			return stripslashes($row["category"]);	
		}	
	}


$sel_record = $_POST['sel_record'];

		if (!$sel_record) {
header("Location: $url$directory/admin/admin_menu.php?".sid);
			exit;
		}

		$sql = "SELECT * FROM review_items_user
WHERE
item_id = '$sel_record'
						";
		
					$sql_result = mysql_query($sql)
		or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));


		if (!$sql_result) {  
   
			echo "<P>Couldn't get item!";

		} else {
			 while ($row = mysql_fetch_array($sql_result)) {
	$item_name = stripslashes($row["item_name"]);
	$item_type = stripslashes($row["item_type"]);
	$item_desc = stripslashes($row["item_desc"]);
	$name = stripslashes($row["name"]);
	$category_id = stripslashes($row["category_id"]);
	$category = getCategoryName($row["category_id"]);
	$email = stripslashes($row["email"]);
}			

BodyHeader("Delete User Submitted Item!");
?>
<center>
  <h1><font face="Verdana, Arial, Helvetica, sans-serif" size="3">You have selected 
    the following item to DELETE:</font></h1>
</center>
<FORM method="POST" action="admin_delete_item_user3.php?<?php echo htmlspecialchars(SID); ?>">
  <input type="hidden" name="sel_record" value="<?php echo "$sel_record"; ?>">
  <table cellspacing=5 cellpadding=5>
    <tr> 
      <td valign=top><strong>Item Name:</strong></td>
      <td valign=top> <?php echo "$item_name"; ?> <input name="item_name" type="hidden" value="<?php echo "$item_name"; ?>">
      </td>
    </tr>
    <tr> 
      <td valign=top><strong>Category:</strong></td>
      <td valign=top><?php echo "$category"; ?><input name="category" type="hidden" value="<?php echo "$category"; ?>"></td>
    </tr>
    <tr> 
      <td valign=top><strong>Item Description:</strong></td>
      <td valign=top><?php echo "$item_desc"; ?><input name="item_desc" type="hidden" value="<?php echo "$item_desc"; ?>"></td>

    </tr>
    <tr> 
      <td valign=top><strong>Item Type:</strong></td>
      <td valign=top><?php echo "$item_type"; ?><input name="item_type" type="hidden" value="<?php echo "$item_type"; ?>"></td>
    </tr>
    <tr> 
      <td valign=top><strong>Name:</strong></td>
      <td valign=top><?php echo "$name"; ?><input name="name" type="hidden" value="<?php echo "$name"; ?>"></td>
    </tr>
    <tr> 
      <td valign=top><strong>Email:</strong></td>
      <td valign=top><?php echo "$email"; ?><input name="email" type="hidden" value="<?php echo "$email"; ?>"></td>
    </tr>

 <?php
	$sql = "SELECT * FROM review_items_supplement order by id asc";
	$sql_result = mysql_query($sql)	or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));
	while ($row = mysql_fetch_array($sql_result)) {
		
		$sql = "select * from review_items_user_supplement_data where review_item_id =".$sel_record." and item_supplement_id = " . $row['id'];
		$supplement_data = mysql_query($sql) or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));
		$data = mysql_fetch_array($supplement_data);
?>
    
    <tr>
      <td><strong><?php echo $row['itemname'] ?></strong></td>
      <td align="left"><?php echo $data['value'] ?><input name="review_supplement_item_<?php echo $row['id'] ?>" type="hidden" value="<?php echo $data['value'] ?>" /></td>
    </tr>
    
<?php
	}
?>

    <tr> 
      <td valign=top><strong>Reason for Deletion (will be emailed to user):</strong></td>
      <td valign=top>
      <textarea name="reason" cols="30" rows="10" id="reason"></textarea></td>
    </tr>
    <tr> 
      <td align=center colspan=2>   
  <INPUT type="submit" value="Delete Item"> 
      </td>
    </tr>
  </table>
</FORM>
<div align="center">Back to <a href="admin_menu.php?<?php echo htmlspecialchars(SID); ?>">Menu</a><br /></div>
<?php
BodyFooter(); 
}
?>
