<?php
//if a session does not yet exist for this user, start one
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


$id= makeStringSafe($_GET['id']);

	if (!$id) {
header("Location: http://$url$directory/admin/admin_menu.php?".sid);
			exit;
		}
	
	
	$sql = "UPDATE  review_users SET 
	active = 'y'
	WHERE id = '$id'
	";

        $sql_result = mysql_query($sql) 
            or die( "Couldn't execute update query."); 

	if (!$sql_result) {  
   
		echo "<P>Couldn't edit record!";

	} else {
		BodyHeader("$sitename:  Enable User Access","",""); 			
		
	
		
        ?>



<div align="center">
    <br /><br />
<br />

    <span class="column_head">You have Enabled the User's Account Access</font>    </span>
  </div><br />
<br />
<br />


  <div align="center" class="medium">Back to <a href="admin_menu.php?<?php echo htmlspecialchars(SID); ?>">Admin Menu</a> or <a href="admin_member_report.php?<?php echo htmlspecialchars(SID); ?>">Member Report</a></div><br />

<?php 
}				
        BodyFooter(); 
 exit;
 
?>
