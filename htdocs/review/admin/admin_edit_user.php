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

$sql = "SELECT * 
			FROM  review_users
			ORDER BY username ASC";

			$sql_result = mysql_query($sql)
		or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));

		if (!$sql_result) {  
   
			echo "<P>Couldn't get list!";

		} else {

		BodyHeader("$sitename: Edit a User","",""); 
        ?>
<link href="../style_forms.css" rel="stylesheet" type="text/css" />
<link href="../style_mem.css" rel="stylesheet" type="text/css" />

		<div align="center">
 <fieldset>
  <p>
        <legend><span class="style3"><?php echo "$sitename"; ?></span><br />
        <span class="index2-summary">Edit a User</span></legend>
  
  <center>
<h2 class="index2-Orange"><font face="Verdana, Arial, Helvetica, sans-serif" size="3">Select a User for Editing</font></h2>
</center>


<FORM method="POST" action="admin_edit_user2.php?<?php echo htmlspecialchars(SID); ?>">

 <table width="52%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
   <td width="100%">
   <p align="center" class="index2-summary"><strong>Username/Name/Email/Date Added:</strong></td>
  </tr>
  <tr>
   <td width="100%">
   <p align="center">
<select name="sel_record">
<option value=""> -- Select a User -- </option>
<?php
while ($row = mysql_fetch_array($sql_result)) {
	$email = stripslashes($row["email"]);
	$name = stripslashes($row["name"]);
		$username = stripslashes($row["username"]);
	$active = stripslashes($row["active"]);
		$id= stripslashes($row['id']);
	$created= stripslashes($row['created']);

	
if ($id != '') {
echo "
	<option value=\"$id\">$username : $name : $email : $created </option>
";
}
}
?>
</select>
</td>
   </tr>
	
<tr>
<td align=center colspan=2><BR>
<INPUT type="submit" value="Select User">
</td>
</tr>

</table>

</FORM>
<br />
<br />

<div align="center">Back to <a href="admin_menu.php?<?php echo htmlspecialchars(SID); ?>">Menu</a><br /></div></fieldset></div>
<?php
        BodyFooter();
		exit;
}
?>

