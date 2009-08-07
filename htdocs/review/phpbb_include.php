<?php
include("config.php");


define('IN_PHPBB', true); 
$phpbb_root_path = $_SERVER["DOCUMENT_ROOT"] . "/$phpbb_dir/";  //$_SERVER["DOCUMENT_ROOT"];
include($phpbb_root_path . 'extension.inc'); 
include($phpbb_root_path . 'common.'.$phpEx); 

// Start session management 
$userdata = session_pagestart($user_ip, PAGE_INDEX); 
init_userprefs($userdata); 
// End session management 

if( !$userdata['session_logged_in'] ) 
   { 
$urlarray = parse_url("$back");

$redirect = "..";
$redirect .= $urlarray['path'];
$redirect .= "?";
$redirect .= $urlarray['query'];	   

BodyHeader("Please Login!","","");	   
?>

<form action="<?php echo "/$phpbb_dir"; ?>/login.php?<?=SID?>" method="post" target="_top">
<input type="hidden" name="redirect" value="<?php echo "$redirect"; ?>" />
			<input type="hidden" name="back_login" value="<?php echo "$back_login"; ?>" />
  <table width="100%" cellpadding="4" cellspacing="1" border="0" class="forumline" align="center">
  <tr>
	<th height="25" class="thHead" nowrap="nowrap">Please enter your username and password to log in.</th>
  </tr>
  <tr>
	<td class="row1"><table border="0" cellpadding="3" cellspacing="1" width="100%">
		  <tr>
			<td colspan="2" align="center">&nbsp;</td>
		  </tr>
		  <tr>
			<td width="45%" align="right"><span class="gen">Username:</span></td>
			<td>
			  <input type="text" name="username" size="25" maxlength="40" value="" />
			</td>
		  </tr>
		  <tr>
			<td align="right"><span class="gen">Password:</span></td>
			<td>
			  <input type="password" name="password" size="25" maxlength="32" />
			</td>
		  </tr>
		  <tr align="center">
			<td colspan="2"><span class="gen">Log me on automatically each visit: <input type="checkbox" name="autologin" /></span></td>
		  </tr>
		  <tr align="center">
			<td colspan="2"><input type="submit" name="login" class="mainoption" value="Log in" /></td>
		  </tr>
		  <tr align="center">
			<td colspan="2"><span><a href="<?php echo "$phpbb_dir"; ?>/profile.php?mode=sendpassword">I forgot my password</a></span></td>
		  </tr>
		</table></td>
  </tr>
</table>

</form>

<?php
BodyFooter();
exit;
}
?>