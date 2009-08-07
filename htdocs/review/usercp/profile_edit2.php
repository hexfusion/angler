<?php
session_start();

include ('../functions.php');
include ('../f_secure.php');
include ('../body.php');
include ('../config.php');

//$name = $_SESSION['name_logged'];
$username = clean($_SESSION['username_logged']);

BodyHeader("Profile","","");


$name= clean($_POST['name']);
$city= clean($_POST['city']);
$state= clean($_POST['state']);
$zip= clean($_POST['zip']);
$age= clean($_POST['age']);
$username= $_POST['username'];
$profession= clean($_POST['profession']);
$aboutme= clean($_POST['aboutme']);
$sig= clean($_POST['sig']);
$passtext= clean($_POST['passtext']);
$skype= clean($_POST['skype']);
$email= clean($_POST['email']);
$adsense_clientid= clean($_POST['adsense_clientid']);
$adsense_channelid= clean($_POST['adsense_channelid']);


if ($adsense_clientid != "") {
if (!preg_match('#^pub-[0-9]{16}$#i', trim($adsense_clientid))) {
		echo 'Your AdSense Client ID should start with <b>pub-</b> and be followed by <b>16 digits</b>.  Please click <a href="'.$directory.'/usercp/profile_edit1.php">here</a> to correct.';
		BodyFooter();
		exit;
	}
		}


if($_POST['passtext'] != "") {
//append salt to users entered password. 
$salted_pass = $passtext . $salt; 

//hash the result
$hashed_pass = md5( $salted_pass ); 

	$sql = "UPDATE review_users
	SET 
	name='".addslashes($name)."',
	city='".addslashes($city)."',
	email='".addslashes($email)."',
	state='$state',
	zip='".addslashes($zip)."',	
	age='$age',
	profession='$profession',
	passtext ='$hashed_pass',
	aboutme='".addslashes($aboutme)."',
	skype='".addslashes($skype)."',
	adsense_clientid='".addslashes($adsense_clientid)."',
	adsense_channelid='".addslashes($adsense_channelid)."',
	sig='".addslashes($sig)."'
	WHERE username='" . mysql_real_escape_string($username) . "'
	";
	} //end if($_POST['passtext'] != "") { 
else {
	$sql = "UPDATE review_users
	SET 
	name='".makeStringSafe($name)."',
	city='".makeStringSafe($city)."',
	email='".makeStringSafe($email)."',
	state='".makeStringSafe($state)."',
	zip='".makeStringSafe($zip)."',	
	age='".makeStringSafe($age)."',
	profession='".makeStringSafe($profession)."',
	aboutme='".makeStringSafe($aboutme)."',
	skype='".makeStringSafe($skype)."',
	adsense_clientid='".makeStringSafe($adsense_clientid)."',
	adsense_channelid='".makeStringSafe($adsense_channelid)."',
	sig='".makeStringSafe($sig)."'
	WHERE username='" . mysql_real_escape_string($username) . "'
	";
	} //end else
        $sql_result = mysql_query($sql) 
	or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));

	if (!$sql_result) {  
   
		echo "<P>Couldn't edit record!";
exit;
	} 

$_SESSION['city'] = "$city";
$_SESSION['state'] = "$state";
$_SESSION['zip'] = "$zip";

echo "<BR><BR><BR>" . ucfirst($name); ?>, You Have Successfully Edited Your Profile.<BR>
<BR>
<BR>
Click <a href="index.php?<?php echo htmlspecialchars(SID); ?>">here</a> to go to your Profile.
<BR><BR><BR><BR>
        <?php 
if ($mail_profile == "y") {
//email a notification to webmaster that photo has been uploaded
$msg = "Hello,\n\n";
$msg .= "$username just updated their profile:\n\n";
$msg .= "
Name: $name
Username: $username
E-mail: $email
Location: $city, $state $zip
Age $age
Profession: $profession
Custom Signature: $sig
Skype Usernames: $skype
Adsense Client ID: $adsense_clientid
Adsense Channel ID: $adsense_channelid
About me: $aboutme\n\n";
$msg .= "";

$to = "$admin";
$subject = "Profile Updated - $sitename";
$mailheaders = "From: $sitename <$admin>\n";
$mailheaders .= "Reply-To: $admin\n\n";

mail($to, $subject, $msg, $mailheaders);
} //end mail profile

//show navigation links on the bottom
include('user_cp_links.php'); 
BodyFooter();
exit;
?>