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
	
	$operation = $_GET['op'];
	if($operation){
		if($operation == "edit"){
			$sql = "update review_items_supplement set itemname = '" . makeStringSafe($_GET['value']) . "' where id = " . makeStringSafe($_GET['id']);
			mysql_query($sql) or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));			
		}else if($operation == "delete"){
			$sql = "delete from review_items_supplement where id = " . makeStringSafe($_GET['id']);
			mysql_query($sql) or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));
			$sql = "delete from review_items_supplement_data where item_supplement_id = " . makeStringSafe($_GET['id']);
			mysql_query($sql) or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));	
		}else if($operation == "new"){
			$sql = "insert into review_items_supplement(itemname) values ('". makeStringSafe($_GET['value'])."')";
			mysql_query($sql) or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));	
		}
	}
	
	
	$sql = "SELECT * FROM review_items_supplement order by id asc";
	$sql_result = mysql_query($sql)	or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));
	
	if (true) {  
		BodyHeader("Manage Item Features","",""); 
    ?>
<div align="center">
  <p><br />
    The following fields will appear with your item and are listed as additional features/information.</p>
 <br />

</div>
<FORM method="POST" action="admin_edit_item2.php?<?php echo htmlspecialchars(SID); ?>">
  <table align="center" width="500" border="0" cellspacing="0" cellpadding="0">

        <tr>
          <td height="18" align="right">&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
    <?php
		while ($row = mysql_fetch_array($sql_result)) {
    ?>

        <tr>
          <td align="right">&nbsp;</td>
          <td style="background-color:#FFFFCC">&nbsp;<?php echo $row["itemname"] ?></td>
          <td nowrap="nowrap"><input type="text" id="review_item_name_<?php echo $row["id"] ?>" value="<?php echo $row["itemname"] ?>"/> 
            &nbsp;<a href="<?php echo $_FILES['SELF']; ?>?op=edit&id=<?php echo $row["id"] ?>" onclick="this.href += '&value=' + document.getElementById('review_item_name_<?php echo $row["id"] ?>').value">Update</a>&nbsp;
            &nbsp;<a href="<?php echo $_FILES['SELF']; ?>?op=delete&id=<?php echo $row["id"] ?>" onclick="this.href += '&value=' + document.getElementById('review_item_name_<?php echo $row["id"] ?>').value">Delete</a>&nbsp;</td>
        </tr>
    <?php
		}
    ?>
    
    <tr>
      <td height="19" colspan="3" align="right"><hr color="#FFFFCC" width="60%"></td>
    </tr>
    <tr>
      <td align="right">&nbsp;</td>
      <td>Add a new item</td>
      <td><input type="text" name="new_review_item" id="new_review_item" value=""/>
      &nbsp;
      
      <a href="<?php echo $_FILES['SELF']; ?>?op=new" onclick="this.href += '&value=' + document.getElementById('new_review_item').value">Add New</a></td>
    </tr>
    <tr>
      <td align="right">&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td align="right">&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3" align="center">&nbsp;&nbsp;</td>
    </tr>
  </table>

</FORM>

<br /><br /><br />
<div align="center">Back to <a href="admin_menu.php?<?php echo htmlspecialchars(SID); ?>">Menu</a><br /></div>
<?php
        BodyFooter();
		exit;
}
?>

