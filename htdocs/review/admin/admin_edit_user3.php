<?php
session_start();

//make sure user has been logged in.
$validU="";

if (isset($_SESSION['valid_user']) && $_SESSION['valid_user']!="")$validU=$_SESSION['valid_user']; 

if ($validU=="")
	{
	// User not logged in, redirect to login page	
                    header("Location: index.php");}

include ("../body.php");
include ("../functions.php");
include ("../f_secure.php");
include ("../config.php");

//set variables to no value
$username = "";
$name = "";
$email = "";
$id = "";
$passtext = "";
$maillist = "";
$refer = $_SESSION['refer'];  //where did the user come from?

//assign values
$username = makeStringSafe($_POST['username']);
$name = makeStringSafe($_POST['name']);

$email = makeStringSafe($_POST['email']);
$passtext = makeStringSafe($_POST['passtext']);
$maillist = makeStringSafe($_POST['maillist']);
$id = makeStringSafe($_POST['id']);

//don't update the password field if there was no password entered
if ($passtext == "") {
$updatepass = "n";
}

//if the maillist is not enabled, the user value has to be set to n.
if ($use_maillist == "no") {
$maillist = "n";
}

//Check to see if a valid email address has been entered.
if ($email == "") {
BodyHeader("Add your Email Address","",""); 
echo "You've entered a bad email address ($email).Please enter your email address after clicking the back button in your browser.  ";
BodyFooter(); 
exit;
}


$username = str_replace(" ", "_", $username); //remove spaces 
$username = str_replace("%20", "_", $username); //remove escaped spaces

$passtext = str_replace(" ", "_", $passtext); //remove spaces
$passtext = str_replace("%20", "_", $passtext); //remove escaped spaces

//append salt to users entered password. 
$salted_pass = $passtext . $salt; 

//hash the result
$hashed_pass = md5( $salted_pass ); 


if ($updatepass == "n") {
	$sql = "UPDATE  review_users
	SET 
name='$name',
username='$username',
email='$email',
maillist='$maillist'
WHERE id = '$id'
"; 
} else {
$sql = "UPDATE  review_users
	SET 
name='$name',
username='$username',
passtext='$hashed_pass',
email='$email',
maillist='$maillist'
WHERE id = '$id'
"; 
}

$result = @mysql_query($sql,$connection)
		or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));

$cur_user = $username;
$cur_pass = $passtext;




$username = ucfirst($username);
$name = ucfirst($name);

//Send an email to the user.


$msg = "Hello $name,

The administrator at $sitename has updated some of your account information.  Please save this email as it contains your new account information.

Access $sitename with the following:

Username - $username
Password - $passtext   (If the password was not changed, there will be no value here.  Use your current password)


If you have any questions or problems please send an email to 
$admin.

Thanks once again for your continued support of $sitename!";


$to = "$email";
$subject = "$sitename - Your New Account Information";
$mailheaders = "From: $sitename <$admin> \n";
$mailheaders .= "Reply-To: $admin\n\n";

mail($to, $subject, $msg, $mailheaders);


#Send a message to the browser for the new member.
BodyHeader("The Account Has Been Edited","",""); 
?><link href="../style_mem.css" rel="stylesheet" type="text/css" />
<link href="../style_forms.css" rel="stylesheet" type="text/css" />


<div align="center">
  <fieldset>
  <p>
    <legend><span class="style3"><?php echo "$sitename"; ?></span><br />
    <span class="index2-summary">Account Edited</span></legend>
  </p>

The account has been edited and an email has been sent to <?php echo "$email"; ?>.  The username and password are below:
<P>Username - <?php echo $cur_user; ?><BR>
  Password - <?php echo $cur_pass; ?> </p>
<P align="center"> 
<div align="center">Back to <a href="admin_menu.php?<?php echo htmlspecialchars(SID); ?>">Admin Menu</a> or <a href="admin_member_report.php?<?php echo htmlspecialchars(SID); ?>">Member Report</a></div></fieldset></div>
  <?php
BodyFooter();
exit;
?>
