<?php
session_start();

$sort = "";
$item_id = "";
$order = "";
$build = "";

                
include ("body.php");
include ("functions.php");
include ("f_secure.php");
include ("config.php");

$item_id = htmlspecialchars($_GET[item_id], ENT_QUOTES);
$item_id = makeStringSafe($_GET['item_id']);

if(!is_numeric($item_id)) {
BodyHeader("Invalid item","","");
echo "The item you are attempting to view is not valid.";
BodyFooter();
exit;
}
$build = "review.rating >= 0";

$sort = @makeStringSafe($_GET['sort']);

if ($sort == "newest") {
	$sort = "review.id";
	$order = "DESC";
}elseif ($sort == "oldest") {
	$sort = "review.id";
	$order = "ASC";
}elseif ($sort == "highrating") {
	$sort = "review.rating";
	$order = "DESC";
}elseif ($sort == "lowrating") {
	$sort = "review.rating";
	$order = "ASC";
}elseif ($sort == "useful") {
	$sort = "review.useful";
	$order = "DESC";
}elseif ($sort == "notuseful") {
	$sort = "review.notuseful";
	$order = "DESC";
}elseif ($sort == "1") {
	$build = "rating = 1";
	$sort = "review.date_added";
	$order = "DESC";
}elseif ($sort == "2") {
	$build = "rating = 2";
	$sort = "review.date_added";
	$order = "DESC";
}elseif ($sort == "3") {
 $build = "rating = 3";
 $sort = "review.date_added";
 $order = "DESC";
}elseif ($sort == "4") {
	$build = "rating = 4";
	$sort = "review.date_added";
	$order = "DESC";
}elseif ($sort == "5") {
	$build = "review.rating = 5";
	$sort = "review.date_added";
	$order = "DESC";
}else {
	$sort = "review.id";
	$order = "DESC";
}

$sel_com = "select *
from products
left join review
on (review.review_item_id = products.item_id)
where
review.review_item_id='" . makeStringSafe($item_id) . "' AND approve = 'y'
AND $build
ORDER BY $sort $order
";

$AllReviews = array();
$sResult = mysql_query("$sel_com");
$sRows = mysql_numrows( $sResult );

while($row = mysql_fetch_array($sResult)) {
	array_push($AllReviews,"$row[item_id]|$row[description]|$row[comment]|$row[prod_group]|$row[id]|$row[rating]|$row[summary]|$row[review]|$row[source]|$row[location]|$row[review_item_id]|$row[visitorIP]|$row[date_added]|$row[useful]|$row[notuseful]|$row[approve]|$row[sig_show]|$row[username]|$row[user_image]|");
} //while

//$row[item_aff_url]|$row[item_aff_txt]|$row[item_aff_code]|
//select the item_id to be displayed in case there are no reviews that have been written.
$sel_com2 = "select description, comment, prod_group, image, item_aff_url, item_aff_txt, item_aff_code from products where item_id = '$item_id'";

$sResult2 = mysql_query("$sel_com2") or die(sprintf("Couldn't execute sql_count, %s: %s", db_errno(), db_error()));

while($row2 = mysql_fetch_array($sResult2)) {
	$item_name2 = stripslashes($row2['description']);
	$item_type2 = stripslashes($row2['prod_group']);
	$item_desc2 = stripslashes($row2['comment']);
	$image = stripslashes($row2['image']);
	$item_aff_url = stripslashes($row2['item_aff_url']);
	$item_aff_txt = stripslashes($row2['item_aff_txt']);
	$item_aff_code = stripslashes($row2['item_aff_code']);
} //while

//Set the number of reviews to display per page in the /info/functions.php file
$lastReview = sizeof($AllReviews);
if(isset($_GET['view']) && $_GET['view']=="all")$NumReviews=$lastReview;
$pg = @$_REQUEST['pg'];

if($pg > 1) {
	$start = ($pg-1)*$NumReviews;
	$npg = $pg + 1;
}else{
	$start = 0;
	$pg = 1; $npg = 2;
}

if (isset($_GET['sort'])) {
	$sort = $_GET['sort'];
}
$PHP_SELF = $_SERVER['PHP_SELF'];
$allPages = ceil($lastReview/$NumReviews);
$stop = $start + $NumReviews;
if($stop >= $lastReview) { $stop = $lastReview; }
if(!$exit1) {exit;}

$nextPage = "";
for($j=1;$j<=$allPages;$j++) {

	if($pg == $j) {
		$nextPage .= " | $j ";
	}else {
		$nextPage .= " | <a href=\"$PHP_SELF?pg=$j&item_id=$item_id&sort=$sort&order=$order&PHPSESSID=$PHPSESSID\">$j</a> ";
	}

} //for

$p=1;
if(isset($_GET['pg']) && $_GET['pg']!="") $p = $_GET['pg'];

$nextPage=cPaging($allPages,$p,$NumReviews,$NumPages);

if(isset($_GET['pg_orig']) && $_GET['pg_orig']>=1)$nextPage.=" | <a href='$PHP_SELF?pg=" .$_GET['pg_orig'] ."&item_id=$item_id&sort=$sort&order=$order&PHPSESSID=$PHPSESSID'>Back To Paged View</a>";

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
					$tempStr .= " | <a href='$PHP_SELF?pg=" . $i ."&item_id=$item_id&sort=$sort&order=$order&PHPSESSID=$PHPSESSID'>$i</a> ";
				}
				$flag++;

		}elseif($flag<=$displayPages && ($i>=$start-($displayPages>>1) && $i<=$start+($displayPages>>1))){
				if($i==$start){
					$tempStr .= " | $i";
				}else{
					$tempStr .= " | <a href='$PHP_SELF?pg=" . $i ."&item_id=$item_id&sort=$sort&order=$order&PHPSESSID=$PHPSESSID'>$i</a> ";
				}
				$flag++;
		}elseif($flag<=$displayPages && $i>=$start-($displayPages>>1)){
				if($i==$start){
					$tempStr .= " | $i";
				}else{
					$tempStr .= " | <a href='$PHP_SELF?pg=" . $i ."&item_id=$item_id&sort=$sort&order=$order&PHPSESSID=$PHPSESSID'>$i</a> ";
				}
				$flag++;

		}elseif($i==1){
			if($i==$start){
				$tempStr.="  $i";
			}else{
				$tempStr .= "  <a href='$PHP_SELF?pg=" . $i ."&item_id=$item_id&sort=$sort&order=$order&PHPSESSID=$PHPSESSID'>[$i ....]</a>&nbsp;&nbsp;";
			}

		}	elseif($i==$count){
			if($i==$start){
				$tempStr.=" | $i";
			}else{
				$tempStr .= " | &nbsp;&nbsp;<a href='$PHP_SELF?pg=" . $i ."&item_id=$item_id&sort=$sort&order=$order&PHPSESSID=$PHPSESSID'>[$i ....]</a> ";
			}
		}
	}
	//calculate previous next
	if($start < $count){
				$tempStr .= " &nbsp;&nbsp;&nbsp;&nbsp;<a href='$PHP_SELF?pg=" . ($start+1) ."&item_id=$item_id&sort=$sort&order=$order&PHPSESSID=$PHPSESSID'><b>Next</b></a> ";
	}

	if($start > 1){
				$tempStr .= " &nbsp;&nbsp;&nbsp;&nbsp;<a href='$PHP_SELF?pg=" . ($start-1) ."&item_id=$item_id&sort=$sort&order=$order&PHPSESSID=$PHPSESSID'><b>Prev</b></a> ";	}
	return $tempStr;
}

if ($lastReview != "0") {
	$nextPage .= " |";
}

////////////////////////////////////////////////////////////////////
//set variable names.
for ($i = 0; $i < 1; $i++) {
	@list($item_id2, $description, $comment, $prod_group, $id, $rating, $summary, $review, $source, $location, $review_item_id, $visitorIP, $date_added, $useful, $notuseful, $approve, $sig_show, $username, $user_image) = split("\|",$AllReviews[$i]);
}

//query to count number of people that have rated.
$sql_count = "SELECT COUNT(*) as total FROM review WHERE rating != '' and review.review_item_id = '$item_id' AND approve = 'y'";
$sql_result_count = mysql_query($sql_count)	or die(sprintf("Couldn't execute sql_count, %s: %s", db_errno(), db_error()));

$rows = mysql_fetch_array($sql_result_count);
$total = $rows["total"];

//find average of reviews
$sql_avg = "select avg(rating) as average from review WHERE  rating !='' AND review.review_item_id = '$item_id' AND approve = 'y'";
$sql_result_avg = mysql_query($sql_avg)	or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));

while ( $row_avg = mysql_fetch_array($sql_result_avg) ) {
	$average = $row_avg["average"];
}

//if there is not a review must assign a value
if ($description == "") { $description = $item_name2; }
if ($comment == "") { $comment = $item_desc2; }


$item_desc_meta = strip_tags($comment);
$item_name_meta = stripslashes($description);

?>
<style type="text/css">
<!--
.style1 {color: #FF9900}
.style5 {font-size: 12px; color: #333333; }
-->
</style>


<table width="296"  border="1" bordercolor="#000000" align="center" cellpadding="6" cellspacing="0">
  <tr>
    <link href="<?php echo "$directory"; ?>/javascript/style_notes.css" rel="stylesheet" type="text/css" />
    <td width="280" valign="top"><script language="JavaScript" src="<?php echo "$directory"; ?>/javascript/review_httpxml.js" type="text/javascript"></script>
      <script language="JavaScript" src="<?php echo "$directory"; ?>/javascript/review_note.js" type="text/javascript"></script>
      <script language="JavaScript1.2" src="<?php echo "$directory"; ?>/javascript/review_move.js" type="text/javascript"></script>


      <table width="118">
        <tr>
          <td width="104"><b class="index2-Orange style1">Overall Rating </b></td>
        </tr>
      </table>
      <?php
		$flag1=0;
		$queryReview1="SELECT a.criteriaId,a.criteriaName,b.ratingValue FROM rating_criteria a,rating_details b,item_rating_criteria c,review d WHERE a.criteriaId=b.criteriaId AND c.item_id=d.review_item_id AND b.review_id=d.id AND a.criteriaId=c.criteriaId AND b.review_id=" . $id;
		$resultReview1=mysql_query($queryReview1) or die(mysql_error());
		if(mysql_num_rows($resultReview1)>0){
		?>
      <?php
		$flag=0;
		$str="";
		$totalOutter=0;
		$countOutter=0;
		$queryR="SELECT a.criteriaId,a.criteriaName FROM rating_criteria a,item_rating_criteria b WHERE a.criteriaId=b.criteriaId AND item_id=" . makeStringSafe($_GET["item_id"]);
		$resultR=mysql_query($queryR) or die(mysql_error());
		while($rowR=mysql_fetch_array($resultR)){
			//we have criteria id, now get review
			$flag=1;
			$cId=$rowR["criteriaId"];
			$cName=$rowR["criteriaName"];

			//now get ratings where criteriaid is $cId
			$queryInner="SELECT a.ratingValue FROM rating_details a,review b WHERE a.review_id=b.id AND b.review_item_id=" .$_GET["item_id"] ." AND a.criteriaId=" .$cId;
			$resultInner=mysql_query($queryInner) or die(mysql_error());
			$totalInner=0;
			$countInner=0;
			while($rowInner=mysql_fetch_array($resultInner)){
				$totalInner+=$rowInner["ratingValue"];
				$countInner++;
				$totalOutter+=$rowInner["ratingValue"];
				$countOutter++;

			}
			$st="";
			if($totalInner>0){
				if($totalInner%$countInner==0){
					for($i=1;$i<=$totalInner/$countInner;$i++){
						$st.="<img src=\"$directory/images/orange.gif\" alt=\"Orange Star\" title=\"Orange Star\" \>";
					}
					for($j=$totalInner/$countInner;$j<5;$j++){
						$st.="<img src=\"$directory/images/gray.gif\" alt=\"Gray Star\" title=\"Gray Star\" \>";
					}
				}else{
					$ct=$totalInner/$countInner;
					for($i=1;$i<=$ct;$i++){
						$st.="<img src=\"$directory/images/orange.gif\" alt=\"Orange Star\" title=\"Orange Star\" \>";
					}
					if($ct<5){
						$st.="<img src=\"$directory/images/grayorange.gif\" alt=\"Gray Orange Star\" title=\"Gray Orange Star\" \>";
						$ct++;
					}
					if($ct<5){
						for($j=$ct;$j<5;$j++){
							$st.="<img src=\"$directory/images/gray.gif\" alt=\"Gray Star\" title=\"Gray Star\" \>";
						}
					}
				}
			}else{
				$st="";
			}
			if($totalInner > 0){
					$str.="<tr><td class=index2-small-bold><strong>$cName</strong></td><td class=index2-small-bold>&nbsp;&nbsp;$st</td></tr>";
			}else{
					$str.="<tr><td class=index2-small-bold><strong>$cName</strong></td><td class=index2-small-bold><strong>&nbsp;&nbsp;N/A</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td></tr>";
			}

		}

	if($flag==1){
		$st="";
		$str1="";
		if($totalOutter>0){
			if($totalOutter%$countOutter==0){
				for($i=1;$i<=$totalOutter/$countOutter;$i++){
					$st.="<img src=\"$directory/images/orange.gif\" alt=\"Orange Star\" title=\"Orange Star\" \>";
				}
				for($j=$totalOutter/$countOutter;$j<5;$j++){
					$st.="<img src=\"$directory/images/gray.gif\" alt=\"Gray Star\" title=\"Gray Star\" \>";
				}
			}else{
				$ct=$totalOutter/$countOutter;
				for($i=1;$i<=$ct;$i++){
					$st.="<img src=\"$directory/images/orange.gif\" alt=\"Orange Star\" title=\"Orange Star\" \>";
				}
				if($ct<5){
					$st.="<img src=\"$directory/images/grayorange.gif\" alt=\"Gray Orange Star\" title=\"Gray Orange Star\" \>";
					$ct++;
				}
				if($ct<5){
					for($j=$ct;$j<5;$j++){
						$st.="<img src=\"$directory/images/gray.gif\" alt=\"Gray Star\" title=\"Gray Star\" \>";
					}
				}
			}
		}else{
			$st="";
		}
		if($totalOutter > 0){
			$str1="<tr><td class=index2-small-bold><strong>Average Rating:[".number_format($totalOutter/$countOutter, 2, '.', '')."]</strong></td><td class=index2-small-bold>&nbsp;&nbsp;$st</td></tr>";
		}else{
			$str1="<tr><td class=index2-small-bold><strong>Average Rating</strong></td><td class=index2-small-bold><strong>&nbsp;&nbsp;N/A</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td></tr>";
		}


		?>
      <br />
      <table cellspacing="0" cellpadding="0" border="1" bordercolor="orange">
        <tr>
          <td><table  cellspacing="2" cellpadding="2">
              <?php echo "$str"; ?>
              <tr height="1">
                <td colspan="2" bgcolor="black"></td>
              </tr>
              <?php echo "$str1"; ?>
            </table></td>
        </tr>
      </table>
	  <?php echo "$useful of ";
	$total_useful = ($useful + $notuseful); echo "$total_useful";
	?> people found this review helpful
      <br />
      <?php
	}else{
		if ($average >= "1.0") { ?>
      <span class="index2-small-bold"><span class="style5">Avg. Customer <a href="<?php echo "$directory"; ?>/index2.php?item_id=<?php
echo "$item_id"; ?>&amp;back=<?php echo $_SERVER['REQUEST_URI']; ?>&amp;<?php echo htmlspecialchars(SID); ?>">Review</a>
      </span>
      <?php } //if average
		$average_for = sprintf ("%01.1f", $average);
		if ($average <= "5.0") { echo "($average_for Stars):";  } ?>
      </span>
      <?php
		if (($average <= ".49")) {
			echo "<img src=\"$directory/images/stars-0-0.gif\" alt=\"0 Stars\" title=\"0 Stars\" \>";
 		} elseif (($average >= ".50")&& ($average <= ".59")) {
			echo "<img src=\"$directory/images/stars-0-5-.gif\" alt=\"1/2 Star\" title=\"1/2 Star\" \>";
		} elseif (($average >= ".60") && ($average <= "1.44")) {
			echo "<img src=\"$directory/images/stars-1-0-.gif\" alt=\"1 Star\" title=\"1 Star\" \>";
		} elseif (($average >= "1.45") && ($average <= "1.59")) {
			echo "<img src=\"$directory/images/stars-1-5-.gif\" alt=\"1.5 Stars\" title=\"1.5 Stars\" \>";
		} elseif (($average >= "1.60") && ($average <= "2.44")) {
			echo "<img src=\"$directory/images/stars-2-0-.gif\" alt=\"2 Stars\" title=\"2 Stars\" \>";
		} elseif (($average >= "2.45") && ($average <= "2.59")) {
			echo "<img src=\"$directory/images/stars-2-5-.gif\" alt=\"2.5 Stars\" title=\"2.5 Stars\" \>";
		} elseif (($average >= "2.60") && ($average <= "3.44")) {
			echo "<img src=\"$directory/images/stars-3-0-.gif\" alt=\"3 Stars\" title=\"3 Stars\" \>";
		} elseif (($average >= "3.45") && ($average <= "3.59")) {
			echo "<img src=\"$directory/images/stars-3-5-.gif\" alt=\"3.5 Stars\" title=\"3.5 Stars\" \>";
		} elseif (($average >= "3.60") && ($average <= "4.44")) {
			echo "<img src=\"$directory/images/stars-4-0-.gif\" alt=\"4 Stars\" title=\"4 Stars\" \>";
		} elseif (($average >= "4.45") && ($average <= "4.59")) {
			echo "<img src=\"$directory/images/stars-4-5-.gif\" alt=\"4.5 Stars\" title=\"4.5 Stars\" \>";
		} elseif ($average >= "4.60") {
			echo "<img src=\"$directory/images/stars-5-0-.gif\" alt=\"5 Stars\" title=\"5 Stars\" \>";
		}
	}


	$item_id = makeStringSafe($_GET["item_id"]);

	if(!is_numeric($item_id)) {
		BodyHeader("Invalid item");
		echo "The item you are trying to view is not valid.";
		BodyFooter();
		exit;
	}

$back = $_SERVER['PHP_SELF'];
?>
      <br />
      <span class="index2-small-bold"><span class="style5">Number of <a href="<?php echo "$directory"; ?>/index2.php?item_id=<?php
echo "$item_id"; ?>&amp;back=<?php echo $_SERVER['REQUEST_URI']; ?>&amp;<?php echo htmlspecialchars(SID); ?>">Reviews</a>:</span> <?php echo "$total"; ?> </span><br />
      <br />
      <br />
<div align="center">
<a href="<?php echo "$directory"; ?>/review_form.php?item_id=<?php
echo "$item_id"; ?>&amp;$back=<?php echo $_SERVER['REQUEST_URI']; ?>&amp;<?php echo htmlspecialchars(SID); ?>"><img src="<?php echo "$directory"; ?>/images/write.gif" title="Submit a <?php echo "$item_name2"; ?> review"  width="35" height="35" border="0" /></a><h3><a href="<?php echo "$directory"; ?>/review_form.php?item_id=<?php
echo "$item_id"; ?>&amp;back=<?php echo $_SERVER['REQUEST_URI']; ?>&amp;<?php echo htmlspecialchars(SID); ?>">Submit</a> a <?php echo "$item_name2"; ?> review </h3>  </div>      
<br />
   
        <br />    </td>
 </tr>
</table>
</div>
      <br />
