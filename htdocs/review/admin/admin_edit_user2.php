<?php
//if a session does not yet exist for this user, start one
session_start();

//make sure user has been logged in.
if (!$_SESSION['valid_user'])
	{
	// User not logged in, redirect to login page
	Header("Location: index.php");
	}

include ("../body.php");
include ("../functions.php");
include ("../f_secure.php");
include ("../config.php");

if($_POST['sel_record'] != "") {
$sel_record = $_POST['sel_record'];
} elseif($_GET['sel_record'] != "") {
$sel_record = $_GET['sel_record'];
}

		if (!$sel_record) {
header("Location: http://$url$directory/admin_edit_user.php?".sid);
			exit;
		}
		
			$sql = "SELECT * FROM  review_users
			WHERE id='$sel_record'";
		
					$sql_result = mysql_query($sql)
		or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));


		if (!$sql_result) {  
   
			echo "<P>Couldn't get item!";

		} else {

			 while ($row = mysql_fetch_array($sql_result)) {
	$name = stripslashes($row["name"]);
	$email = stripslashes($row["email"]);
	$username = stripslashes($row["username"]);
	$active = stripslashes($row["active"]);
	$id= stripslashes($row['id']);
	$created= stripslashes($row['created']);
	
}

		BodyHeader("$sitename: Edit a User","",""); 
        ?>
<link href="../style_mem.css" rel="stylesheet" type="text/css" />
<link href="../style_forms.css" rel="stylesheet" type="text/css" />


<style type="text/css">
<!--
.style8 {color: #FF0000}
-->
</style>
<script type="text/javascript">
<!--
function MM_validateForm() { //v4.0
  if (document.getElementById){
    var i,p,q,nm,test,num,min,max,errors='',args=MM_validateForm.arguments;
    for (i=0; i<(args.length-2); i+=3) { test=args[i+2]; val=document.getElementById(args[i]);
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
} }
//-->
</script>
<div align="center">
  <fieldset>
  <p>
    <legend><span class="style3"><?php echo "$sitename"; ?></span><br />
    <span class="index2-summary">Edit a User</span></legend>
  </p>
  <form action="admin_edit_user3.php?<?php echo htmlspecialchars(SID); ?>" method="post"><input name="id" type="hidden" value="<?php echo "$id"; ?>" />
    <p>
      <label for="name">First Name:</label>
      <input name="name" type="text" id="name" value="<?php echo "$name"; ?>" size="30" maxlength="64" />
    </p>
           <p>
      <label for="username">Username:</label>
      <input name="username" type="text" id="username" value="<?php echo "$username"; ?>" size="30" maxlength="50" />
    </p>
    <p>
      <label for="email">E-mail Address:</label>
      <input name="email" type="text" id="email" value="<?php echo "$email"; ?>" size="30" maxlength="64" /></p>
      
      
            
      
   <?php
if ($use_maillist == "y") { ?>  <p>
      <label for="maillist">Add to Mail List:</label>
      <input name="maillist" type="checkbox" id="maillist" value="y" />
Yes
<input name="maillist" type="checkbox" id="maillist" value="n" checked="checked" />
No
<?php } ?>
</p>
    <p>
      <label for="passtext">Password:</label>
      <input name="passtext" type="text" id="passtext" />
  <br />

    <span class="small">(Password will not be changed if left blank.)  </span><br />
    <br />
 </p> 
 
 <p>&nbsp;</p>
 <br />
    <br />
    <input name="Submit" type="submit" onclick="MM_validateForm('name','','R','username','','R','email','','RisEmail');return document.MM_returnValue" value="Update <?php echo "$username"; ?>" />
  </form>
  <br />
  <br />
  <div align="center" class="medium">Back to <a href="admin_menu.php?<?php echo htmlspecialchars(SID); ?>">Admin Menu</a> or <a href="admin_member_report.php?<?php echo htmlspecialchars(SID); ?>">Member Report</a></div>
  <br />
  <br />
  </fieldset>
</div>

<?php
BodyFooter(); 
}
?>
