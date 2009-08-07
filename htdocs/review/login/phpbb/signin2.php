<?php
include('/usr/local/psa/home/vhosts/review-script.com/httpdocs/forum/config.php'); 
include ("/usr/local/psa/home/vhosts/review-script.com/httpdocs/beta/functions.php");
include ("/usr/local/psa/home/vhosts/review-script.com/httpdocs/beta/body.php");

function get_userip() { 
$ip; 
if (getenv("HTTP_CLIENT_IP")) $ip = getenv("HTTP_CLIENT_IP"); 
else if(getenv("HTTP_X_FORWARDED_FOR")) $ip = getenv("HTTP_X_FORWARDED_FOR"); 
else if(getenv("REMOTE_ADDR")) $ip = getenv("REMOTE_ADDR"); 
else $ip = "UNKNOWN"; 
return $ip; 
} 

function encode_ip($dotquad_ip) { 
 $ip_sep = explode('.', $dotquad_ip); 
 return sprintf('%02x%02x%02x%02x', $ip_sep[0], $ip_sep[1], $ip_sep[2], $ip_sep[3]); 
} 

login_to_phpbb();

function login_to_phpbb() { 

// global $mysql_connection; 
 $user_ip = get_userip(); 
 $user_ip = encode_ip($user_ip); 
 $sid = ""; 
 $username = $_POST['username']; 
 $password = $_POST['password']; 
$item_id = $_POST['item_id'];
$back = $_POST['back'];

 $sql = "SELECT user_id, username, user_password, user_active, user_level FROM forum_users 
 WHERE username = '" . str_replace("\'", "''", $username) . "'"; 

	$result = mysql_query($sql)
		or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));

	$num = mysql_numrows($result);
                if(!$num) {
BodyHeader("Incorrect Info..!"); 
echo "The wrong username or password has been submitted. Please click the back button in your browser and try again.";
exit;
        } 

 if( $row = mysql_fetch_array($result) ) { 

 if( md5($password) == $row['user_password'] && $row['user_active'] ) { 
   $session_id = session_begin($row['user_id'], $user_ip); 
   if ($session_id) { 
 BodyHeader("Write Your Review"); 
?> 
<h3 align="center"> 
Thank you <?php echo "$username"; ?> for logging in. 


</h2> 
<table border="0"> 
  <tr> 
    <td><a href="../../review_form.php?=SID&item_id=<?php if ($item_id == "") {
$item_id = "1";
}

echo "$item_id"; ?>&signedin=y&back=<?php echo "$back"; ?>"><img src="/images/write.gif" width="35" height="35" border="0"></a></td> 
    <td> <font face=verdana,arial,helvetica size=4><b>Write Your Own Review</b></font> </td> 
  </tr> 
</table> 
</p> 
<p><strong><font face=verdana,arial,helvetica color=#CC6600>You have successfully signed in</font></strong><br> 
</p> 
<p> &nbsp;<a href="../review_form.php?=SID&item_id=<?php if ($item_id == "") {
$item_id = "1";
}

echo "$item_id"; ?>&signedin=y&back=<?php echo "$back"; ?>&sid=<?php echo "$session_id"; ?>&username=<?php echo "$username"; ?>&back=<?php echo "$back"; ?>">Continue</a> to write a review.
  <?php
BodyFooter();
 return TRUE; 
   } 
   else { 
echo "Failure";
    return FALSE; 

   } 
  } 
  else { 
   return FALSE; 
  } 
 } 
} 

function session_begin($user_id,$user_ip) { 
 global $mysql_connection,$SID; 

 $sql = "SELECT * FROM forum_config"; 
	$result = mysql_query($sql)
		or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));

	$num = mysql_numrows($result);
                if(!$num) { 
echo "forum_config not able to be selected.";
exit;
        } 
 $board_config = @mysql_fetch_array($result) or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));


 $current_time = time(); 
 $cookiename = "review-script"; 
 $cookiepath = "/"; 
 $cookiedomain = ""; 
 $cookiesecure = 0; 
 $session_id = md5(uniqid($user_ip)); 
 $page_id = 0; 
 $login = 1; 
 $sessionid = ""; 
 $sessiondata['userid'] = $user_id; 

 $sql = "SELECT * FROM forum_users 
 WHERE user_id = $user_id"; 

	$result = mysql_query($sql)
		or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));

	$num = mysql_numrows($result);
                if(!$num) { 
echo "forum_users was unable to be selected.";
exit;
        } 
 $userdata = @mysql_fetch_array($result) or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));

 $sql = "INSERT INTO forum_sessions 
 (session_id, session_user_id, session_start, session_time, session_ip, session_page, session_logged_in) 
 VALUES ('$session_id', $user_id, $current_time, $current_time, '$user_ip', $page_id, $login)"; 

	$result = mysql_query($sql)
		or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));


 if($result) { 
  setcookie($cookiename . '_data', serialize($sessiondata), $current_time + 31536000, $cookiepath, $cookiedomain, $cookiesecure); 
  setcookie($cookiename . '_sid', $session_id, 0, $cookiepath, $cookiedomain, $cookiesecure); 
  $SID = 'sid=' . $session_id; 
  return TRUE; 
 } 
} 
?>