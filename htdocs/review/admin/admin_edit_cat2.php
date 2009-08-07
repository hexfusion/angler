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



$sel_record = clean($_GET['sel_record']);

	if (!$sel_record) {
header("Location: $url$directory/admin/admin_edit_cat1.php?".sid);
			exit;
		}
		
			$sql = "SELECT * FROM review_category_list WHERE cat_id_cloud = " .$sel_record;

					$sql_result = mysql_query($sql)
		or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));


		if (!$sql_result) {  
   
			echo "<P>Couldn't get item!";

		} else {
			 while ($row = mysql_fetch_array($sql_result)) {
	$category = stripslashes($row["category"]);
	$parent=$row['parent'];
	$parent_id=$row['parent_id'];
}			

BodyHeader("Edit Category!");
?>
<center><BR>
  <h1><font face="Verdana, Arial, Helvetica, sans-serif" size="3">You have selected
    the following category for Editing:</font></h1>
</center>
<script language="JavaScript">
	function handleSubmit(){
		var frm=document.editFrm;
		if(frm.sel_record.value==frm.sub_recordOf.value){
			alert("Can not be parent of itself");
			return false; 
		}else{
			return true;
		}
	}
</script>
<FORM name="editFrm" method="POST" action="admin_edit_cat3.php?<?php echo htmlspecialchars(SID); ?>" onsubmit="javascript: return handleSubmit();">
  <input type="hidden" name="sel_record" value="<?php echo $sel_record ?>">

  <table align="center" cellpadding=5 cellspacing=5>
    <tr> 
      <td valign=top><strong>Category:</strong></td>
      <td valign=top><input type=text name=category value="<?php echo stripslashes(htmlentities($category)); ?>">
      </td>
    </tr>
    <tr>
    <td>Subcategory Of:</td>
    <td>
 <select name="sub_recordOf">
<option value="-1"> -- Parent Category-- </option>

<?php
	function displayCat($catId, $pi, $dashes = 0){
		$st = "";
		$query = 'select * from review_category_list where parent_id = ' .$catId. ' order by catorder';
		for($i=0; $i<$dashes; $i++){
			$st .= "--";
		}
		
		$dashes += 2;
		$result = mysql_query($query);
		while($row = mysql_fetch_array($result)){
			if($pi==$row["cat_id_cloud"]){
				echo "<option value='" .$row["cat_id_cloud"]."' selected>" .$st . $row["category"] . "</option>";
			}else{
				echo "<option value='" .$row["cat_id_cloud"]."'>" .$st . $row["category"] . "</option>";
			}
			displayCat($row["cat_id_cloud"], $pi,$dashes);
		}
	}
	displayCat(-1, $parent_id);
?>


</select>	  
    </td>
    </tr>
    <tr>    </tr>
    <tr> 
      <td align=center colspan=2> <INPUT type="submit" value="Approve Edit"> 
      </td>
    </tr>
  </table>

</FORM>
<div align="center">Back to <a href="admin_menu.php?<?php echo htmlspecialchars(SID); ?>">Menu</a><br /></div>
<?php
BodyFooter(); 
}
?>
