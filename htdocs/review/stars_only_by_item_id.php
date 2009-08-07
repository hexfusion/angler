<?php  
session_start();

/*
this file allows you to display the stars for one item.  You must append the item_id to the end of the url for the item you wish to display.  For example, http://www.rental-script.com/review/stars_only_by_item_id.php?item_id=1
OR you can put the item_id directly in the script by changing a couple lines below.
1.  change  -  $item_id = "";   to    $item_id = "1 (or whatever number your item is)";
2.  Delete this line  -  $item_id = $_GET['item_id'];
*/

include_once ("body.php");
include_once ("functions.php");
include_once ("f_secure.php");
include_once ("config.php");

$item_id = "";

$item_id = htmlspecialchars($_GET[item_id], ENT_QUOTES);

if (!is_numeric($item_id)) {
echo "invalid item_id";
exit;
}

$query = "select * from review_items where item_id = '" . makeStringSafe($item_id) . "'"; 
$result = mysql_query($query) or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));


?>
<style type="text/css">
<!--
.style1 {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
}
.style2 {
	font-size: 10px;
	font-weight: bold;
}
-->
</style>
<table width="99%" border="0" align="center" cellpadding="3" cellspacing="0"> 

  <?php 
while ($row = mysql_fetch_array($result)) { 
	$item_name = stripslashes($row["item_name"]);
	//$item_id = $row["item_id"];
	$item_desc = stripslashes($row["item_desc"]);
	$item_type = stripslashes($row["item_type"]);

$sql_avg = "select avg(rating) as average from review, review_items WHERE  rating !='' AND rating != '0' AND review.review_item_id = '" . makeStringSafe($item_id) . "' AND approve = 'y' AND review_items.item_name != ''";

			$sql_result_avg = mysql_query($sql_avg)
		or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));

//Get total number of reviews for each item.
$result2 = mysql_query("select count(*) from review WHERE  rating !='' AND review.review_item_id = '" . makeStringSafe($item_id) . "' AND approve = 'y'");  
    $total2 = mysql_result($result2, 0, 0);  


while ($row2 = mysql_fetch_array($sql_result_avg)) { 
 extract($row2);
}

?> 
  <tr> 
    <td><?php echo "<a href=$directory/index2.php?item_id=$item_id>" . stripslashes($item_name) . "</a>"; ?>
<br />      
      <?php $average_for = sprintf ("%01.1f", $average); 
if ($total2 >= 2) {
$plural = "s";
} else {
$plural = "";
}
if ($average >= "1.0") { echo "<font face=verdana,arial,helvetica size=-2><b>Avg. Customer Review<br /> 
      </b> ($total2 Review$plural):";  } 

//$average_for Stars //replace $total2 Reviews with $average_for Stars if you'd like to show the average rating instead of the total number of reviews
?> 
      </font> 
       <?php   		 $display = ($average/5)*100; ?>
              <div class="rating_bar">
                <div style="width:<?php echo "$display"; ?>%"></div>
              </div> </td> 
  </tr> 
  <?php } ?> 
</table> 
