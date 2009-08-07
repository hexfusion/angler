<?php
session_start();

include ("../body.php");
include ("../config.php");
include ("../functions.php");
include ("../f_secure.php");

$username = clean($_GET['username']);
$regkey = clean($_GET['regkey']);

//Check to see if the username is already in the database.
if($result = mysql_fetch_array(mysql_query("SELECT username, name, email, passtext, regkey FROM review_users WHERE username = '" . mysql_escape_string($username) . "'")))
{
	if($result['regkey'] == $regkey)
	{
		BodyHeader("Activate Account"); 
		mysql_query("UPDATE review_users SET active = 'y' WHERE username = '" . mysql_escape_string($username) . "'");
		
//protect against a session fixation attack
session_regenerate_id();
		
$_SESSION['username'] = $_GET['username'];
$_SESSION['passtext'] = $_SESSION['passtext'];
$_SESSION['signedin'] = "y";
$_SESSION['registered'] = "yes";
$_SESSION['name'] = "$name";
$_SESSION['active'] = "y";
session_register('signedin');
session_register('registered');


if ($use_maillist == "y" && $_GET['maillist'] == "y") {
$domain = GetHostByName($_SERVER['REMOTE_ADDR']);
//insert user info into mail database if they chose that option
	$sql = "INSERT INTO wiml_maillist
	SET email_name='".$result['name']."',
email_address='".$result['email']."',
email_ip='$domain',
email_date='".time()."',
group_id='$groupid'
";

$result = @mysql_query($sql,$connection)
		or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));
} //end use_maillist



		?>
<font size="3" face="Verdana, Arial, Helvetica, sans-serif"><br /><br />
Thank you for activating your account.  Please return to our <a href="<?php echo "$url$directory"; ?>/index.php?<?php echo htmlspecialchars(SID); ?>">main</a> page or create your <a href="../usercp/index.php?<?php echo htmlspecialchars(SID); ?>">profile</a>.</font>
		<?php
//setcookie("username", $username, time() + 31536000, "/");
//setcookie("signedin", $signedin, time() + 31536000, "/");
//setcookie("registered", "yes", time() + 31536000, "/");
		BodyFooter(); 
		exit;
	}
	else
	{
		
		BodyHeader("Activation Problem."); 
		?>
		<font size="3" face="Verdana, Arial, Helvetica, sans-serif">Your activation key was incorrect.  Please check the email you received from us to be sure that the url you clicked on is not broken over two lines.</font>
		<?php
		BodyFooter(); 
		exit;
	}
}
?>
