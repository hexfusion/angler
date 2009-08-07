<?php
//session_start();
//$vb_dir = $_SESSION['vb_dir'];
//print_r($_SESSION);
//exit;
//include ("config.php");

//where did the user come from?
/*
$urlarray = parse_url("$back");

$redirect = "..";
$redirect .= $urlarray['path'];
$redirect .= "?";
$redirect .= $urlarray['query'];	
*/

chdir("../$vb_dir/");  
require('./global.php');   
include ("config.php");
chdir("..$directory"); 

if ($bbuserinfo['userid']!=0) { 
//global $username;
$username=$bbuserinfo['username']; 
$back = $_SERVER['HTTP_REFERER'];
//header("Location: $back"); 
} else { 
BodyHeader("Please Login!","","");	   
//echo "<BR>$vb_dir is vb_dir5<BR>";
?> 

<!-- login form -->
		<form action="/<?php echo $vb_dir; ?>/login.php" method="post" onsubmit="md5hash(vb_login_password,vb_login_md5password,vb_login_md5password_utf)">
		<script type="text/javascript" src="clientscript/vbulletin_md5.js"></script>
		<table cellpadding="0" cellspacing="3" border="0">
		<tr>
			<td class="smallfont">User Name</td>
			<td><input type="text" class="button" name="vb_login_username" id="navbar_username" size="10" accesskey="u" tabindex="1" value="User Name" onfocus="if (this.value == 'User Name') this.value = '';" /></td>
			<td class="smallfont" colspan="2" nowrap="nowrap"><label for="cb_cookieuser_navbar"><input type="checkbox" name="cookieuser" value="1" tabindex="3" id="cb_cookieuser_navbar" accesskey="c" checked="checked" />Remember Me?</label></td>
		</tr>
		<tr>
			<td class="smallfont">Password</td>
			<td><input type="password" class="button" name="vb_login_password" size="10" accesskey="p" tabindex="2" /></td>
			<td><input type="submit" class="button" value="Log in" tabindex="4" title="Enter your username and password in the boxes provided to login, or click the 'register' button to create a profile for yourself." accesskey="s" /></td>
		</tr>
		</table>
		<input type="hidden" name="s" value="" />
		<input type="hidden" name="do" value="login" />
		<input type="hidden" name="forceredirect" value="1" />			
		<input type="hidden" name="vb_login_md5password" />
		<input type="hidden" name="vb_login_md5password_utf" />
		<input type="hidden" name="url" value="<?php $current = $_SERVER['PHP_SELF'];
$item_id = $_GET['item_id'];
$back =  "$current?item_id=$item_id"; echo "$back"; ?>"/> 
		</form>
		<!-- / login form -->

                        <?php 

//echo "$back is back<BR>";
/*
$urlarray = parse_url("$back");

$redirect = "..";
$redirect .= $urlarray['path'];
$redirect .= "?";
$redirect .= $urlarray['query'];

print_r(parse_url($_SERVER['referer']));


echo $_SERVER['PHP_SELF']; 
echo "<BR> redirect is $redirect"; 
*/
BodyFooter();
exit;
} //end else

?> 
 
 
