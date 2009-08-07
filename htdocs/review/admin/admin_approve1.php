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

$sel_com = "select *
from review_items
left join review
on (review.review_item_id = review_items.item_id)
where
approve = 'n'
ORDER BY source ASC
";

$sql_result = mysql_query($sel_com)
		or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));

		if (!$sql_result) {  
   
			echo "<P>Couldn't get list!";

		} else {

		BodyHeader("$sitename: Approve a Review"); 
        ?> 
<center> 
  <BR> 
  <h2><font face="Verdana, Arial, Helvetica, sans-serif" size="3">Select a Review  for Approval</font></h2> 
</center> 
<FORM method="POST" action="admin_approve2.php?<?php echo htmlspecialchars(SID); ?>"> 
  <table cellspacing=5 cellpadding=5> 
  <table width="76%" border="0" align="center" cellpadding="0" cellspacing="0"> 
    <tr> 
      <td width="100%"> <p align="center"><strong>Item Type : Item Name : Summary : Source : Date Added</strong></td> 
    </tr> 
    <tr> 
      <td width="100%"> <p align="center"> 
          <select name="sel_record"> 
            <option value=""> -- Select a Review -- </option> 
            <?php
while ($row = mysql_fetch_array($sql_result)) {

	$summary = stripslashes($row["summary"]);
	$source = stripslashes($row["source"]);
	$date_added = $row["date_added"];
	$id = $row["id"];
	$item_name = stripslashes($row["item_name"]);
	$item_type = stripslashes($row["item_type"]);
	
echo "
	<option value=\"$id\">$item_type : $item_name : $summary : $source : $date_added</option>
";
}
?> 
          </select> </td> 
    </tr> 
    <tr> 
      <td align=center colspan=2><BR> 
        <INPUT type="submit" value="Select Review"> </td> 
    </tr> 
  </table> 
</FORM> 
<div align="center">Back to <a href="admin_menu.php?<?php echo htmlspecialchars(SID); ?>">Menu</a><br /></div>
<?php  
BodyFooter();
exit;
}
?> 