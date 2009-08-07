<?php
session_start();
$sort = @$_REQUEST['sort'];

include ('body.php');
include ('functions.php');
include ('f_secure.php');
include ('config.php');

$item_id = $_GET['item_id']; $build = "review.rating >= 0";

$sort = @$_REQUEST['sort'];

 if ($sort == "newest") {
 $sort = "review.id";
 $order = "DESC";
 } elseif ($sort == "oldest") {
 $sort = "review.id";
 $order = "ASC";
 } elseif ($sort == "highrating") {
 $sort = "review.rating";
 $order = "DESC";
 } elseif ($sort == "lowrating") {
 $sort = "review.rating";
 $order = "ASC";
 }  elseif ($sort == "useful") {
 $sort = "review.useful";
 $order = "DESC";
 }  elseif ($sort == "notuseful") {
 $sort = "review.notuseful";
 $order = "DESC";
 }  elseif ($sort == "1") {
 $build = "rating = 1";
 $sort = "review.date_added";
 $order = "DESC";
 }   elseif ($sort == "2") {
 $build = "rating = 2";
 $sort = "review.date_added";
 $order = "DESC";
 }   elseif ($sort == "3") {
 $build = "rating = 3";
 $sort = "review.date_added";
 $order = "DESC";
 }   elseif ($sort == "4") {
 $build = "rating = 4";
 $sort = "review.date_added";
 $order = "DESC";
 }   elseif ($sort == "5") {
 $build = "review.rating = 5";
 $sort = "review.date_added";
 $order = "DESC";
 } else {
 $sort = "review.id";
 $order = "DESC";
 }$sel_com = "select *
from products
left join review
on (review.review_item_id = products.item_id)
where
review.review_item_id=$item_id AND approve = 'y'
AND $build
ORDER BY $sort $order
";

$AllReviews = array();
$sResult = mysql_query("$sel_com");
$sRows = mysql_numrows( $sResult );

while($row = mysql_fetch_array($sResult)) {
	array_push($AllReviews,"$row[item_id]|$row[description]|$row[comment]|$row[prod_group]|$row[id]|$row[rating]|$row[summary]|$row[review]|$row[source]|$row[location]|$row[review_item_id]|$row[visitorIP]|$row[date_added]|$row[useful]|$row[notuseful]|$row[approve]|$row[sig_show]|$row[username]|");
} //while

//select the item_id to be displayed in case there are no reviews that have been written.
$sel_com2 = "select description, prod_group from products
where
item_id = $item_id
";

$sResult2 = mysql_query("$sel_com2")
		or die(sprintf("Couldn't execute sql_count, %s: %s", db_errno(), db_error()));


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
$allPages = ceil($lastReview/$NumReviews);
$stop = $start + $NumReviews;
if($stop > $lastReview) { $stop = $lastReview; }

 $nextPage = "";
 for($j=1;$j<=$allPages;$j++) {
	if($pg == $j) { $nextPage .= " | $j ";}
	else {  $nextPage .= " | <a href=\"$PHP_SELF?pg=$j&item_id=$item_id&sort=$sort&order=$order\">$j</a> ";  }

 } //for
if ($lastReview != "0") { $nextPage .= " |"; }

////////////////////////////////////////////////////////////////////
//set variable names.
for ($i = 0; $i < 1; $i++) {
	@list($item_id2, $description, $comment, $prod_group, $id, $rating, $summary, $review, $source, $location, $review_item_id, $visitorIP, $date_added, $useful, $notuseful, $approve, $sig_show, $username) = split("\|",$AllReviews[$i]);
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
<link href="review.css" rel="stylesheet" type="text/css">
<hr noshade size=1 width=100%>
<b class="style3">Customer reviews for <?php echo "$item_name2"; ?></b><br />

<?php if ($average >= "1.0") { ?>
<font face=verdana,arial,helvetica size=-2><b>Avg. Customer Review</b> 
<?php } //if average
$average_for = sprintf ("%01.1f", $average); 
if ($average <= "5.0") { echo "($average_for Stars):";  } ?>
</font> 
      <?php   if (($average <= ".49")) {
echo "<img src=images/stars-0-0.gif>"; 
 } elseif (($average >= ".50")&& ($average <= ".59")) {
echo "<img src=images/stars-0-5-.gif>";
} elseif (($average >= ".60") && ($average <= "1.44")) {
echo "<img src=images/stars-1-0-.gif>"; 
} elseif (($average >= "1.45") && ($average <= "1.59")) {
echo "<img src=images/stars-1-5-.gif>";
} elseif (($average >= "1.60") && ($average <= "2.44")) {
echo "<img src=images/stars-2-0-.gif>"; 
} elseif (($average >= "2.45") && ($average <= "2.59")) {
echo "<img src=images/stars-2-5-.gif>"; 
} elseif (($average >= "2.60") && ($average <= "3.44")) {
echo "<img src=images/stars-3-0-.gif>"; 
} elseif (($average >= "3.45") && ($average <= "3.59")) {
echo "<img src=images/stars-3-5-.gif>"; 
} elseif (($average >= "3.60") && ($average <= "4.44")) {
echo "<img src=images/stars-4-0-.gif>"; 
} elseif (($average >= "4.45") && ($average <= "4.59")) {
echo "<img src=images/stars-4-5-.gif>"; 
} elseif ($average >= "4.60") {
echo "<img src=images/stars-5-0-.gif>"; 
}

$item_id = $_GET["item_id"];
$back = $_SERVER['PHP_SELF'];
?>
<br />
<font face=verdana,arial,helvetica size=-2><b>Number of Reviews:</b> <?php echo "$total"; ?>
<br />
<a href="recommend.php?item_id=<?php echo "$item_id"; ?>&<?php echo htmlspecialchars(SID); ?>">Email</a> a friend about this <?php echo "$item_type2"; ?>.</font><br />
<span class="small"><a href="phprint.php">Print</a> this page</span><br />
<br />

<b class=small><a href="review_form.php?item_id=<?php
echo "$item_id"; ?>&<?php echo htmlspecialchars(SID); ?>"><img src="images/write.gif" width="35" height="35" border="0">Write an online review</a> and share your thoughts.</b>
<p> 
<form name="form1" method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>?<?php echo htmlspecialchars(SID); ?>&item_id=<?php echo "$item_id"; ?>">
  <select name="sort">
    <option value="newest" <?php if ($sort == "") { echo "selected"; } ?>>Sort Display</option>
    <option value="newest" <?php if ($sort == "newest") { echo "selected"; } ?>>Newest First</option>
    <option value="oldest" <?php if ($sort == "oldest") { echo "selected"; } ?>>Oldest First</option>
    <option value="highrating" <?php if ($sort == "highrating") { echo "selected"; } ?>>Highest Rating First</option>
    <option value="lowrating" <?php if ($sort == "lowrating") { echo "selected"; } ?>>Lowest Rating First</option>
    <option value="useful" <?php if ($sort == "useful") { echo "selected"; } ?>>Most Helpful First</option>
    <option value="notuseful" <?php if ($sort == "notuseful") { echo "selected"; } ?>>Least Helpful First</option>
    <option value="5" <?php if ($sort == "5") { echo "selected"; } ?>>5-Star Reviews Only</option>
    <option value="4" <?php if ($sort == "4") { echo "selected"; } ?>>4-Star Reviews Only</option>
    <option value="3" <?php if ($sort == "3") { echo "selected"; } ?>>3-Star Reviews Only</option>
    <option value="2" <?php if ($sort == "2") { echo "selected"; } ?>>2-Star Reviews Only</option>
    <option value="1" <?php if ($sort == "1") { echo "selected"; } ?>>1-Star Reviews Only</option>
  </select>
  <input name="item_id" type="hidden" value="<?php echo "$item_id"; ?>">
  <input type="submit" name="Submit" value="Go" class = submit>
</form></p>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0"> 
<BR><hr noshade size=1 width=100%>
<?php //display page navigation on top  
$parsed = parse_url($_SERVER['PHP_SELF']);
$lastpage = $parsed['path'];

//don't display the pagination if there are no reviews
if ($lastReview != "0") { 
?>
<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <th width="33%" scope="col"><div align="left"><font color="#0033CC">Showing
              <?php $begin = $start+1; echo "$begin"; ?>
        to <?php echo "$stop"; ?> of <?php echo "$lastReview"; ?> reviews </font></div></th>
    <th width="33%" scope="col"><?php echo "$nextPage"; ?>
        <?php //only show view all if there is more than one page
if($lastReview > $NumReviews) { 
?>
      - <a href="viewall.php?lastpage=<?php echo "$lastpage"; ?>&item_id=<?php echo "$item_id"; ?>&sort=<?php echo "$sort"; ?>&order=<?php echo "$order"; ?>">View All</a>
      <?php } //end viewall check ?>
    </th>
    <th width="34%" scope="col"><?php 


$stop = $start + $NumReviews;
if($stop > $lastReview) { $stop = $lastReview; }
else { $next = "<a href=\"$PHP_SELF?pg=$npg&item_id=$item_id&sort=$sort&order=$order\">Next</a>"; }
if($pg > 1) { $ppg = $pg-1; $prev = "<a href=\"$PHP_SELF?pg=$ppg&item_id=$item_id&sort=$sort&order=$order\">Previous</a>"; }


if(isset($prev)) { echo "$prev &nbsp;&nbsp;"; }
if(isset($next)) { echo "&nbsp;&nbsp; $next"; }
?></th>
  </tr>
</table>
<hr noshade size=1 width=100%>
<br /><!-- startprint -->
<?php } //end pagination check 
//end top page navigation 

$item_id = $_GET["item_id"];

//set variable names.
for ($i = $start; $i < $stop; $i++) {
	list($item_id, $description, $comment, $prod_group, $id, $rating, $summary, $review, $source, $location, $review_item_id, $visitorIP, $date_added, $useful, $notuseful, $approve, $sig_show, $username) = split("\|",$AllReviews[$i]);
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
     <?php if ($rating == ".5") {
echo "<img src=images/stars-0-5.gif>";
} elseif ($rating == "1") {
echo "<img src=images/stars-1-0.gif>"; 
} elseif ($rating == "1.5") {
echo "<img src=images/stars-1-5.gif>";
} elseif ($rating == "2") {
echo "<img src=images/stars-2-0.gif>"; 
} elseif ($rating == "2.5") {
echo "<img src=images/stars-2-5.gif>";
} elseif ($rating == "3") {
echo "<img src=images/stars-3-0.gif>"; 
} elseif ($rating == "3.5") {
echo "<img src=images/stars-3-5.gif>";
} elseif ($rating == "4") {
echo "<img src=images/stars-4-0.gif>"; 
} elseif ($rating == "4.5") {
echo "<img src=images/stars-4-5.gif>";
} elseif ($rating == "5") {
echo "<img src=images/stars-5-0.gif>"; 
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
  <td><font face=verdana,arial,helvetica size=-1>reviewer: <b> <a href="reviewer_about.php?<?php echo htmlspecialchars(SID); ?>&name=<?php echo "$source"; ?>"><?php echo ucfirst($source); ?></a></b> 
    from <?php echo "$location"; ?> - <span class="style1"><a href="reviewer_about.php?username=<?php echo "$username"; ?>">See all reviews by <?php echo ucfirst($source); ?></a></span><br />
  </font></td>
</tr>
<tr> 

<?
//print_r($_SESSION);
//show users signature?
if($sig_show == "y") { 
$sql = "SELECT sig FROM 
			userdb
			WHERE 
			username = '$username'";

echo "$sql is sql";
		
			$sql_result = mysql_query($sql)
		or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));
		
	while ($row = mysql_fetch_array($sql_result)) { 
$sig = stripslashes($row["sig"]); 
}
//$_SESSION['sig'] = "$sig";
}

if ($use_bbcode == "yes") {
include("bbcode.php");

  $sig = str_replace($bbcode, $htmlcode, $sig);
  $sig = nl2br($sig);//second pass

  $review = str_replace($bbcode, $htmlcode, $review);
 $review = nl2br($review);//second pass
}
?>


  <td><?php echo "$review";  if($sig_show == "y") { echo "<BR><BR>__________________<BR>$sig"; } ?></td>
</tr>
<tr> 
  <td> <form action="useful.php?item_id=<?php echo "$item_id"; ?>" method="post">
      <input name="id" type="hidden" value="<?php echo "$id"; ?>">
      <input name="item_id" type="hidden" value="<?php echo "$item_id"; ?>">
      <input name="description" type="hidden" value="<?php echo "$item_name2"; ?>">
      <br />
      <span class="small">Was this review helpful to you?&nbsp;&nbsp;</span> 
      <input type="image" value="1" name="useful" src="images/yes.gif" border="0" width="34" height="16" align="absmiddle">
      <input type="image" value="1" name="notuseful" src="images/no.gif" border="0" width="34" height="16" align="absmiddle">
    (<A href="report.php?id=<?php echo "$id"; ?>&item_id=<?php echo "$item_id"; ?>" class="style1">Report this</A>) <br />
      <BR>
      <br />
    </form></td>
</tr>
<?php  }
echo "</table>";
?>
<!-- stopprint -->
<?
 
  $item_id = $_GET["item_id"];
//display page navigation on bottom  
//$parsed = parse_url($_SERVER['PHP_SELF']);
//$lastpage = $parsed['path'];

//don't display the pagination if there are no reviews
if ($lastReview != "0") { 
?>
<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <th width="33%" scope="col"><div align="left"><font color="#0033CC">Showing 
        <?php $begin = $start+1; echo "$begin"; ?>
        to <?php echo "$stop"; ?> of <?php echo "$lastReview"; ?> reviews </font></div></th>
  

  <th width="33%" scope="col"><?php echo "$nextPage"; ?> 
<?php //only show view all if there is more than one page
if($lastReview > $NumReviews) { ?>

- <a href="viewall.php?lastpage=<?php echo "$lastpage"; ?>&item_id=<?php echo "$item_id"; ?>&sort=<?php echo "$sort"; ?>&order=<?php echo "$order"; ?>">View All</a><?php } //end viewall check ?> </th>


<th width="34%" scope="col">
<?php 
if(isset($prev)) { echo "$prev &nbsp;&nbsp;"; }
if(isset($next)) { echo "&nbsp;&nbsp; $next"; }
?></th>
  </tr>
</table>

<?php } //end pagination check 
//show the footer when going outside the first include page.
if (isset($_GET['pg'])) {
BodyFooter();
}
exit;
?>