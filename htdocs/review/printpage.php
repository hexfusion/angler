<?php
session_start();
$sort = @$_SESSION['sort'];

include ('body.php');
include ('functions.php');
include ('f_secure.php');
include ('config.php');

$item_id = mysql_real_escape_string($_GET['item_id']); 


if (!isset($sig)) {
$sig = "";
}

$build = "review.rating >= 0";

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
from review_items
left join review
on (review.review_item_id = review_items.item_id)
where
review.review_item_id='$item_id' AND approve = 'y'
AND $build
ORDER BY $sort $order
";

$AllReviews = array();
$sResult = mysql_query("$sel_com");
$sRows = mysql_numrows( $sResult );

while($row = mysql_fetch_array($sResult)) {
	array_push($AllReviews,"$row[item_id]|$row[item_name]|$row[item_desc]|$row[item_type]|$row[id]|$row[rating]|$row[summary]|$row[review]|$row[source]|$row[location]|$row[review_item_id]|$row[visitorIP]|$row[date_added]|$row[useful]|$row[notuseful]|$row[approve]|$row[sig_show]|$row[username]|");
} //while

//select the item_id to be displayed in case there are no reviews that have been written.
$sel_com2 = "select item_name, item_type, item_image from review_items
where
item_id='" . mysql_real_escape_string($item_id) . "'
";

$sResult2 = mysql_query("$sel_com2")
		or die(sprintf("Couldn't execute sql_count, %s: %s", db_errno(), db_error()));


while($row2 = mysql_fetch_array($sResult2)) {
$item_name2 = stripslashes($row2['item_name']);
$item_type2 = stripslashes($row2['item_type']);
$item_image = stripslashes($row2['item_image']);
$item_desc = stripslashes($row2['item_desc']);
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
if($stop >= $lastReview) { $stop = $lastReview; }

 $nextPage = "";
 for($j=1;$j<=$allPages;$j++) {
	if($pg == $j) { $nextPage .= " | $j ";}
	else {  $nextPage .= " | <a href=\"$PHP_SELF?pg=$j&item_id=$item_id&sort=$sort&order=$order\">$j</a> ";  }

 } //for
if ($lastReview != "0") { $nextPage .= " |"; }

////////////////////////////////////////////////////////////////////
//set variable names.
for ($i = 0; $i < 1; $i++) {
	@list($item_id2, $item_name, $item_desc, $item_type, $id, $rating, $summary, $review, $source, $location, $review_item_id, $visitorIP, $date_added, $useful, $notuseful, $approve, $sig_show, $username) = split("\|",$AllReviews[$i]);
}

//query to count number of people that have rated.
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


	?>
<html>
<body>
<link href="review.css" rel="stylesheet" type="text/css">
<hr noshade size=1 width=100%>
<table width="100%"  border="0" align="center">
  <tr>
    <td><b class="style3">Customer reviews for <?php echo "$item_name2"; ?></b><br />
<br />

      <?php echo "$item_desc"; ?> <br />
<br />
</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><?php if ($average >= "1.0") { ?>
      <font face=verdana,arial,helvetica size=-2><b>Avg. Customer Review</b>
      <?php } //if average
$average_for = sprintf ("%01.1f", $average); 
if ($average <= "5.0") { echo "($average_for Stars):";  } ?>
      </font>
      <?php  		 $display = ($average/5)*100; ?>
              <div class="rating_bar">
                <div style="width:<?php echo "$display"; ?>%"></div>
              </div><?php

$item_id = $_GET["item_id"];
$back = $_SERVER['PHP_SELF'];
?></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><font face=verdana,arial,helvetica size=-2><b>Number of Reviews:</b> <?php echo "$total"; ?> </font></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><?php
//if there is an image, resize it and then display it.
$filename = "images/items/$item_image";
if (is_file($filename)) {
$html="<a href=$url$directory/$filename target=_blank ><img src=resize_item_image.php?filename=$filename border=\"0\" alt=\"Click here to see a larger image of $item_name2\"></a>";
print $html;
} ?>
    </td>
    <td><form>
        <input type="button" value="Print this page" onClick="window.print()">
      </form></td>
  </tr>
</table>
<hr noshade size=1 width=100%>
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
</form>
</p>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
<BR>
<hr noshade size=1 width=100%>
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
  </tr>
</table>
<hr noshade size=1 width=100%>
<br />
<!-- startprint -->
<?php } //end pagination check 
//end top page navigation 

$item_id = $_GET["item_id"];

//set variable names.
for ($i = $start; $i < $stop; $i++) {
	list($item_id, $item_name, $item_desc, $item_type, $id, $rating, $summary, $review, $source, $location, $review_item_id, $visitorIP, $date_added, $useful, $notuseful, $approve, $sig_show, $username) = split("\|",$AllReviews[$i]);
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
    <?php		 $display = ($rating/5)*100; ?>
              <div class="rating_bar">
                <div style="width:<?php echo "$display"; ?>%"></div>
              </div><?php
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
  <td><font face=verdana,arial,helvetica size=-1>reviewer: <b> <a href="<?php echo "$directory"; ?>/reviewer_about.php?username=<?php echo "$username"; ?>"><?php echo ucfirst($source); ?></a></b> from <?php echo "$location"; ?> <br />
    </font></td>
</tr>
<tr>
  <?
//print_r($_SESSION);
//show users signature?
if($sig_show == "y") { 
$sql = "SELECT sig FROM 
			review_users
			WHERE 
			username = '$username'";

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
  <td><form action="<?php echo "$directory"; ?>/useful.php?item_id=<?php echo "$item_id"; ?>" method="post">
      <input name="id" type="hidden" value="<?php echo "$id"; ?>">
      <input name="item_id" type="hidden" value="<?php echo "$item_id"; ?>">
      <input name="item_name" type="hidden" value="<?php echo "$item_name2"; ?>">
      <br />
      <br />
    </form></td>
</tr>
<?php  }
echo "</table>";

 
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
  </tr>
</table>
<br />
<?php } //end pagination check 
$from = $_SERVER['HTTP_REFERER'];
echo "This page was printed from $from";
?>
</body>
</html>
