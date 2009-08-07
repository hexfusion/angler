<?php
//if a session does not yet exist for this user, start one
session_start();

//make sure user has been logged in.
$validU="";

if (isset($_SESSION['valid_user']) && $_SESSION['valid_user']!="")$validU=$_SESSION['valid_user']; 

if ($validU=="")
	{
	// User not logged in, redirect to login page
	Header("Location: index.php");
	}

include ("../body.php");
include ("../functions.php");
include ("../f_secure.php");
include ("../config.php");

		$sql = "SELECT * FROM review_users ORDER BY username
				 ";
			
			$sql_result = mysql_query($sql)
		or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));

		if (!$sql_result) {  
   
			echo "<P>Couldn't get list!";

		} else {

		BodyHeader("$sitename: Delete a User"); 
        ?>
<center>
  <h1>Select a User 
    from the 
    <?php echo "$sitename"; ?>
    Database</h1>
  <p>This will remove username from reviews and database. If you merely wish to prevent a user from writing additional reviews, you may want to consider this <a href="admin_member_report.php?<?php echo htmlspecialchars(SID); ?>">option</a>.</p>
</center>


<FORM method="POST" action="admin_del_user2.php?<?php echo htmlspecialchars(SID); ?>">

<table cellspacing=5 cellpadding=5>

<div align="center">
 <center>
 <table width="52%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
   <td width="100%">
   <p align="center"><strong>Users:</strong></td>
  </tr>
  <tr>
   <td width="100%">
   <p align="center">
<select name="user">
<option value=""> -- Select a Category -- </option>
<?php
while ($row = mysql_fetch_array($sql_result)) {
	$username = stripslashes($row["username"]);
echo "
	<option value=\"$username\">$username </option>
";
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
<div align=center>Back to <a href=admin_menu.php?<?php echo htmlspecialchars(SID); ?>>Menu</a><br /></div>
<?php
        BodyFooter();
		exit;
}
?>