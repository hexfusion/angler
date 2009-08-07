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
$item_name = stripslashes($_POST['item_name']);

if ($_POST['category']) {
$category = stripslashes($_POST['category']);
}

	if (!$sel_record) {
header("Location: http://$url$directory/admin/admin_del1.php?".sid);
			exit;
		}
	
	
	$sql = "DELETE FROM review_items WHERE item_id = \"$sel_record\"";
    $sql_result = mysql_query($sql) or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error())); 

	$sql = "DELETE FROM review_items_supplement_data  WHERE review_item_id = \"$sel_record\"";
    mysql_query($sql) or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error())); 


	if (!$sql_result) {  
   
		echo "<P>Couldn't delete record!";

	} else {
		$query="DELETE FROM item_rating_criteria WHERE item_id=" . $sel_record;
		mysql_query($query) or die(mysql_error());

		//delete review critias
		$query="SELECT id FROM review WHERE review_item_id=" . $sel_record;
		$result=mysql_query($query) or die(mysql_error());
		$str="";
		while($row=mysql_fetch_array($result)){
			if($str==""){
				$str=$row["id"];
			}else{
				$str="," . $row["id"];
			}
		}
		
		if($str!=""){
			$query="DELETE FROM rating_details WHERE review_id IN (" . $str . ")";
			mysql_query($query) or die(mysql_error());
		}
		
		BodyHeader("$sitename:  Delete A Review"); 
        ?>
<h1><font face="Verdana, Arial, Helvetica, sans-serif" size="3">You have deleted 
  <?php echo $item_name ?> from the Item Database</font></h1>
<P> 

<?php
	function getCategoryName($catId){
		$sql="Select * from review_category_list where cat_id_cloud = " . $catId;
		$sql_result = mysql_query($sql);
		while ($row = mysql_fetch_array($sql_result)) {
			return stripslashes($row["category"]);	
		}	
	}
?>

<table align="center">
  <tr> 
    <td valign=top><strong>Item Name:</strong></td>
    <td valign=top> <?php echo "$item_name" ?>
    </td>
  </tr>
  <tr> 
    <td valign=top><strong>Category:</strong></td>
    <td valign=top><?php echo getCategoryName($category) ?></td>
  
  </tr>
</table>
<div align="center">Back to <a href="admin_menu.php?<?php echo htmlspecialchars(SID); ?>">Menu</a><br /></div>
  <?php 
				
        BodyFooter(); 
 }
 
?>

