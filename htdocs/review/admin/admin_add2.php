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


$image_upload = @$_POST['image_upload'];
$item_name = addslashes($_POST['item_name']);
$item_desc = addslashes($_POST['item_desc']);
$item_type = addslashes($_POST['item_type']);
$category = $_POST['category'];
$item_aff_url = addslashes($_POST['item_aff_url']);
$item_aff_txt = addslashes($_POST['item_aff_txt']);
$item_aff_code = addslashes($_POST['item_aff_code']);

if ($item_name == "") {
echo "You are required to add an item name!";
exit;
}


	$sql = "INSERT INTO review_items
	SET 
item_name='$item_name',
item_desc='$item_desc',
item_type='$item_type',
item_aff_url='$item_aff_url',
item_aff_txt='$item_aff_txt',
item_aff_code='$item_aff_code',
category='$category',
category_id = " . $category;

$result = @mysql_query($sql,$connection) or die(sprintf("Couldn't execute sql_count, %s: %s", db_errno(), db_error()));

///////////////////////////////////////////////////////////////////////////////////////
	$new_item_id = mysql_insert_id();
	
	$sql = "SELECT * FROM review_items_supplement order by id asc";
	$sql_result = mysql_query($sql)	or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));
	while ($row = mysql_fetch_array($sql_result)) {
		$textarea_name = "review_supplement_item_" . $row['id'];
		$supplement_item_value = $_POST[$textarea_name];
		$checkbox_name = "show_review_supplement_item_" . $row['id'];
		if(!isset($_POST[$checkbox_name])) {
			$supplement_item_selected = 0;
		}else{
			$supplement_item_selected = 1;
		}
		$sql = "insert into review_items_supplement_data (review_item_id, item_supplement_id, value, selected) values (".$new_item_id.", " . $row['id'] . ", '".$supplement_item_value."', ".$supplement_item_selected.")";
		mysql_query($sql)	or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));
	}

///////////////////////////////////////////////////////////////////////////////////////


$item_id = $new_item_id;
//insert which criterias to be allowed for this item
$query="SELECT criteriaId FROM rating_criteria WHERE isActive='T'";
$result=mysql_query($query) or die(mysql_error());
while($row=mysql_fetch_array($result)){
	$cId=$row["criteriaId"];
	if(isset($_POST["chk$cId"]) && $_POST["chk$cId"]==$cId){
		$query="INSERT INTO item_rating_criteria VALUES($item_id, $cId)";
		mysql_query($query) or die(mysql_error());
	}
}
 
#Send a message to the browser
BodyHeader("Your account has been created!");
?>
<center>
  <p>Your item account has been added. </p>
  <p>Use the following link to allow users review access</p>
  <p>
    <input name="textfield" type="text" value="<?php echo "$url$directory/index2.php?item_id=$item_id"; ?>" size="60">
    <br /><?php echo "<a href=$url$directory/index2.php?item_id=$item_id>$url$directory/index2.php?item_id=$item_id</a>"; ?>
    <BR>
  </p>
  <div align="center">
    <P align="center">Would you like to upload an image to be displayed with this item? <a href="admin_upload.php?item_id=<?php echo "$item_id"; ?>&<?php echo htmlspecialchars(SID); ?>">Yes</a>
    <p>Back to <a href="admin_menu.php?<?php echo htmlspecialchars(SID); ?>">Menu</a></p>
  </div>
  <br />
</center>
<?php
BodyFooter();
exit;
?>
