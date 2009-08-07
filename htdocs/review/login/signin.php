<?php
session_start();
session_regenerate_id(TRUE);

//Prevent Cross-Site Request Forgeries//
$token = md5(uniqid(rand(), TRUE));
$_SESSION['token'] = $token;
$_SESSION['token_timestamp'] = time();
////


$item_id = @$_GET['item_id'];

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

include ("../body.php");
include ("../functions.php");
include ("../f_secure.php");
include ("../config.php");
BodyHeader("Please Log In","","");
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
<link href="../review.css" rel="stylesheet" type="text/css" />
<fieldset>
<legend><img src="<?php echo "$directory"; ?>/images/write.gif" width="35" height="35" alt="review_icon" align="absmiddle" /><span class="index2-summary"><strong><?php echo "$sitename Login"; ?></strong></span></legend>
<div id="formResponse">
  <form action="#" method="post" name="myForm" id="myForm">
    <br />
    <input name="item_id" type="hidden" value="<?php echo "$item_id"; ?>" />
    <input name="back" type="hidden" value="<?php echo "$back"; ?>" />
   <br />
 <span class="index2-Orange">What is your username? </span>
    <p>
      <label for="name">My username is:</label>
      <input type="text" name="username" value="" size="40" maxlength="85" />
    </p>
    <span class="index2-Orange"><br />
    Do you have a <?php echo "$sitename"; ?> password?<br />
    </span>
    <p>
      <label for="name">
      <input type="radio" checked="checked" value="sign-in" name="action" />
      Yes, I have a password: <br />
      <input name="action" type="radio" onclick="MM_goToURL('parent','register.php');return document.MM_returnValue" value="continue" />
      No, I am a new customer. </label>
      <input name="passtext" type="password" id="passtext" size="40" maxlength="20" value="" />
    </p>
    <input type="hidden" name="token" value="<?php echo $token; ?>" />
    <br />
    <br />
    <br />
    <div align="center">
      <input type="button" id="mySubmit" class="formButton" value="Login" onclick="formObj.submit()" />
    </div>
  </form>
</div>
</fieldset>
<script type="text/javascript">
var formObj = new DHTMLSuite.form({ formRef:'myForm',action:'<?php echo "$directory"; ?>/login/success.php?back=<?php echo "$back"; ?>&amp;<?php echo htmlspecialchars(SID); ?>',responseEl:'formResponse'});
</script>
<?php
BodyFooter();
?>
