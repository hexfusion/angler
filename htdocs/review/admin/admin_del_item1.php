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

		$sql = "SELECT * FROM review_items
				 ORDER BY item_name ASC";
			
			$sql_result = mysql_query($sql)
		or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));

		if (!$sql_result) {  
   
			echo "<P>Couldn't get list!";

		} else {

		BodyHeader("$sitename: Delete an Item for Review"); 
        ?>
<center>
  <h2><font face="Verdana, Arial, Helvetica, sans-serif" size="3">Select an Item 
    from the <?php echo "$sitename"; ?> Database</font></h2>
</center>


<FORM method="POST" action="admin_del_item2.php?<?php echo htmlspecialchars(SID); ?>">

 <table width="52%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
   <td width="100%">
   <p align="center"><strong>Category/Name/Type:</strong></td>
  </tr>
  <tr>
   <td width="100%">
   <p align="center">
<select name="sel_record">
<option value="">-- Select an Item -- </option>
<?php
while ($row = mysql_fetch_array($sql_result)) {
	$item_name = stripslashes($row["item_name"]);
	$item_type = stripslashes($row["item_type"]);
	$category = stripslashes($row["category"]);
	$item_id = $row["item_id"];

echo "
	<option value=\"$item_id\">$category : $item_name : $item_type</option>
";

}
?>
</select>
</td>
   </tr>

 </center>
 </div>
	
<tr>
<td align=center colspan=2><BR>
<INPUT type="submit" value="Select Item">
</td>
</tr>

</table>

</FORM>

<div align=center>Back to <a href=admin_menu.php?<?php echo htmlspecialchars(SID); ?>>Menu</a><br /></div>

<?php
        BodyFooter();
		exit;
}
?>

