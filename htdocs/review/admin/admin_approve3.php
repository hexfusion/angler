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
$summary = $_POST["summary"];
$review = $_POST["review"];
$source = $_POST["source"];
$date_added = $_POST["date_added"];
$item_id = $_POST["item_id"];

	if (!$sel_record) {
header("Location: http://$url$directory/admin/index.php?".sid);
			exit;
		}
	
	
	$sql = "UPDATE review SET approve = 'y' 
			WHERE id = \"$sel_record\"
			";

        $sql_result = mysql_query($sql) 
            or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error())); 

	if (!$sql_result) {  
   
		echo "<P>Couldn't approve record!";

	} else {

//send an email to subscriptions
$sql = "SELECT review_item_id FROM review 
					WHERE id='$sel_record'
					";
		
					$sql_result = mysql_query($sql)
		or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));
 while ($row = mysql_fetch_array($sql_result)) {
	$item_id = stripslashes($row["review_item_id"]);
} //end while


if ($approve == "n") {
$sql = "SELECT username FROM review_subscriptions WHERE item_id_sub = '$item_id' AND username !=''
";

$sql_result = mysql_query($sql)
		or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));
		 while ($row = mysql_fetch_array($sql_result)) {
	$username = stripslashes($row["username"]);
} //end while
	$num = mysql_numrows($sql_result);

for ($i = 1; $i <= $num; $i++) {

$sql = "SELECT email, name FROM 
			review_users
			WHERE 
			username = '$username'
			";

			$sql_result = mysql_query($sql)
		or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));
		 while ($row = mysql_fetch_array($sql_result)) {
	$email = stripslashes($row["email"]);
$name = stripslashes($row["name"]);
} //end while

$mailsub = "../subscriptions/mail.php";
$fd = fopen ($mailsub, "r") or die("cannot open $mailsub");  
$msg= fread ($fd, filesize ($mailsub)); 
fclose ($fd); 
$msg = str_replace("\$username", $username, $msg);
$msg = str_replace("\$item_name", $item_name, $msg);
$msg = str_replace("\$admin", $admin, $msg);
$msg = str_replace("\$email", $email, $msg);
$msg = str_replace("\$sitename", $sitename, $msg);
$msg = str_replace("\$name", $name, $msg);
$msg = str_replace("\$item_id", $item_id, $msg);
$msg = str_replace("\$url", $url, $msg);
$msg = str_replace("\$directory", $directory, $msg);
$to = "$email";
$subject = "$sitename - New Review Notification";

$mailheaders = "From: $sitename <$admin> \n";
mail($to, $subject, $msg, $mailheaders);
}
} //end for


		BodyHeader("$sitename:  Approve A Review"); 
        ?>
<h1><center><BR><font face="Verdana, Arial, Helvetica, sans-serif" size="3">You have Approved 
  <?php echo stripslashes($source); ?>'s Review</font></center></h1>
<P> 
<table align="center">
  <tr> 
    <td valign=top><strong>Summary:</strong></td>
    <td valign=top> <?php echo stripslashes($summary); ?> <INPUT type="hidden" name="summary" value="<?php echo $summary ?>"> 
    </td>
  </tr>
  <tr> 
    <td valign=top><strong>Review:</strong></td>
    <td valign=top><?php echo stripslashes($review); ?></td>
    <INPUT type="hidden" name="review" value="<?php echo $review ?>"></td>
  </tr>
  <tr> 
    <td valign=top><strong>Source:</strong></td>
    <td valign=top><?php echo stripslashes($source); ?></td>
    <INPUT type="hidden" name="source" value="<?php echo "$source" ?>" size=35 maxlength=150></td>
  </tr>
  <tr> 
    <td valign=top><strong>Date Added:</strong></td>
    <td valign=top><?php echo "$date_added"; ?></td>
    <INPUT type="hidden" name="date_added" value="<?php echo "$date_added" ?>" size=35 maxlength=75></td>
  </tr>
</table>
<div align="center">Back to <a href="admin_menu.php?<?php echo htmlspecialchars(SID); ?>">Menu</a><br /></div>
  <?php 
				
        BodyFooter(); 
 }
 
?>

