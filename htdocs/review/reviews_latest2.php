<?php
//this file will show a limited number of the latest reviews on a page.
include_once ("functions.php");
include_once ("f_secure.php");
include_once ("body.php");
include_once ("config.php");
//number of reviews to show on page
$show_num = 5;

//number of words to show of review
$show_words = 5;

//BodyHeader("Welcome to $sitename");

		$sql = "SELECT id, SUBSTRING_INDEX(review, ' ', $show_words) as review, source, review_item_id, username,  date_format(date_added, '%M %d, %Y') as date_added, rating FROM review WHERE approve = 'y' AND review != '' ORDER BY id DESC LIMIT $show_num";
		$result = mysql_query($sql)	or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));
		while($row = mysql_fetch_array($result)) {
			$review = stripslashes($row['review']);
			$username2 = stripslashes($row['username']);
			$id = stripslashes($row['id']);
			$review = stripslashes($row['review']);
			$source = stripslashes($row['source']);
			$review_item_id = stripslashes($row['review_item_id']);
			$rating = stripslashes($row['rating']);
			$date_added = stripslashes($row['date_added']);
			$sql2 = "SELECT item_name FROM review_items where item_id='$review_item_id'";
			$result2 = mysql_query($sql2)or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));
			while($row2 = mysql_fetch_array($result2)) {
				$item_name = stripslashes($row2['item_name']);
			} 
			//bbcode
			if ($use_bbcode == "yes") {
				include("bbcode.php");
 				$review = str_replace($bbcode, $htmlcode, $review);
				// $review = nl2br($review);//second pass
			}
		?>

<span class="style2"><a href="<?php echo "$directory"; ?>/review-item/<?php echo "$review_item_id"; ?>.php?<?php echo htmlspecialchars(SID); ?>" class="style4"><?php echo ucfirst($item_name); ?></a><?php		  
								$display = ($rating/5)*100; ?>
<div class="rating_bar">
  <div style="width:<?php echo "$display"; ?>%"></div>
</div>

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Reviewer: <a href="<?php echo "$directory"; ?>/reviewer/<?php echo "$username2"; ?>.php?<?php echo htmlspecialchars(SID); ?>"><?php echo "$source"; ?></a><br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo "$date_added"; ?><br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo "$review"; ?>...<br />
</span>

<br />
<?php }//end while
//BodyFooter();
?>
