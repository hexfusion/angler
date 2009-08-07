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

$category = $_POST['sel_record'];

$sql = "SELECT * FROM review_items";
if ($category != -1){
	$sql .= " WHERE category_id = " . $category;
}
$sql .= " ORDER BY item_name ASC";

			$sql_result = mysql_query($sql)
			or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));

		if (!$sql_result) {  
   
			echo "<P>Couldn't get list!";

		} else {

		BodyHeader("$sitename: Select Display Order"); 
        ?>
<center><BR>
  <h2><font face="Verdana, Arial, Helvetica, sans-serif" size="3">Sort Order for Review Items - Category: <?php echo "$category"; ?> <br />
      <font size="2">(Enter numerically the order in which you would like items displayed)</font></font></h2>
</center>


<FORM method="POST" action="admin_sort3.php?<?php echo htmlspecialchars(SID); ?>">
 <table width="52%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <th scope="col">Item ID</th>
    <th scope="col">Item Name</th>
    <th scope="col">Item Type</th>
    <th scope="col">Sort Order</th>
  </tr>
<?php
while ($row = mysql_fetch_array($sql_result)) {
	$category_id = $row["category_id"];
	$item_name = stripslashes($row["item_name"]);
	$item_type = stripslashes($row["item_type"]);
	$item_id = $row["item_id"];
	$sortorder = $row["sortorder"];
?>
  <tr>
    <th scope="col"><?php echo "$item_id"; ?><input name="item_id[]" type="hidden" size="5" maxlength="3" value="<?php echo "$item_id"; ?>"></th>
    <th scope="col"><?php echo "$item_name"; ?><input name="item_name[]" type="hidden" size="5" maxlength="3" value="<?php echo "$item_name"; ?>"></th>
    <th scope="col"><?php echo "$item_type"; ?><input name="item_type[]" type="hidden" size="5" maxlength="3" value="<?php echo "$item_type"; ?>"></th>
    <th scope="col"><input name="sortorder[]" type="text" size="5" maxlength="3" value="<?php echo "$sortorder"; ?>">

<input name="category" type="hidden" value="<?php echo "$category_id"; ?>">
</th>
  </tr>

<?php
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

<div align="center">Back to <a href="admin_menu.php?<?php echo htmlspecialchars(SID); ?>">Menu</a><br /></div>
<?php
        BodyFooter();
		exit;
}
?>

