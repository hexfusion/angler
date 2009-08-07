<?php
//if a session does not yet exist for this user, start one
session_start();

function getCategoryName($catId){
	$sql='Select * from review_category_list where cat_id_cloud = ' . $catId;
	$sql_result = mysql_query($sql);
	while ($row = mysql_fetch_array($sql_result)) {
		return stripslashes($row["category"]);	
	}	
}

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

//print_r($_POST);
//echo "<BR>";

$item_desc = addslashes($_POST["item_desc"]);
$item_name = addslashes($_POST["item_name"]);
$sel_record = addslashes($_POST["sel_record"]);
$item_type = addslashes($_POST["item_type"]);
$category_id = $_POST["category"];
$item_id = $_POST["sel_record"];
$item_aff_url = addslashes($_POST['item_aff_url']);
$item_aff_txt = addslashes($_POST['item_aff_txt']);
$item_aff_code = addslashes($_POST['item_aff_code']);

$category = getCategoryName($category_id);


if (!$sel_record) {
header("Location: $url$directory/admin/index.php?".sid);
			exit;
		}

	$sql = "UPDATE review_items SET 
item_name='$item_name',
item_desc='$item_desc',
item_type='$item_type',
item_aff_url='$item_aff_url',
item_aff_txt='$item_aff_txt',
item_aff_code='$item_aff_code',
category = '".addslashes($category)."',
category_id = '$category_id'
WHERE item_id = '$sel_record'";
        $sql_result = mysql_query($sql) or die( "Couldn't execute update query."); 

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

		$sql = "update review_items_supplement_data set value = '" . $supplement_item_value . "', selected = " . $supplement_item_selected . " where review_item_id = " . $sel_record . " and item_supplement_id = " . $row['id'];
		
		//echo "$sql<BR>";
	$results = mysql_query($sql);
		
$num_rows = mysql_num_rows($results);

//echo "$num_rows Rows<BR>\n";

if ($num_rows < 1) {
//insert instead of update
$sql = "insert into review_items_supplement_data (review_item_id, item_supplement_id, value, selected) values (".$sel_record.", " . $row['id'] . ", '".$supplement_item_value."', ".$supplement_item_selected.")";

		//echo "NUMROWS:   $sql<BR>"; 

		mysql_query($sql);
}//end num_rows
		
	}

///////////////////////////////////////////////////////////////////////////////////////


	if (!$sql_result) {  
   
		echo "<P>Couldn't edit record!";

	} else {

//insert which criterias to be allowed for this item
$query="DELETE FROM item_rating_criteria WHERE item_id=" . $sel_record;
mysql_query($query) or die(mysql_error());

$query="SELECT criteriaId FROM rating_criteria WHERE isActive='T'";
$result=mysql_query($query) or die(mysql_error());
while($row=mysql_fetch_array($result)){
	$cId=$row["criteriaId"];
	if(isset($_POST["chk$cId"]) && $_POST["chk$cId"]==$cId){
		$query="INSERT INTO item_rating_criteria VALUES($item_id,$cId)";
		mysql_query($query) or die(mysql_error());
	}
}

		BodyHeader("$sitename:  Edit A Item","",""); 
        ?>

<h1>
  <center>
    <BR>
    <font face="Verdana, Arial, Helvetica, sans-serif" size="3">You have Edited 
    the following: </font>
  </center>
</h1>
<table width="464" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td width="234" valign=top><strong>Item Name:</strong></td>
    <td width="230" valign=top><input name=item_name type=text value="<?php echo stripslashes($item_name); ?>" size="50">
    </td>
  </tr>
  <tr>
    <td valign=top><strong>Item Description:</strong></td>
    <td valign=top><textarea name="item_desc" cols="50" rows="20"><?php echo stripslashes($item_desc); ?></textarea>
    </td>
  </tr>
  <tr>
    <td valign=top><strong>Item Type:<font size="1"><br />
      <font face="Arial, Helvetica, sans-serif">(<a href="../review_form.php?item_id=1">review_form.php</a> where it says &quot;How do rate this _ &quot; The word you insert here will be displayed at the end of that sentence.)</font></font></strong></td>
    <td valign=middle><input type=text name=item_type value="<?php echo stripslashes($item_type); ?>">
    </td>
  </tr>
  <tr>
    <td valign=top><strong>Category:</strong></td>
    <td valign=top><input type=text name=web value="<?php echo "$category"; ?>">
    </td>
  </tr>
  <tr>
    <td align=center colspan=2><strong><br />
      OPTIONAL: </strong>The fields below are to add a link to index2.php.</td>
  </tr>
  <tr>
    <td valign=top><strong>URL (include http://):</strong></td>
    <td valign=top><div align="center">
        <input name="item_aff_url " type="text" id="item_aff_url " value="<?php echo stripslashes($item_aff_url); ?>" size="30">
      </div></td>
  </tr>
  <tr>
    <td valign=top><strong>Text for URL:</strong></td>
    <td valign=top><div align="center">
        <input name=" item_aff_txt" type="text" id=" item_aff_txt" value="<?php echo stripslashes($item_aff_txt); ?>" size="30">
      </div></td>
  </tr>
  <tr>
    <td colspan="2" valign=top><strong><em>OR</em></strong> if you would like to insert html code that contains the url and text: </td>
  </tr>
  <tr>
    <td valign=top><strong>HTML Code :</strong></td>
    <td valign=top><div align="center">
        <textarea name=" item_aff_code" id=" item_aff_code"><?php echo stripslashes($item_aff_code); ?></textarea>
      </div></td>
  </tr>
  <?php
	$sql = "SELECT * FROM review_items_supplement order by id asc";
	$sql_result = mysql_query($sql)	or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));
	while ($row = mysql_fetch_array($sql_result)) {
		
		$sql = "select * from review_items_supplement_data where review_item_id =".$sel_record." and item_supplement_id = " . $row['id'];
		$supplement_data = mysql_query($sql)	or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));
		$data = mysql_fetch_array($supplement_data);
?>
  <tr>
    <td>&nbsp;<strong><?php echo $row['itemname'] ?></strong></td>
    <td align="center"><textarea rows="3" name="review_supplement_item_<?php echo $row['id'] ?>" ><?php echo $data['value'] ?></textarea>
      <input name="show_review_supplement_item_<?php echo $row['id'] ?>" type="checkbox" value="<?php echo $row['id'] ?>" 
	  <?php 
	  	if($data['selected'] > 0 ){
			echo " checked ";
		} 
	  ?>
       />
      Select</td>
  </tr>
  <?php
	}
?>
  <tr>
  
  <td colspan=2><br />
    <div align=center>Choose Review Criterias</div>
    <table cellspacing=0 cellpadding=0 width="100%" border=1 bordercolor="black">
      <tr>
      
      <td valign=top><table width="100%" cellspacing=2 cellpadding=2>
          <?php 
								$query="SELECT criteriaId FROM item_rating_criteria WHERE item_id=$sel_record";
								$result=mysql_query($query) or die(mysql_error());
								$cFlag=0;
								$cArray=array();
								while($row=mysql_fetch_array($result)){
									$cArray[$cFlag]=$row["criteriaId"];
									$cFlag++;
								}
								 
								$query="SELECT * FROM rating_criteria WHERE isActive='T'";
								$result=mysql_query($query) or die(mysql_error());
								$counter=0;
								if(mysql_num_rows($result) > 0){
									$counter=1;
									while($row=mysql_fetch_array($result)){
										$chk="";
										for($i=0;$i<sizeOf($cArray);$i++){
											if($row["criteriaId"]==$cArray[$i]){
												$chk="checked";
												break;
											}
										}
											
										if($counter==1){
										?>
          <tr>
            <td><?php echo stripslashes($row["criteriaName"]); ?></td>
            <td><input type=checkbox name="chk<?php echo $row['criteriaId']; ?>" value="<?php echo $row['criteriaId']; ?>" <?php echo "$chk"; ?>></td>
            <?
										}else{
										?>
            <td><?php echo stripslashes($row["criteriaName"]); ?></td>
            <td><input type=checkbox name="chk<?php echo $row['criteriaId']; ?>" value="<?php echo $row['criteriaId']; ?>" <?php echo "$chk"; ?>></td>
            <?
										}
									
										if($counter==2){
											?>
          </tr>
          <?php
											$counter=1;
										}else{
											$counter++;
										}
									}
								}
								if($counter==1){
									?>
          </tr>
          
          <?php
								}
							?>
        </table></td>
      </tr>
      
    </table></td>
  </tr>
  
  <tr>
    <td align=center colspan=2>&nbsp;</td>
  </tr>
</table>
<P align="center">Would you like to upload an image to be displayed with this item? <a href="admin_upload.php?item_id=<?php echo "$item_id"; ?>&<?php echo htmlspecialchars(SID); ?>">Yes</a>
<P align="center">Would you like to DELETE the image that is being displayed with this item? <a href="admin_del_item_image.php?item_id=<?php echo "$item_id"; ?>&<?php echo htmlspecialchars(SID); ?>">DELETE</a>
<P>
<div align="center">Back to <a href="admin_menu.php?<?php echo htmlspecialchars(SID); ?>">Menu</a><br />
</div>
<?php 	
        BodyFooter(); 
 }
?>
