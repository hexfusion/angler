<?
//if a session does not yet exist for this user, start one
session_start();

include ("../body.php");
include ("../functions.php");
include ("../config.php");

//Check to see that the username and Password entered have admin access.
$sqlaccess = "SELECT username, passtext
		FROM admin 
		WHERE username='" . $_SESSION['admin_username'] . "' 
		AND passtext = '" . $_SESSION['admin_passtext'] . "'
		";

	$resultaccess = mysql_query($sqlaccess)
		or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));

	$numaccess = mysql_numrows($resultaccess);


	if ($numaccess == 0) {
BodyHeader("Access Not Allowed!");
?>
<P align="center"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">To 
  access the Administration area you need to have approved access. The username 
  and Password you entered are not approved!<br>
  <a href="index.php">Try again</a></font> 
  <?
BodyFooter();  

exit;

}

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
    from the <? echo "$sitename"; ?> Database for Sorting </font></h2>
</center>


<FORM method="POST" action="admin_sort2.php?<?=SID?>">

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
<option value=""> -- Select a Category -- </option>



<?


while ($row = mysql_fetch_array($sql_result)) {

	$category = $row["category"];

?>
	<option value="<? echo "$category"; ?>"><? echo "$category"; ?></option>


<?
}
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

  <div align="center">Back to <a href="admin_menu.php?<?=SID?>">Menu</a></div>
  <br>
</center>
<?
 
        BodyFooter();
		exit;
}
?>

