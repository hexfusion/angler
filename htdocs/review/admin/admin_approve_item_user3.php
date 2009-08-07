<?php
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


$item_image = $_POST['item_image'];
$sel_record = $_POST['sel_record'];
$item_name = $_POST['item_name'];
$item_desc = $_POST['item_desc'];
$item_type = $_POST['item_type'];
$cat_id_cloud = $_POST['category'];

$sql = 'select cat_id_cloud, category from review_category_list where cat_id_cloud = ' . mysql_real_escape_string($cat_id_cloud) . '';
		$result = mysql_query($sql);
$row = mysql_fetch_array( $result );
$category = $row['category'];

$name = $_POST['name'];
$email = $_POST['email'];
$item_image = $_POST['item_image'];
$delete_image = $_POST['delete_image'];

if ($delete_image == "y") {
$base = $_SERVER['DOCUMENT_ROOT'];
$item_image = "$base$directory/images/items/$item_image";

 if (file_exists($item_image)) {                                  
                       @unlink($item_image);                
                   }      

$item_image = "";
}


	$sql = "INSERT INTO review_items
	SET 
		item_name='" . mysql_real_escape_string($item_name) . "',
		item_desc='" . mysql_real_escape_string($item_desc) . "',
		item_type='" . mysql_real_escape_string($item_type) . "',
		category='" . mysql_real_escape_string($category) . "',
		category_id='" . mysql_real_escape_string($cat_id_cloud) . "'
	";
	
$result = @mysql_query($sql,$connection)
	or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));

$item_id = mysql_insert_id();

///////////////////////////////////////////////////////////////////////////////////////
	$new_item_id = $item_id;
	
	$sql = "SELECT * FROM review_items_supplement order by id asc";
	$sql_result = mysql_query($sql)	or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));
	while ($row = mysql_fetch_array($sql_result)) {
		$textarea_name = "review_supplement_item_" . $row['id'];
		$supplement_item_value = $_POST[$textarea_name];
		$checkbox_name = "show_review_supplement_item_" . $row['id'];
		if(!isset($_POST[$checkbox_name])) {
			$supplement_item_selected = 0;
		}else{
			$supplement_item_selected = 1;
		}
		$sql = "insert into review_items_supplement_data (review_item_id, item_supplement_id, value, selected) values (".$new_item_id.", " . $row['id'] . ", '".$supplement_item_value."', ".$supplement_item_selected.")";
		mysql_query($sql)	or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));
	}

///////////////////////////////////////////////////////////////////////////////////////
//get image extension
$path_parts = pathinfo("$item_image");
$exts = $path_parts['extension'];


$base = $_SERVER['DOCUMENT_ROOT'];
$imageold = "$base$directory/images/items/$item_image";
$imagenew = "$base$directory/images/items/$new_item_id.$exts";


 if (file_exists($imageold)) {                                  
					   rename("$imageold", "$imagenew");    
                 } 



//stripslashes so they won't appear in the email
$item_desc = stripslashes($item_desc);	
$item_name = stripslashes($item_name);
$item_type = stripslashes($item_type);
$category = stripslashes($category);
$name = stripslashes($name);
$email = stripslashes($email);
$item_image = "$new_item_id.$exts";

if ($delete_image != "y" && $exts != "") {
//update the database to show the name of the image
	$sql = "UPDATE review_items SET 
item_image='$item_image'
WHERE item_id = \"$item_id\"";

$result = @mysql_query($sql,$connection)
	or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));
}



//Send an email to the User who Submitted the Item.
$msg = "<a href=$url>$sitename</a> - User Submitted Item for Review<BR><BR>\n\n";
$msg .= "Hello $name,<BR><BR>\n\n";
$msg .= "You visited $sitename and submitted a suggested item for review.\n\n";
$msg .= "Here is the address if you'd like to write a review: <BR><BR><a href=$url$directory/index2.php?item_id=$item_id>$url$directory/index2.php?item_id=$item_id</a> <BR><BR>\n\n";
$msg .= "<b>Members Name</b>:		$name<BR>\n";
$msg .= "<b>Members E-Mail</b>:		$email<BR>\n";
$msg .= "<b>Item Name</b>:			$item_name<BR>\n";
$msg .= "<b>Item Description</b>:	$item_desc<BR>\n";
$msg .= "<b>Item Type</b>:			$item_type<BR>\n";
$msg .= "<b>Category</b>:			$category<BR><BR>\n\n\n";

$msg .= "Thank you for your input!!!<BR><BR><BR>\n\n\n";

$subject = "$sitename - User Submitted Item for Review";

/* To send HTML mail, you can set the Content-type header. */
$mailheaders  = "MIME-Version: 1.0\n";
$mailheaders .= "Content-type: text/html; charset=iso-8859-1\n";

/* additional headers */
$to = "$email";
//$mailheaders .= "To: $name <$email>\n";
$mailheaders .= "From: $admin <$admin>\n";
$mailheaders .= "Reply-To: $admin\n\n";
mail($to, $subject, $msg, $mailheaders);


//Delete record from review_items_user database as it has already been inserted into review_items.
	$sql = "DELETE FROM review_items_user WHERE item_id = \"$sel_record\"";
    $sql_result = mysql_query($sql) or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error())); 

	$sql = "DELETE FROM review_items_user_supplement_data  WHERE review_item_id = \"$sel_record\"";
    $sql_result = mysql_query($sql) or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error())); 


	if (!$sql_result) {  
   BodyHeader("$sitename:  Delete A User Sumbitted - UNSUCCESSFUL"); 
		echo "<P>Couldn't delete record!";
BodyFooter(); 
	} 

#Send a message to the browser
BodyHeader("Your Item has been added!","","");
?>
<center>
  <p>Your item account has been added. </p>
  <p>Use the following link to allow users review access</p>
  <p>
    <input name="textfield" type="text" value="<?php echo "$url$directory/index2.php?item_id=$item_id"; ?>" size="60">
    <BR>
  </p>
  <div align="center">Back to <a href="admin_menu.php?<?php echo htmlspecialchars(SID); ?>">Menu</a></div>
  <br />
</center>
<?php
BodyFooter();
exit;
?>
