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

	$sql = "SELECT cat_id_cloud, category, catorder FROM review_category_list";
	$sql_result = mysql_query($sql)	or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));

		if (!$sql_result) {  
   
			echo "<P>Couldn't get list!";

		} else {

		BodyHeader("$sitename: Select Category Display Order"); 
        ?>
<center><BR>
  <h2><font face="Verdana, Arial, Helvetica, sans-serif" size="3">Category Sort Order for Review Items<br />
      <font size="2">(Enter numerically the order in which you would like items displayed)</font></font></h2>
</center>


<FORM method="POST" action="admin_sort_cat2.php?<?php echo htmlspecialchars(SID); ?>">


 <table width="52%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <th scope="col">Category</th>
    <th scope="col">Sort Order</th>
  </tr>


<?php
while ($row = mysql_fetch_array($sql_result)) {

	$category = $row["category"];
	$cat_id_cloud = $row["cat_id_cloud"];
	$catorder = $row["catorder"];
?>

  <tr>
    <th scope="col">
		<?php echo "$category"; ?>
        <input name="category[]" type="hidden" value="<?php echo $category; ?>">
        <input name="cat_id_cloud[]" type="hidden" value="<?php echo $cat_id_cloud; ?>">
    </th>

    <th scope="col">
	<input name="catorder[]" type="text" value="<?php echo $catorder; ?>">
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

