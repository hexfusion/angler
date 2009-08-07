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

$sql = "SELECT category FROM 
			review_category_list";

			$sql_result = mysql_query($sql)
		or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));

		if (!$sql_result) {  
   
			echo "<P>Couldn't get list!";

		} else {

		BodyHeader("$sitename: Select Display Order"); 
        ?>
<center><BR>
  <h2><font face="Verdana, Arial, Helvetica, sans-serif" size="3">Select a Category 
    from the <?php echo "$sitename"; ?> Database for Sorting </font></h2>
</center>


<FORM method="POST" action="admin_sort2.php?<?php echo htmlspecialchars(SID); ?>">

<table cellspacing=5 cellpadding=5>

<div align="center">
 <center>
 <table width="52%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
   <td width="100%">
   <p align="center"><strong>Category:</strong></td>
  </tr>
  <tr>
   <td width="100%">
   <p align="center">
<select name="sel_record">
<option value="-1"> -- Select a Category -- </option>
<?php
	function displayCat($catId, $dashes = 0){
		$st = "";
		$query = 'select * from review_category_list where parent_id = ' .$catId. ' order by catorder';
		for($i=0; $i<$dashes; $i++){
			$st .= "--";
		}
		
		$dashes += 2;
		$result = mysql_query($query);
		while($row = mysql_fetch_array($result)){
			echo "<option value='" .$row["cat_id_cloud"]."'>" .$st . $row["category"] . "</option>";
			displayCat($row["cat_id_cloud"], $dashes);
		}
	}
	displayCat(-1);
?>
</select>
</td>
   </tr>
<tr>
<td align=center colspan=2><BR>
<INPUT type="submit" value="Select Category">
</td>
</tr>
</table>


</FORM>

  <div align="center">Back to <a href="admin_menu.php?<?php echo htmlspecialchars(SID); ?>">Menu</a></div>
  <br />
</center>
<?php
 
        BodyFooter();
		exit;
}
?>

