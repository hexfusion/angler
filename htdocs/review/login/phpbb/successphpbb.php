<?php
include("../../config.php");


define('IN_PHPBB', true); 
$phpbb_root_path = $_SERVER["DOCUMENT_ROOT"] . "/$phpbb_dir/";  //$_SERVER["DOCUMENT_ROOT"];
include($phpbb_root_path . 'extension.inc'); 
include($phpbb_root_path . 'common.'.$phpEx); 

// Start session management 
$userdata = session_pagestart($user_ip, PAGE_INDEX); 
init_userprefs($userdata); 
// End session management 

//print_r($userdata);

$signedin = "y";
session_register('signedin');
session_register('registered');
session_register('username_logged');
session_register('name_logged');
session_register('source');
$_SESSION['username_logged'] = $userdata['username'];
$_SESSION['source'] = $userdata['username'];
$_SESSION['signedin'] = "y";
$_SESSION['registered'] = "yes";
$_SESSION['name_logged'] = $userdata['username'];


include("../../body.php");

BodyHeader("Write Your Review"); 
?>
<h3 align="center">
Thank you <?php echo ucfirst($username); ?> for logging in.
</h2>
<table border="0">
  <tr>
    <td><a href="<?php echo "$directory"; ?>/review_form.php?<?=SID?>&item_id=<?php if ($item_id == "") {
$item_id = "1";
}

echo "$item_id"; ?>&signedin=y&back=<?php echo "$back"; ?>"><img src="<?php echo "$directory"; ?>/images/write.gif" width="35" height="35" border="0"></a></td>
    <td><font face=verdana,arial,helvetica size=4><b>Write Your Own Review</b></font> </td>
  </tr>
</table>
</p>
<p><strong><font face=verdana,arial,helvetica color=#CC6600>You have successfully signed in</font></strong><br>
</p>
<? /*
<p> Click to&nbsp;<a href="../review_form.php?<?=SID?>
&item_id=
<?php if ($item_id == "") {
$item_id = "1";
}

echo "$item_id"; ?>
&signedin=y&back=<?php echo "$back"; ?>">Continue.</a> */ //find out if the referring page has a query attached or not so we know whether to use a ? or & in the hyperlink to continue. $urlarray = parse_url("$back"); $back_path = $urlarray['path']; $back_query = $urlarray['query']; ?> Click&nbsp;<a href="<?php echo "$directory"; ?>/index.php?<?=SID?>">here</a> to return to the main page.<span class="body"> </span>
<p><a href="../../usercp/index.php?<?=SID?>">User Conrol Panel</a>
  <?php
BodyFooter();
?>
