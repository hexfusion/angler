<?
//this file will show a limited number of the latest reviews on a page.
/*
include ('functions.php');
include ('f_secure.php');
include ('body.php');
include ('config.php');
*/
//number of reviews to show on page
$show_num = 5;

//number of words to show of review
$show_words = 6;

//BodyHeader("Welcome to $sitename");
?>
<style type="text/css">
<!--
.style2 {font-size: 10px}
.style3 {
	font-size: 12px;
	font-weight: bold;
}
.style4 {font-size: 12px}
-->
</style>
<table width="205" border="1" align="left" cellpadding="5" cellspacing="0">
  <tr><td><p align="center"><BR>
        <span class="style3">
    Latest <?php echo "$show_num"; ?> Reviews</span></p>
      <p><span class="style2"> 
      <?


$sql = "SELECT id, SUBSTRING_INDEX(review, ' ', $show_words) as review, source, review_item_id, date_format(date_added, '%m-%d-%Y') as date_added, rating FROM review ORDER BY id DESC LIMIT $show_num
		";

	$result = mysql_query($sql)
		or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));

while($row = mysql_fetch_array($result)) {
$review = stripslashes($row['review']);
$id = stripslashes($row['id']);
$review = stripslashes($row['review']);
$source = stripslashes($row['source']);
$review_item_id = stripslashes($row['review_item_id']);
$rating = stripslashes($row['rating']);
$date_added = stripslashes($row['date_added']);

$sql2 = "SELECT item_name FROM review_items where item_id=$review_item_id
		";

	$result2 = mysql_query($sql2)
		or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));

while($row2 = mysql_fetch_array($result2)) {
$item_name = stripslashes($row2['item_name']);
} 
?>
  
    <a href="<?php echo "$directory"; ?>/index2.php?item_id=<?php echo "$review_item_id"; ?>"><?php echo "$item_name"; ?></a> - <?php echo "$review"; ?>...<BR>
      Reviewed by: <a href="<?php echo "$directory"; ?>/reviewer_reviews.php?source=<?php echo "$source"; ?>"><?php echo "$source"; ?></a> on <?php echo "$date_added"; ?> | Rating:
      <?
      		 $display = ($rating/5)*100; ?>
              <div class="rating_bar">
                <div style="width:<?php echo "$display"; ?>%"></div>
              </div><?php
echo "<p></p>";
}//end while
?> 
          </span></p></td>
  </tr>
</table>

<p>&nbsp;</p>
<?
//BodyFooter();
?>