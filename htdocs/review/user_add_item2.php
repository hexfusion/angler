<?php
session_start();

include ("./body.php");
include ("./functions.php");
include ("./config.php");
include ("./f_secure.php");

//if (isset($_POST['username']) && isset($_POST['passtext'])) {
	// first check if the number submitted is correct
	$number   = $_POST['txtNumber'];
	

$category = makeStringSafe($_POST['category']);
$category = str_replace('+', ' ', $category);

	
		$sql="select * from review_category_list where cat_id_cloud = '$category'"; 
		$sql_result = mysql_query($sql);
		while ($row = mysql_fetch_array($sql_result)) {
		//	return stripslashes($row["cat_id_cloud"]);	
			
			$cat_id_cloud = $row["cat_id_cloud"];
			$category = $row["category"];
		}	

$item_name = makeStringSafe($_POST['item_name']);
$item_desc = makeStringSafe($_POST['item_desc']);
$item_type = makeStringSafe($_POST['item_type']);
$category_id = "$cat_id_cloud";
$name = makeStringSafe($_POST['name']);
$email = makeStringSafe($_POST['email']);


$_SESSION['item_name'] = "$item_name";
$_SESSION['item_desc'] = "$item_desc";
$_SESSION['item_type'] = "$item_type";
$_SESSION['category'] = "$category";
$_SESSION['name'] = "$name";
$_SESSION['email'] = "$email";



	if (md5($number) != $_SESSION['image_random_value']) {
	BodyHeader("You need to enter the correct verification number!","",""); 
	?>You need to enter the correct verification number!  Click <a href="user_add_item.php?<?php echo htmlspecialchars(SID); ?>">here</a> to try again.<?php
	// remove the random value from session			
	$_SESSION['image_random_value'] = '';
	BodyFooter();
	exit;
	}
	//}

//check to make sure an item was entered.
if ($item_name == "") {
BodyHeader("Please enter an item name!","","");	
echo "Please click the back button on your browser and enter an item name";
BodyFooter();
exit;
}


//check user input and remove any reference to javascript.
$errjava = "<font color=red><BR><BR><B>No Javascript is allowed!  Please click edit and remove the offending code.<BR><BR></B></font>";

$item_name = preg_replace("'<script[^>]*?>.*?</script>'si", "$errjava", $item_name);
$item_desc = preg_replace("'<script[^>]*?>.*?</script>'si", "$errjava", $item_desc);
$item_type = preg_replace("'<script[^>]*?>.*?</script>'si", "$errjava", $item_type);
$category = preg_replace("'<script[^>]*?>.*?</script>'si", "$errjava", $category);
$name = preg_replace("'<script[^>]*?>.*?</script>'si", "$errjava", $name);
$email = preg_replace("'<script[^>]*?>.*?</script>'si", "$errjava", $email);

//check to see if item already exists
$sql = "SELECT item_name FROM 
		review_items where item_name='" . mysql_real_escape_string($item_name) . "'

		";
			$sql_result = mysql_query($sql)
		or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));

	$num = mysql_numrows($sql_result);
	
	if ($num >=1) {
BodyHeader("Item already exists!","","");	
echo "The Item you submitted ($item_name) already exists.  Please click the back button on your browser and enter a different Item";
BodyFooter();
exit;
}

if ($item_name != "") {

	$sql = "INSERT INTO review_items_user
	SET 
	item_name='" . mysql_real_escape_string($item_name) . "',
item_desc='" . mysql_real_escape_string($item_desc) . "',
item_type='" . mysql_real_escape_string($item_type) . "',
category='" . mysql_real_escape_string($category) . "',
category_id =" . $category_id . ",
name='" . mysql_real_escape_string($name) . "',
email='" . mysql_real_escape_string($email) . "'
	";

$result = @mysql_query($sql,$connection) or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));

///////////////////////////////////////////////////////////////////////////////////////
	$new_item_id = mysql_insert_id();
	
	$sql = "SELECT * FROM review_items_supplement order by id asc";
	$sql_result = mysql_query($sql)	or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));
	while ($row = mysql_fetch_array($sql_result)) {
		$textarea_name = "review_supplement_item_" . $row['id'];
		$supplement_item_value = $_POST[$textarea_name];
		$sql = "insert into review_items_user_supplement_data (review_item_id, item_supplement_id, value) values (".$new_item_id.", " . $row['id'] . ", '".$supplement_item_value."')";
		mysql_query($sql) or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));
	}

///////////////////////////////////////////////////////////////////////////////////////


}

$item_id=mysql_insert_id();


$item_desc = stripslashes($item_desc);	
$item_name = stripslashes($item_name);
$item_type = stripslashes($item_type);
$category = stripslashes($category);
$name = stripslashes($name);
$email = stripslashes($email);

//Send an email to the administrator with the users information.

$msg = "<a href=$url>$sitename</a> - User Submitted Item for Review<BR><BR>\n\n";
$msg .= "<b>Members Name</b>:		$name<BR>\n";
$msg .= "<b>Members E-Mail</b>:		$email<BR>\n";
$msg .= "<b>Item Name</b>:			$item_name<BR>\n";
$msg .= "<b>Item Description</b>:	$item_desc<BR>\n";
$msg .= "<b>Item Type</b>:			$item_type<BR>\n";
$msg .= "<b>Category</b>:			$category<BR><BR>\n\n";

$msg .= "Please Approve this review item in the admin area.  
<BR>
<a href=$url$directory/admin>$url$directory/admin</a><BR><BR><BR><BR>\n";

$subject = "$sitename - User Submitted Item for Review";
/* To send HTML mail, you can set the Content-type header. */
$mailheaders  = "MIME-Version: 1.0\n";
$mailheaders .= "Content-type: text/html; charset=iso-8859-1\n";

/* additional headers */
$to = $admin;
$mailheaders .= "From: $name <$email> \n";
$mailheaders .= "Reply-To: $email\n\n";

mail($to, $subject, $msg, $mailheaders);

#Send a message to the browser
BodyHeader("Your Item has been submitted.","","");
?>
<link href="review.css" rel="stylesheet" type="text/css" />

<center>
  <p>Your item  has been submitted. </p>
  <p>If you included your name and email address you'll be contacted if this item was approved. </p>
  <p class="index2-Orange">Would you like to upload an image to be displayed with this item? <a href="user_add_item_upload.php?item_id=<?php echo "$new_item_id"; ?>">YES</a> </p>
  <p>    <BR>
  </p>
<?php
$directory = @$_FILES['directory'];
?>
  <div align="center">Back to <a href="<?php echo "$directory"; ?>index.php?<?php echo htmlspecialchars(SID); ?>">Home</a></div>
  <br />
</center>
<?php
BodyFooter();
exit;
?>
