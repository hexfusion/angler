<?php
session_start();

//Prevent Cross-Site Request Forgeries//
if ($_POST['token'] != $_SESSION['token']) {
echo "invalid";
exit;
}
////

$passtext = $_POST['passtext'];
$back = $_POST['back'];
$action = $_POST['action'];
$item_id = @$_POST['item_id'];
$email = @$_POST['email'];
$username = $_POST['username'];

if ($action == "continue") {
	header("Location: $directory/login/register.php?username=$username&back=$back&item_id=$item_id");
	exit;
	}

	
include_once ('../body.php');
include_once ('../config.php');
//Check to see that the username and Password entered have admin access.
include_once ('../functions.php');
include_once ('../f_secure.php');

$passtext=makeStringSafe($passtext);
$username=makeStringSafe($username);
//append salt to users entered password. 
$salted_pass = $passtext . $salt; 

//hash the result
$hashed_pass = md5( $salted_pass ); 

$sqlaccess = "SELECT username, active, name, city, state, zip
		FROM review_users
		WHERE username='" . mysql_real_escape_string($username) . "' 
		and passtext = '$hashed_pass'
		";
	$resultaccess = mysql_query($sqlaccess)
		or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));

	$numaccess = mysql_numrows($resultaccess);

	//	if ($numaccess == 0 || $numaccess > 1) {
	if ($numaccess == 0) {
//BodyHeader("Access Not Allowed!");
?>
<br />
<br />
<p align="center">To write a review you need to have approved access. The Username and Password you entered are not approved!<br />
  <a href="<?php echo "$directory"; ?>/login/register.php?<?php echo htmlspecialchars(SID); ?>&amp;item_id=<?php if ($item_id == "") {
$item_id = "1";
}
echo "$item_id"; ?>&amp;back=<?php echo "$back"; ?>">Please Register...It's free!</a><br />
<br />
<br />

  <span class="small">- <a href="<?php echo "$directory"; ?>/login/forgotpassword.php?<?php echo htmlspecialchars(SID); ?>&amp;item_id=<?php if ($item_id == "") {
$item_id = "1";
}
echo "$item_id"; ?>&amp;back=<?php echo "$back"; ?>">Forgot your password? Click here</a> </span><br /></p>

  <?php
//BodyFooter();  

exit;

}

$row = mysql_fetch_array($resultaccess);

			
					if( $row['active'] != 'y' )
			{
//BodyHeader("Inactive Account!"); 
				?>
</p>
<table width="85%"  border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <th scope="col"><p>Sorry, your account is not active. </p>
      <p>Please <a href="<?php echo "$directory"; ?>/login/register.php?<?php echo htmlspecialchars(SID); ?>">Register</a>!</p></th>
  </tr>
</table>
<p>&nbsp;</p>
<?php
			//	  BodyFooter();
				  exit;	
			}
$signedin = "y";

//protect against a session fixation attack
session_regenerate_id();

$_SESSION['username_logged'] = $_POST['username'];
$_SESSION['passtext'] = $_POST['passtext'];
$_SESSION['signedin'] = "y";
$_SESSION['active'] = "y";
$_SESSION['registered'] = "yes";
$_SESSION['name_logged'] = $row['name'];
$_SESSION['city'] = $row['city'];
$_SESSION['state'] = $row['state'];
$_SESSION['zip'] = $row['zip'];
session_register('signedin');
$_SESSION['signedin']="y";
session_register('registered');
$_SESSION['registered']="yes";
//setcookie("username", $username, time() + 31536000, "/");
//setcookie("signedin", $signedin, time() + 31536000, "/");
//setcookie("registered", "yes", time() + 31536000, "/");
//BodyHeader("Write Your Review"); 
?>
<br />
<br />
<br /><div align="center">
<p class="index2-Orange"><strong><?php echo ucfirst($_SESSION['name_logged']); ?>, you have successfully signed in.</strong><br />
</p>
<?php    
//if ($item_id != "" && $item_id != "$item_id") {
if ($_POST['back'] != "") {
?>
<span class="body">Click&nbsp;<a href="<?php echo "$back"; ?>">here</a> to proceed
<?php } else { ?>
<span class="body">Click&nbsp;<a href="<?php echo "$url$directory/index.php?".htmlspecialchars(SID); ?>">here</a> to proceed
<?php }  ?>
 or visit your <a href="<?php echo "$directory"; ?>/usercp/index.php?<?php echo htmlspecialchars(SID); ?>">User Control Panel</a></span> </div>
<br />
<br />
<br />
