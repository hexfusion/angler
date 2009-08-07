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
header("Location: http://$url$directory/admin/admin_approve_item.php?".sid);
			exit;
		}

//get list of existing categories
$sql_cat = "SELECT * FROM review_category_list
				 ";
			
			$sql_result_cat = mysql_query($sql_cat)
		or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));

		if (!$sql_result_cat) {  
   
			echo "<P>Couldn't get list!";
exit;
		} 


			$sql = "SELECT * FROM review_items_user 
					WHERE item_id='$sel_record'
					";
		
					$sql_result = mysql_query($sql)
  or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));


		if (!$sql_result) {  
   
			echo "<P>Couldn't get item!";

		} else {

			 while ($row = mysql_fetch_array($sql_result)) {
	$item_id = $row["item_id"];
	$item_name = $row["item_name"];
	$item_type = $row["item_type"];
	$item_desc = $row["item_desc"];
	$name = $row["name"];
	$item_image = $row["item_image"];
	$category = $row["category"];
	$category_id = $row["category_id"];
	$email = $row["email"];
}			

BodyHeader("Approve User Submitted Item","","");
?> 
<center> 
  <BR> 
  <h1><font face="Verdana, Arial, Helvetica, sans-serif" size="3">You have selected the following User Submitted Item for Approval:</font></h1> 
</center> 
<table cellspacing=5 cellpadding=5>
  <form method="POST" action="admin_approve_item_user3.php?<?php echo htmlspecialchars(SID); ?>">
    <input type="hidden" name="sel_record" value=<?php echo $sel_record; ?> />
	  <input type="hidden" name="item_image" value=<?php echo $item_image; ?> />
    <tr>
      <td valign=top><strong>Item Name:</strong></td>
      <td valign=top><input type="text" name="item_name" value="<?php echo stripslashes($item_name); ?>" />      </td>
   <?php if ($item_image != "") { ?>   <td rowspan="6" valign=top><p><img src="<?php echo "$directory/images/items/$item_image"; ?>" alt="image" /></p>
      <p>
        <input name="delete_image" type="checkbox" id="delete_image" value="y" />
      Check box to <font color="#FF0000">DELETE</font> image </p>
      </td>   <?php } ?>
    </tr>
    <tr>
      <td valign=top><strong>Item Type:</strong></td>
      <td valign=top><input type="text" name="item_type" value="<?php echo stripslashes($item_type); ?>" />      </td>
    </tr>
    <tr><td colspan="2"><div align="left"><script src="<?php echo $directory; ?>/admin/nicEditor/nicEdit.js" type="text/javascript"></script>
<script type="text/javascript">
bkLib.onDomLoaded(function() {
new nicEditor({fullPanel : true, iconsPath : '<?php echo $directory; ?>/admin/nicEditor/nicEditorIcons.gif'}).panelInstance('item_desc');
});
</script>
   <textarea id="item_desc" name="item_desc" style="width: 580px; height: 300px;"><?php echo stripslashes($item_desc); ?></textarea>
         
      </div><br /><br /></td></tr>          
    <tr>
      <td valign=top><strong>Submitter's Name:</strong></td>
      <td valign=top><input type="text" name="name" value="<?php echo stripslashes($name); ?>" size=35 maxlength=75 />      </td>
    </tr>
    <tr>
      <td valign=top><strong>Category:</strong></td>
      <td valign=top><select name="category">
        <option value=""> -- Select a Category -- </option>
        <?php

	function displayCat($catId, $dashes = 0){
		global $category_id;
		$st = "";
		$query = 'select * from review_category_list where parent_id = ' .$catId. ' order by catorder';
		for($i=0; $i<$dashes; $i++){
			$st .= "--";
		}
		
		$dashes += 2;
		$result = mysql_query($query);
		while($row = mysql_fetch_array($result)){
			if ($row["cat_id_cloud"] == $category_id){
				echo "<option value='" .$row["cat_id_cloud"]."' selected >" .$st . $row["category"] . "</option>";
			}else{
				echo "<option value='" .$row["cat_id_cloud"]."'>" .$st . $row["category"] . "</option>";
			}
			displayCat($row["cat_id_cloud"], $dashes);
		}
	}
	displayCat(-1);



?>
      </select></td>
    </tr>
    <tr>
      <td valign=top><strong>Email:</strong></td>
      <td valign=top><input type="text" name="email" value="<?php echo stripslashes($email); ?>" size=35 maxlength=75 />      </td>
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
      <td>&nbsp;<strong><?php echo $row['itemname'] ?></strong></td>
      <td align="center"><textarea cols="45" rows="3" name="review_supplement_item_<?php echo $row['id'] ?>" ><?php echo $data['value'] ?></textarea>&nbsp;&nbsp;<input name="show_review_supplement_item_<?php echo $row['id'] ?>" type="checkbox" value="<?php echo $row['id'] ?>" 
	  <?php 
	  	if($data['selected'] > 0 ){
			echo " checked ";
		} 
	  ?>
       />        
        Select</td>
    </tr>
    
<?php
	}
?>
    
  
    <tr>
      <td align=center colspan=3><input name="submit" type="submit" value="Approve Item" />      </td>
    </tr>
  </form>
</table>
<div align="center">Back to <a href="admin_menu.php?<?php echo htmlspecialchars(SID); ?>">Menu</a><br /></div>
<?php
 BodyFooter(); 
}
?> 
