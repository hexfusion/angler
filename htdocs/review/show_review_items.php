<?php  
session_start();

include ('body.php');
include ('functions.php');
include ('f_secure.php');
include ('config.php');


//Display the header
BodyHeader("$sitename","","");
?>


<hr noshade size=1> 
<table width="99%" border="0" align="center" cellpadding="3" cellspacing="0"> 
  <tr> 
    <td width="88">&nbsp;</td> 
    <td width="267"><strong>Name</strong></td> 
    <td width="369"><strong>Description</strong></td> 
  </tr> 
  <?php 
$sql = "SELECT * FROM review_items, review where review_items.item_id=review.review_item_id AND review.rating != ''
		";

	$result = mysql_query($sql)
		or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));

while($row = mysql_fetch_array($result)) {
$review = stripslashes($row['review']);
$username2 = stripslashes($row['username']);
$id = stripslashes($row['id']);
$review = stripslashes($row['review']);
$source = stripslashes($row['source']);
$review_item_id = stripslashes($row['review_item_id']);
$rating = stripslashes($row['rating']);
$date_added = stripslashes($row['date_added']);

?> 
  <tr> 
    <td width="88"><a href="index2.php?item_id=<?php echo "$item_id"; ?>&<?php echo strip_tags(SID); ?>"><img src="<?php echo "$directory"; ?>/images/review.gif" hspace="1" vspace="1" border="0"></a></td> 
    <td><?php echo stripslashes($item_name); ?><br />      
      <?php $average_for = sprintf ("%01.1f", $average); 
if ($total2 >= 2) {
$plural = "s";
} else {
$plural = "";
}

?> 

    </td> 
    <td><?php echo stripslashes($item_desc); ?><BR><BR><BR></td> 
  </tr> 
  <?php } ?> 
</table> 

  <BR>
  <?php
 

BodyFooter();
?> 

