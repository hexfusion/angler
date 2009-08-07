<?php
session_start();

//Prevent Cross-Site Request Forgeries//
$token = md5(uniqid(rand(), TRUE));
$_SESSION['token'] = $token;
$_SESSION['token_timestamp'] = time();
////

include ("body.php");
include ("functions.php");
include ("f_secure.php");
include ("config.php");

$errjava = "<font color=red><BR><BR><B>No Javascript is allowed!<BR><BR></B></font>";

$item_id = clean($_GET['item_id']);
$item_id = preg_replace("'<script[^>]*?>.*?</script>'si", "$errjava", $item_id);



if ((!@$_POST['back']) && (!@$_GET['back'])) {
$back = $_SERVER['HTTP_REFERER'];
} elseif (isset($_GET['back']) && (!$_POST['back'])) {
$back = $_GET['back'];
} elseif (isset($_POST['back']) && (!$_GET['back'])) {
$back = $_POST['back'];
}

//some firewalls will block the http_referer.  If it is blocked, we'll send the user to the home page.
if($back == "") {
$back = "/";
}

//$item_id = makeStringSafe($_GET['item_id']);

$username = @$_SESSION['username'];



//get name from database
if (isset($_SESSION['username'])) {
$sql = "SELECT name FROM 
			review_users
			WHERE 
			username = '" . mysql_real_escape_string($username) . "'";
		
			$sql_result = mysql_query($sql)
		or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));
		
	while ($row = mysql_fetch_array($sql_result)) { 
$name = stripslashes($row["name"]); 
}
}
/*
$sql = "SELECT * FROM 
			review_items
			WHERE 
			item_id = '$item_id'";
	*/	
	
	$sql = "SELECT * FROM 
			review_items
			WHERE 
			item_id = '" . mysql_real_escape_string($item_id) . "'";
			
			$sql_result = mysql_query($sql)
		or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));
		
	while ($row = mysql_fetch_array($sql_result)) { 
$item_name = stripslashes($row["item_name"]); 
$item_desc = stripslashes($row["item_desc"]); 
$item_type = stripslashes($row["item_type"]); 
}

BodyHeader("Email a Friend...","Tell a Friend about $item_name","$item_name");
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
<table width="80%"  border="0" align="center" cellpadding="2" cellspacing="2">
  <tr>
    <td><p align="center"><span class="style2">Email a Friend About This <?php echo ucfirst($item_type); ?></span></p>
    <p>Want to tell a friend about <?php echo "$item_name"; ?>? It's easy! Just fill out the form below and click the Email-My-Friend button and they will receive your personal recommendation! </p></td>
  </tr>
</table>
<br />
<form name="form1" method="post" action="recommend2.php?<?PHP 
    // generate dynamic_info var 
    print('data=' . 5);   
  ?>&amp;<?php echo htmlspecialchars(SID); ?>">
  <input name="item_name" type="hidden" value="<?php echo "$item_name"; ?>">
<input name="item_type" type="hidden" value="<?php echo "$item_type"; ?>">
<input name="item_desc" type="hidden" value="<?php echo "$item_desc"; ?>">
<input name="back" type="hidden" value="<?php echo "$back"; ?>">
<input name="item_id" type="hidden" value="<?php echo "$item_id"; ?>">
<table width="59%"  border="0" align="center" cellpadding="2" cellspacing="2">
  <tr>
    <td><strong>Name of Friend:</strong></td>
    <td><input name="recipient" type="text" id="recipient"></td>
  </tr>
  <tr>
    <td><strong>Email of Friend </strong></td>
    <td><input name="rec_email" type="text" id="rec_email"></td>
  </tr>
  <tr>
    <td><strong>Your Name: </strong></td>
    <td><input name="username" type="text" id="username" value="<?php 
if (isset($name)) {
echo ucfirst($name); } ?>"></td>  </tr>
  <tr>
    <td><strong>Your Email Address: </strong></td>
    <td><input name="email" type="text" id="email" value=<?php 
if ($_SESSION['signedin'] == "y") {
$email = @$_SESSION['username'];
echo "$email"; } ?>></td>
  </tr>
  <tr>
    <td><strong>Your Personal Message: </strong></td>
    <td><textarea name="message" cols="40" rows="10" id="message">I found this <?php echo ucfirst($item_type); ?> at <?php echo "$sitename"; ?>.  It is called <?php echo ucfirst($item_name); ?>.  Check it out! 
	
Here is the direct link:  

<?php echo "$url$directory"; ?>/index2.php?item_id=<?php echo "$item_id"; ?></textarea></td>
  </tr>
  <tr>
    <td><span class="small"><b>Enter Code to Right:<br />
(Case SeNsiTiVe) </b></span></td>
    <td><table border="0">
      <tr>
        <td align="right" valign="middle">&nbsp;</td>
        <td valign="middle"><input name="txtNumber" type="text" id="txtNumber" value="" />
            <br /></td>
        <td valign="middle"><img src="./login/randomImage3.php" id='randImage2' alt="Verification Number" align="absmiddle" /></td>
        <td valign="middle"><div>
           
<input name="button" type="button" onclick="document.getElementById('randImage2').setAttribute('src', './login/randomImage3.php?r='+ Math.floor(Math.random() * 5000));" value="New Code!" />
   </input>
        </div></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td colspan="2"><div align="center">
      <br />
      <input name="Submit" type="submit" onClick="MM_validateForm('recipient','','R','rec_email','','RisEmail','username','','R','email','','RisEmail');return document.MM_returnValue" value="Email-My-Friend">
    </div></td>
    </tr>
</table><input type="hidden" name="token" value="<?php echo $token; ?>" />
</form>
<p>&nbsp;</p>
<div align="center">Back to <a href=<?php echo "$back"; ?>><?php echo "$item_name"; ?></a></div>
<?php
BodyFooter();
exit;
?>

