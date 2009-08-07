<?php
session_start();

include ('body.php');
include ('functions.php');
include ('f_secure.php');
include ('config.php');

$id = makeStringSafe($_POST['id']);
$item_id = makeStringSafe($_POST['item_id']);

if(!is_numeric($item_id)) {
BodyHeader("Invalid item");
echo "The item you are attempting to view is not valid.";
BodyFooter();
exit;
}

if(!is_numeric($id)) {
BodyHeader("Invalid item");
echo "The item you are attempting to view is not valid.";
BodyFooter();
exit;
}

//check to see if user has already voted on this item.
if (isset($_SESSION["useful$id"]) || isset($_COOKIE["useful$id"])) {
BodyHeader("You already voted on this review.","Useful Reviews for $item_name","$item_name");
echo "Sorry but you have already voted on this review.  Only one Yes or No is allowed per review.<br />
<br />
Click here to <a href=\"$directory/review-item/$item_id.php?". htmlspecialchars(SID) ."\">return</a>
";
BodyFooter();
exit;
}

setcookie("useful$id", $id, time()+31536000, "/", ".$domain");
$_SESSION["useful$id"] = "useful$id";

//print_r[$_COOKIE]; 
//_x is added to the end because of the image coordinates on the button.
if (isset($_POST['useful_x'])) {
$useful = $_POST['useful_x'];
}
if (isset($_POST['notuseful_x'])) {
$notuseful = $_POST['notuseful_x'];
}

 if (isset($useful)) {
 //$id = $_POST["id"];
 $sql_useful = "UPDATE  
			review
			SET
			useful = useful+1
			WHERE
			review.review_item_id = '$item_id' 
			AND review.id = $id
			";
			
		$sql_result_useful = mysql_query($sql_useful)
		or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));
} elseif (isset($notuseful)) {
 $id = $_POST["id"];
 $sql_useful = "UPDATE  
			review
			SET
			notuseful = notuseful+1
			WHERE
			review.review_item_id = '$item_id' 
			AND review.id = $id
			";
			
		$sql_result_useful = mysql_query($sql_useful)
		or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));
} 

$sql = "select *
from review_items
left join review
on (review.review_item_id = review_items.item_id)
where
review.review_item_id = '$item_id'
			AND review.id = '$id'";

			$sql_result = mysql_query($sql)
		or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));

//query to count number of people that have rated.
$sql_count = "SELECT COUNT(*) as total FROM review WHERE rating !='' and review.review_item_id = '$item_id'";
			$sql_result_count = mysql_query($sql_count)
		or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));
		
    $rows = mysql_fetch_array($sql_result_count);

    $total = $rows["total"];


//find average of reviews
$sql_avg = "select avg(rating) as average from review WHERE review.review_item_id = '$item_id'";
			$sql_result_avg = mysql_query($sql_avg)
		or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));
		
		while ( $row_avg = mysql_fetch_array($sql_result_avg) ) {
$average = $row_avg["average"];
}

while ($row = mysql_fetch_array($sql_result)) { 

$id = $row["id"]; 
$item_name = stripslashes($row["item_name"]); 
$item_desc = stripslashes($row["item_desc"]); 
$item_type = stripslashes($row["item_type"]); 
$source = stripslashes($row["source"]); 
$location = stripslashes($row["location"]); 
$review = stripslashes($row["review"]); 
$summary = stripslashes($row["summary"]); 
$rating = stripslashes($row["rating"]); 
$date_added = stripslashes($row["date_added"]);
$useful = $row["useful"];
$notuseful = $row["notuseful"];

BodyHeader("Reviews - $sitename","Useful Reviews for $item_name","$item_name");
?>
<hr noshade size=1>
<a name="cust-reviews"><b class="h1">Customer Reviews for <?php echo "$item_name"; ?></b><br />
</a> <font face=verdana,arial,helvetica size=-2><b>Avg. Customer Review</b> (<?php $average_for = sprintf ("%01.1f", $average); echo "$average_for Stars"; ?>):</font> 

<?php 		 $display = ($average/5)*100; ?>
              <div class="rating_bar">
                <div style="width:<?php echo "$display"; ?>%"></div>
              </div>

<br />
<font face=verdana,arial,helvetica size=-2><b>Number of Reviews:</b> <?php echo "$total"; ?></font>
<BR>
<?php
$item_id = $_POST['item_id'];

if ((@!$_POST['back']) && (@!$_GET['back'])) {
$back = $_SERVER['HTTP_REFERER'];
} elseif (isset($_GET['back']) && (@!$_POST['back'])) {
$back = $_GET['back'];
} elseif (isset($_POST['back']) && (!@$_GET['back'])) {
$back = $_POST['back'];
}

?>
<b class=small><a href="review_form.php?<?php echo htmlspecialchars(SID); ?>&back=<?php 
echo "$back"; ?>&item_id=<?php echo "$item_id"; ?>">Write an online review</a> and share your thoughts with other 
customers.</b> 
<p> 
<table width="95%" border="0" align="center" cellpadding="0" cellspacing="0">

  <tr> 
    <td><font face=verdana,arial,helvetica size=-1> <?php echo "$useful of "; 
	$total_useful = ($useful + $notuseful); echo "$total_useful";
	?> people found the following review helpful:<br />
      </font></td>
  </tr>
  <tr> 
    <td><font face=verdana,arial,helvetica size=-1> 
      <?php 		 $display = ($rating/5)*100; ?>
              <div class="rating_bar">
                <div style="width:<?php echo "$display"; ?>%"></div>
              </div>
<?php echo "$summary,"; 
      
 
$tempdate = explode("-", "$date_added"); 
$date_added = mktime(0,0,0,$tempdate[1],$tempdate[2],$tempdate[0]); 
$newdate = date("M d, Y", "$date_added"); 
		 echo "$newdate";
		 
		 ?>
      <br />
      </font></td>
  </tr>
  <tr> 
    <td><font face=verdana,arial,helvetica size=-1>Reviewer: <b> <?php echo "$source"; ?></b> 
      from <?php echo "$location"; ?><br />
      </font></td>
  </tr>
  <tr> 
    <td><?php 
if ($use_bbcode == "yes") {
include("bbcode.php");

  $sig = @str_replace($bbcode, $htmlcode, $sig);
  $sig = nl2br($sig);//second pass

  $review = str_replace($bbcode, $htmlcode, $review);
 $review = nl2br($review);//second pass
}
echo "$review"; ?></td>
  </tr>
  <tr> 
    <td> <br /> <p><strong><font size="3">Thanks for voicing your opinion!</font></strong></p>
      <p>Thanks for the valuable feedback you provided to other <?php echo "$sitename"; ?> 
        users. Your vote will be counted and will appear on the review page within 
        24 hours. </p>
      <p>Click here to return to <a href="javascript: history.go(-1)"><?php echo "$item_name"; ?></a>.
   <br />
      </p>
      <p><BR>
      </p></td>
  </tr>
  <?php } ?>
</table>
<?php
BodyFooter();
exit;
?>
