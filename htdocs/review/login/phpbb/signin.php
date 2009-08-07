<?php
include ("../..//body.php");
include ("../../f_secure.php");
include ("../../functions.php");
include ("../../config.php");

//find where the forum path is.
$path =  $_SERVER['DOCUMENT_ROOT'];
include ("$path/$phpbb_dir/config.php");

define('IN_PHPBB', true); 
$phpbb_root_path = "$path/$phpbb_dir/";  //$_SERVER["DOCUMENT_ROOT"];
include($phpbb_root_path . 'extension.inc'); 
include($phpbb_root_path . 'common.'.$phpEx); 
$page_id = -1000; 

// Start session management 
$userdata = session_pagestart($user_ip, $page_id, $session_length); 
init_userprefs($userdata); 
// End session management 

//print_r ($userdata);

if ($userdata['session_logged_in']) 
{ 
    $login_correct = true; 
 BodyHeader("Write Your Review"); 
?> 
<h2 align="center"> 
You are already logged in.</h2>  
<div align="center">
  (click to <a href="<?php echo "/$phpbb_dir"; ?>/login.php?logout=true&sid=<?php echo $userdata['session_id']?>&username=<?php echo $userdata['username']; ?>" target="_blank">Logout</a>)
  
</div>
<table border="0"> 
  <tr> 
    <td><a href="/review_form.php?=SID&item_id=<?php if ($item_id == "") {
$item_id = "1";
}

echo "$item_id"; ?>&signedin=y&back=<?php echo "$back"; ?>"><img src="<?php echo "$directory"; ?>/images/write.gif" width="35" height="35" border="0"></a></td> 
    <td> <font face=verdana,arial,helvetica size=4><b>Write Your Own Review</b></font> </td> 
  </tr> 
</table> 
</p> 
<p><strong><font face=verdana,arial,helvetica color=#CC6600>You are already signed in</font></strong><br> 
</p> 
<p> &nbsp;<a href="<?php echo "$directory"; ?>/review_form.php?=SID&item_id=<?php if ($item_id == "") {
$item_id = "1";
}

echo "$item_id"; ?>&signedin=y&back=<?php echo "$back"; ?>&sid=<?php echo "$session_id"; ?>&username=<?php echo "$username"; ?>">Continue</a> to write a review.
  <?php

BodyFooter();
// exit;
} else {

BodyHeader("Log In!");
?>

<form method="post" action="<?php echo "/$phpbb_dir"; ?>/login.php">
<!--the redirect is in relation to your base directory-->
<input name="redirect" type="hidden" value="../<?php echo "$directory/login/phpbb/successphpbb.php"; ?>">
  <table width="410" border="0" cellpadding="2">
    <tr>
      <td nowrap>
        <div align="RIGHT">Username :&nbsp;</div></td>
      <td nowrap><input type="TEXT" name="username">
      </td>
    </tr>
    <tr>
      <td nowrap>
        <div align="RIGHT">Password :&nbsp;</div></td>
      <td nowrap><input type="PASSWORD" name="password">
      </td>
    </tr>
    <tr>
      <td nowrap>&nbsp;</td>
      <td nowrap>
        <div align="LEFT">
          <input type="SUBMIT" name="login" value="login">
      </div></td>
    </tr>
  </table>
</form>

<?php
BodyFooter();
}
exit;
?>
