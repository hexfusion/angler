<?php  
session_start();

include ('body.php');
include ('functions.php');
include ('f_secure.php');
include ('config.php');

$criteria = $_GET['criteria'];
$searchterm = $_GET['searchterm'];

$default_sort = 'item_name';
$allowed_sort = array ('review_item_category','item_name','category','rating');
/* if sort is not set, or it is not in the allowed list, then set it to a default value. Otherwise, set it to what was passed in. */
if (!isset ($_GET['sort']) ||
    !in_array ($_GET['sort'], $allowed_sort)) {
    $sort = $default_sort;
} else {
    $sort = $_GET['sort'];
}

//$sort = @$_REQUEST['sort'];
$order = @$_REQUEST['order'];


/* else {
 $sort = "date_added";
 $order = "DESC";
 } */

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


/*
if (!$_GET['criteria']) {
$_SESSION['criteria']; = "$criteria";
$_SESSION['searchterm']; = "$searchterm";
}
*/
$_SESSION['limit'] = "$limit";
    $result = mysql_query("select count(*) from review_items WHERE $criteria= '$searchterm'");  
    $total = mysql_result($result, 0, 0);  

    // work out the pager values 

    $pager = getPagerData($total, $limit, $page);    
    $offset = $pager->offset;  
    $limit  = $pager->limit;  
    $page   = $pager->page;  

    // use pager values to fetch data 
	
///////////////////////////////////////////////////////////
// T2 MODS
///////////////////////////////////////////////////////////
	if($sort == "rating") {
		$query = " SELECT review_items.*, SUM(review.rating),
COUNT(review.id) AS total, (SUM(review.rating)/COUNT(review.id)) AS orate FROM review, review_items WHERE review_items.$criteria='$searchterm' AND review_items.item_id=review.review_item_id GROUP BY review_items.item_id ORDER BY orate $order limit $offset, $limit";
	} //if
	else {
		$query = "select * from review_items WHERE $criteria= '$searchterm' order by $sort $order limit $offset, $limit";
}

echo "$query is query";
///////////////////////////////////////////////////////////
// END T2 MODS
///////////////////////////////////////////////////////////
//this code could be used if you want to expand the options of linking from the left nav. to execute searches:
/*
$query = "select * from review_items WHERE $criteria LIKE '%$searchterm%'
";
if (isset($rating)){
	$Build = "AND rating ='$rating' ";
	$query = $query.$Build;}

if (isset($summary)){
	$Build = "AND summary ='$summary' ";
	$query = $query.$Build;}

if (isset($review)){
	$Build = "AND review ='$review' ";
	$query = $query.$Build;}

if (isset($location)){
	$Build = "AND location ='$location' ";
	$query = $query.$Build;}


$Build =  "order by $sort $order limit $offset, $limit";	
$query = $query.$Build;
//$query = "select * from review_items order by $sort $order limit $offset, $limit";
*/
    $result = mysql_query($query) 
or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));

//if there are no results, send a message:
$num = mysql_numrows($result);

	if ($num == 0) {
BodyHeader("No results!","","");
//import top menu for reviews

?><BR><BR><BR><BR>
<table align="center">Results for All Listings by <?php echo "$criteria" ?>: 
    <?php echo "$searchterm" ?><BR><BR>No results found.  Please narrow your search criteria and try again by clicking <a href=<?php echo "$back"; ?>>here</a>.<BR><BR><BR><BR><BR></table>
<?
BodyFooter();
exit;
}

//Display the header
BodyHeader("Search - All","","");
?>
<body link="#0033CC" vlink="#0033CC" alink="#0033CC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<div align="center"> 
  <p class="summaryCaption"> 

  </p>
<p class="summaryCaption">Results for<b> &quot;<?php echo "$criteria"; ?></b>&quot; 
  <p class="bodyreview"><b><?php echo "$total" ?></b> Results Found : Currently displaying 
    up to <b><?php echo "$limit" ?></b> results</p>
  <p class="bodyreview">How many results would you like to show on each page? 
  </p>
  <form name="form1" method="post" action="<?php $_SERVER['PHP_SELF']; ?>?criteria=<?php echo "$criteria"; ?>&searchterm=<?php echo "$searchterm"; ?>&sort=<?php echo "$sort"; ?>&order=<?php echo "$order"; ?>&<?php echo htmlspecialchars(SID); ?>">

    <select name=limit>
      <option value="5" selected></option>
      <option value="<?php echo "$total" ?>">All</option>
      <option value="5">5</option>
      <option value="10">10</option>
      <option value="15">15</option>
      <option value="20">20</option>
      <option value="25">25</option>
      <option value="30">30</option>
      <option value="35">35</option>
      <option value="40">40</option>
      <option value="45">45</option>
      <option value="50">50</option>
      <option value="100">100</option>
    </select>
    <input type="submit" name="Submit" value="Show Me">
  </form>
  
</div> 
<hr noshade size=1>
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr class="pages"> 
    <td width="101"> 
      <?php //if there is no order set, set it to ASC
if ($_GET['order'] == "") { $_GET['order'] = "ASC"; } ?>
      <div align="left"><b><a href="<?php echo $_SERVER['PHP_SELF']; ?>?criteria=<?php echo "$criteria"; ?>&searchterm=<?php echo "$searchterm"; ?>&sort=item_name&order=<?php if ($_GET['order'] == "DESC") { 
echo "ASC"; 
} elseif ($_GET['order'] == "ASC") { 
echo "DESC"; 
}  ?>" a:visited {color: #9900FF; font-weight: bold}  >Name</a><?php if ($sort=="item_name") { if ($_GET['order'] == "DESC") { 
echo " v"; } elseif ($_GET['order'] == "ASC") { 
echo " ^"; } }?></b></div></td>
    <?
/*
$query = "select * from review_items WHERE $criteria= '$searchterm' order by $sort $order limit $offset, $limit";

//echo "<BR>$query is query<BR>";

    $result = mysql_query($query) 
or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));


SELECT * FROM review ORDER BY overallrating $order LIMIT 0, 30
*/
?>
    <td width="123"> 
      <div align="left"><b><a href="<?php echo $_SERVER['PHP_SELF']; ?>?criteria=<?php echo "$criteria"; ?>&searchterm=<?php echo "$searchterm"; ?>&sort=rating&order=<?php if ($_GET['order'] == "DESC") { 
echo "ASC"; 
} elseif ($_GET['order'] == "ASC") { 
echo "DESC"; 
}  ?>">Rating</a><?php if ($sort=="rating") { if ($_GET['order'] == "DESC") { 
echo " v"; } elseif ($_GET['order'] == "ASC") { 
echo " ^"; } }?></b></div></td>
    <!--
   <td width="117"> 
      <div align="left"><b><a href="search_all_tim.php?sort=category&order=<?php // if ($_GET['order'] == "DESC") { 
//echo "ASC"; } elseif ($_GET['order'] == "ASC") { 
//echo "DESC"; } ?>">Type</a></b></div></td>
-->
    <td width="116"> 
      <div align="left"><b><a href="<?php echo $_SERVER['PHP_SELF']; ?>?criteria=<?php echo "$criteria"; ?>&searchterm=<?php echo "$searchterm"; ?>&sort=category&order=<?php if ($_GET['order'] == "DESC") { 
echo "ASC"; 
} elseif ($_GET['order'] == "ASC") { 
echo "DESC"; 
}  ?>">Category</a><?php if ($sort=="category") { if ($_GET['order'] == "DESC") { 
echo " v"; } elseif ($_GET['order'] == "ASC") { 
echo " ^"; } }?></b></div></td>
    
    
  </tr>
  <?php 
while ($row = mysql_fetch_array($result)) { 
	$item_name = stripslashes($row["item_name"]);
	$item_id = $row["item_id"];
	$item_desc = stripslashes($row["item_desc"]);
	$item_type = stripslashes($row["item_type"]);
    $category = stripslashes($row['category']);
    $area = stripslashes($row['area']);
    $neighborhood = stripslashes($row['neighborhood']);
    $address = stripslashes($row['address']);
    $zip = stripslashes($row['zip']);



if ($_GET['order'] == "DESC") { 
$order2 = "ASC"; 
} elseif ($_GET['order'] == "ASC") { 
$order2 =  "DESC"; 
}

$sql_avg = "select avg(rating) as average from review WHERE  rating !='.10' AND review.review_item_id = $item_id AND approve = 'y' order by average $order2";

//$_SESSION['average'] = $average;
//echo "<BR>$sql_avg    is sql avg<BR>";

			$sql_result_avg = mysql_query($sql_avg)
		or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));

//Get total number of reviews for each item.


$result2 = mysql_query("select count(*) from review WHERE  rating !='.10' AND review.review_item_id = $item_id AND approve = 'y' ");  
    $total2 = mysql_result($result2, 0, 0);  


while ($row2 = mysql_fetch_array($sql_result_avg)) { 
extract($row2);
}
?>

  <tr> 
    <td colspan="6" valign="top" nowrap><hr noshade size=1></td>
  </tr>
  <tr> 
    <td height="45" valign="top" nowrap class="bodyreviewlnk" ><?php echo "<a href=index2.php?item_id=$item_id>$item_name </a>"; ?><span> 
      </span> <p> </p></td>
    <td valign="top" nowrap> <span class="bodyreview">Overall Rating:</span><span class="bodyreviewsm"> 
      <?php  		 $display = ($average/5)*100; ?>
              <div class="rating_bar">
                <div style="width:<?php echo "$display"; ?>%"></div>
              </div><?php
$item_id = clean($_GET["item_id"]);
$back = $_SERVER['PHP_SELF'];
?>
      <br />
      <?php $average_for = sprintf ("%01.1f", $average); 
if ($total2 >= 2) {
$plural = "s";
} if ($total2 == 0) {
$plural = "s";
} else {
$plural = "";
}
if ($average <= "5.0") { echo " $total2 Review$plural";  } 

//$average_for Stars //replace $total2 Reviews with $average_for Stars if you'd like to show the average rating instead of the total number of reviews
?>
      </span></td>
    <td valign="top" nowrap class="body"><?php echo "$category"; ?><BR></td>
  </tr>
  <?php } ?>
</table>
<div align="center"> 
  <hr noshade size=1>
  <font class= "pages"> 
  <?
 // output paging system (could also do it before we output the page content) 
    if ($page == 1) // this is the first page - there is no previous page 
        echo "";  
    else            // not the first page, link to the previous page 
        echo "<a href=\"search_all_tim.php?page=" . ($page - 1) . "&limit=$limit\">Previous</a> | ";  

    for ($i = 1; $i <= $pager->numPages; $i++) {  
        if ($i > 1) echo " | ";   
        if ($i == $pager->page)  
            echo "Page $i";  
        else  
            echo "<a href=\"search_all_tim.php?page=$i&limit=$limit\">Page $i</a>";  
    }  

    if ($page == $pager->numPages) // this is the last page - there is no next page 
        echo "";  
    else            // not the last page, link to the next page 
        echo " | <a href=\"search_all_tim.php?page=" . ($page + 1) . "&limit=$limit\">Next</a>";  

BodyFooter();

function getPagerData($numHits, $limit, $page)  
       {  
           $numHits  = (int) $numHits;  
           $limit    = max((int) $limit, 1);  
           $page     = (int) $page;  
           $numPages = ceil($numHits / $limit);  

           $page = max($page, 1);  
           $page = min($page, $numPages);  

           $offset = ($page - 1) * $limit;  

           $ret = new stdClass;  

           $ret->offset   = $offset;  
           $ret->limit    = $limit;  
           $ret->numPages = $numPages;  
           $ret->page     = $page;  

           return $ret;  
       }  
?>

