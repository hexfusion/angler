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

$sel_record = $_POST['sel_record'];

		if (!$sel_record) {
header("Location: http://$url$directory/admin/admin_edit_item1.php?".sid);
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
	$item_type = stripslashes($row["item_type"]);
	$item_desc = stripslashes($row["item_desc"]);
$item_aff_url = stripslashes($row['item_aff_url']);
$item_aff_txt = stripslashes($row['item_aff_txt']);
$item_aff_code = stripslashes($row['item_aff_code']);

}			

BodyHeader("Edit Item!");
?>
<center><BR>
  <h1><font face="Verdana, Arial, Helvetica, sans-serif" size="3">You have selected 
    the following Item for Editing:</font></h1>
</center>
<FORM method="POST" action="admin_edit_item3.php?<?php echo htmlspecialchars(SID); ?>">
  <input type="hidden" name="sel_record" value="<?php echo $_POST['sel_record']; ?>">
  

  <p>&nbsp;</p>
  <table width="464" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
      <td width="234" valign=top><strong>Item Name:</strong></td>
      <td width="230" valign=top>
        <input type=text name=item_name value="<?php echo "$item_name"; ?>"> </td>
    </tr>
    <tr>
      <td valign=top><strong>Item Description:</strong></td>
      <td valign=top><textarea name="item_desc" cols="50" rows="10"><?php echo "$item_desc"; ?></textarea> </td>
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
<?php
//display all categories with the correct one selected.
$sql = "SELECT DISTINCT category FROM review_category_list WHERE category != '' ORDER BY category";

			$sql_result = mysql_query($sql)
		or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));
?>
<select name="category">
<option value=""> -- Select a Category -- </option>
<?php
while ($row = mysql_fetch_array($sql_result)) {
	$category2 = $row["category"];
?>
  <OPTION VALUE="<?php echo "$category2"; ?>"<?php if ($row['category'] == "$category") { echo " SELECTED"; } ?>><?php echo "$category2"; ?></OPTION>
<?php
}
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
          <textarea name="item_aff_code" id=" item_aff_code"><?php echo "$item_aff_code"; ?></textarea>
      </div></td>
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