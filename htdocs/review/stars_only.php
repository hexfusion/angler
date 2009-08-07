<?php  
session_start();

include_once ("body.php");
include_once ("functions.php");
include_once ("f_secure.php");
include_once ("config.php");


$NumReviews = 1;  //Choose how many reviews per page to display

$NumPages=5;	//Choose how many pages to be displayed in pagination

// get the pager input values 
$page=1;
if(isset($_GET['page']) && $_GET['page']!="")

$page=$_GET['page'];
$page = htmlspecialchars(makeStringSafe($page));


if (!is_numeric($page)) {
echo "invalid page";
exit;
}

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


$limit = htmlspecialchars(makeStringSafe($limit));

if (!is_numeric($limit)) {
echo "invalid item_id";
exit;
}

$result = mysql_query("select count(*) from review_items  where item_name != '' ");  
$total = mysql_result($result, 0, 0);  

// work out the pager values 

$offset = ($page-1) * $limit;  
$stop  =$limit;  


// use pager values to fetch data 

$query = "select * from review_items where item_name != '' order by sortorder ASC limit $offset, $stop"; 
$result = mysql_query($query) or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));


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
<table width="99%" border="0" align="center" cellpadding="3" cellspacing="0"> 

  <?php 
while ($row = mysql_fetch_array($result)) { 
	$item_name = stripslashes($row["item_name"]);
	$item_id = $row["item_id"];
	$item_desc = stripslashes($row["item_desc"]);
	$item_type = stripslashes($row["item_type"]);

$sql_avg = "select avg(rating) as average from review, review_items WHERE  rating !='' AND rating != '0' AND review.review_item_id = '" . makeStringSafe($item_id) . "' AND approve = 'y' AND review_items.item_name != ''";

			$sql_result_avg = mysql_query($sql_avg)
		or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));

//Get total number of reviews for each item.
$result2 = mysql_query("select count(*) from review WHERE  rating !='' AND review.review_item_id = '" . makeStringSafe($item_id) . "' AND approve = 'y'");  
    $total2 = mysql_result($result2, 0, 0);  


while ($row2 = mysql_fetch_array($sql_result_avg)) { 
 extract($row2);
}

?> 
  <tr> 
    <td><a href="<?php echo "$directory"; ?>/index2.php?item_id=<?php echo "$item_id"; ?>"><?php echo htmlspecialchars(stripslashes($item_name)); ?></a>
	<?php //echo "<a href=$directory/index2.php?item_id=$item_id>'" . htmlspecialchars(stripslashes($item_name)) . "'</a>"; ?>
<br />      
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
       <?php   		 $display = ($average/5)*100; ?>
              <div class="rating_bar">
                <div style="width:<?php echo "$display"; ?>%"></div>
              </div>  </td> 
  </tr> 
  <?php } ?> 
</table> 
<div align="center"><BR>
<BR>
  <BR>
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
					$tempStr .= " | <a href='$PHP_SELF?page=" . $i ."&PHPSESSID=$PHPSESSID'>$i</a> ";		
				}
				$flag++;		
			
		}elseif($flag<=$displayPages && ($i>=$start-($displayPages>>1) && $i<=$start+($displayPages>>1))){
				if($i==$start){
					$tempStr .= " | $i";		
				}else{
					$tempStr .= " | <a href='$PHP_SELF?page=" . $i ."&PHPSESSID=$PHPSESSID'>$i</a> ";		
				}
				$flag++;		
		}elseif($flag<=$displayPages && $i>=$start-($displayPages>>1)){
				if($i==$start){
					$tempStr .= " | $i";		
				}else{
					$tempStr .= " | <a href='$PHP_SELF?page=" . $i ."&PHPSESSID=$PHPSESSID'>$i</a> ";		
				}
				$flag++;		
		
		}elseif($i==1){
			if($i==$start){
				$tempStr.="  $i";			
			}else{
				$tempStr .= "  <a href='$PHP_SELF?page=" . $i ."&PHPSESSID=$PHPSESSID'>[$i ....]</a>&nbsp;&nbsp;";			
			}
			
		}	elseif($i==$count){
			if($i==$start){
				$tempStr.=" | $i";			
			}else{
				$tempStr .= " | &nbsp;&nbsp;<a href='$PHP_SELF?page=" . $i ."&PHPSESSID=$PHPSESSID'>[$i ....]</a> ";			
			}
		}		
	}			
	//calculate previous next
	if($start < $count){
				$tempStr .= " &nbsp;&nbsp;&nbsp;&nbsp;<a href='$PHP_SELF?page=" . ($start+1) ."&PHPSESSID=$PHPSESSID'><b>Next</b></a> ";
	}
	
	if($start > 1){
				$tempStr .= " &nbsp;&nbsp;&nbsp;&nbsp;<a href='$PHP_SELF?page=" . ($start-1) ."&PHPSESSID=$PHPSESSID'><b>Prev</b></a> ";	}
	return $tempStr;
}


?> 
