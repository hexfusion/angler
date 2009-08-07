<?php
include ('body.php');
include ('functions.php');
include ('f_secure.php');
include ('config.php');

$item_id = $_GET['item_id'];

$build = "rating >= 0";

$sort = @$_REQUEST['sort'];

 if ($sort == "newest") {
 $sort = "date_added";
 $order = "DESC";
 } elseif ($sort == "oldest") {
 $sort = "date_added";
 $order = "ASC";
 } elseif ($sort == "highrating") {
 $sort = "rating";
 $order = "DESC";
 } elseif ($sort == "lowrating") {
 $sort = "rating";
 $order = "ASC";
 }  elseif ($sort == "useful") {
 $sort = "useful";
 $order = "DESC";
 }  elseif ($sort == "notuseful") {
 $sort = "notuseful";
 $order = "DESC";
 }  elseif ($sort == "1") {
 $build = "rating = 1";
 $sort = "date_added";
 $order = "DESC";
 }   elseif ($sort == "2") {
 $build = "rating = 2";
 $sort = "date_added";
 $order = "DESC";
 }   elseif ($sort == "3") {
 $build = "rating = 3";
 $sort = "date_added";
 $order = "DESC";
 }   elseif ($sort == "4") {
 $build = "rating = 4";
 $sort = "date_added";
 $order = "DESC";
 }   elseif ($sort == "5") {
 $build = "rating = 5";
 $sort = "date_added";
 $order = "DESC";
 } else {
 $sort = "date_added";
 $order = "DESC";
 }


$sel_com = "select *
from products
left join review
on (review.review_item_id = products.item_id)
where
review.review_item_id = $item_id AND approve = 'y'
AND $build
ORDER BY $sort $order
";

$AllReviews = array();
$sResult = mysql_query("$sel_com");
$sRows = mysql_numrows( $sResult );

while($row = mysql_fetch_array($sResult)) {
	array_push($AllReviews,"$row[item_id]|$row[description]|$row[comment]|$row[prod_group]|$row[id]|$row[rating]|$row[summary]|$row[review]|$row[source]|$row[location]|$row[review_item_id]|$row[visitorIP]|$row[date_added]|$row[useful]|$row[notuseful]|$row[approve]|");
} //while

//select the item_id to be displayed in case there are no reviews that have been written.
$sel_com2 = "select description, prod_group from products
where
item_id = $item_id
";

$sResult2 = mysql_query("$sel_com2");

while($row2 = mysql_fetch_array($sResult2)) {
$item_name2 = stripslashes($row2['description']);
$item_type2 = stripslashes($row2['prod_group']);
} //while


//Set the number of reviews to display per page in the /info/functions.php file
$lastReview = sizeof($AllReviews);
$pg = @$_REQUEST['pg'];
if($pg > 1) { $start = ($pg-1)*$NumReviews; $npg = $pg + 1; }
else {$start = 0;  $pg = 1; $npg = 2; }

if (isset($_GET['sort'])) {
$sort = $_GET['sort'];
}
$PHP_SELF = $_SERVER['PHP_SELF'];
$stop = $start + $NumReviews;
if($stop > $lastReview) { $stop = $lastReview; }
else { $nextPage = "<a href=\"$PHP_SELF?pg=$npg&item_id=$item_id&sort=$sort&order=$order\">Continue to Page $npg</a>"; }
if($pg > 1) { $ppg = $pg-1; $prevPage = "<a href=\"$PHP_SELF?pg=$ppg&item_id=$item_id&sort=$sort&order=$order\">Back to Page $ppg</a>"; }

////////////////////////////////////////////////////////////////////
//set variable names.
for ($i = 0; $i < 1; $i++) {
	@list($item_id2, $description, $comment, $prod_group, $id, $rating, $summary, $review, $source, $location, $review_item_id, $visitorIP, $date_added, $useful, $notuseful, $approve) = split("\|",$AllReviews[$i]);
}

//query to count number of people that have rated.
$sql_count = "SELECT COUNT(*) as total FROM review WHERE rating != '' and review.review_item_id = $item_id AND approve = 'y'";

			$sql_result_count = mysql_query($sql_count)
		or die(sprintf("Couldn't execute sql_count, %s: %s", db_errno(), db_error()));
		
    $rows = mysql_fetch_array($sql_result_count);
    $total = $rows["total"];

//find average of reviews
$sql_avg = "select avg(rating) as average from review WHERE  rating !='' AND review.review_item_id = $item_id AND approve = 'y'";
			$sql_result_avg = mysql_query($sql_avg)
		or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));
		
		while ( $row_avg = mysql_fetch_array($sql_result_avg) ) {
$average = $row_avg["average"];
}
//show the header when going outside the first include page.
if (isset($_GET['pg'])) {
BodyHeader("$sitename");
}
	?>

<hr noshade size=1 width=100%>
<a name="cust-reviews"><b class="h1">Customer reviews for <?php echo "$item_name2"; ?></b><br />
</a> 
<?php if ($average >= "1.0") { ?>
<font face=verdana,arial,helvetica size=-2><b>Avg. Customer Review</b> 
<?php } //if average
 $average_for = sprintf ("%01.1f", $average); 
if ($average >= "1.0") { echo "($average_for Stars):";  } ?> 
</font> 
      <?php if ($average <= ".9") {
echo "<span class=\"style2\"><b>Be the first to review this $item_type2</b></span>";
} elseif (($average >= "1.0") && ($average <= "1.4")) {
echo "<img src=$directory/images/stars-1-0.gif>"; 
} elseif (($average >= "1.5") && ($average <= "2.4")) {
echo "<img src=$directory/images/stars-2-0.gif>"; 
} elseif (($average >= "2.5") && ($average <= "3.4")) {
echo "<img src=$directory/images/stars-3-0.gif>"; 
} elseif (($average >= "3.5") && ($average <= "4.4")) {
echo "<img src=$directory/images/stars-4-0.gif>"; 
} elseif ($average >= "4.5") {
echo "<img src=$directory/images/stars-5-0.gif>"; 
}
$item_id = $_GET["item_id"];
?>
<br />
<font face=verdana,arial,helvetica size=-2><b>Number of Reviews:</b> <?php echo "$total"; ?>
<br />
<a href="/review/recommend.php?item_id=<?php echo "$item_id"; ?>">Email</a> a friend about this <?php echo "$item_type2"; ?>.</font><br />
<br />

<b class=small><a href="/review/review_form.php?item_id=<?php echo "$item_id"; ?>"><img src="/review/images/write.gif" width="35" height="35" border="0">Write an online review</a> and share your thoughts with other's.</b>
<p> 
<form name="form1" method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>?item_id=<?php echo "$item_id"; ?>">
  <select name="sort">
    <option value="newest">Newest First</option>
    <option value="oldest">Oldest First</option>
    <option value="highrating">Highest Rating First</option>
    <option value="lowrating">Lowest Rating First</option>
    <option value="useful">Most Helpful First</option>
    <option value="notuseful">Least Helpful First</option>
    <option value="5">5-Star Reviews Only</option>
    <option value="4">4-Star Reviews Only</option>
    <option value="3">3-Star Reviews Only</option>
    <option value="2">2-Star Reviews Only</option>
    <option value="1">1-Star Reviews Only</option>
  </select>
  <input name="item_id" type="hidden" value="<?php echo "$item_id"; ?>">
  <input type="submit" name="Submit" value="Go" class = submit>
</form></p>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0"> 
<?php 
  
  //////////////////////////////////////////////////////////////////////////////////////////
  echo "<BR><hr noshade size=1 width=100%><BR>";


$item_id = $_GET["item_id"];

//set variable names.
for ($i = $start; $i < $stop; $i++) {
	list($item_id, $description, $comment, $prod_group, $id, $rating, $summary, $review, $source, $location, $review_item_id, $visitorIP, $date_added, $useful, $notuseful, $approve) = split("\|",$AllReviews[$i]);

?>
<tr> 
  <td><font face=verdana,arial,helvetica size=-1> <?php echo "$useful of "; 
	$total_useful = ($useful + $notuseful); echo "$total_useful";
	?> people found the following review helpful:<br />
    <br />
    </font></td>
</tr>
<tr> 
  <td><font face=verdana,arial,helvetica size=-1> 
    <?php if ($rating == "1") {
echo "<img src=$directory/images/stars-1-0.gif>"; 
} elseif ($rating == "2") {
echo "<img src=$directory/images/stars-2-0.gif>"; 
} elseif ($rating == "3") {
echo "<img src=$directory/images/stars-3-0.gif>"; 
} elseif ($rating == "4") {
echo "<img src=$directory/images/stars-4-0.gif>"; 
} elseif ($rating == "5") {
echo "<img src=$directory/images/stars-5-0.gif>"; 
} 
 echo "$summary,"; ?>
    <?php

$tempdate = explode("-", "$date_added"); 
$date_added = mktime(0,0,0,$tempdate[1],$tempdate[2],$tempdate[0]); 
$newdate = date("M d, Y", "$date_added"); 
		 echo "$newdate";
		 
		 ?>
    <br />
    </font></td>
</tr>
<tr> 
  <td><font face=verdana,arial,helvetica size=-1>reviewer: <b> <?php echo "$source"; ?></b> 
    from <?php echo "$location"; ?><br />
    </font></td>
</tr>
<tr> 
  <td><?php echo "$review"; ?></td>
</tr>
<tr> 
  <td> <form action="/review/useful.php?item_id=<?php echo "$item_id"; ?>" method="post">
      <input name="id" type="hidden" value="<?php echo "$id"; ?>">
      <input name="item_id" type="hidden" value="<?php echo "$item_id"; ?>">
      <input name="description" type="hidden" value="<?php echo "$item_name2"; ?>">
      <br />
      <span class="small">Was this review helpful to you?&nbsp;&nbsp;</span> 
      <input type="image" value="1" name="useful" src="/review/images/yes.gif" border="0" width="34" height="16" align="absmiddle">
      <input type="image" value="1" name="notuseful" src="/review/images/no.gif" border="0" width="34" height="16" align="absmiddle">
      <br />
      <BR>
      <br />
    </form></td>
</tr>
<?php  }
echo "</table>";

 
  $item_id = $_GET["item_id"];

  
  echo "<table width=95%><tr>";
if(isset($prevPage)) { echo "<td align=left>$prevPage<br />&nbsp;</td>"; }
else { echo "<td>&nbsp;<br />&nbsp;</td>"; }
if(isset($nextPage)) { echo "<td align=right>$nextPage<br />&nbsp;</td>"; }
else { echo "<td>&nbsp;<br />&nbsp;</td>"; }
echo "</tr></table>";

//show the footer when going outside the first include page.
//if ($_GET['pg'] > 1) {
if (isset($_GET['pg'])) {
BodyFooter();
}
?>