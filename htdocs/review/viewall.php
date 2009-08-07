<?php
session_start();

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
from review_items
left join review
on (review.review_item_id = review_items.item_id)
where
review.review_item_id = $item_id AND approve = 'y'
AND $build
ORDER BY $sort $order
";

$AllReviews = array();
$sResult = mysql_query("$sel_com");
$sRows = mysql_numrows( $sResult );

while($row = mysql_fetch_array($sResult)) {
	array_push($AllReviews,"$row[item_id]|$row[item_name]|$row[item_desc]|$row[item_type]|$row[id]|$row[rating]|$row[summary]|$row[review]|$row[source]|$row[location]|$row[review_item_id]|$row[visitorIP]|$row[date_added]|$row[useful]|$row[notuseful]|$row[approve]|");
} //while

//select the item_id to be displayed in case there are no reviews that have been written.
$sel_com2 = "select item_name, item_type from review_items
where
item_id = $item_id
";

$sResult2 = mysql_query("$sel_com2");

while($row2 = mysql_fetch_array($sResult2)) {
$item_name2 = stripslashes($row2['item_name']);
$item_type2 = stripslashes($row2['item_type']);
} //while


//Set the number of reviews to display per page in the /info/functions.php file
//override functions.php setting to show ALL reviews.
$NumReviews = 9999999;

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
$lastpage = $_GET['lastpage'];
 for($j=1;$j<=$allPages;$j++) {

if (isset($_GET['pg_orig'])) {
$pg_orig = $_GET['pg_orig'];
$nextPage .= " <a href=\"$lastpage?pg=$pg_orig&item_id=$item_id&sort=$sort&order=$order#review\">Back to Paged View</a> ";  } elseif ($pg == $j) { $nextPage .= " <a href=\"$lastpage?pg=$j&item_id=$item_id&sort=$sort&order=$order#review\">Back to Paged View</a> ";  }


 } //for

////////////////////////////////////////////////////////////////////
//set variable names.
for ($i = 0; $i < 1; $i++) {
	@list($item_id2, $item_name, $item_desc, $item_type, $id, $rating, $summary, $review, $source, $location, $review_item_id, $visitorIP, $date_added, $useful, $notuseful, $approve) = split("\|",$AllReviews[$i]);
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

BodyHeader("View All Reviews - $sitename","","");
	?>

<hr noshade size=1 width=100%>
<b class="h1">Customer reviews for <?php echo "$item_name2"; ?></b><br />
<?php if ($average >= "1.0") { ?>
<font face=verdana,arial,helvetica size=-2><b>Avg. Customer Review</b>
<?php } //if average
$average_for = sprintf ("%01.1f", $average); 
if ($average <= "5.0") { echo "($average_for Stars):";  } ?>
</font>
<?php   		 $display = ($average/5)*100; ?>
              <div class="rating_bar">
                <div style="width:<?php echo "$display"; ?>%"></div>
              </div><?php

$item_id = $_GET["item_id"];
$back = $_SERVER['PHP_SELF'];
?>
<br />
<font face=verdana,arial,helvetica size=-2><b>Number of Reviews:</b> <?php echo "$total"; ?> <br />
<a href="recommend.php?<?php echo htmlspecialchars(SID); ?>&item_id=<?php echo "$item_id"; ?>">Email</a> a friend about this <?php echo "$item_type2"; ?>.</font><br />
<br />
<b class=small><a href="review_form.php?<?php echo htmlspecialchars(SID); ?>&item_id=<?php
echo "$item_id"; ?>"><img src="images/write.gif" width="35" height="35" border="0">Write an online review</a> and share your thoughts.</b>
<p>
<form name="form1" method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>?<?php echo htmlspecialchars(SID); ?>&item_id=<?php echo "$item_id"; ?>">
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
</form>
</p>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <BR>
  <hr noshade size=1 width=100%>
  <?php //display page navigation on top  
$parsed = parse_url($_SERVER['PHP_SELF']);
$lastpage = $parsed['path'];

//don't display the pagination if there are no reviews
//if ($lastReview != "0") { 
?>
  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
    <tr>
      <th width="33%" scope="col"><div align="left"><font color="#0033CC">Showing
          <?php $begin = $start+1; echo "$begin"; ?>
          to <?php echo "$stop"; ?> of <?php echo "$lastReview"; ?> reviews </font></div></th>
      <th width="33%" scope="col"><?php echo "$nextPage"; ?>
        <?php //only show view all if there is more than one page
/*if($lastReview > $NumReviews) { 
?>
        - <a href="viewall.php?lastpage=<?php echo "$lastpage"; ?>&item_id=<?php echo "$item_id"; ?>&sort=<?php echo "$sort"; ?>&order=<?php echo "$order"; ?>">View All</a>
        <?php } //end viewall check */ ?>
      </th>
      <th width="34%" scope="col">&nbsp;</th>
    </tr>
  </table>
  <hr noshade size=1 width=100%>
  <br />
  
    <table width="100%"  border="0" cellspacing="0" cellpadding="0">
    
  <?php //} //end pagination check 
//end top page navigation 

$item_id = $_GET["item_id"];

//set variable names.
for ($i = $start; $i < $stop; $i++) {
	list($item_id, $item_name, $item_desc, $item_type, $id, $rating, $summary, $review, $source, $location, $review_item_id, $visitorIP, $date_added, $useful, $notuseful, $approve) = split("\|",$AllReviews[$i]);

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
      <?php 		 $display = ($rating/5)*100; ?>
              <div class="rating_bar">
                <div style="width:<?php echo "$display"; ?>%"></div>
              </div><?php
 echo "$summary,"; ?>
      <?php

$tempdate = explode("-", "$date_added"); 
$date_added = mktime(0,0,0,$tempdate[1],$tempdate[2],$tempdate[0]); 
$newdate = date("M d, Y", "$date_added"); 
		 echo "$newdate"; ?>
      <br />
      </font></td>
  </tr>
  <tr>
    <td><font face=verdana,arial,helvetica size=-1>reviewer: <b> <?php echo "$source"; ?></b> from <?php echo "$location"; ?><br />
      </font></td>
  </tr>
  <tr>
    <td><?php echo "$review"; ?></td>
  </tr>
  <tr>
    <td><form action="useful.php?item_id=<?php echo "$item_id"; ?>" method="post">
        <input name="id" type="hidden" value="<?php echo "$id"; ?>">
        <input name="item_id" type="hidden" value="<?php echo "$item_id"; ?>">
        <input name="item_name" type="hidden" value="<?php echo "$item_name2"; ?>">
        <br />
        <span class="small">Was this review helpful to you?&nbsp;&nbsp;</span>
        <input type="image" value="1" name="useful" src="images/yes.gif" border="0" width="34" height="16" align="absmiddle">
        <input type="image" value="1" name="notuseful" src="images/no.gif" border="0" width="34" height="16" align="absmiddle">
        <br />
        <BR>
        <br />
      </form></td>
  </tr>

<?php  } ?>
</table>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr> 
                <td width="45%">Showing 
                  <?php $begin = $start+1; echo "$begin"; ?>
                  to <?php echo "$stop"; ?> of <?php echo "$lastReview"; ?> reviews 
                </td>
                <td width="39%"><?php echo "$nextPage"; ?> 
                  <?php //only show view all if there is more than one page
/* if($lastReview > $NumReviews) { 
?>
                  - <a href="viewall.php?lastpage=<?php echo "$lastpage"; ?>&item_id=<?php echo "$item_id"; ?>&sort=<?php echo "$sort"; ?>&order=<?php echo "$order"; ?>">View 
                  All</a> 
                  <?php } //end viewall check */ ?>
                </td>
                <td width="16%"> 
                  <?php 

/*
$stop = $start + $NumReviews;
if($stop > $lastReview) { $stop = $lastReview; }
else { $next = "<a href=\"$PHP_SELF?pg=$npg&item_id=$item_id&sort=$sort&order=$order#review\">Next</a>"; }
if($pg > 1) { $ppg = $pg-1; $prev = "<a href=\"$PHP_SELF?pg=$ppg&item_id=$item_id&sort=$sort&order=$order#review\">Previous</a>"; }


if(isset($prev)) { echo "$prev"; }
if(isset($next)) { echo "$next"; }

*/
?>
                </td>
              </tr>
</table>
<?php 
BodyFooter();
exit;
?>
