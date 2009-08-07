<?php
session_start();

//make sure user has been logged in.
$validU="";

if (isset($_SESSION['valid_user']) && $_SESSION['valid_user']!="")$validU=$_SESSION['valid_user']; 

if ($validU=="")
	{
	// User not logged in, redirect to login page	
                    header("Location: index.php");}

include ("../body.php");
include ("../functions.php");
include ("../f_secure.php");
include ("../config.php");


//how many active members?
$query2 = "SELECT count(*) as tot_active FROM  review_users WHERE active = 'y' AND username != ''
";

    $result2 = mysql_query($query2) 
or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));

while ($row2 = mysql_fetch_array($result2)) { 
	$tot_active = stripslashes($row2["tot_active"]);
	}
	
	//how many inactive members?
$query2 = "SELECT count(*) as tot_inactive FROM  review_users WHERE active = 'n' AND username != ''
";

    $result2 = mysql_query($query2) 
or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));

while ($row2 = mysql_fetch_array($result2)) { 
	$tot_inactive = stripslashes($row2["tot_inactive"]);
	}

BodyHeader("Member Report","","");
?>
<table width="95%" border="0" cellspacing="5" cellpadding="1" align="center">
  <tr>
    <td colspan="7"><p class="index2">There are a total of <?php echo "$tot_active"; ?> active members and <?php echo "$tot_inactive"; ?> inactive members.</p>
      <p> <span class="medium"><strong>&middot;</strong> The disable link gives the ability to set user to inactive while maintaining username in the database. Member access is disabled.<br />
          <strong>&middot;</strong> To edit a user, click on the username. </span><br />
        <br />
        <br />
        Sort by <a href="admin_member_report.php?sorder=username">Username</a> - <a href="admin_member_report.php?sorder=name">Name</a> - <a href="admin_member_report.php?sorder=active">Enabled</a> - <a href="admin_member_report.php?sorder=created">Join Date</a></p></td>
  </tr>
  <tr class="style3">
	<td width="20%" class="column_head"><div align="left">Name </div></td><td width="20%" class="column_head"><div align="left">Username </div></td>
   <td width="12%" class="column_head"><div align="center">Join Date </div></td>
    <td width="12%" class="column_head"><div align="center">Enable </div></td>
    <td width="12%" class="column_head"><div align="center">Disable </div></td>
    <td width="16%" class="column_head"><div align="center">Details</div></td>
	
  </tr>
  <?php
  

	
	if (isset($_GET['sorder'])) {
	$sorder = makeStringSafe($_GET["sorder"]);
} elseif (isset($_SESSION['sorder'])) {
$sorder = $_SESSION['sorder']; 
} 

if ($sorder == "") {
$sorder = "username";
}

$_SESSION['sorder'] = "$sorder";	

$query2 = "SELECT * FROM  review_users ORDER BY $sorder ASC
";

    $result2 = mysql_query($query2) 
or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));

while ($row2 = mysql_fetch_array($result2)) { 
	$email = stripslashes($row2["email"]);
	$name = stripslashes($row2["name"]);
	$created = stripslashes($row2["created"]);
		$username = stripslashes($row2["username"]);
	$active = stripslashes($row2["active"]);
	$id= stripslashes($row2['id']);

?>
  <tr class="maintext">
  <td><a href="admin_edit_user2.php?sel_record=<?php echo "$id"; ?>&amp;<?php echo htmlspecialchars(SID); ?>"><?php echo "$name"; ?></a></td>
	<td><a href="admin_edit_user2.php?sel_record=<?php echo "$id"; ?>&amp;<?php echo htmlspecialchars(SID); ?>"><?php echo "$username"; ?></a></td>
<td><a href="admin_edit_user2.php?sel_record=<?php echo "$id"; ?>&amp;<?php echo htmlspecialchars(SID); ?>"><?php echo "$created"; ?></a></td>
    <td>
	<?php if ($active == 'n') { ?>
	
	<div align="center"><a href="admin_enable_user.php?id=<?php echo "$id"; ?>&<?php echo htmlspecialchars(SID); ?>">Enable</a></div>
	<?php } else { echo "<div align=\"center\">enabled</div>";  } //end active n ?>
	
	</td>
    <td>
	<?php if ($active == 'y') { ?>
	
	<div align="center"><a href="admin_disable_user.php?id=<?php echo "$id"; ?>&<?php echo htmlspecialchars(SID); ?>">Disable</a></div>
	<?php } else { echo "<div align=\"center\">disabled</div>";  } //end active n ?>
	
	</td>
    <td><div align="center">
        <a href="admin_member_details.php?id=<?php echo "$id"; ?>&<?php echo htmlspecialchars(SID); ?>">Details</a>      </div></td>
	   	 

  </tr>
  <?php
			  
}			
?>
</table>
<br /><br />
<br />

<div align="center" class="medium">Back to <a href="admin_menu.php?<?php echo htmlspecialchars(SID); ?>">Menu</a></div>
<?php
BodyFooter();
exit;
?>
