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


		$sql = "SELECT * FROM 
			review WHERE approve = 'n' ORDER BY source ASC";
			
			$sql_result = mysql_query($sql)
		or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));

		if (!$sql_result) {  
   
			echo "<P>Couldn't get list!";

		} else {

		BodyHeader("$sitename: Delete Unapproved Reviews"); 
        ?>
<center><BR>
  <h2><font face="Verdana, Arial, Helvetica, sans-serif" size="3">Are you sure 
    you want to delete the following reviews?</font></h2>
</center>


<FORM method="POST" action="admin_del_unapp2.php?<?php echo htmlspecialchars(SID); ?>">
<table width="80%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr> 
    <td><div align="center"><strong><font size="3" face="Arial, Helvetica, sans-serif">Summary</font></strong></div></td>
    <td><div align="center"><strong><font size="3" face="Arial, Helvetica, sans-serif">Review</font></strong></div></td>
    <td><div align="center"><strong><font size="3" face="Arial, Helvetica, sans-serif">Source</font></strong></div></td>
    <td><div align="center"><strong><font size="3" face="Arial, Helvetica, sans-serif">Date 
        Added</font></strong></div></td>
  </tr>
<?php


while ($row = mysql_fetch_array($sql_result)) {

	$summary = $row["summary"];
	$review = $row["review"];
	$source = $row["source"];
	$date_added = $row["date_added"];
	$id = $row["id"];
?>
<tr> 
    <td><div align="center"><?php echo "$summary"; ?></div></td>
    <td><div align="center"><?php echo "$review"; ?></div></td>
    <td><div align="center"><?php echo "$source"; ?></div></td>
    <td><div align="center"><?php echo "$date_added"; ?></div></td>
  </tr>
<?php
}

echo "

<tr>
<td align=center colspan=2><BR>
<INPUT type=\"submit\" value=\"Delete Reviews\">
</td>
</tr>

</table>

</FORM>

";
?>
  <div align="center">Back to <a href="admin_menu.php?<?php echo htmlspecialchars(SID); ?>">Menu</a></div>
<?php
BodyFooter();
exit;
}
?>

