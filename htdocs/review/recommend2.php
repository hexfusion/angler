<?php
session_start();

//Prevent Cross-Site Request Forgeries//
if ($_POST['token'] != $_SESSION['token']) {
echo "invalid";
exit;
}
////

include ("./body.php");
include ("./functions.php");
include ("./config.php");
include ("./f_secure.php");

if (isset($_POST['rec_email']) && isset($_POST['email'])) {
	// first check if the number submitted is correct
	$number   = $_POST['txtNumber'];
	
	if (md5($number) != $_SESSION['image_random_value']) {
	BodyHeader("You need to enter the correct verification number!","",""); 
	
		echo "You need to enter the correct verification number!  Click the back button in your browser and click the New Code button and enter the new code to try again.";
	// remove the random value from session			
	$_SESSION['image_random_value'] = '';
	exit;
	}
	}
	
	//do a flood check.  The number 600 below is seconds which translates to 10 minutes.  This can be changed.
	$item_id = clean($_POST['item_id']);

	if($_COOKIE['sentmessage'] >= '5'){	
	BodyHeader("You have already sent 5 recommendations!  Please come back after a short break.","",""); 

echo "You have already sent 5 recommendations!  Please come back after a short break.";
BodyFooter();
exit();
}else{
$sentmessage = $_COOKIE['sentmessage'];
$num = $sentmessage + 1;
setcookie("sentmessage","$num",time()+600);
}
	
	
			// remove the random value from session			
			$_SESSION['image_random_value'] = '';

// Check for data 
if (isset($_GET['data']) && $_GET['data']) { 
  if ($_GET['data'] != 5) { 
    //not a valid form but don't let scumbag know it.
    echo "Thanks for your submission";
	exit;
  } 
} else { 
  //not a valid form but don't let scumbag know it.
    echo "Thanks for your submission";
	exit;
} 

// Check for HTTP_REFERER 


// Check for correct method, should be post.
if (isset($_GET['item_id'])) { 
  //not a valid form but don't let scumbag know it.
    echo "Thanks for your submission";
	exit;
} 


if ((!$_POST['back']) && (!$_GET['back'])) {
$back = $_SERVER['HTTP_REFERER'];
} elseif (isset($_GET['back']) && (!$_POST['back'])) {
$back = $_GET['back'];
} elseif (isset($_POST['back']) && (!@$_GET['back'])) {
$back = $_POST['back'];
}

//some firewalls will block the http_referer.  If it is blocked, we'll send the user to the home page.
if($back == "") {
$back = "/";
}

$back = clean("$back");   

$recipient = clean($_POST['recipient']);
$rec_email = clean($_POST['rec_email']);
$message = clean($_POST['message']);
$back = $_POST['back'];
//$item_id = clean($_POST['item_id']);
$item_name = clean($_POST['item_name']);
$item_desc = clean($_POST['item_desc']);
$item_type = clean($_POST['item_type']);
$username = clean($_POST['username']);
$email = clean($_POST['email']);


/*
if (!preg_match("/^(.+)@[a-zA-Z0-9-]+\.[a-zA-Z0-9.-]+$/si", $email)) {
BodyHeader("Invalid Email Address.");
echo "Sorry, that was an invalid email address.  Please click the back broswer and try again.";
BodyFooter();
exit;
}
*/
if (eregi("^[a-zA-Z0-9_]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$]", $email)) 
{
BodyHeader("Invalid Email Address.");
echo "Sorry, that was an invalid email address.  Please click the back broswer and try again.";
BodyFooter();
exit;
}

//check user input and remove any reference to javascript.
$errjava = "<font color=red><BR><BR><B>No Javascript is allowed!  Please click edit and remove the offending code.<BR><BR></B></font>";

$message = preg_replace("'<script[^>]*?>.*?</script>'si", "$errjava", $message);
$recipient = preg_replace("'<script[^>]*?>.*?</script>'si", "$errjava", $recipient);
$rec_email = preg_replace("'<script[^>]*?>.*?</script>'si", "$errjava", $rec_email);
$username = preg_replace("'<script[^>]*?>.*?</script>'si", "$errjava", $username);
$email = preg_replace("'<script[^>]*?>.*?</script>'si", "$errjava", $email);

$message = nl2br($message);


$send_date = date("Y-m-d",time());

//clean variables to prevent sql injections.
$message = mysql_real_escape_string($message);
$recipient = mysql_real_escape_string($recipient);
$rec_email = mysql_real_escape_string($rec_email);
$username = mysql_real_escape_string($username);
$send_date = mysql_real_escape_string($send_date);
$email = mysql_real_escape_string($email);

	$sql = "INSERT INTO review_recommend
	SET 
message='$message',
recipient='$recipient',
rec_email='$rec_email',
username='$username',
send_date='$send_date',
email='$email'
	";

$result = @mysql_query($sql,$connection)
	or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));

$recipient = stripslashes($_POST['recipient']);
$rec_email = stripslashes($_POST['rec_email']);
$message = stripslashes($_POST['message']);
if ((!@$_POST['back']) && (!@$_GET['back'])) {
$back = $_SERVER['HTTP_REFERER'];
} elseif (isset($_GET['back']) && (!@$_POST['back'])) {
$back = $_GET['back'];
} elseif (isset($_POST['back']) && (!@$_GET['back'])) {
$back = $_POST['back'];
}

//some firewalls will block the http_referer.  If it is blocked, we'll send the user to the home page.
if($back == "") {
$back = "/";
}

$item_name = stripslashes($_POST['item_name']);
$item_desc = stripslashes($_POST['item_desc']);
$item_type = stripslashes($_POST['item_type']);
$username = stripslashes($_POST['username']);
$email = stripslashes($_POST['email']);

$message = nl2br($message);


//Send an email
$msg = "Following is a message that $username submitted in regards to a recommendation for the following:<BR><BR>
<a href=$back>$item_name</a>
<BR><BR>
$message
<BR><BR>
Please click <a href=$back>here</a>.\n";

$subject = "Your friend $username recommends this...";
// To send HTML mail, you can set the Content-type header. //
$mailheaders  = "MIME-Version: 1.0\n";
$mailheaders .= "Content-type: text/html; charset=iso-8859-1\n";

// additional headers //
//$to = $rec_email;
$to  = "$rec_email" . ", "; // note the comma
$to .= "$admin";


$mailheaders .= "From: $username <$email> \n";
$mailheaders .= "Reply-To: $email\n\n";

$domain = strtolower($domain);
$back = strtolower($back);

//only send an email if it is generated from the domain it is installed on.
if (@preg_match("/$domain/", $back)) {
mail($to, $subject, $msg, $mailheaders);
 }


#Send a message to the browser
BodyHeader("Your Recommendation Has Been Made.","$item_name has been recommended","$item_name");
?>
<center>
  <table width="95%" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td><p>Your Recommendation Has Been Made. </p>
        <p>Here is the message sent to <?php echo "$recipient"; ?></p>
        <p><?php echo "<strong>\"$msg\"</strong>"; ?></p>
        <p> <br />
        </p>
      <div align="center">Back to <a href=<?php echo "$back"; ?>><?php echo "$item_name"; ?></a></div></td>
    </tr>
  </table>
  <p><br />
</p>
</center>
<?php
BodyFooter();
exit;
?>
