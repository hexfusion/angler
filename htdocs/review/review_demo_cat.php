<?php  
/*
This file will display a single category in the standard format.
Make sure you link to it like this: 
http://www.yoursite.com/review/review_demo_cat.php?category=Scripts 
Notice after the php I have ?category=Scripts 
Just replace Scripts with the name of the category you want to display.
*/
session_start();

include ('body.php');
include ('functions.php');
include ('f_secure.php');
include ('config.php');

$category = $_GET['category'];

    // get the pager input values 
    $page = @$_GET['page'];
if (isset($_POST['limit'])) {
    $limit = $_POST['limit'];  
} elseif (isset($_GET['limit'])) {
$limit = $_GET['limit']; 
} else {
$limit = 5;
}

$_SESSION['limit'] = "$limit";
    $result = mysql_query("select count(*) from review_items");  
    $total = mysql_result($result, 0, 0);  

    // work out the pager values 

    $pager = getPagerData($total, $limit, $page);    
    $offset = $pager->offset;  
    $limit  = $pager->limit;  
    $page   = $pager->page;  

    // use pager values to fetch data 

    $query = "SELECT * FROM review_items 
WHERE category='$category' 
AND category != '' 
ORDER BY sortorder ASC limit $offset, $limit"; 

    $result = mysql_query($query) 
or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));


//Display the header
BodyHeader("Reviews - $sitename","","");
?>
<style type="text/css">
<!--
.style1 {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
}
.style2 {
	font-size: 10px;
	font-weight: bold;
}
-->
</style>
<div align="center">
  <p class="style1">This is just a demo page. The layout and design are 100% flexible. <br />
  Alternate layouts can be seen <a href="layouts.php?<?php echo htmlspecialchars(SID); ?>">here</a>. </p>
  <p class="style1">How many review items would you like to show on each page? </p>

<form name="form1" method="post" action="<?php $_SERVER['PHP_SELF']; ?>?<?php echo htmlspecialchars(SID); ?>">
<select name=limit>
      <option value="5" selected>5</option>
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
<table width="99%" border="0" align="center" cellpadding="3" cellspacing="0"> 
  <tr> 
    <td width="88">&nbsp;</td> 
    <td width="267"><strong>Name</strong></td> 
    <td width="369"><strong>Description</strong></td> 
  </tr> 
  <?php 
while ($row = mysql_fetch_array($result)) { 
	$item_name = stripslashes($row["item_name"]);
	$item_id = $row["item_id"];
	$item_desc = stripslashes($row["item_desc"]);
	$item_type = stripslashes($row["item_type"]);

$sql_avg = "select avg(rating) as average from review WHERE  rating !='' AND review.review_item_id = $item_id AND approve = 'y'";

			$sql_result_avg = mysql_query($sql_avg)
		or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));

//Get total number of reviews for each item.
$result2 = mysql_query("select count(*) from review WHERE  rating !='' AND review.review_item_id = $item_id AND approve = 'y'");  
    $total2 = mysql_result($result2, 0, 0);  


while ($row2 = mysql_fetch_array($sql_result_avg)) { 
 extract($row2);
}

?> 
  <tr> 
    <td width="88"><a href=index2.php?<?php echo htmlspecialchars(SID); ?>&item_id=<?php echo "$item_id"; ?>><img src="<?php echo "$directory"; ?>/images/review.gif" hspace="1" vspace="1" border="0"></a></td> 
    <td><?php echo stripslashes($item_name); ?><br />      
      <?php $average_for = sprintf ("%01.1f", $average); 
if ($total2 >= 2) {
$plural = "s";
} else {
$plural = "";
}
if ($average >= "1.0") { echo "<font face=verdana,arial,helvetica size=-2><b>Avg. Customer Review<br /> 
      </b> ($total2 Review$plural):";  } 

//$average_for Stars //replace $total2 Reviews with $average_for Stars if you'd like to show the average rating instead of the total number of reviews
?> 
      </font> 
      <?php 		 $display = ($average/5)*100; ?>
              <div class="rating_bar">
                <div style="width:<?php echo "$display"; ?>%"></div>
              </div>
    </td> 
    <td><?php echo stripslashes($item_desc); ?><BR><BR><BR></td> 
  </tr> 
  <?php } ?> 
</table> 
<div align="center"><BR>
    <span class="style1">Didn't see the Item you would like to review? <a href="user_add_item.php?<?php echo htmlspecialchars(SID); ?>">Suggest</a> one! </span><BR>
  <BR>
  <?php
 // output paging system (could also do it before we output the page content) 
    if ($page == 1) // this is the first page - there is no previous page 
        echo "";  
    else            // not the first page, link to the previous page 
        echo "<a href=\"demo.php?page=" . ($page - 1) . "&limit=$limit\">Previous</a> | ";  

    for ($i = 1; $i <= $pager->numPages; $i++) {  
        if ($i > 1) echo " | ";   
        if ($i == $pager->page)  
            echo "Page $i";  
        else  
            echo "<a href=\"demo.php?page=$i&limit=$limit\">Page $i</a>";  
    }  

    if ($page == $pager->numPages) // this is the last page - there is no next page 
        echo "";  
    else            // not the last page, link to the next page 
        echo " | <a href=\"demo.php?page=" . ($page + 1) . "&limit=$limit\">Next</a>";  

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
  
</div>
