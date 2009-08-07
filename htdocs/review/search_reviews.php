<?php  
session_start();
header("Cache-control: private");

include ('body.php');
include ('functions.php');
include ('f_secure.php');
include ('config.php');

if (!$_POST['search_term']) {
$search_term = mysql_escape_string(strtolower($_GET['search_term']));
} else {
$search_term = mysql_escape_string(strtolower($_POST['search_term']));
}

//make sure user has entered a search term of a specified length.
$search_len = strlen($search_term);
$min_search = 3;
if ($search_len <= $min_search) {
BodyHeader("Please add more characters to your search term!","","");
?>
<link href="review.css" rel="stylesheet" type="text/css">

Please enter a longer search term.  You must enter more than <?php echo "$min_search"; ?> search characters.
      <form action="search_reviews.php?<?php echo htmlspecialchars(SID); ?>" method="post" name="search" id="search"><input name="search_term" type="text" id="search_term" size="10" value="<?php echo stripslashes($search_term); ?>" maxlength="255"> 
   <input name="Submit" type="submit" class="maintext" value="Search Reviews"></form>
<?
BodyFooter();
exit;
}

//search terms not allowed.  Also add insignificant words you do not want to return results for.
$search_na = array (' shit ',' a ',' is ',' are ');
$search_count = count($search_na);
//print_r($search_term);
//echo "$search_count is search count<BR>";

for ($i=0; $i < $search_count; $i += 1) {
			$search_term = preg_replace('/'.$search_na[$i].'/i', " x ", "$search_term");
}

/* //not used now because of the preg_replace above.
if (in_array ($search_term, $search_na)) {
BodyHeader("That search term is not allowed!");
?>
Sorry, <b><?php echo "$search_term"; ?></b> is a search term that is not allowed.  Please try again with a different search term.
<BR>
      <form action="search_reviews.php?<?php echo htmlspecialchars(SID); ?>" method="post" name="search" id="search"><input name="search_term" type="text" id="search_term" size="10" value="<?php echo stripslashes($search_term); ?>"  maxlength="255"> 
   <input name="Submit" type="submit" class="maintext" value="Search Reviews"></form>
<?
BodyFooter();
exit;
}
*/

//check user input and remove any reference to javascript.
$errjava = "<font color=red><BR><BR><B>No Javascript is allowed!  Please click edit and remove the offending code.<BR><BR></B></font>";
$search_term = preg_replace("'<script[^>]*?>.*?</script>'si", "$errjava", $search_term);

/*
//get rid of duplicate search terms.
  //separate each word based on a space. 
  $words = explode(" ", trim( $search_term ) ); 

  //ignore duplicates
  $words = array_unique($words); 
  //$words = array_flip(array_flip($words));  //this will also work.

  //take the array and separate the words with a space to put back in a sentence...without duplicates. 
  $search_term = implode(" ", $words);
*/
 $words = explode(" ", trim( $search_term ) ); 

//echo "<BR><BR>now search term is:  $search_term<BR><BR>";

$default_sort = 'source';
$allowed_sort = array ('item_name','category','rating','source','review','summary');
/* if sort is not set, or it is not in the allowed list, then set it to a default value. Otherwise, set it to what was passed in. */
if (!isset ($_GET['sort']) ||
    !in_array ($_GET['sort'], $allowed_sort)) {
    $sort = $default_sort;
} else {
    $sort = $_GET['sort'];
}

$order = @$_REQUEST['order'];

    // get the pager input values 
    $page = @$_GET['page'];
if (isset($_POST['limit'])) {
    $limit = $_POST['limit'];  
} elseif (isset($_GET['limit'])) {
$limit = $_GET['limit']; 
}  elseif (isset($_SESSION['limit'])) {
$limit = $_SESSION['limit']; 
} else {
$limit = 5;
}

//count the results of the search input.
$num_se = count($words);
//echo "<BR><BR>$num_se is num_se<BR><BR>";
//dynamically create the query.

$query = "select count(*) from review WHERE review LIKE '%".$words[0]."%'";

for ($i=1; $i < $num_se; $i += 1) {
	$Build = "OR review LIKE '%".$words[$i]."%' ";
	$query = $query.$Build;
}

	$result = mysql_query($query)
		or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));
    $total = mysql_result($result, 0, 0);  

if ($total <= 0) {
BodyHeader("No Results for $search_term","","");
?>No results were found for <?php echo "$search_term"; ?>.  Please try a different search.<BR>
      <form action="search_reviews.php?<?php echo htmlspecialchars(SID); ?>" method="post" name="search" id="search"><input name="search_term" type="text" id="search_term" size="10"  maxlength="255"> 
   <input name="Submit" type="submit" class="maintext" value="Search Reviews"></form>
<?
BodyFooter();
exit;
}

    // work out the pager values 
    $pager = getPagerData($total, $limit, $page);    
    $offset = $pager->offset;  
    $limit  = $pager->limit;  
    $page   = $pager->page;  

$_SESSION['limit'] = "$limit";	
$limit_last = $_GET['limit_last'];

//build main select query based on the search terms entered.
for ($i=1; $i < $num_se; $i += 1) {
	$Build2 .= "OR review LIKE '%".$words[$i]."%' ";
}

if (isset($limit_last) && ($_GET['allPages'] == $_GET['pg'])) { 
$query = "SELECT review.id, SUBSTRING_INDEX(review, ' ', 6) as review, source, SUBSTRING_INDEX(summary, ' ', 5) as summary, review_item_id, review_items.item_name, date_format(date_added, '%M %d, %Y') as date_added, rating, review_users.username FROM review_items, review_users
left join review
on (review.review_item_id = review_items.item_id)
WHERE review LIKE '%".$words[0]."%' $Build2  AND approve='y' order by $sort $order limit $offset, $limit_last
"; 
/*$query = "SELECT review.id, SUBSTRING_INDEX(review, ' ', 6) as review, source, SUBSTRING_INDEX(summary, ' ', 5) as summary, review_item_id, review_items.item_name, date_format(date_added, '%M %d, %Y') as date_added, rating, review.username, MATCH(review.review, review.summary) 
AGAINST ('$search_term') AS score 
FROM review_items, review 
WHERE review.review_item_id = review_items.item_id AND 
MATCH(review.review, review.summary) AGAINST ('$search_term')
AND approve='y' order by $sort $order limit $offset, $limit_last
"; */
} 
else {
$query = "SELECT review.id, SUBSTRING_INDEX(review, ' ', 6) as review, source, SUBSTRING_INDEX(summary, ' ', 5) as summary, review_item_id, review_items.item_name, date_format(date_added, '%M %d, %Y') as date_added, rating, review_users.username FROM review_items, review_users
left join review
on (review.review_item_id = review_items.item_id)
WHERE review LIKE '%".$words[0]."%' $Build2  AND approve='y' order by $sort $order limit $offset, $limit
";}

//echo "$query";
//set limit back to the original value.
if (isset($limit_orig)) {
$limit = $limit_orig;
}

	$result = mysql_query($query)
		or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));

while($row = mysql_fetch_array($result)) {
$id = stripslashes($row['id']);
$review = stripslashes($row['review']);
$source = stripslashes($row['source']);
$review_item_id = stripslashes($row['review_item_id']);
$rating = stripslashes($row['rating']);
$date_added = stripslashes($row['date_added']);
$summary = stripslashes($row['summary']);
$item_id = stripslashes($row['review_item_id']);
$item_name = stripslashes($row['item_name']);
$username = stripslashes($row['username']);
}

    $result = mysql_query($query) 
or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));

//Display the header
BodyHeader("Search Results for $search_term","","");
?>

<LINK rel="stylesheet" type="text/css" name="style" href="review.css">
<div align="center">
  <p><SPAN>We found <b><?php echo "$total" ?></b> results for "<b><?php echo ucfirst($search_term); ?></b>"</SPAN>
    <br />
    <SPAN class="small">Common or banned words were replaced with the &quot;x&quot;</SPAN> 
  <p><br />
    How many results would you like to show on each page? </p>
  <form name="form1" method="post" action="<?php $_SERVER['PHP_SELF']; ?>?search_term=<?php echo "$search_term"; ?>&sort=<?php echo "$sort"; ?>&order=<?php echo "$order"; ?>&<?php echo htmlspecialchars(SID); ?>">
<select name="limit">
<?
//this fancy select box will only display in increments of 5 and not exceed the total number of results.
if ($limit == $total) { ?>
<option value="<?php echo "$total" ?>"<?php if ($limit == $total) { echo "selected"; } ?>">All</option> 
<?php }

for($i = 0; $i < $total; $i += 5) {
if ($total > "5") {
if ($i >= 5) {
?>
<option value="<?php echo "$i"; ?>"<?php if ($limit == "$i") { echo " SELECTED"; } ?>><?php echo "$i"; ?></option> 
<?php 
}//if i >=5
} 
else { echo "<option value=\"--\" selected"; ?>>--</option> <?php } //if
}//for 
if ($total != $limit) { ?>      <option value="<?php echo "$total" ?>"<?php if ($limit == $total) { echo "selected"; } ?>">All</option> <?php } //end if total ?>
  </select>
    <input name="Submit" type="submit" value="Show Me">
    <span class="small">(Current display: Up to <b><?php echo "$limit" ?></b> results) </span>
  </form>
</div>
<hr noshade size=1>
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    <td width="115"><?php //if there is no order set, set it to ASC
if ($_GET['order'] == "") { $_GET['order'] = "ASC"; } ?>
      <div align="left"><span class="small">Sort by</span><BR>
        <b><a href="<?php echo $_SERVER['PHP_SELF']; ?>?search_term=<?php echo "$search_term"; ?>&sort=item_name&order=<?php if ($_GET['order'] == "DESC") { 
echo "ASC"; 
} elseif ($_GET['order'] == "ASC") { 
echo "DESC"; 
}  ?>">Name</a>
        <?php if ($sort=="item_name") { if ($_GET['order'] == "DESC") { 
echo " <img src=\"images/sort_down.gif\">"; } elseif ($_GET['order'] == "ASC") { 
echo " <img src=\"images/sort_up.gif\">"; } }?>
        </b></div></td>
    <td width="121"><div align="left"><span class="small">Sort by</span><BR>
        <b><a href="<?php echo $_SERVER['PHP_SELF']; ?>?search_term=<?php echo "$search_term"; ?>&sort=rating&order=<?php if ($_GET['order'] == "DESC") { 
echo "ASC"; 
} elseif ($_GET['order'] == "ASC") { 
echo "DESC"; 
}  ?>">Rating</a>
        <?php if ($sort=="rating") { if ($_GET['order'] == "DESC") { 
echo " <img src=\"images/sort_down.gif\">"; } elseif ($_GET['order'] == "ASC") { 
echo " <img src=\"images/sort_up.gif\">"; } }?>
        </b></div></td>
    <td width="107"><div align="left"><span class="small">Sort by<br />
        </span><b><a href="<?php echo $_SERVER['PHP_SELF']; ?>?search_term=<?php echo "$search_term"; ?>&sort=summary&order=<?php if ($_GET['order'] == "DESC") { 
echo "ASC"; 
} elseif ($_GET['order'] == "ASC") { 
echo "DESC"; 
}  ?>">Summary</a>
        <?php if ($sort=="summary") { if ($_GET['order'] == "DESC") { 
echo " <img src=\"images/sort_down.gif\">"; } elseif ($_GET['order'] == "ASC") { 
echo " <img src=\"images/sort_up.gif\">"; } }?>
        </b></div></td>
    <td width="222"><div align="left"><span class="small">Sort by</span><b><a href="<?php echo $_SERVER['PHP_SELF']; ?>?search_term=<?php echo "$search_term"; ?>&sort=review&order=<?php if ($_GET['order'] == "DESC") { 
echo "ASC"; 
} elseif ($_GET['order'] == "ASC") { 
echo "DESC"; 
}  ?>"><br />
        Review</a>
        <?php if ($sort=="review") { if ($_GET['order'] == "DESC") { 
echo " <img src=\"images/sort_down.gif\">"; } elseif ($_GET['order'] == "ASC") { 
echo " <img src=\"images/sort_up.gif\">"; } }?>
        </b></div></td>
    <td width="189"><div align="left"><span class="small">Sort by</span><b><BR>
        <a href="<?php echo $_SERVER['PHP_SELF']; ?>?search_term=<?php echo "$search_term"; ?>&sort=source&order=<?php if ($_GET['order'] == "DESC") { 
echo "ASC"; 
} elseif ($_GET['order'] == "ASC") { 
echo "DESC"; 
}  ?>">Reviewer</a>
        <?php if ($sort=="source") { if ($_GET['order'] == "DESC") { 
echo " <img src=\"images/sort_down.gif\">"; } elseif ($_GET['order'] == "ASC") { 
echo " <img src=\"images/sort_up.gif\">"; } }?>
        </b></div></td>
  </tr>
  <?php 
while ($row = mysql_fetch_array($result)) { 
$id = stripslashes($row['id']);
$review = stripslashes($row['review']);
$rating = stripslashes($row['rating']);
$source = stripslashes($row['source']);
$summary = stripslashes($row['summary']);
$review_item_id = stripslashes($row['review_item_id']);
$date_added = stripslashes($row['date_added']);
$item_id = stripslashes($row['review_item_id']);
$item_name = stripslashes($row['item_name']);

if ($_GET['order'] == "DESC") { 
$order2 = "ASC"; 
} elseif ($_GET['order'] == "ASC") { 
$order2 =  "DESC"; 
}

//Get total number of reviews for each item.
$average = $rating;

$result2 = mysql_query("select count(*) from review WHERE  rating !='0' AND review.review_item_id = $item_id AND approve = 'y' ");  
    $total2 = mysql_result($result2, 0, 0);  
?>
  <tr>
    <td colspan="6" valign="top" nowrap><hr noshade size=1></td>
  </tr>
  <tr>
    <td height="45" valign="top" nowrap><?php echo "<a href=review_single.php?item_id=$item_id&id=$id>$item_name </a>"; ?>
      <p> </p></td>
    <td valign="top" nowrap><span>Average Rating:</span><span>
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
//put search_term into array to highlight search term.
$keywords[] = $search_term; 

//clean up bbcode and get it ready for display
if ($use_bbcode == "yes") {
include("bbcode.php");

  $sig = str_replace($htmlcode, $bbcode, $sig);
  $sig = nl2br($sig);//second pass

  $review = str_replace($bbcode, $htmlcode, $review);
  $review = nl2br($review);//second pass
  $review = html_entity_decode($review);
}
?>
      <br />
      </span></td>
    <td valign="top" nowrap class="body"><?php echo "$summary"; ?><BR></td>
    <td valign="top" nowrap class="body"><?php echo( search_highlight( "$review", $keywords ) ); ?>...<BR></td>
    <td valign="top" nowrap class="body"><a href="<?php echo "$directory"; ?>/reviewer_about.php?username=<?php echo "$username"; ?>"><?php echo "$source"; ?></a><BR></td>
  </tr>
  <?php } ?>
</table>
<div align="center">


<hr noshade size=1>
<?php //page system
$NumReviews = "$limit";
$lastReview = $total;

$pg = @$_REQUEST['pg'];
if($pg > 1) { $start = ($pg-1)*$NumReviews; $npg = $pg + 1; }
else {$start = 0;  $pg = 1; $npg = 2; }

if (isset($_GET['sort'])) {
$sort = $_GET['sort'];
}
$PHP_SELF = $_SERVER['PHP_SELF'];

if (!$_GET['allPages']) {
$allPages = ceil($lastReview/$NumReviews);
} else {
$allPages = $_GET['allPages'];
}

if (!($_GET['allPages'])) {
$totalpages = $allPages;
}
$stop = $start + $NumReviews;

if (!$_GET['limit_last'] && !$_POST['limit'] && $_GET['view'] != "all") {
$limit_last = $allPages*$limit - $lastReview; 
$limit_last = $limit - $limit_last;
} elseif (isset($_POST['limit'])) {
$limit = $_POST['limit'];
$limit_last = $allPages*$limit - $lastReview; 
$limit_last = $limit - $limit_last;
}

if($stop > $lastReview) { $stop = $lastReview;
}

 $nextPage = "";
 for($j=1;$j<=$allPages;$j++) {

//if get view.
if ($_GET['view'] == "all") {
//$allPages = $_GET['allPages'];

$pg_orig = $_GET['pg_orig'];
	$pagedview = " <a href=\"search_reviews.php?pg=$pg_orig&search_term=$search_term&limit=5&sort=$sort&order=$order&allPages=$allPages&limit_last=$limit_last\">Back to Paged View</a> ";  
 } 
else { 
	if($pg == $j && $total > "5" && $lastReview > $NumReviews) { $nextPage .= " | $j ";}
	elseif ($pg == "1" && $total < "5") { //do nothing
	}
	elseif ($lastReview > $NumReviews) {  $nextPage .= " | <a href=\"$PHP_SELF?pg=$j&limit=$limit&search_term=$search_term&sort=$sort&order=$order&allPages=$allPages&limit_last=$limit_last\">$j</a> ";  } //else { echo " <a href=\"search_reviews.php?pg=1&search_term=$search_term&limit=5&sort=$sort&order=$order&allPages=$allPages&limit_last=$limit_last\">Back to Paged View</a> ";  }
} //else

 } //for
//don't display the pagination if there are no reviews
if ($lastReview != "0") { 
?>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="39%">Showing
      <?php $begin = $start+1; echo "$begin"; ?>
      to
      <?php if ($_GET['view'] == "all") { echo "$total"; } else { echo "$stop"; } ?>
      of <?php echo "$lastReview"; ?> reviews </td>
    <?php if ($_GET['view'] != "all") { ?>
    <td width="40%"><?php echo "$nextPage"; ?>
      <?php //only show view all if there is more than one page
if($lastReview > $NumReviews) { 
?>
      - <a href="search_reviews.php?search_term=<?php echo "$search_term"; ?>&sort=<?php echo "$sort"; ?>&order=<?php echo "$order"; ?>&limit=<?php echo "$total"; ?>&pg=1&pg_orig=<?php echo "$pg"; ?>&view=all&allPages=<?php echo "$allPages"; ?>&limit_last=<?php echo "$limit_last"; ?>" target="_parent">View All</a>
      <?php } //end viewall check ?>
    </td>
    <td width="21%"><?php 
$stop = $start + $NumReviews;
if($stop > $lastReview) { $stop = $lastReview; }
elseif ($lastReview > $NumReviews) { $next = "<a href=\"$PHP_SELF?pg=$npg&search_term=$search_term&sort=$sort&order=$order&allPages=$totalpages&limit_last=$limit_last\">Next</a>"; }
if($pg > 1) { $ppg = $pg-1; $prev = "<a href=\"$PHP_SELF?pg=$ppg&search_term=$search_term&sort=$sort&order=$order&allPages=$totalpages&limit_last=$limit_last\">Previous</a>"; }

if(isset($prev)) { echo "$prev "; }
if(isset($next)) { echo "$next"; }
?>
    </td>
    <?php } //end check if ($_GET['view'] 
if(isset($pagedview)) { echo "<td width=\"21%\">$pagedview"; }?>
    </td>
  </tr>
</table>
<hr align=left noshade size=1 width=100%>
<?php } //end pagination check 

//Highlight search words
function search_highlight( $review, $keywords ) 
{ 
    foreach( $keywords as $word ) { 
        $review = preg_replace( "/$word/i", '<span class="searchhigh">' . $word . '</span>', "$review" ); 
    } 
    return( "$review" ); 
}//end function 
BodyFooter();
exit;
?>
