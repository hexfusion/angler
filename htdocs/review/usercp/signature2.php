<?php
session_start();

/* if ($use_phpBB == "yes") {
include_once("../phpbb_include.php");   
} elseif ($use_vb == "yes") {
include_once("../vbulletin_include.php");
}//end use_vb */

if ($_SESSION['signedin'] != "y" && $use_vb == "no")
	{
	// User not logged in, redirect to login page
	Header("Location: ../login/signin.php");
	}

include ("../body.php");
include ("../config.php");
include ("../functions.php");
include ("../f_secure.php");

$username = $_SESSION['username'];
$passtext = $_SESSION['passtext'];
$sig = $_POST['sig'];
$sig_url = $_POST['sig_url'];

$sig = stripslashes($_POST['sig']);
$sig_url = stripslashes($_POST['sig_url']);

if ($use_bbcode == "yes") {

//bbcode
$bbcode = array(//"<", ">",
                "[list]", "[*]", "[/list]", 
                "[img]", "[/img]", 
                "[b]", "[/b]", 
                "[u]", "[/u]", 
                "[i]", "[/i]",
                '[color="', "[/color]",
                "[size=\"", "[/size]",
                '[url="', "[/url]",
                "[mail=\"", "[/mail]",
                "[code]", "[/code]",
                "[quote]", "[/quote]",
                '"]');
$htmlcode = array(//"&lt;", "&gt;",
                "<ul>", "<li>", "</ul>", 
                "<img src=\"", "\">", 
                "<b>", "</b>", 
                "<u>", "</u>", 
                "<i>", "</i>",
                "<span style=\"color:", "</span>",
                "<span style=\"font-size:", "</span>",
                '<a href="', "</a>",
                "<a href=\"mailto:", "</a>",
                "<code>", "</code>",
                "<table width=100% bgcolor=lightgray><tr><td bgcolor=white>Quote:  ", "</td></tr></table>",
                '">');

  $sig = str_replace($bbcode, $htmlcode, $sig);
  $sig = nl2br($sig);//second pass

  $sig_url = str_replace($bbcode, $htmlcode, $sig_url);
  $sig_url = nl2br($sig_url);//second pass
}

echo $sig;

$sql = "UPDATE review_users
	SET sig='$sig',
sig_url='$sig_url'
WHERE
username='$username'
AND
passtext='$passtext'
";

$result = @mysql_query($sql,$connection)
		or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));

BodyHeader("Your Signature Has Been Saved"); 
?> 
<h3 align="center"> 
Thank you <?php echo "$username"; ?> for adding/updating your signature. 


<?php 

echo "<BR> sig is $sig and sig_url is $sig_url";



BodyFooter();
?>