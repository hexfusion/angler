<?
session_start();
include ("../../config.php");

//find where the forum path is.
$path =  $_SERVER['DOCUMENT_ROOT'];
include ("$path/$phpbb_dir/config.php");

define('IN_PHPBB', true); 
$phpbb_root_path = "$path/$phpbb_dir/";  //$_SERVER["DOCUMENT_ROOT"];
include($phpbb_root_path . 'extension.inc'); 
include($phpbb_root_path . 'common.'.$phpEx); 

// Start session management 
//$userdata = session_pagestart($user_ip, PAGE_INDEX); 
//init_userprefs($userdata); 
// End session management 

//$path =  $_SERVER['DOCUMENT_ROOT'];
//include ("$path/$phpbb_dir/config.php");
include("functions_mod_user.php");

$item_id = $_POST["item_id"];
$back = $_POST["back"];

$username = $_POST["username"];
$password = $_POST["password"];
$email = $_POST["email"];


insert_user("$username", md5("$password"), "$email");

/*****************
below begin adding data to review script database
*/////////////////
include ("../../f_secure.php");
include ("../../functions.php");
include ("../../body.php");

$_SESSION['username_logged'] = $_POST['username'];
$_SESSION['name_logged'] = $_POST['username'];
$username_logged = $_POST['username'];
$_SESSION['signedin'] = "y";
$_SESSION['active'] = "n";
$active = "n";
$regkey = generateKey(25);
$created = date("Y-m-d");
$_SESSION['maillist'] = "$maillist";
//get ip address to save in case of fraudulent transactions.
$domain = GetHostByName($_SERVER['REMOTE_ADDR']);

//append salt to users entered password. 
$salted_pass = $password . $salt; 

//hash the result
$hashed_pass = md5( $salted_pass ); 


	$sql = "INSERT INTO review_users
	SET active='$active',
name='$username_logged',
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
$username = urlencode($username);

$msg .= "Thank you for registering at $sitename.\n\n
Your membership will not be available until you activate it by clicking the link below:\n
" . $url . $directory . "/login/approve.php?regkey=" . $regkey . "&username=" . $username . "&maillist=" . $maillist . "&PHPSESSID=" . $PHPSESSID . "\n";
//in case username has a space in it, make it decoded for correct display
$username = urldecode($username);
$msg .= "
Here is your access information: 

Username: " . $username . " 
Password: " . $password . "\n

Thank you, $sitename\n\n";
$to = "$email";
$subject = "$sitename - Please Activate your account";
$mailheaders = "From: $username <$email> \n";
$mailheaders .= "Reply-To: $email\n\n";
mail($to, $subject, $msg, $mailheaders);

$_SESSION['name_logged'] = "$name";
$_SESSION['username_logged'] = $_POST['username'];
//$_SESSION['passtext'] = $_POST['passtext'];

//Send an email to the administrator with the users information.
$msg = "$sitename - New Registration Information\n\n";
$msg .= "Members Name:		$name\n";
$msg .= "Members Username:		$username\n";
$msg .= "Members Password:		$password\n";
$msg .= "Members E-Mail:		$email\n";
$to = "$admin";
$subject = "$sitename - New Registration Information";
$mailheaders = "From: $username <$email> \n";
$mailheaders .= "Reply-To: $email\n\n";
mail($to, $subject, $msg, $mailheaders);

#Send a message to the browser for the new member.
BodyHeader("Your Account Has Been Created"); 
?>
  Your account has been created and an activation email has been sent to <? echo "$email"; ?>.  You can not access <? echo "$sitename"; ?> until you activate your account.  Your username and password are below:
  <P>Username - <?php echo $username; ?><BR>
               Password - <?php echo $password; ?> </p>

<P align="center">
<font size="4"><a href="<? echo "$back"; ?>"><img src="<?php echo "$directory"; ?>/images/write.gif" width="35" height="35" border="0"></a>

<!--Click to&nbsp; <a href="<? echo "$back"; ?>">Continue</a> --> Please check your email to activate your account. You can also take this opportunity to <a href="../../usercp/index.php?<?=SID?>">Create</a> your profile.
<P>                
<P>Be sure to keep your username and password confidential.  If you share your password with others, our security software will automatically detect that access is not coming from your computer and access to the members area will be blocked.</p>


                <?php
BodyFooter();
function generateKey($len)
{
	for($i=1; $i<=$len; $i++)
	$key .= rand(0,9);
return($key);
}
exit;
 ?>