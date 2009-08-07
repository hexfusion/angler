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
include("./editor/fckeditor.php") ;


$sel_record = clean($_GET['sel_record']);

		if (!$sel_record) {
header("Location: $url$directory/admin/admin_edit_item1.php?".sid);
			exit;
		}
		
			$sql = "SELECT * FROM review_items WHERE item_id='$sel_record'";
		
					$sql_result = mysql_query($sql)
		or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));

		if (!$sql_result) {  
   
			echo "<P>Couldn't get item!";

		} else {
			 while ($row = mysql_fetch_array($sql_result)) {
	$item_name = stripslashes($row["item_name"]);
	$category = stripslashes($row["category"]);
	$category_id = stripslashes($row["category_id"]);
	$item_type = stripslashes($row["item_type"]);
	$item_desc = stripslashes($row["item_desc"]);
$item_aff_url = stripslashes($row['item_aff_url']);
$item_aff_txt = stripslashes($row['item_aff_txt']);
$item_aff_code = stripslashes($row['item_aff_code']);

}			

BodyHeader("Edit Item!","","");
?>
<center><BR>
  <h1><font face="Verdana, Arial, Helvetica, sans-serif" size="3">You have selected 
    the following Item for Editing:</font></h1>
</center>
<FORM method="POST" action="admin_edit_item3.php?<?php echo htmlspecialchars(SID); ?>">
  <input type="hidden" name="sel_record" value="<?php echo "$sel_record"; ?>">
  

  <p>&nbsp;</p>
  <table width="650" border="0" cellspacing="0" cellpadding="8" align="center">
    <tr>
      <td width="231" valign=top><strong>Item Name:</strong></td>
      <td width="419" valign=top>
      <input name=item_name type=text value="<?php echo "$item_name"; ?>" size="50"> </td>
    </tr>
    <tr>
      <td valign=top><strong>Item Description:</strong></td>
      <td valign=top>
	  
	<script src="nicEditor/nicEdit.js" type="text/javascript"></script>
<script type="text/javascript">
bkLib.onDomLoaded(function() {
new nicEditor({fullPanel : true, iconsPath : '<?php echo $directory; ?>/admin/nicEditor/nicEditorIcons.gif'}).panelInstance('item_desc');
});
</script>
   <textarea id="item_desc" name="item_desc" style="width: 580px; height: 300px;"><?php echo "$item_desc"; ?></textarea>

	  
      </td>
    </tr>
    <tr>
      <td valign=top><strong>Item Type:<font size="1"><br />
              <font face="Arial, Helvetica, sans-serif">(<a href="../review_form.php?item_id=1">review_form.php</a> where it says &quot;How do rate this _ &quot; The word you insert here will be displayed at the end of that sentence.)</font></font></strong></td>
      <td valign=middle>
        <input type=text name=item_type value="<?php echo "$item_type"; ?>"> </td>
    </tr>
    <tr>
      <td valign=top><strong>Category:</strong></td>
      <td valign=top>
<select name="category">
<option value=""> -- Select a Category -- </option>


<?php
	function displayCat($catId, $dashes = 0){
		global $category, $category_id;
		$st = "";
		$query = 'select * from review_category_list where parent_id = ' .$catId. ' order by catorder';
		for($i=0; $i<$dashes; $i++){
			$st .= "--";
		}
		
		$dashes += 2;
		$result = mysql_query($query);
		while($row = mysql_fetch_array($result)){
			if($category_id == $row["cat_id_cloud"]){
				echo "<option value='" .$row["cat_id_cloud"]."' selected>" .$st . $row["category"] . "</option>";
			}else{
				echo "<option value='" .$row["cat_id_cloud"]."'>" .$st . $row["category"] . "</option>";
			}
			displayCat($row["cat_id_cloud"], $dashes);
		}
	}
	displayCat(-1);
?>


</select>

</td> 
    </tr>
    <tr>
      <td align=center colspan=2><strong><br />
    OPTIONAL: </strong>The fields below are to add a link to index2.php.</td>
    </tr>
    <tr>
      <td valign=top><strong>URL (include http://):</strong></td>
      <td valign=top><div align="center">
          <input name="item_aff_url" type="text" id="item_aff_url" value="<?php echo "$item_aff_url"; ?>" size="30">
      </div></td>
    </tr>
    <tr>
      <td valign=top><strong>Text for URL:</strong></td>
      <td valign=top><div align="center">
          <input name=" item_aff_txt" type="text" id=" item_aff_txt" value="<?php echo "$item_aff_txt"; ?>" size="30">
      </div></td>
    </tr>
    <tr>
      <td colspan="2" valign=top><strong><em>OR</em></strong> if you would like to insert html code that contains the url and text: </td>
    </tr>
    <tr>
      <td valign=top><strong>HTML Code :</strong></td>
      <td valign=top><div align="center">
          <textarea name="item_aff_code" cols="50" rows="9" id=" item_aff_code"><?php echo "$item_aff_code"; ?></textarea>
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
      <td align="center"><textarea cols="45" rows="3" name="review_supplement_item_<?php echo $row['id'] ?>" ><?php echo $data['value'] ?></textarea>&nbsp;&nbsp;<input name="show_review_supplement_item_<?php echo $row['id'] ?>" type="checkbox" value="<?php echo $row['id'] ?>" 
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
					<td valign=top>
						<table width="100%" cellspacing=2 cellpadding=2>
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
											<tr><td><?php echo stripslashes($row["criteriaName"]); ?></td><td><input type=checkbox name="chk<?php echo $row['criteriaId']; ?>" value="<?php echo $row['criteriaId']; ?>" <?php echo "$chk"; ?>></td>
										<?php
										}else{
										?>
											<td><?php echo stripslashes($row["criteriaName"]); ?></td><td><input type=checkbox name="chk<?php echo $row['criteriaId']; ?>" value="<?php echo $row['criteriaId']; ?>" <?php echo "$chk"; ?>></td>
										<?php
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
						</table>
					</td>
				</tr>	
			</table>
		</td>
	</tr>
    
	<tr>
      <td align=center colspan=2>
        <INPUT name="submit" type="submit" value="Update Item">
      </td>
    </tr>
  </table>
  <p>&nbsp;</p>
</FORM>
<div align="center">Back to <a href="admin_menu.php?<?php echo htmlspecialchars(SID); ?>">Menu</a><br /></div>
<?php
 BodyFooter(); 
}
?>