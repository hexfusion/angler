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

if (!isset($_SESSION['username_logged'])) {
$_SESSION['username_logged'] = "";
}	

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
	$category = stripslashes($row["category"]);
	$item_type = stripslashes($row["item_type"]);
	$item_desc = stripslashes($row["item_desc"]);
}
}

$sql = "INSERT INTO review_favorites
	SET item_id_fav='$item_id',
username='$username'
	";

$result = @mysql_query($sql,$connection)
	or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));
	
BodyHeader("Saved to Favorites!","","");
?>
<link href="../review.css" rel="stylesheet" type="text/css" />
<div align="center"><br />
  <br />
  <br />
  <div id="borderbox"> <br />
  <?php echo ucfirst($item_name); ?> has been saved to your favorites.<br /> <br /> </div> 
  <br />
  <br />
  <p>Back to <a href="<?php echo "$back"; ?>&amp;<?php echo htmlspecialchars(SID); ?>">previous page</a></p>
  <br />
  <br />
  <br />
</div>
<?php
BodyFooter();
?>
