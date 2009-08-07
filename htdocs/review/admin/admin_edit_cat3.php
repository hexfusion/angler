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

$category = addslashes($_POST["category"]);
$sel_record = $_POST["sel_record"];
$sub_recordOf=$_POST["sub_recordOf"];

	if (!$sel_record) {
header("Location: http://$url$directory/admin/admin_edit_cat1.php?".sid);
			exit;
		}
/*check if parent is being made child of child  */
$flag="0";

function checkParentOfSub($catId){
	global $flag,$sub_recordOf,$sel_record;
	$query = 'Select * from review_category_list where parent_id = ' .$catId ;
	$result = mysql_query($query);

	while($row=mysql_fetch_array($result)){
		if($row["cat_id_cloud"] == $sub_recordOf){
			 $flag="1";
		}
		checkParentOfSub($row["cat_id_cloud"]);
	}
}


checkParentOfSub($sel_record);

if($flag=="0"){
	$sql = "UPDATE review_category_list SET category = '$category',	parent_id = '$sub_recordOf'	WHERE cat_id_cloud = " . $sel_record;
    $sql_result = mysql_query($sql) or die( "Couldn't execute update query."); 

	if (!$sql_result) {  
		echo "<P>Couldn't edit record!";
	} 

	$sql = "UPDATE review_items SET category_id = " .$sel_record . " WHERE category_id = " . $sel_record;
    $sql_result = mysql_query($sql)  or die( "Couldn't execute update query."); 

	if (!$sql_result) {  
		echo "<P>Couldn't edit record!";
	} else {
		BodyHeader("$sitename:  Edit A Review","",""); 
?>
	
	<h1><center><BR>
	<font face="Verdana, Arial, Helvetica, sans-serif" size="3">You have successfully
	edited the following category: </font>
	</center></h1>
	<table align="center" cellpadding=5 cellspacing=5>
  	<tr>
    	<td valign=top><strong>Category:</strong></td>
    	<td valign=top><?php echo stripslashes($category); ?> </td>
  	</tr>
	</table>
	<P> 
	<div align="center">Back to <a href="admin_menu.php?<?php echo htmlspecialchars(SID); ?>">Menu</a><br /></div>
<?php
}
}else{

		BodyHeader("$sitename:  Edit A Review","",""); 
?>
	<h1><center><BR>
	<font face="Verdana, Arial, Helvetica, sans-serif" size="3">Could not
	edit the following category, you can not make a parent category as child of a child category: </font>
	</center></h1>
	<table align="center" cellpadding=5 cellspacing=5>
  	<tr>
    	<td valign=top><strong>Category:</strong></td>
    	<td valign=top><?php echo stripslashes($category); ?> </td>
  	</tr>
	</table>
	<P> 
	<div align="center">Back to <a href="admin_menu.php?<?php echo htmlspecialchars(SID); ?>">Menu</a><br /></div>
<?php
}	
BodyFooter(); 

exit;
?>
