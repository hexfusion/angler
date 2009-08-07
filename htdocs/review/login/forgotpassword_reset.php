<?php
session_start();
include ('../functions.php');
include ('../f_secure.php');
include ('../body.php');
include ('../config.php');

//if user has just reset password we'll send them to the index page.
$file = basename($_SERVER['HTTP_REFERER']);
if ($file == "success.php") {
//send to index page
header("Location: $url$directory/");
}

//$email = $_POST['email'];
$item_id = $_GET['item_id'];
$back = $_GET['back'];

$resetid = $_GET['r'];
$username = $_GET['u'];

if (($resetid  == "")) {
BodyHeader("No Reset ID!"); 
echo "You must click the link you received in your email.  Please enter your email address on the password retrieval page and then click the link you receive via email.  Go to <a href=$url$directory/usercp/forgotpassword.php>$url$directory/usercp/forgotpassword.php</a>.";
BodyFooter(); 
exit;
}

if ($username == "") {
BodyHeader("No Username?"); 
echo "You must click the link you received in your email.  Please enter your email address on the password retrieval page and then click the link you receive via email.  Go to <a href=$url$directory/usercp/forgotpassword.php>$url$directory/usercp/forgotpassword.php</a>.";
BodyFooter(); 
exit;
}

$sql = "SELECT *
	FROM review_users_reset 
	WHERE username ='" . mysql_real_escape_string($username) . "'
	AND resetid='" . mysql_real_escape_string($resetid) . "'
	";

$result = mysql_query($sql)
	or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));
	

while ($row = mysql_fetch_array($result)) {
	$username = $row['username'];
	$userresetid = $row['userresetid'];
	$expire = $row['expire'];
	$email = $row['email'];
	}

	
$num = mysql_numrows($result);

if ($num == 1) {

//generate a random password
// RANDOM KEY PARAMETERS
$keychars = "0123456789";
$length = 6;

// RANDOM KEY GENERATOR
$passtext = "";
for ($i=0;$i<$length;$i++)
  $passtext .= substr($keychars, rand(1, strlen($keychars) ), 1); 

//append salt to users entered password. 
$salted_pass = $passtext . $salt; 

//hash the result
$hashed_pass = md5( $salted_pass ); 

//update user record by inserting new password
	$sql = "UPDATE review_users SET 
	passtext = '$hashed_pass'
	WHERE username = '" . mysql_real_escape_string($username) . "'";

        $sql_result = mysql_query($sql) 
	or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));

	if (!$sql_result) {  
		echo "<P>Couldn't edit record!";
		exit;
	} 

//send email to user with their new password.
$msg = "Hello $username,\n\n";
$msg .= "Your password at $sitename has been reset.  You can change this password by logging into your user control panel at $url$directory/usercp/

Here is your login information:

Username:  $username
Password:  $passtext

Thanks,

$sitename
\n\n";

$to = "$email";
$subject = "$sitename New Password Information";
$mailheaders = "From: $sitename <$admin>\n";
$mailheaders .= "Reply-To: $admin\n\n";
mail($to, $subject, $msg, $mailheaders);

//delete review_user_reset data since it is no longer needed.
$sql = "DELETE FROM review_users_reset  
		WHERE username = '" . mysql_real_escape_string($username) . "'
		AND resetid = '" . mysql_real_escape_string($resetid) . "'
		LIMIT 1
		";

        $sql_result = mysql_query($sql) 
            or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error())); 

	if (!$sql_result) {  
   		echo "<P>Couldn't delete record!";
		exit;
	} 

BodyHeader("Your Password Has Been Reset."); 
?>
 <center> 
  <p><font face="Verdana, Arial, Helvetica, sans-serif" size="2">Your new password has been sent to <?php echo "$email"; ?>.</font></p> 
  <p> <font face="Verdana, Arial, Helvetica, sans-serif" size="2">After you receive your information, please <a href="signin.php?<?php echo htmlspecialchars(SID); ?>&item_id=<?php if ($item_id == "") {
$item_id = "1";
}
echo "$item_id"; ?>&back=<?php echo "$back"; ?>">login</a></font> </p> 
</center> 
<?php
BodyFooter(); 
exit;
} else if ($num == 0)  {
BodyHeader("$email was not found!"); 
?>
<center><?php echo "$email"; ?> was not in our database!<P>Please try <a href="forgotpassword.php?<?php echo htmlspecialchars(SID); ?>&item_id=<?php if ($item_id == "") {
$item_id = "1";
}
echo "$item_id"; ?>&back=<?php echo "$back"; ?>">again</a>.</P></center>
<?php
BodyFooter(); 
exit;
}  
?> 
