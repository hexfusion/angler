<?php
//if a session does not yet exist for this user, start one
session_start();

/* if ($use_phpBB == "yes") {
include_once("../phpbb_include.php");   
} elseif ($use_vb == "yes") {
include_once("../vbulletin_include.php");
}//end use_vb */

if ($_SESSION['signedin'] != "y" && $_POST['active'] != "n")
	{
	// User not logged in, redirect to login page
	Header("Location: ../login/signin.php");
	}


$username = $_SESSION['username_logged'];

include ("../body.php");
include ("../functions.php");
include ("../f_secure.php");
include ("../config.php");

$sub_id = $_GET["sub_id"];
	
	
	$sql = "DELETE FROM review_subscriptions WHERE sub_id = \"$sub_id\" LIMIT 1";

        $sql_result = mysql_query($sql) 
            or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error())); 

	if (!$sql_result) {  
   
		echo "<P>Couldn't delete record!";

	} else {
		BodyHeader("$sitename:  Delete A Subscription"); 
        ?>
<link href="../review.css" rel="stylesheet" type="text/css">

<div align="center">
  <p>&nbsp;</p>
  <p><br />
        <span class="box1">You have successfully deleted an item from your subscriptions list. </span>
    </p>
</div>
<P>
<P> 
  
<div align="center">
  <p>Back to <a href="../usercp/index.php?<?php echo htmlspecialchars(SID); ?>">User Control Panel</a><br />
    </p>
  <p>&nbsp;</p>
  <p><br />
      </p>
</div>
  <?php 
				
        BodyFooter(); 
 }
 
?>

