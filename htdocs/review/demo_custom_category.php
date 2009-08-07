<?php
session_start();

include ('body.php');
include ('functions.php');
include ('f_secure.php');
include ('config.php');

//change this to display only a specific category.
$catuser = "movies";

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

$result = mysql_query("select count(*) from review_items WHERE category = '$catuser'");
$total = mysql_result($result, 0, 0);

// work out the pager values

$offset = ($page-1) * $limit;
$stop  =$limit;


// use pager values to fetch data

$query = "SELECT review_items.item_name,
       review_items.item_id,
	   review_items.item_desc,
	   review_items.item_type,
       review_items.sortorder,
       avg(review.rating) AS average
FROM review_items left join review
  on review.review_item_id = review_items.item_id
  WHERE category = '$catuser'
GROUP BY review_items.item_name,
       review_items.item_id,
       review_items.sortorder
ORDER BY case when review_items.sortorder = 0 then 1 else 0 end,
         review_items.sortorder,
         average DESC limit $offset, $stop";

$result = mysql_query($query) or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));

$ucatuser = ucfirst("$catuser");
//Display the header
BodyHeader("$ucatuser Reviews","","");
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

$sql_avg = "select avg(rating) as average from review WHERE  rating !='' AND review.review_item_id = '" . mysql_real_escape_string($item_id) . "' AND approve = 'y' order by average";


			$sql_result_avg = mysql_query($sql_avg)
		or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));

//Get total number of reviews for each item.
$result2 = mysql_query("select count(*) from review WHERE  rating !='' AND review.review_item_id = '" . mysql_real_escape_string($item_id) . "' AND approve = 'y'");
    $total2 = mysql_result($result2, 0, 0);


while ($row2 = mysql_fetch_array($sql_result_avg)) {
 extract($row2);
}

?>
  <tr valign="middle">
    <td width="88"><a href="index2.php?item_id=<?php echo "$item_id"; ?>&amp;<?php echo strip_tags(SID); ?>"><img src="<?php echo "$directory"; ?>/images/review.gif" hspace="1" vspace="1" border="0"></a></td>
    <td><?php echo stripslashes($item_name); ?><br />
      <?php $average_for = sprintf ("%01.1f", $average);
if ($total2 >= 2) {
$plural = "s";
} else {
$plural = "";
}
if ($average >= "1.0") { echo "<font face=verdana,arial,helvetica size=-2><b>Avg. Customer Review<br />
      </b> ($total2 Review$plural):";  }

?>
      </font>
      <?php   		 $display = ($average/5)*100; ?>
              <div class="rating_bar">
                <div style="width:<?php echo "$display"; ?>%"></div>
              </div>
    </td>
    <td><br />
      <?php echo stripslashes($item_desc); ?><br />
      <br />
      <br />
    </td>
  </tr>
  <?php } ?>
</table>
<div align="center"> <br />
  <span class="style1">Didn't see the Item you would like to review? <a href="user_add_item.php?<?php echo htmlspecialchars(SID); ?>">Suggest</a> one! </span></div>
<br />
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


BodyFooter();
?>
