<?php
//this file will show the users with the most reviews.
$username = $_SESSION['username_logged'];
include_once ("body.php");
include_once ("functions.php");
include_once ("f_secure.php");
include_once ("config.php");

//can't display login box if user is on the registration page due to a conflict.
if($_SERVER['SCRIPT_NAME'] == "$directory/login/register.php") {
echo "Please enter your registration information to post reviews.";
} elseif


//if user is not signed in, display the login box
 ($_SESSION['signedin'] != "y") {



//Prevent Cross-Site Request Forgeries//
$token = md5(uniqid(rand(), TRUE));
$_SESSION['token'] = $token;
$_SESSION['token_timestamp'] = time();
////


//override the back setting in case user is logging in from non-review page
if (!@$_GET['back']) {
$back = $_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING'];
} else {
$back = $_GET['back'];
}  

//coming from non review
if (!@$_GET['item_id']) {
$back = $_SERVER['HTTP_REFERER'];
}

//$back= $_SERVER['REQUEST_URI'];


?>
<script type="text/javascript">
<!--
function MM_goToURL() { //v3.0
  var i, args=MM_goToURL.arguments; document.MM_returnValue = false;
  for (i=0; i<(args.length-1); i+=2) eval(args[i]+".location='"+args[i+1]+"'");
}
//-->
</script>
<script type="text/javascript" src="<?php echo "$directory"; ?>/javascript/form-submit.js"></script>
<script type="text/javascript" src="<?php echo "$directory"; ?>/javascript/ajax.js"></script>
<link href="<?php echo "$directory"; ?>/review.css" rel="stylesheet" type="text/css" />
<h2>Login</h2>
<div id="formResponse">
  <form action="#" method="post" name="myForm" id="myForm">
    <input name="back" type="hidden" value="<?php echo "$back"; ?>" />
    <p>
      <label for="name">Username:</label>
      <input type="text" name="username" value="" size="10" maxlength="85" />
    </p>
    <p>
      <label for="name"> Password: </label>
      <input name="passtext" type="password" id="passtext" size="10" maxlength="20" value="" />
    </p>
    <input type="hidden" name="token" value="<?php echo $token; ?>" />
    <br />
    <div align="center">
      <input type="button" id="mySubmit" class="formButton" value="Login" onclick="formObj.submit()" />
      <br />
      <span class="style2"><a href="<?php echo "$directory"; ?>/login/forgotpassword.php?item_id=1&amp;<?php echo htmlspecialchars(SID); ?>">Forgot Your Password?</a></span> </div>
  </form>
  <br />
  <br />
  Not a Member Yet?<br />
  <span class="style2">You must be a member to write reviews. <a href="<?php echo "$directory"; ?>/login/register.php?<?php echo htmlspecialchars(SID); ?>"> Click Here to Register</a></span></div>
<script type="text/javascript">
var formObj = new DHTMLSuite.form({ formRef:'myForm',action:'<?php echo "$directory"; ?>/login/success.php?back=<?php echo "$back"; ?>&amp;<?php echo htmlspecialchars(SID); ?>',responseEl:'formResponse'});
</script>
<?php 

} else { // end  if not logged in.

//find number of useful responses.
$sql = "SELECT count(review) AS num_reviews FROM review WHERE username='" . mysql_real_escape_string($username) . "'
";
$sql_result = mysql_query($sql)	or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));

$i = mysql_fetch_array($sql_result); 
$num_reviews = $i['num_reviews']; 



?>
Welcome Back <?php echo ucfirst($_SESSION['username_logged']); ?> <span class="style2"> <br />
<br />
<table width="90%" border="0">
  <tr>
    <td width="60"><div align="center">
        <?php
    //if there is an image, resize it and then display it.
$filename = "usercp/upload/images/$username.jpg";
if (file_exists($filename)) {
$html="<a class=\"thumbnail\" href=\"#thumb\">  <img src=$directory/usercp/upload/images/resize_block.php?filename=$username.jpg border=\"0\"><span><img src=\"$directory/$filename\" />$username</span></a>";
print $html;
} else { ?>
        <img src="<?php echo "$directory"; ?>/usercp/upload/images/Anonymous.jpg" width="48" height="48" />
        <?php } ?>
      </div></td>
    <td><a href="<?php echo "$directory"; ?>/usercp/profile_edit1.php?<?php echo htmlspecialchars(SID); ?>">Edit Profile</a><br />
      <a href="<?php echo "$directory"; ?>/usercp/upload/upload.php?<?php echo htmlspecialchars(SID); ?>">Edit Photo</a><br />
      <a href="<?php echo "$directory"; ?>/usercp/notes.php">Manage Notes</a><br />
      <a href="<?php echo "$directory"; ?>/reviewer_about.php?username=<?php echo $_SESSION['username_logged']; ?>&amp;<?php echo htmlspecialchars(SID); ?>"> My Reviews</a></td>
  </tr>
  <tr>
    <td colspan="2"><div align="center"><br />
    Thanks for posting <?php echo "$num_reviews"; ?> Reviews!</div></td>
  </tr>
  <tr>
    <td colspan="2"><div align="center"></div>      
      <div align="right"><a href="logout.php?<?php echo htmlspecialchars(SID); ?>">Logout</a><br />
    <a href="<?php echo "$directory"; ?>/usercp/profile_edit1.php?<?php echo htmlspecialchars(SID); ?>">Change My Password</a></div></td>
  </tr>
</table>
</span>
<?php } ?>
