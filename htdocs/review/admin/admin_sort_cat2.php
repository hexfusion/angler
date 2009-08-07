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

$catorder = $_POST["catorder"];
$category = $_POST["category"];
$cat_id_cloud = $_POST["cat_id_cloud"];

$total = count($catorder);

for($i=0;$i<$total;$i++) {
	$sql = "UPDATE review_category_list SET catorder = '$catorder[$i]' WHERE cat_id_cloud = '$cat_id_cloud[$i]'";
    $sql_result = mysql_query($sql) or die( "Couldn't execute update query."); 
}

	if (!$sql_result) {  
		echo "<P>Couldn't edit record!";
		exit;
} else {


$sql = "SELECT * FROM review_category_list ORDER BY category ASC";

			$sql_result = mysql_query($sql)
			or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));

		if (!$sql_result) {  
   
			echo "<P>Couldn't get list!";
			exit;
		}


		BodyHeader("$sitename:  Select Display Order"); 
        ?>
<h1><center><BR>
<font face="Verdana, Arial, Helvetica, sans-serif" size="3">You have selected the following display order: </font>
</center></h1>
<table width="52%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <th scope="col">Category</th>
    <th scope="col">Sort Order</th>
  </tr>
  <?php


while ($row = mysql_fetch_array($sql_result)) {

	$category = $row["category"];
	$catorder = $row["catorder"];
?>
  <tr>
    <th scope="col"><?php echo "$category"; ?></th>
    <th scope="col"><?php echo "$catorder"; ?></th>
  </tr>
  <?php
}
?>
</table>
<P>
<div align="center">Back to <a href="admin_menu.php?<?php echo htmlspecialchars(SID); ?>">Menu</a><br /></div>
  <?php 
				
        BodyFooter(); 
 }
 
?>

