<?php
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


#Send a message to the browser
BodyHeader("Admin Custom Links","","");
?>
<p>Place custom Admin Links on this page.</p>
<p>&nbsp;</p>
<div align="center">
    
    Back to <a href="admin_menu.php?<?php echo htmlspecialchars(SID); ?>">Menu</a>
</div>
  <br />
  
  
  
  
<?php
//what version?
$result = mysql_query("SELECT version FROM review_admin") or
    die(sprintf("Couldn't execute sql_count, %s: %s", db_errno(), db_error()));

$rows = mysql_fetch_array($result);
$version = $rows["version"];

echo "Five Star Review Script Version $version";

BodyFooter();
exit;
?>
