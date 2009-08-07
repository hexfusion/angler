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


		$sql = "SELECT * FROM review_items_user
						";
			
			$sql_result = mysql_query($sql)
		or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));

		if (!$sql_result) {  
   
			echo "<P>Couldn't get list!";

		} else {

		BodyHeader("$sitename: Delete a User Submitted Item"); 
        ?>
<center>
  <h2><font face="Verdana, Arial, Helvetica, sans-serif" size="3">Select an item 
    from the 
    <?php echo "$sitename"; ?>
    Database</font></h2>
</center>


<FORM method="POST" action="admin_delete_item_user2.php?<?php echo htmlspecialchars(SID); ?>">

<table cellspacing=5 cellpadding=5>

<div align="center">
 <center>
 <table border="0" cellpadding="0" cellspacing="0" width="52%">
  <tr>
   <td width="100%">
   <p align="center"><strong>Item Name/Category:</strong></td>
  </tr>
  <tr>
   <td width="100%">
   <p align="center">
<select name="sel_record">
<option value=""> -- Select a Review -- </option>
<?php
while ($row = mysql_fetch_array($sql_result)) {

	$item_name = stripslashes($row["item_name"]);
	$category = getCategoryName($row["category_id"]);
	$item_id = $row["item_id"];

echo "
	<option value=\"$item_id\">$item_name : $category </option>
";

}

echo "
</select>
</td>
   </tr>

 </center>
 </div>
	
<tr>
<td align=center colspan=2><BR>
<INPUT type=\"submit\" value=\"Select Item\">
</td>
</tr>

</table>

</FORM>

";
?>
<div align=center>Back to <a href=admin_menu.php?<?php echo htmlspecialchars(SID); ?>>Menu</a><br /></div>

<?php
        BodyFooter();
		exit;
}
?>

