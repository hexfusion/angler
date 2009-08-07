<?
//if a session does not yet exist for this user, start one
session_start();

include ("../body.php");
include ("../functions.php");
include ("../config.php");

$item_id[] = $_POST["item_id"];
$sortorder[] = $_POST["sortorder"];
$item_name[] = $_POST["item_name"];

$total = count($item_id);
$category = $_POST['category'];

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
   <a href="admin_menu.php">Try again</a></font> 
  <?
BodyFooter();  

exit;

}	

for($i=0;$i<$total;$i++) {
	$sql = "UPDATE review_items SET 
sortorder='$sortorder[$i]'
WHERE item_id = '$item_id[$i]'";

        $sql_result = mysql_query($sql) 
            or die( "Couldn't execute update query."); 
}

	if (!$sql_result) {  
   
		echo "<P>Couldn't edit record!";
		exit;
 
} else {


$sql = "SELECT * FROM 
			review_items 
WHERE category = '$category'
ORDER BY item_name ASC";

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
  <?


while ($row = mysql_fetch_array($sql_result)) {

	$item_name = $row["item_name"];
	$item_type = $row["item_type"];
	$item_id = $row["item_id"];
	$sortorder = $row["sortorder"];
?>
  <tr>
    <th scope="col"><? echo "$item_id"; ?></th>
    <th scope="col"><? echo "$item_name"; ?></th>
    <th scope="col"><? echo "$item_type"; ?></th>
    <th scope="col"><? echo "$sortorder"; ?></th>
  </tr>
  <?
}
?>
</table>
<P>
<div align="center">Back to <a href="admin_menu.php?<?=SID?>">Menu</a><br></div>
  <? 
				
        BodyFooter(); 
 }
 
?>

