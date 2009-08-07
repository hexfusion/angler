<?php
session_start();

//Prevent Cross-Site Request Forgeries//
if ($_POST['token'] != $_SESSION['token']) {
echo "invalid";
exit;
}
////

include ('../functions.php');
include ('../f_secure.php');
include ('../body.php');
include ('../config.php');

$email = $_POST['email'];
$item_id = $_GET['item_id'];
$back = $_GET['back'];

if (($email  == "")) {
	header("Location: forgotpassword.php");
	exit;
}

$email  = strtolower(trim($email)); 

if ($email == "") {
BodyHeader("Bad Email Address"); 
echo "You've entered a bad email address.";
BodyFooter(); 
exit;
}

$sql = "SELECT *
	FROM review_users
	WHERE email ='$email'";

$result = mysql_query($sql)
	or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));
	

while ($row = mysql_fetch_array($result)) {
	$username = $row['username'];
	$passtext = $row['passtext'];
	$name = $row['name'];
	$email = $row['email'];
	$active = $row['active'];
	$regkey = $row['regkey'];
	}

	
$num = mysql_numrows($result);

if ($num >= 1) {

//check to see if user has activated account.
if ($active == "n") {
BodyHeader("Please activate your account"); 
echo "Before you can access this site, you need to activate your account.  Activation instructions have just been sent to $email.  Click <a href=../index.php>here</a> to return to our main page.";

//Send an email to the user.
$msg = "$sitename - Please Activate your account\n\n";
$msg .= "Hello " . $username . ",\n";

//in case username has a space in it, make it encoded
$username = urlencode($username);

$msg .= "Thank you for registering at $sitename.\n\n
Your membership will not be available until you activate it by clicking the link below:\n
" . $url . $directory . "/login/approve.php?regkey=" . $regkey . "&username=" . $username . "&maillist=" . $maillist . "&PHPSESSID=" . $PHPSESSID . "\n";
//in case username has a space in it, make it decoded for correct display
$username = urldecode($username);
$msg .= "
Here is your access information: 

Username: " . $username . " 

Thank you, $sitename\n\n";
$to = "$email";
$subject = "$sitename - Please Activate your account";
$mailheaders = "From: $sitename <$admin> \n";
$mailheaders .= "Reply-To: $admin\n\n";
mail($to, $subject, $msg, $mailheaders);

BodyFooter(); 
exit;
} //end check to see if user has activated account.
 


//insert data into review_users_reset table
$tomorrow  = mktime(0, 0, 0, date("m")  , date("d")+1, date("Y"));
$created = date("Y-m-d", $tomorrow);

// RANDOM KEY PARAMETERS
$keychars = "0123456789";
$length = 10;

// RANDOM KEY GENERATOR
$resetid = "";
for ($i=0;$i<$length;$i++) {
  $resetid .= substr($keychars, rand(1, strlen($keychars) ), 1); 
}

	$sql = "INSERT INTO review_users_reset
	SET resetid='$resetid',
username='$username',
email='$email',
expire='$created'
";


$result = @mysql_query($sql,$connection)
		or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));

$msg = "Hello $name,\n\n";
$msg .= "You have requested to reset your password on $sitename. If you did not request this, please ignore it. It will expire and become useless in 24 hours.

To reset your password, please visit the following page:
$url$directory/login/forgotpassword_reset.php?u=$username&r=$resetid

When you visit that page, your password will be reset, and the new password will be emailed to you.

Your username is: $username

To edit your profile and/or change your password after it is reset, go to this page:
$url$directory/usercp/

Thanks,

$sitename
\n\n";

$to = "$email";
$subject = "$sitename Password Information";
$mailheaders = "From: $sitename <$admin>\n";
$mailheaders .= "Reply-To: $admin\n\n";

mail($to, $subject, $msg, $mailheaders);

BodyHeader("Your Account Information Has Been Sent"); 
?>
 <center> 
  <p><font face="Verdana, Arial, Helvetica, sans-serif" size="2">Your account information has been sent to <?php echo "$email"; ?>.</font></p>
  <p><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Please check your email for the validation code. </font></p>
  <p> <font face="Verdana, Arial, Helvetica, sans-serif" size="2">Feel free to browse our <a href="<?php echo "$url$directory"; ?>">reviews</a>. </font></p> 
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
