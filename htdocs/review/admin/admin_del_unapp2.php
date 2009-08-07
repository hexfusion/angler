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
		
			$sql2 = "SELECT * FROM 
			review WHERE approve = 'n' ORDER BY source ASC";
			
			$sql_result2 = mysql_query($sql2)
		or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));
	
	
	$sql = "DELETE FROM review WHERE approve = 'n'";

        $sql_result = mysql_query($sql) 
            or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error())); 

	if (!$sql_result) {  
   
		echo "<P>Couldn't delete record!";

	} else {
		BodyHeader("$sitename:  Delete A Review"); 
        ?>
<h1><center><BR><font face="Verdana, Arial, Helvetica, sans-serif" size="3">You have deleted 
  the following reviews:</font></center></h1>
<P> 
<table width="80%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr> 
    <td><div align="center"><strong><font size="3" face="Arial, Helvetica, sans-serif">Summary</font></strong></div></td>
    <td><div align="center"><strong><font size="3" face="Arial, Helvetica, sans-serif">Review</font></strong></div></td>
    <td><div align="center"><strong><font size="3" face="Arial, Helvetica, sans-serif">Source</font></strong></div></td>
    <td><div align="center"><strong><font size="3" face="Arial, Helvetica, sans-serif">Date 
        Added</font></strong></div></td>
  </tr>
  <?php


while ($row2 = mysql_fetch_array($sql_result2)) {

	$summary = $row2["summary"];
	$review = $row2["review"];
	$source = $row2["source"];
	$date_added = $row2["date_added"];
	$id = $row2["id"];
?>
  <tr> 
    <td><div align="center"><?php echo "$summary"; ?></div></td>
    <td><div align="center"><?php echo "$review"; ?></div></td>
    <td><div align="center"><?php echo "$source"; ?></div></td>
    <td><div align="center"><?php echo "$date_added"; ?></div></td>
  </tr>
  <?php
}
?>
</table><BR>
<div align="center">Back to <a href="admin_menu.php?<?php echo htmlspecialchars(SID); ?>">Menu</a><br /></div>
<?php 
BodyFooter(); 
}
?>
