<?php

// include the config files
include ('body.php');
include ('functions.php');
include ('f_secure.php');
include ('config.php');

$category = htmlspecialchars(urldecode(@$_GET['category']));


		// Print the heading
		//Display the header
BodyHeader("Yahoo Style Layout - $sitename","","");
		echo " <h2>Review Items</h2> ";
		
		// get a count of how many links are in the database
		$link_count = "SELECT count(*) as count FROM products WHERE category = '$category'";
		$result = mysql_query($link_count);
		$row=mysql_fetch_array($result);
		$num_cat=$row['count'];
		//RBM
		//$rbm=$parent=$_GET['category'];
		$rbm=$parent=$category;
		while($parent!=''){
			$res=mysql_query('Select parent from review_category_list where category="'.$parent.'"') or die(mysql_error());
			$parent=mysql_result($res,0,0);
			$rbm='<a href="review_categories_yahoo_cats2.php?category='.urlencode($parent).'">'.$parent.'</a> / '.$rbm;
		}
		$rbm='<a href="review_categories_yahoo_cats2.php">Start</a> '.$rbm;
		echo $rbm.'<br /><br />';
		// if there aren't any links, then lets generate a user-friendly notice that there aren't any
		if ($num_cat <1){
			echo "<b>There are no results for your search</b>.<br /><br />";
		}
		//SUBCATEGORIES
		
		//$res=mysql_query('Select * from review_category_list where parent="'.$_GET['category'].'"');
		$res=mysql_query('Select * from review_category_list where parent="'.urldecode(@$_GET['category']).'"');
		while($row=mysql_fetch_array($res)){
			echo '<a href="review_categories_yahoo_cats2.php?category='.urlencode($row['category']).'"><img src="images/folder_open.gif" border="0" hspace="5">'.$row['category'].'</a><br /><br />';
		}
		echo "<table width=100%>";
		// if there are links, let's display them!

		// first step is to set up the header for each category
		$query = "SELECT DISTINCT category FROM products WHERE category = '$category' ORDER BY category";
		$result = mysql_query($query); 
		
		while ( $row = mysql_fetch_array($result))
			{
			$category = $row["category"];
						
			if ($category != "") {
			echo "<BR><B>$category</B><BR><BR>";
			}
$category = addslashes($category);			
			// then, lets get a new query and results set so we can display the links from each category
			$query2 = "SELECT * FROM products 
WHERE category='$category' 
AND category != '' 
ORDER BY sort_order";
			$result2 = mysql_query($query2); 
				$num_links = mysql_numrows($result2);

$i=1; 
while ( $row = mysql_fetch_array($result2))
				{
	$description = stripslashes($row["description"]);
	$item_id = $row["item_id"];
	$comment = stripslashes($row["comment"]);
	 
			//	 for ($i=1; $i<=$num_links; $i++) {		
			
$sql_count = "SELECT COUNT(*) as total FROM review WHERE rating != '' and review.review_item_id = '$item_id' AND approve = 'y'";

			$sql_result_count = mysql_query($sql_count)
		or die(sprintf("Couldn't execute sql_count, %s: %s", db_errno(), db_error()));
		
    $rows = mysql_fetch_array($sql_result_count);
    $total = $rows["total"];

//find average of reviews
$sql_avg = "select avg(rating) as average from review WHERE  rating !='' AND review.review_item_id = '$item_id' AND approve = 'y'";
			$sql_result_avg = mysql_query($sql_avg)
		or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));
		
		while ( $row_avg = mysql_fetch_array($sql_result_avg) ) {
$average = $row_avg["average"];
}


			
				
if ($i % 2) { echo "<tr><td width=50%><a href=index2.php?item_id=$item_id>$description</a>";


 if ($average >= "1.0") { ?>
      <span class="ad_title"><br />
<b>Avg. Customer Review</b>
      <?php } //if average
$average_for = sprintf ("%01.1f", $average); 
if ($average <= "5.0") { echo "($average_for Stars):";  } ?>
      </font>
      <?php   if (($average <= ".49")) {
echo "<img src=/review/images/stars-0-0.gif>"; 
 } elseif (($average >= ".50")&& ($average <= ".59")) {
echo "<img src=/review/images/stars-0-5-.gif>";
} elseif (($average >= ".60") && ($average <= "1.44")) {
echo "<img src=/review/images/stars-1-0-.gif>"; 
} elseif (($average >= "1.45") && ($average <= "1.59")) {
echo "<img src=/review/images/stars-1-5-.gif>";
} elseif (($average >= "1.60") && ($average <= "2.44")) {
echo "<img src=/review/images/stars-2-0-.gif>"; 
} elseif (($average >= "2.45") && ($average <= "2.59")) {
echo "<img src=/review/images/stars-2-5-.gif>"; 
} elseif (($average >= "2.60") && ($average <= "3.44")) {
echo "<img src=/review/images/stars-3-0-.gif>"; 
} elseif (($average >= "3.45") && ($average <= "3.59")) {
echo "<img src=/review/images/stars-3-5-.gif>"; 
} elseif (($average >= "3.60") && ($average <= "4.44")) {
echo "<img src=/review/images/stars-4-0-.gif>"; 
} elseif (($average >= "4.45") && ($average <= "4.59")) {
echo "<img src=/review/images/stars-4-5-.gif>"; 
} elseif ($average >= "4.60") {
echo "<img src=/review/images/stars-5-0-.gif>"; 
}




echo "out of $total reviews.</td>"; }
else { echo "<td width=50%><a href=index2.php?item_id=$item_id>$description</a> - $comment";


?>
      <span class="ad_title"><br />
<b>Avg. Customer Review</b>
      <?php 
$average_for = sprintf ("%01.1f", $average); 
if ($average <= "5.0") { echo "($average_for Stars):";  } ?>
      </font>
      <?php   if (($average <= ".49")) {
echo "<img src=/review/images/stars-0-0.gif>"; 
 } elseif (($average >= ".50")&& ($average <= ".59")) {
echo "<img src=/review/images/stars-0-5-.gif>";
} elseif (($average >= ".60") && ($average <= "1.44")) {
echo "<img src=/review/images/stars-1-0-.gif>"; 
} elseif (($average >= "1.45") && ($average <= "1.59")) {
echo "<img src=/review/images/stars-1-5-.gif>";
} elseif (($average >= "1.60") && ($average <= "2.44")) {
echo "<img src=/review/images/stars-2-0-.gif>"; 
} elseif (($average >= "2.45") && ($average <= "2.59")) {
echo "<img src=/review/images/stars-2-5-.gif>"; 
} elseif (($average >= "2.60") && ($average <= "3.44")) {
echo "<img src=/review/images/stars-3-0-.gif>"; 
} elseif (($average >= "3.45") && ($average <= "3.59")) {
echo "<img src=/review/images/stars-3-5-.gif>"; 
} elseif (($average >= "3.60") && ($average <= "4.44")) {
echo "<img src=/review/images/stars-4-0-.gif>"; 
} elseif (($average >= "4.45") && ($average <= "4.59")) {
echo "<img src=/review/images/stars-4-5-.gif>"; 
} elseif ($average >= "4.60") {
echo "<img src=/review/images/stars-5-0-.gif>"; 
}




echo "out of $total reviews.</td>"; }

$i++;
			}
echo "</tr></table><br /><br />
<br />

<br />
<center>Click <a href=\"javascript:history.back(1)\">here</a> to go back</center><br />
<br />
";
			
		}
BodyFooter();
?>
