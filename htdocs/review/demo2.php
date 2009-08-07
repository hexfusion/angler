<?php  
session_start();

include ('body.php');
include ('functions.php');
include ('f_secure.php');
include ('config.php');

// get the pager input values 
$page=1;
if(isset($_GET['page']) && $_GET['page']!="")$page=$_GET['page'];

if (isset($_POST['limit'])) {
    $limit = $_POST['limit'];
	$_SESSION['limit']=$_POST['limit'];  
} elseif (isset($_GET['limit'])) {
	$limit = $_GET['limit']; 
	$_SESSION['limit']=$_GET['limit'];
}elseif(isset($_SESSION['limit']) && $_SESSION['limit']!=""){
	$limit=$_SESSION['limit'];
}else {
	$limit = 5;
}

$result = mysql_query("select count(*) from products");  
$total = mysql_result($result, 0, 0);  

// work out the pager values 

$offset = ($page-1) * $limit;  
$stop  =$limit;  


// use pager values to fetch data 
/*
$query = "SELECT products.description, 
       products.item_id,
	   products.comment,
	   products.prod_group,  
       products.sort_order, 
       avg(review.rating) AS average
FROM products left join review
  on review.review_item_id = products.item_id
GROUP BY products.description, 
       products.item_id,  
       products.sort_order
ORDER BY case when products.sort_order = 0 then 1 else 0 end,
         products.sort_order,
         average DESC limit $offset, $stop"; 
		*/ 
		
		
		$default_sort = 'description';

$allowed_sort = array ('description','average','rating','source','review','summary','score');
/* if sort is not set, or it is not in the allowed list, then set it to a default value. Otherwise, set it to what was passed in. */
if (!isset ($_GET['sort']) ||!in_array ($_GET['sort'], $allowed_sort)) {
    $sort = $default_sort;
} else {
    $sort = $_GET['sort'];
}

$order = @$_GET['order'];

if ($order == "") {
	$order= "ASC";
}


		$query = "SELECT products.description, 
       products.item_id,
	   products.comment,
	   products.prod_group,  
       products.sort_order, 
       avg(review.rating) AS average,
	   count(review.rating) as totalf
FROM products left join review
  on review.review_item_id = products.item_id
GROUP BY products.description, 
       products.item_id,  
       products.sort_order
order by $sort $order limit $offset, $stop"; 
		 
		  
		 
$result = mysql_query($query) or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));


//Display the header
BodyHeader("Five Star Review Demo - $sitename","","");
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
  <p class="style1">How many review items would you like to show on each page? </p>
  <form action="<?php $_SERVER['PHP_SELF']; ?>?<?php echo htmlspecialchars(SID); ?>" method="post" name="form1" id="form1">
    <select name="limit">
      <option value="5" selected="selected">5</option>
      <option value="10" <?php if ($limit==10) echo "selected" ?>>10</option>
      <option value="15" <?php if ($limit==15) echo "selected" ?>>15</option>
      <option value="20" <?php if ($limit==20) echo "selected" ?>>20</option>
      <option value="25" <?php if ($limit==25) echo "selected" ?>>25</option>
      <option value="30" <?php if ($limit==30) echo "selected" ?>>30</option>
      <option value="35" <?php if ($limit==35) echo "selected" ?>>35</option>
      <option value="40" <?php if ($limit==40) echo "selected" ?>>40</option>
      <option value="45" <?php if ($limit==45) echo "selected" ?>>45</option>
      <option value="50" <?php if ($limit==50) echo "selected" ?>>50</option>
      <option value="100" <?php if ($limit==100) echo "selected" ?>>100</option>
    </select>
    <input type="submit" name="Submit" value="Show Me" />
  </form>
</div>

<table width="80%" border="1" align="center" cellpadding="3" cellspacing="0" bordercolor="#CCCCCC">
  <tr>
    <td><div align="left"><strong><b><a href="<?php echo $_SERVER['PHP_SELF']; ?>?pg=<?php echo @$pg?>&limit=<?php echo @$limit?>&sort=description&order=<?php if ($_GET['order'] == "DESC") { 
echo "ASC"; 
} elseif ($_GET['order'] == "ASC") { 
echo "DESC"; 
}  ?>">Program Name</a>
            <?php if ($sort=="description") { if ($_GET['order'] == "DESC") { 
echo " <img src=\"$directory/images/sort_down.gif\">"; } elseif ($_GET['order'] == "ASC") { 
echo " <img src=\"$directory/images/sort_up.gif\">"; } }
?>
        </strong></div></td>
    <td><div align="center"><strong>
            <b><a href="<?php echo $_SERVER['PHP_SELF']; ?>?pg=<?php echo @$pg?>&limit=<?php echo @$limit?>&sort=average&order=<?php if ($_GET['order'] == "DESC") { 
echo "ASC"; 
} elseif ($_GET['order'] == "ASC") { 
echo "DESC"; 
}  ?>">Rating</a>
            <?php if ($sort=="average") { if ($_GET['order'] == "DESC") { 
echo " <img src=\"$directory/images/sort_down.gif\">"; } elseif ($_GET['order'] == "ASC") { 
echo " <img src=\"$directory/images/sort_up.gif\">"; } }
?>
        </strong></div></td>
    <td><div align="center"><strong><b><a href="<?php echo $_SERVER['PHP_SELF']; ?>?pg=<?php echo @$pg?>&amp;limit=<?php echo @$limit?>&amp;sort=totalf&amp;order=<?php if ($_GET['order'] == "DESC") { 
echo "ASC"; 
} elseif ($_GET['order'] == "ASC") { 
echo "DESC"; 
}  ?>">Total Reviews</a>
          <?php if ($sort=="totalf") { if ($_GET['order'] == "DESC") { 
echo " <img src=\"$directory/images/sort_down.gif\">"; } elseif ($_GET['order'] == "ASC") { 
echo " <img src=\"$directory/images/sort_up.gif\">"; } }
?>
    </strong></div></td>
    <td><div align="center"><strong>Read &amp; Submit Reviews</strong></div></td>
  </tr>
  <?php 
while ($row = mysql_fetch_array($result)) { 
	$description = stripslashes($row["description"]);
	$item_id = $row["item_id"];
	$comment = stripslashes($row["comment"]);
	$prod_group = stripslashes($row["prod_group"]);

$sql_avg = "select avg(rating) as average from review WHERE  rating !='' AND review.review_item_id = $item_id AND approve = 'y' order by average";


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
    <td><div align="left"><?php echo "$description"; ?> </div></td>
    <td><div align="center">
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

?>
      </div></td>
    <td><div align="center"><?php echo $total2 ?> </div></td>
    <td><div align="center"><a href="<?php echo "$directory/index2.php?item_id=$item_id"; ?>">Read &amp; Submit Reviews</a></div></td>
  </tr>
  <?php } ?>
</table>
<div align="center">
<br />
<span class="style1">Didn't see the Item you would like to review? <a href="user_add_item.php?<?php echo htmlspecialchars(SID); ?>">Suggest</a> one! </span><br />
<br />
<?php

$nextPage=cPaging($total/$limit,$page,$limit,$NumPages);
echo $nextPage;

function cPaging($count,$start,$pageSize,$displayPages){
	global  $PHP_SELF,$item_id,$sort,$order,$PHPSESSID;
	$tempStr="";
	$flag=1;
	$center=$start+($displayPages>>1);
	if($center < 0) $center=0;
	for($i=1;$i<=$count;$i++){
		
		if($i<=($displayPages>>1) && $i>=$start-($displayPages>>1) ){
				if($i==$start){
					$tempStr .= " | $i";		
				}else{
					$tempStr .= " | <a href='$PHP_SELF?page=" . $i ."&PHPSESSID=$PHPSESSID&amp;sort=$sort&order=$order'>$i</a> ";		
				}
				$flag++;		
			
		}elseif($flag<=$displayPages && ($i>=$start-($displayPages>>1) && $i<=$start+($displayPages>>1))){
				if($i==$start){
					$tempStr .= " | $i";		
				}else{
					$tempStr .= " | <a href='$PHP_SELF?page=" . $i ."&PHPSESSID=$PHPSESSID&amp;sort=$sort&order=$order'>$i</a> ";		
				}
				$flag++;		
		}elseif($flag<=$displayPages && $i>=$start-($displayPages>>1)){
				if($i==$start){
					$tempStr .= " | $i";		
				}else{
					$tempStr .= " | <a href='$PHP_SELF?page=" . $i ."&PHPSESSID=$PHPSESSID&amp;sort=$sort&order=$order'>$i</a> ";		
				}
				$flag++;		
		
		}elseif($i==1){
			if($i==$start){
				$tempStr.="  $i";			
			}else{
				$tempStr .= "  <a href='$PHP_SELF?page=" . $i ."&PHPSESSID=$PHPSESSID&amp;sort=$sort&order=$order'>[$i ....]</a>&nbsp;&nbsp;";			
			}
			
		}	elseif($i==$count){
			if($i==$start){
				$tempStr.=" | $i";			
			}else{
				$tempStr .= " | &nbsp;&nbsp;<a href='$PHP_SELF?page=" . $i ."&PHPSESSID=$PHPSESSID&amp;sort=$sort&order=$order'>[$i ....]</a> ";			
			}
		}		
	}			
	//calculate previous next
	if($start < $count){
				$tempStr .= " &nbsp;&nbsp;&nbsp;&nbsp;<a href='$PHP_SELF?page=" . ($start+1) ."&PHPSESSID=$PHPSESSID&amp;sort=$sort&order=$order'><b>Next</b></a> ";
	}
	
	if($start > 1){
				$tempStr .= " &nbsp;&nbsp;&nbsp;&nbsp;<a href='$PHP_SELF?page=" . ($start-1) ."&PHPSESSID=$PHPSESSID&amp;sort=$sort&order=$order'><b>Prev</b></a> ";	}
	return $tempStr;
}


BodyFooter();
?>