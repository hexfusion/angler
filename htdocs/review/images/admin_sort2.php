<?
//if a session does not yet exist for this user, start one
session_start();

include ("../body.php");
include ("../functions.php");
include ("../config.php");

$category = $_POST['sel_record'];

echo "$category is category";

//Check to see that the username and Password entered have admin access.
$sqlaccess = "SELECT username, passtext
		FROM admin 
		WHERE username='" . $_SESSION['admin_username'] . "' 
		AND passtext = '" . $_SESSION['admin_passtext'] . "'
		";

	$resultaccess = mysql_query($sqlaccess)
		or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));

	$numaccess = mysql_numrows($resultaccess);


	if ($numaccess == 0) {
BodyHeader("Access Not Allowed!");
?>
 <P align="center"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">To access the Administration area you need to have approved access. The username and Password you entered are not approved!<br> 
   <a href="index.php">Try again</a></font> 
  <?
BodyFooter();  

exit;

}

$sql = "SELECT * FROM 
			review_items 
WHERE category = '$category'
ORDER BY item_name ASC";

echo "$sql is sql";

			$sql_result = mysql_query($sql)
			or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));

		if (!$sql_result) {  
   
			echo "<P>Couldn't get list!";

		} else {

		BodyHeader("$sitename: Select Display Order"); 
        ?>
<center><BR>
  <h2><font face="Verdana, Arial, Helvetica, sans-serif" size="3">Sort Order for Review Items - Category: <? echo "$category"; ?> <br>
      <font size="2">(Enter numerically the order in which you would like items displayed)</font></font></h2>
</center>


<FORM method="POST" action="admin_sort3.php?<?=SID?>">


 <table width="52%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <th scope="col">Item ID</th>
    <th scope="col">Item Name</th>
    <th scope="col">Item Type</th>
    <th scope="col">Sort Order</th>
  </tr>


<?
while ($row = mysql_fetch_array($sql_result)) {

	$category = $row["category"];
	$item_name = $row["item_name"];
	$item_type = $row["item_type"];
	$item_id = $row["item_id"];
	$sortorder = $row["sortorder"];
?>

  <tr>
    <th scope="col"><? echo "$item_id"; ?><input name="item_id[]" type="hidden" size="5" maxlength="3" value=<? echo "$item_id"; ?>></th>
    <th scope="col"><? echo "$item_name"; ?><input name="item_name[]" type="hidden" size="5" maxlength="3" value=<? echo "$item_name"; ?>></th>
    <th scope="col"><? echo "$item_type"; ?><input name="item_type[]" type="hidden" size="5" maxlength="3" value=<? echo "$item_type"; ?>></th>
    <th scope="col"><input name="sortorder[]" type="text" size="5" maxlength="3" value=<? echo "$sortorder"; ?>>

<input name="category" type="hidden" value="<? echo "$category"; ?>">
</th>
  </tr>

<?
}
?>

</td>
   </tr>

 
 
	
<tr>
<td align=center><BR>
<INPUT type="submit" value="Save Order">
</td>
</tr>
</table>

</FORM>

<div align="center">Back to <a href="admin_menu.php?<?=SID?>">Menu</a><br></div>
<?
        BodyFooter();
		exit;
}
?>

