<?php
//if a session does not yet exist for this user, start one
session_start();

//make sure user has been logged in.
$validU = "";

if (isset($_SESSION['valid_user']) && $_SESSION['valid_user'] != "")
    $validU = $_SESSION['valid_user'];

if ($validU == "") {
    // User not logged in, redirect to login page
    Header("Location: index.php");
}

include ("../body.php");
include ("../functions.php");
include ("../f_secure.php");
include ("../config.php");

$sel_record = $_POST['sel_record'];

if (!$sel_record) {
    header("Location: http://$url$directory/admin_del1.php?" . sid);
    exit;
}

$sql = "select *
from review_items
left join review
on (review.review_item_id = review_items.item_id)
where
id = '$sel_record'
ORDER BY source ASC
";

$sql_result = mysql_query($sql) or die(sprintf("Couldn't execute query, %s: %s",
    db_errno(), db_error()));


if (!$sql_result) {

    echo "<P>Couldn't get item!";

} else {

    while ($row = mysql_fetch_array($sql_result)) {

        $summary = stripslashes($row["summary"]);
        $review = html_entity_decode($row["review"]);
        $source = stripslashes($row["source"]);
        $date_added = $row['date_added'];
        $item_id = $row["review_item_id"];
    }

    BodyHeader("Delete Review!");
?>
<center>
  <h1><font face="Verdana, Arial, Helvetica, sans-serif" size="3">You have selected 
    the following review to DELETE:</font></h1>
</center>
<FORM method="POST" action="admin_del3.php?<?php echo htmlspecialchars(SID); ?>">
  <input type="hidden" name="sel_record" value=<?php echo $sel_record ?>>
  <input type=hidden name="item_id" value="<?php echo "$item_id"; ?>">
  <table cellspacing=5 cellpadding=5>
    <tr> 
      <td valign=top><strong>Summary:</strong></td>
      <td valign=top> <?php echo "$summary" ?> 
      </td>
    </tr>
    <tr> 
      <td valign=top><strong>Review:</strong></td>
      <td valign=top><?php echo $review ?></td>
      </td>
    </tr>
    <tr> 
      <td valign=top><strong>Source:</strong></td>
      <td valign=top><?php echo $source ?></td>
     </td>
    </tr>
    <tr> 
      <td valign=top><strong>Date Added:</strong></td>
      <td valign=top><?php echo "$date_added"; ?></td>
      <INPUT type="hidden" name="date_added" value=<?php echo "$date_added" ?> size=35 maxlength=75></td>
    </tr>
  	<tr>
		<td colspan=2>
		<table width="100%" cellspacing=1 cellpadding=1>
		<tr>
			<tr>
				<td colspan=5>Ratings Criteria<br />&nbsp;</td>
			</tr>
		</tr>
		<?php
    $query = "SELECT a.criteriaId,a.criteriaName FROM rating_criteria a,item_rating_criteria b WHERE a.criteriaId=b.criteriaId AND item_id=" .
        $item_id;
    $result = mysql_query($query) or die(mysql_error());
    $str = "";
    while ($row = mysql_fetch_array($result)) {
        $counter = $row["criteriaId"];
        $queryTemp = "SELECT ratingValue FROM rating_details WHERE review_id=$sel_record AND criteriaId=" .
            $row["criteriaId"];
        $resultTemp = mysql_query($queryTemp) or die(mysql_error());
        $val = 0;
        if (mysql_num_rows($resultTemp) == 1) {
            $rowTemp = mysql_fetch_array($resultTemp);
            $val = $rowTemp["ratingValue"];
        }
        $str .= "<tr><td colspan=6 bgcolor=gray><font color=white><b>" . $row["criteriaName"] .
            "</b></font></td></tr>";
        $str .= "<tr>";
        if ($val == "1") {
            $str .= "<td><input type=radio name=rad$counter value=1 checked><b>1</b></td>";
            $str .= "<td><input type=radio name=rad$counter value=2><b>2</b></td>";
            $str .= "<td><input type=radio name=rad$counter value=3><b>3</b></td>";
            $str .= "<td><input type=radio name=rad$counter value=4><b>4</b></td>";
            $str .= "<td><input type=radio name=rad$counter value=5><b>5</b></td>";
            $str .= "<td><input type=radio name=rad$counter value=0><b>N/A</b></td>";
        } elseif ($val == "2") {
            $str .= "<td><input type=radio name=rad$counter value=1><b>1</b></td>";
            $str .= "<td><input type=radio name=rad$counter value=2 checked><b>2</b></td>";
            $str .= "<td><input type=radio name=rad$counter value=3><b>3</b></td>";
            $str .= "<td><input type=radio name=rad$counter value=4><b>4</b></td>";
            $str .= "<td><input type=radio name=rad$counter value=5><b>5</b></td>";
            $str .= "<td>N/A<input type=radio name=rad$counter value=0><b>N/A</b></td>";
        } elseif ($val == "3") {
            $str .= "<td><input type=radio name=rad$counter value=1><b>1</b></td>";
            $str .= "<td><input type=radio name=rad$counter value=2><b>2</b></td>";
            $str .= "<td><input type=radio name=rad$counter value=3 checked><b>3</b></td>";
            $str .= "<td><input type=radio name=rad$counter value=4><b>4</b></td>";
            $str .= "<td><input type=radio name=rad$counter value=5><b>5</b></td>";
            $str .= "<td><input type=radio name=rad$counter value=0><b>N/A</b></td>";
        } elseif ($val == "4") {
            $str .= "<td><input type=radio name=rad$counter value=1><b>1</b></td>";
            $str .= "<td><input type=radio name=rad$counter value=2><b>2</b></td>";
            $str .= "<td><input type=radio name=rad$counter value=3><b>3</b></td>";
            $str .= "<td><input type=radio name=rad$counter value=4 checked><b>4</b></td>";
            $str .= "<td><input type=radio name=rad$counter value=5><b>5</b></td>";
            $str .= "<td><input type=radio name=rad$counter value=0><b>N/A</b></td>";
        } elseif ($val == "5") {
            $str .= "<td><input type=radio name=rad$counter value=1><b>1</b></td>";
            $str .= "<td><input type=radio name=rad$counter value=2><b>2</b></td>";
            $str .= "<td><input type=radio name=rad$counter value=3><b>3</b></td>";
            $str .= "<td><input type=radio name=rad$counter value=4><b>4</b></td>";
            $str .= "<td><input type=radio name=rad$counter value=5 checked><b>5</b></td>";
            $str .= "<td><input type=radio name=rad$counter value=0><b>N/A</b></td>";
        } else {
            $str .= "<td><input type=radio name=rad$counter value=1><b>1</b></td>";
            $str .= "<td><input type=radio name=rad$counter value=2><b>2</b></td>";
            $str .= "<td><input type=radio name=rad$counter value=3><b>3</b></td>";
            $str .= "<td><input type=radio name=rad$counter value=4><b>4</b></td>";
            $str .= "<td><input type=radio name=rad$counter value=5><b>5</b></td>";
            $str .= "<td><input type=radio name=rad$counter value=0 checked><b>N/A</b></td>";
        }
        $str .= "</tr>";
    }
    echo $str;
?>
		</table>
    	</td>
	</tr>	
    <tr> 
      <td align=center colspan=2>   
	  <input type=hidden name="review" value="<?php echo htmlspecialchars($review); ?>">
  <input type=hidden name="source" value="<?php echo htmlspecialchars($source); ?>">
  <input type=hidden name="summary" value="<?php echo htmlspecialchars($summary); ?>">
  <INPUT type="submit" value="Delete Review"> 
      </td>
    </tr>
  </table>
</FORM>
  <div align="center">Back to <a href="admin_menu.php?<?php echo
htmlspecialchars(SID); ?>">Menu</a></div>
<?php
    BodyFooter();
}
?>
