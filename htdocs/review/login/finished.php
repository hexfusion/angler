<?php
session_start();

include ("../body.php");
include ("../config.php");
include ("../functions.php");
include ("../f_secure.php");

//if this is the second time around and the user changes a value, have to use the new value, not the session.
unset($username);
unset($passtext);
unset($maillist);
unset($email);
unset($name);


$passtext = @$_POST['passtext'];
$maillist = @$_POST['maillist'];
$name = @$_POST['name'];
$email = @$_POST['email'];
$username = @$_POST['username'];



$username=makeStringSafe($username);
$passtext=makeStringSafe($passtext);
$maillist=makeStringSafe($maillist);
$email=makeStringSafe($email);
$name=makeStringSafe($name);

$username = str_replace(" ", "_", $username); //remove spaces 
$username = str_replace("%20", "_", $username); //remove escaped spaces

$passtext = str_replace(" ", "_", $passtext); //remove spaces
$passtext = str_replace("%20", "_", $passtext); //remove escaped spaces


if ($username == '') {
echo "<br />
<br />
You need to enter a Username!  Click <a href=register.php?PHPSESSID=$PHPSESSID>here</a> to try again.<br />
<br />
";
exit;
}

if (isset($_POST['username']) && isset($_POST['passtext'])) {

$_SESSION['username'] = "$username";
$_SESSION['passtext'] = "$passtext";
$_SESSION['maillist'] = "$maillist";
$_SESSION['email'] = "$email";
$_SESSION['name'] = "$name";

	// first check if the number submitted is correct
	$number   = $_POST['txtNumber'];
	
	if (md5($number) != $_SESSION['image_random_value']) {
	//BodyHeader("You need to enter the correct verification number!","",""); 
	echo "<br />
<br />
You need to enter the correct verification number!  Click <a href=register.php?PHPSESSID=$PHPSESSID>here</a> to try again.<br />
<br />
";
	// remove the random value from session			
	$_SESSION['image_random_value'] = '';
	exit;
	}
	}



$item_id = $_POST['item_id'];
$back = $_POST['back'];
$name = stripslashes($_POST['name']);


if ($email == "") {
//BodyHeader("Add your Email Address","",""); 
echo "<br />
<br />
You've entered a bad email address ($email).Please enter your email address after clicking the back button in your browser. <br />
<br />
 ";
//BodyFooter(); 
exit;
}

//Check to see if the username is already in the database.
$sql = "SELECT username FROM review_users
	WHERE username = '" . mysql_real_escape_string($username) . "'
	"; 

$result = mysql_query($sql) 
        or die ("Can't execute username query sql."); 
$num = mysql_numrows($result); 
if ($num >= 1) {
//BodyHeader("Username Already Taken","",""); 
?>
<link href="../review.css" rel="stylesheet" type="text/css" />
<br />
<span class="index2-summary"><b><?php echo "$username"; ?></b> is already 
in the database, please enter a different username by clicking the back button on your browser.<br />
</span>
<?php
//BodyFooter(); 
exit;
}

//Check to see if the email is already in the database.
$sql = "SELECT email FROM review_users
	WHERE email = '$email'
	"; 

$result = mysql_query($sql) 
        or die ("Can't execute query sql."); 
$num = mysql_numrows($result); 
if ($num >= 1) {
//BodyHeader("Email Already Used","",""); 
?>
<span class="index2-summary"><br />
<b><?php echo "$email"; ?></b> is already 
in the database.  If you forgot your username or password you can retrieve them <a href="forgotpassword.php?<?php echo htmlspecialchars(SID); ?>&amp;item_id=<?php if ($item_id == "") {
$item_id = "1";
}
echo "$item_id"; ?>&amp;back=<?php echo "$back"; ?>">here</a>. <br />
<br />
</span>
<?php
//BodyFooter(); 
exit;
}

$_SESSION['username_logged'] = "$username";
$_SESSION['name_logged'] = $_POST['name'];

$username_logged = "$username";
			// remove the random value from session			
			$_SESSION['image_random_value'] = '';

$_SESSION['signedin'] = "y";
$_SESSION['active'] = "n";
$active = "n";
$regkey = generateKey(25);
$created = date("Y-m-d");
$_SESSION['maillist'] = "$maillist";
//get ip address to save in case of fraudulent transactions.
$domain = GetHostByName($_SERVER['REMOTE_ADDR']);

//append salt to users entered password. 
$salted_pass = $passtext . $salt; 

//hash the result
$hashed_pass = md5( $salted_pass ); 

//$name=makeStringSafe($name);
//$email=makeStringSafe($email);

	$sql = "INSERT INTO review_users
	SET active='$active',
name='$name',
username='$username_logged',
passtext='$hashed_pass',
email='$email',
created='$created',
regkey='$regkey',
visitorIP='$domain'
";

$result = @mysql_query($sql,$connection)
		or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));

//Send an email to the user.
$msg = "$sitename - Please Activate your account\n\n";
$msg .= "Hello " . $username . ",\n";

//in case username has a space in it, make it encoded
//$username = urlencode($username);

$msg .= "Thank you for registering at $sitename.\n\n
Your membership will not be available until you activate it by clicking the link below:\n
" . $url . $directory . "/login/approve.php?regkey=" . $regkey . "&username=" . $username . "&maillist=" . $maillist . "&PHPSESSID=" . $PHPSESSID . "\n";
//in case username has a space in it, make it decoded for correct display
//$username = urldecode($username);
$msg .= "
Here is your access information: 

Username: " . $username . " 
Password: " . $passtext . "\n

Thank you, $sitename\n\n";
$to = "$email";
$subject = "$sitename - Please Activate your account";
$mailheaders = "From: $username <$email> \n";
$mailheaders .= "Reply-To: $email\n\n";
mail($to, $subject, $msg, $mailheaders);

//Send an email to the administrator with the users information.
$msg = "$sitename - New Registration Information\n\n";
$msg .= "Members Name:		$name\n";
$msg .= "Members Username:		$username\n";
$msg .= "Members Password:		$passtext\n";
$msg .= "Members E-Mail:		$email\n";
$to = "$admin";
$subject = "$sitename - New Registration Information";
$mailheaders = "From: $username <$email> \n";
$mailheaders .= "Reply-To: $email\n\n";
mail($to, $subject, $msg, $mailheaders);

#Send a message to the browser for the new member.
//BodyHeader("Your Account Has Been Created","",""); 
?>
<br />
<br />
<br />
<span class="index2-summary">Your account has been created and an activation email has been sent to <?php echo "$email"; ?>.  You can not access <?php echo "$sitename"; ?> until you activate your account.  Your username and password are below:</span><br />
<br />
<p class="index2-summary">Username - <?php echo $username; ?><br />
  Password - <?php echo $passtext; ?> </p>
<p class="index2-summary"> Thank you for signing up with <?php echo "$sitename"; ?>. To complete your enrollment <strong>we need to verify your email address</strong>.</p>
<p class="index2-summary">We're sending you a confirmation email to the address you provided (<?php echo "$email"; ?>) -- <strong>follow the instructions in the email</strong> to complete your   enrollment.</p>
<p align="center" class="index2-Orange">Check your email.</p>
<p>&nbsp;</p>
<p class="index2-summary">You can also take this opportunity to <a href="../usercp/index.php?<?php echo htmlspecialchars(SID); ?>">Create</a> your profile.</p>
<p class="index2-summary">Be sure to keep your username and password confidential.  If you share your password with others, our security software will automatically detect that access is not coming from your computer and access to the members area will be blocked.</p>
<?php
//BodyFooter();
//exit;
?>
