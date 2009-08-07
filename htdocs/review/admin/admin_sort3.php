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

$item_id = $_POST["item_id"];
$sortorder = $_POST["sortorder"];
$item_name = $_POST["item_name"];

$total = count($item_id);
$category_id = $_POST['category'];



for($i=0;$i<$total;$i++) {
	$sql = "UPDATE review_items SET sortorder='$sortorder[$i]' WHERE item_id = '$item_id[$i]'";

        $sql_result = mysql_query($sql) 
            or die( "Couldn't execute update query."); 
}

	if (!$sql_result) {  
   
		echo "<P>Couldn't edit record!";
		exit;
 
} else {


$sql = "SELECT * FROM review_items WHERE category_id = '$category_id' ORDER BY item_name ASC";

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
    <th scope="col">Item ID</th>
    <th scope="col">Item Name</th>
    <th scope="col">Item Type</th>
    <th scope="col">Sort Order</th>
  </tr>
  <?php


while ($row = mysql_fetch_array($sql_result)) {

	$item_name = stripslashes($row["item_name"]);
	$item_type = stripslashes($row["item_type"]);
	$item_id = $row["item_id"];
	$sortorder = $row["sortorder"];
?>
  <tr>
    <th scope="col"><?php echo "$item_id"; ?></th>
    <th scope="col"><?php echo "$item_name"; ?></th>
    <th scope="col"><?php echo "$item_type"; ?></th>
    <th scope="col"><?php echo "$sortorder"; ?></th>
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

