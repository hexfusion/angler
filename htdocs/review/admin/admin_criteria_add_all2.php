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
	
	//insert which criterias to be allowed for this item
	//$query="DELETE FROM item_rating_criteria WHERE item_id=" . $sel_record;
	//mysql_query($query) or die(mysql_error());
	
	$category = $_POST['category'];
	$chkcId = $_POST["chk$cId"];
	
	if ($category != "") {
		$sql = "SELECT item_id FROM review_items WHERE category_id = " . $category ; 
	} else {
		$sql = "SELECT item_id FROM review_items"; 
	}
		
	$sql_result = mysql_query($sql) or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));
	while ($row = mysql_fetch_array($sql_result)) {
		$item_id = $row["item_id"];
	
		$query="SELECT criteriaId FROM rating_criteria WHERE isActive='T'";
		$result=mysql_query($query) or die(mysql_error());
		while($row=mysql_fetch_array($result)){
			$cId=$row["criteriaId"];
			if(isset($_POST["chk$cId"]) && $_POST["chk$cId"]==$cId){
				$query="INSERT INTO item_rating_criteria VALUES($item_id,$cId)";
				mysql_query($query) or die(mysql_error());
			}
		}
	}// end while $row
	BodyHeader("Review Criteria Changed","",""); 
?>
<h1><center><BR>
<font face="Verdana, Arial, Helvetica, sans-serif" size="3">You have added those criteria to all Items selected.</font>
</center></h1>

<P>
<div align="center">Back to <a href="admin_menu.php?<?php echo htmlspecialchars(SID); ?>">Menu</a><br /></div>
  <?php 	
        BodyFooter(); 

?>