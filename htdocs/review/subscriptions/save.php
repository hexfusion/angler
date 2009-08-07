<?php
session_start();

include ('../functions.php');
include ('../f_secure.php');
include ('../body.php');
include ('../config.php');

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
	
//print_r($_SESSION);

$item_id = $_GET['item_id'];
$username = $_SESSION['username_logged'];
$back = $_SERVER['HTTP_REFERER'];

$sql = "SELECT * FROM review_items WHERE item_id='$item_id'";
		
					$sql_result = mysql_query($sql)
		or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));

		if (!$sql_result) {  
   
			echo "<P>Couldn't get item!";

		} else {

			 while ($row = mysql_fetch_array($sql_result)) {
	$item_name = stripslashes($row["item_name"]);
} //end while
} //end else
$created = date("Y-m-d",time());
$sql = "INSERT INTO review_subscriptions 
SET item_id_sub='$item_id',
username='$username',
created='$created'
";


$result = @mysql_query($sql,$connection)
	or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));
	
BodyHeader("Saved to Subscriptions!","","");
?>
<link href="../review.css" rel="stylesheet" type="text/css">

<div align="center"><br />
<br />
<br />

<div id="borderbox"> <br />
 <?php echo ucfirst($item_name); ?>
  has been saved to your subscriptions.  You will receive an email notification to alert you of any new reviews to this item.
  <br />
<br /></div>  
  <p>&nbsp;</p>
  <p>Back to <a href="<?php echo "$back"; ?>&<?php echo htmlspecialchars(SID); ?>">previous page</a></p>
  <p><br />
<br />
<br /></p></div>
<?php
BodyFooter();
exit;
?>
