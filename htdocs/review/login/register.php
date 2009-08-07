<?php
session_start();
//session_destroy();
//session_start();
unset($image_random_value);
setcookie ("username", "", time() - 3600);

/*To force pages to always load the data that was entered in the form prior to hitting a submit button, and prevent the browser's cache message from displaying*/
   header("Cache-Control: must-revalidate");
   $offset = 60 * 60 * 24 * -1;
   $ExpStr = "Expires: " . gmdate("D, d M Y H:i:s", time() + $offset) . " GMT";
   header($ExpStr); 


$item_id = $_GET['item_id'];
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

include ("../body.php");
include ("../config.php");
include ("../functions.php");
include ("../f_secure.php");
include "Sajax2.php";

// Function to check if a username exists inside the database
function check_user_exist($username) {
	$username = mysql_escape_string($username);
	// Make a list of words to postfix on username for suggest
	$suggest = array('_review', '1', '_reviewer', '_rocks','_the_best');
	//$suggest = array();
	$sql = "SELECT `username` FROM `review_users` WHERE `username` = '$username'";
	$result = mysql_query($sql);
	if(mysql_num_rows($result) > 0) {
		// Username not available
		$avail[0] = 'no';
		$i = 2;
		// Loop through suggested ones checking them
		foreach($suggest AS $postfix) {
			$sql = "SELECT `username` FROM `review_users` WHERE `username` = '".$username.$postfix."'";
			$result = mysql_query($sql);
			if(mysql_num_rows($result) < 1) {
				$avail[$i] = $username.$postfix;
				$i ++;
			}
		}
		$avail[1] = $i - 1;
		return $avail;
	}
	// Username is available
	return array('yes');
}

sajax_init(); // Intialize Sajax
//$sajax_debug_mode = 1; //Uncomment to put Sajax in debug mode
sajax_export("check_user_exist"); // Register the function
sajax_handle_client_request(); // Serve client instances

BodyHeader("Registration","Register to Write a Review","");
?>
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_validateForm() { //v4.0
  var i,p,q,nm,test,num,min,max,errors='',args=MM_validateForm.arguments;
  for (i=0; i<(args.length-2); i+=3) { test=args[i+2]; val=MM_findObj(args[i]);
    if (val) { nm=val.name; if ((val=val.value)!="") {
      if (test.indexOf('isEmail')!=-1) { p=val.indexOf('@');
        if (p<1 || p==(val.length-1)) errors+='- '+nm+' must contain an e-mail address.\n';
      } else if (test!='R') { num = parseFloat(val);
        if (isNaN(val)) errors+='- '+nm+' must contain a number.\n';
        if (test.indexOf('inRange') != -1) { p=test.indexOf(':');
          min=test.substring(8,p); max=test.substring(p+1);
          if (num<min || max<num) errors+='- '+nm+' must contain a number between '+min+' and '+max+'.\n';
    } } } else if (test.charAt(0) == 'R') errors += '- '+nm+' is required.\n'; }
  } if (errors) alert('The following error(s) occurred:\n'+errors);
  document.MM_returnValue = (errors == '');
}
//-->
</script>
<script type="text/javascript">
	<?php
	sajax_show_javascript();
	?>
	function check_handle(result) {
		if(result[0] == 'yes') {
			document.getElementById('not_available').style.display = 'none';
			document.getElementById('available').style.display = 'block';
		}
		else {
			document.getElementById('available').style.display = 'none';
			document.getElementById('not_available').style.display = 'block';
			var str = 'Sorry that username is not available. Enter a new username or select one of these: <br />';
			for(i = 1; i < result[1]; i++) {
				str += "<input type=\"radio\" name=\"try\" onclick=\"switch_username('"+result[i+1]+"')\"/>" + result[i+1] + "<br />";
			}
			document.getElementById('not_available').innerHTML = str;
		}
	}

	function check_user_exist() {
		var username = document.getElementById('username').value;
		x_check_user_exist(username, check_handle);
	}

	function switch_username(username) {
		document.getElementById('username').value = username;
	}
	</script>

<script type="text/javascript" src="../javascript/form-submit.js"></script>
<script type="text/javascript" src="../javascript/ajax.js"></script>

	<style type="text/css">
	#available {
		display: none;
		color: green;
	}
	#not_available {
		display: none;
		color: red;
	}
	</style>
    <link href="../default.css" rel="stylesheet" type="text/css" />
    <link href="../review.css" rel="stylesheet" type="text/css" />
<fieldset>
<p>
  <legend><img src="<?php echo "$directory"; ?>/images/write.gif" width="35" height="35" alt="review_icon" align="absmiddle" /><span class="index2-summary"><strong><?php echo "$sitename Registration"; ?></strong></span></legend>
</p>
<div id="formResponse"><form action="#" method="post" name="myForm" id="myForm">
  <input name="item_id" type="hidden" value="<?php echo "$item_id"; ?>" />
  <input name="back" type="hidden" value="<?php echo "$back"; ?>" />
<br />
<br />
  <strong class="index2-Orange">New to <?php echo $sitename; ?>? Register Below.</strong><br />
  <br />

  <p>
    <label for="name">My name is:</label>
    <input type="text" name="name" value="<?php if (isset($_SESSION['name'])) { echo $_SESSION['name']; } ?>" size="40" maxlength="85" />
  </p>
  <p>
    <label for="name">My desired username is:</label>
    <input type="text" name="username" value="<?php if (isset($_SESSION['username'])) { echo $_SESSION['username']; } ?>" size="40" maxlength="85" onblur="check_user_exist(); return false;" />
&nbsp;  </p><div id="available"> Username is available! </div>
  <div id="not_available"> Sorry that username is not available. </div>
  </p>
    <p>
    <label for="name">My e-mail address:</label>
    <input type="text" name="email" value="<?php if (isset($_SESSION['email'])) { echo $_SESSION['email']; } ?>" size="40" maxlength="85" />
  </p>
  <p>
  <label for="name">Enter Code to Right: <span class="small">(Case SeNsiTiVe)</span>
  <div align="right">
  <input name="button" type="button" onclick="document.getElementById('randImage2').setAttribute('src', '<?php echo "$directory"; ?>/login/randomImage3.php?r='+ Math.floor(Math.random() * 5000));" value="New Code!" />
  &nbsp;&nbsp;&nbsp;
  </div>
  </label>
  <input name="txtNumber" type="text" id="txtNumber" value="" />
  <img src="<?php echo "$directory"; ?>/login/randomImage3.php" id='randImage2' alt="Verification Number" />
  </input>
  </p>
  <?php
if ($use_maillist == "y") { ?>
  <p>
    <label for="name">Add to Mail List:</label>
    <input name="maillist" type="checkbox" id="maillist" value="y" checked="checked" />
    Yes
    <input name="maillist" type="checkbox" id="maillist" value="n" />
    No 
  </p>
  <?php } ?><br />
<br />

<strong class="index2-Orange">Protect Your Information With a Password.</strong><br />
<br />  
  <p>
    <label for="name">Enter a new password:</label>
    <input type="password" name="passtext" value="<?php if (isset($_SESSION['passtext'])) { echo $_SESSION['passtext']; } ?>" size="40" maxlength="85" />
  </p>
  <br />
    <br />
    <br />
    
    <div align="center"><input type="button" id="mySubmit" class="formButton" value="Send" onclick="formObj.submit()" /></div>
    
  
</form></div>
</fieldset>
<script type="text/javascript">
var formObj = new DHTMLSuite.form({ formRef:'myForm',action:'../login/finished.php?back=<?php echo "$back"; ?>',responseEl:'formResponse'});
</script>
<?php
BodyFooter();
?> 
