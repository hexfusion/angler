<?php
	session_start();
	
	//make sure user has been logged in.
	$validU = "";
	if (isset($_SESSION['valid_user']) && $_SESSION['valid_user']!="")$validU=$_SESSION['valid_user']; 
	
	if ($validU=="")
	{
		// User not logged in, redirect to login page
		header("Location: index.php");
	}
	
	include ("../body.php");
	include ("../functions.php");
	include ("../f_secure.php");
	include ("../config.php");
	
	$category = $_POST['category'];
	
	if ($_POST['category'] == "") {
		BodyHeader("Category Cannot Be Blank!","","");	
		echo "The category can not be blank.  Please click the back button on your browser and enter a category";
		BodyFooter();
		exit;
	}

	echo isset($_POST['parent']) . "***********";

	//check to see if category already exists
	$sql = "SELECT category FROM review_category_list where category = '$category' ";
	$sql_result = mysql_query($sql)	or die(sprintf("Couldn't execute sql, %s: %s", db_errno(), db_error()));

	$num = mysql_numrows($sql_result);
	
//	if ($num >=1) {
//BodyHeader("Category already exists!");	
//echo "The category you selected ($category) already exists.  Please click the back button on your browser and enter a different category";
//BodyFooter();
//exit;
//}
//	$sql = "INSERT INTO review_category_list SET category='$category'";
//	if(isset($_POST['parent'])){
//		$tres=mysql_query('Select count(category) from review_category_list where category="'.$_POST['parent'].'"');
//		if(mysql_result($tres,0,0)!=0)
//			$sql.=',parent="'.$_POST['parent'].'"';
//	}
//$result = @mysql_query($sql,$connection)
//	or die(sprintf("Couldn't execute sql, %s: %s", db_errno(), db_error()));
//
//
//#Send a message to the browser
//BodyHeader("Your category has been created!","","");
?>
<center>
  <p>Your category has been added. </p>
  <p>You may now add an item to review - click <a href="admin_add1.php?<?php echo htmlspecialchars(SID); ?>">here</a>. </p>
  <div align="center">Back to <a href="admin_menu.php?<?php echo htmlspecialchars(SID); ?>">Menu</a></div>
  <br />
</center>
<?php
BodyFooter();
exit;
?>