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
$item_desc = stripslashes($_POST['item_desc']);
$item_type = stripslashes($_POST['item_type']);
$name = stripslashes($_POST['name']);
$email = stripslashes($_POST['email']);
$category = stripslashes($_POST['category']);
$reason = stripslashes($_POST['reason']);

	if (!$sel_record) {
header("Location: http://$url$directory/admin/admin_menu.php?".sid);
			exit;
		}
	
	
	$sql = "DELETE FROM review_items_user WHERE item_id = \"$sel_record\"";
    $sql_result = mysql_query($sql) or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error())); 

	$sql = "DELETE FROM review_items_user_supplement_data  WHERE review_item_id = \"$sel_record\"";
    $sql_result = mysql_query($sql) or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error())); 


	if (!$sql_result) {  
   
		echo "<P>Couldn't delete record!";

	} else {
		BodyHeader("$sitename:  Delete A User Submitted Item"); 
        ?>
<h1><font face="Verdana, Arial, Helvetica, sans-serif" size="3">You have deleted 
  <?php echo $item_name ?> from the Database</font></h1>
<table align="center" cellpadding=5 cellspacing=5>
  <tr>
    <td valign=top><strong>Item Name:</strong></td>
    <td valign=top> <?php echo "$item_name"; ?> </td>
  </tr>
  <tr>
    <td valign=top><strong>Category:</strong></td>
    <td valign=top><?php echo "$category"; ?></td>
  </tr>
  <tr>
    <td valign=top><strong>Item Description:</strong></td>
    <td valign=top><?php echo "$item_desc"; ?></td>
  </tr>
  <tr>
    <td valign=top><strong>Item Type:</strong></td>
    <td valign=top><?php echo "$item_type"; ?></td>
  </tr>
  <tr>
    <td valign=top><strong>Name:</strong></td>
    <td valign=top><?php echo "$name"; ?></td>
  </tr>
  <tr>
    <td valign=top><strong>Email:</strong></td>
    <td valign=top><?php echo "$email"; ?></td>
  </tr>
  
  
///////////////////////////////////////////////////////////////////////////////////////
<?php
	$new_item_id = mysql_insert_id();
	
	$sql = "SELECT * FROM review_items_supplement order by id asc";
	$sql_result = mysql_query($sql)	or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));
	while ($row = mysql_fetch_array($sql_result)) {
		$textarea_name = "review_supplement_item_" . $row['id'];
		$supplement_item_value = $_POST[$textarea_name];
?>

  <tr>
    <td valign=top><strong><?php echo $row['itemname'] ?>:</strong></td>
    <td valign=top><?php echo $supplement_item_value; ?></td>
  </tr>

<?php
	}
?>
///////////////////////////////////////////////////////////////////////////////////////
  
  
  <tr>
    <td valign=top><strong>Reason for Deletion (will be emailed to user):</strong></td>
    <td valign=top>
      <textarea name="reason" cols="30" rows="10" id="reason"><?php echo "$reason"; ?></textarea></td>
  </tr>
  <tr>
    <td align=center colspan=2>&nbsp;
    </td>
  </tr>
</table>
<P>
<div align="center">Back to <a href="admin_menu.php?<?php echo htmlspecialchars(SID); ?>">Menu</a><br /></div>
  <?php 

//Send an email to the user.

$msg = "<a href=$url>$sitename</a> - Your User Submitted Item<BR><BR>\n\n";
$msg .= "<b>Members Name</b>:		$name<BR>\n";
$msg .= "<b>Members E-Mail</b>:		$email<BR>\n";
$msg .= "<b>Item Name</b>:			$item_name<BR>\n";
$msg .= "<b>Item Description</b>:	$item_desc<BR>\n";
$msg .= "<b>Item Type</b>:			$item_type<BR>\n";
$msg .= "<b>Category</b>:			$category<BR><BR>\n\n";

//$msg .= "Although your item has not been added to the database, we thank you for the suggestion.<BR>\n";

$msg .= "$reason<BR><BR><BR><BR>\n";


$subject = "$sitename - User Submitted Item for Review was Rejected";

/* To send HTML mail, you can set the Content-type header. */
$mailheaders  = "MIME-Version: 1.0\n";
$mailheaders .= "Content-type: text/html; charset=iso-8859-1\n";

/* additional headers */
$to = "$email";
//$mailheaders .= "To: $name <$email>\n";
$mailheaders .= "From: $admin \n";
$mailheaders .= "Reply-To: $admin\n\n";

mail($to, $subject, $msg, $mailheaders);

        BodyFooter(); 
 }
 
?>

