<?php
session_start();

$id = "";
$item_id = "";
$verify = "";

include ("./body.php");
include ("./functions.php");
include ("./config.php");
include ("./f_secure.php");

$id = makeStringSafe($_POST['id']);
$item_id = makeStringSafe($_POST['item_id']);

$verify = makeStringSafe($_POST['verify']);

$vcode = date(dm);
if ($verify != $vcode) {
BodyHeader("Invalid","","");
echo "You need to enter the correct verification code.<br />
<br />
<br />
";
BodyFooter();
exit;
}

//do a flood check.  The number 600 below is seconds which translates to 10 minutes.  This can be changed.
	$item_id = clean($_POST['item_id']);

	if($_COOKIE['reported'] >= '5'){	
	BodyHeader("You have already reported 5 reviews!  Please come back after a short break.","",""); 

echo "You have already reported 5 reviews!  Please come back after a short break.";
BodyFooter();
exit();
}else{
$reported = $_COOKIE['reported'];
$num1 = $reported + 1;
setcookie("reported","$num1",time()+600);
}


if(!is_numeric($id)) {
BodyHeader("Invalid item","","");
echo "The item you are attempting to view is not valid.";
BodyFooter();
exit;
}


if(!is_numeric($item_id)) {
BodyHeader("Invalid item","","");
echo "The item you are attempting to view is not valid.";
BodyFooter();
exit;
}


$username = @clean($_SESSION['username_logged']);


if ((!@$_POST['back']) && (!@$_GET['back'])) {
$back = $_SERVER['HTTP_REFERER'];
} elseif (isset($_GET['back']) && (!$_POST['back'])) {
$back = $_GET['back'];
} elseif (isset($_POST['back']) && (!$_GET['back'])) {
$back = $_POST['back'];
}
	$sql = "SELECT * FROM review
WHERE 
id = '" . mysql_real_escape_string($id) . "'
LIMIT 1";


$result = @mysql_query($sql,$connection)
	or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));

while($row = mysql_fetch_array($result)) {
$summary = stripslashes($row['summary']);
$review = stripslashes($row['review']);
$source = stripslashes($row['source']);
$location = stripslashes($row['location']);
$visitorIP = stripslashes($row['visitorIP']);
$date_added = stripslashes($row['date_added']);
$source = stripslashes($row['source']);
$approve = stripslashes($row['approve']);
} //while

//Send an email
$msg = "Following is a review that a user felt was inappropriate::<BR><BR>
<a href=$url$directory/review_single.php?item_id=$item_id&id=$id>$url$directory/review_single.php?item_id=$item_id&id=$id</a>
<BR><BR>
Summary - $summary <BR>
Review - $review <BR>
Source - $source <BR>
Review ID - $id <BR>
Location - $location <BR>
Visitor IP - <a href=http://network-tools.com/default.asp?prog=trace&Netnic=whois.arin.net&host=$visitorIP>$visitorIP</a> <BR>
Date Review was Added - $date_added <BR>
<BR><BR>
Delete this review is necessary by logging into your admin panel.

Please click <a href=$url$directory/review_single.php?item_id=$item_id&id=$id>here</a> to view the review in question.\n";

$subject = "Inappropriate Review Notification...";
/* To send HTML mail, you can set the Content-type header. */
$mailheaders  = "MIME-Version: 1.0\n";
$mailheaders .= "Content-type: text/html; charset=iso-8859-1\n";

/* additional headers */
$to = $admin;
$mailheaders .= "From: $username <$admin> \n";
$mailheaders .= "Reply-To: $admin\n\n";

mail($to, $subject, $msg, $mailheaders);

#Send a message to the browser
BodyHeader("Your Report Has Been Sent.","","");
?>
<link href="review.css" rel="stylesheet" type="text/css">

<center>
  <p class="xbig">Your Report Has Been Sent. </p>
  <p align="left">Here is the message sent to <?php echo "$admin"; ?></p>
  <p align="left"><?php echo "<strong>\"$msg\"</strong>"; ?></p>
  <p>    <BR>
  </p>
<div align="center">Back to <a href=<?php echo "$back"; ?>>Previous Page</a></div>

  <br />
</center>
<?php
BodyFooter();
exit;
?>
