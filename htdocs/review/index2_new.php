<?php
session_start();

$sort = "";
$item_id = "";
$order = "";
$build = "";

                
include ('body.php');
include ('functions.php');
include ('f_secure.php');
include ('config.php');

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

BodyHeader("Reviews for $item_name_meta", "Reviews for $item_name_meta - $item_desc_meta", "$item_name_meta");
?>
<script type="text/javascript" src="javascript/dhtmlgoodies.js"></script>
<script type="text/javascript">
window.onload = initShowHideDivs;
</script>
<link href="./pm/pm.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="./pm/js/virtualpaginate.js"></script>
<!-- enable this to make editing this page easier <link href="review.css" rel="stylesheet" type="text/css" /> -->
<?php
function getCategoryName($catId){
	$sql='Select * from review_category_list where cat_id_cloud = ' . $catId;
	$sql_result = mysql_query($sql);
	while ($row = mysql_fetch_array($sql_result)) {
		return stripslashes($row["category"]);	
	}	
}

$id="";
if(isset($_GET['item_id']) && $_GET['item_id']!=""){
	$id=$_GET['item_id'];
}

if($id!=""){
	$query="SELECT category FROM products WHERE item_id='" . mysql_real_escape_string($id) . "'
";
	$rs=mysql_query($query) or die(mysql_error());
	if(mysql_num_rows($rs)> 0){
		$rw=mysql_fetch_array($rs);
		$bId=$rw["category"];
		$tStr="";
		$tStr=  "<a href='review_categories_yahoo_cats2.php?PHPSESSID=$PHPSESSID&amp;category=" . urlencode($bId) ."'> " .getCategoryName($bId) ."</a>";
		$tStr.=displayCat($bId);
		$tStr="<a href='review_categories_yahoo_cats2.php?PHPSESSID=$PHPSESSID'>Start</a>" . "&nbsp;&lt;&lt;&nbsp;". $tStr;
		echo $tStr;
	}
}

function displayCat($catId){
global $tStr;
$st="";
if($catId=="") return;
$query="Select parent_id from review_category_list where cat_id_cloud = '" .$catId ."'";

$result=mysql_query($query);

while($row=mysql_fetch_array($result)){
	if($row["parent_id"]!=-1){
		$tStr= "<a href='review_categories_yahoo_cats2.php?PHPSESSID=$PHPSESSID&amp;category=" . urlencode($row["parent_id"]) ."'>" . getCategoryName($row["parent_id"])."</a>&nbsp;&lt;&lt;&nbsp;" .$tStr;
	}
	displayCat($row["parent_id"]);
}
}
?>
<br />
<link href="default.css" rel="stylesheet" type="text/css" />
<br />
<div id="borderbox">
  <table width="100%"  border="0" align="center" cellpadding="6" cellspacing="0" style="borderbox">
    <tr>
      <link href="<?php echo "$directory"; ?>/javascript/style_notes.css" rel="stylesheet" type="text/css" />
      <td valign="top"><div id="reviewnote" style="left:250px;top:180px">
          <table width="250" cellpadding="0" cellspacing="0" border="0">
            <tr>
              <td colspan="2"><img src="<?php echo "$directory"; ?>/images/spacer.gif" width="250" height="5" /></td>
            </tr>
            <tr>
              <td align="right" nowrap="nowrap"><a href="#" onclick="javascript: window.open('<?php echo "$directory"; ?>/help_notes.php', 'notehelp', 'height=300,width=600,toolbar=no,scrollbars=no,resizable=yes,status=no'); return false;"><img
src="<?php echo "$directory"; ?>/images/sticky_questionmark.gif" alt="Review Sticky Notes Help"
border="0" /></a><a onclick="javascript: close_note();"><img src="<?php echo "$directory"; ?>/images/sticky_close.gif" alt="Close" width="17" height="15" border="0" /></a><a onclick="javascript: save_note('<?php echo "$directory"; ?>/save_notes.php');"><img src="<?php echo "$directory"; ?>/images/save.bmp" alt="Save Review" width="18" height="16" border="0" /></a> &nbsp; </td>
            </tr>
            <tr>
              <td colspan="2"><img src="<?php echo "$directory"; ?>/images/spacer.gif" alt="Spacer" width="250" height="15" /> </td>
            </tr>
            <tr>
              <td align="center"><form action="" method="post" name="note" id="note">
                  <input type="hidden" name="action" value="savenote" />
                  <input type="hidden" name="item_id" value="<?php echo $_GET['item_id']; ?>" />
                  <?php if (isset($_SESSION["username_logged"]) && $_SESSION["username_logged"]!=""){?>
                  <input type="hidden" id="user_name" name="user_name" value="<?php echo $_SESSION['username_logged']; ?>" />
                  <?php }else{?>
                  <input type="hidden" id="user_name" name="user_name" value="" />
                  <?php }?>
                  <?php
	$prevNotes="";
	if (isset($_SESSION["username_logged"]) && $_SESSION["username_logged"]!="" && isset($_GET['item_id']) && $_GET['item_id']!=""){
		$queryNotes="SELECT note_notes,note_date FROM products_note WHERE note_user_name='".$_SESSION["username_logged"] ."' AND note_item_id=$item_id";
		$resultNotes=mysql_query($queryNotes) or die(mysql_error());
		if(mysql_num_rows($resultNotes) > 0){
			$prevNotesRow=mysql_fetch_array($resultNotes);
			$prevNotes=stripslashes($prevNotesRow['note_notes']);
		}
	}
?>
                  <textarea onchange="javascript: has_changed=true;" id="notearea" name="notearea" class="sticky" cols="25" rows="7">
<?php echo $prevNotes; ?>

<?php if (isset($_SESSION["username_logged"]) && $_SESSION["username_logged"]!=""){?>
<?php }else{?>
Please login to post notes about this Listing!
<?php }?>
</textarea>
                </form></td>
            </tr>
          </table>
        </div>
        <script language="JavaScript" src="<?php echo "$directory"; ?>/javascript/review_httpxml.js" type="text/javascript"></script>
        <script language="JavaScript" src="<?php echo "$directory"; ?>/javascript/review_note.js" type="text/javascript"></script>
        <script language="JavaScript1.2" src="<?php echo "$directory"; ?>/javascript/review_move.js" type="text/javascript"></script>
        <table>
          <tr>
            <td><b class="index2-Orange"><?php echo "$item_name2"; ?> : User Reviews </b></td>
            <td>&nbsp; &nbsp;<a href="javascript: show_review_note();"><img src="<?php echo "$directory/"; ?>images/sticky-index.png" title="Sticky Review Notes for <?php echo "$item_name2"; ?>" alt="Sticky Review Notes for <?php echo "$item_name2"; ?>" width="16" height="16" border="0" /></a></td>
            <td><b>Notes</b></td>
          </tr>
        </table>
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
        <?php
	}else{
		if ($average >= "1.0") { ?>
        <span class="index2-small-bold">Avg. Customer Review
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
        <span class="index2-small-bold">Number of Reviews: <?php echo "$total"; ?> </span><br />
        <br />
        <span class="index2-small-bold">
        <div class="dhtmlgoodies_question">Click to View Additional Details</div>
        <div class="dhtmlgoodies_answer">
          <div>
            <?php
				///////////////////////////////////////////////////////////////////////////////////////
					
					$sql = "SELECT * FROM products_supplement_data, products_supplement where products_supplement_data.selected = 1 and products_supplement.id = products_supplement_data.item_supplement_id and products_supplement_data.review_item_id = " . $item_id . " order by id asc";
					$sql_result = mysql_query($sql)	or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));
					while ($row = mysql_fetch_array($sql_result)) {
				///////////////////////////////////////////////////////////////////////////////////////
					echo "<br />" . $row['itemname'].":&nbsp;&nbsp;" . $row['value']."<br />";
				}
				///////////////////////////////////////////////////////////////////////////////////////
      ?>
          </div>
        </div>
        </span> <br />
        <span class="index2-Orange">Product Description</span><br />
        <br />
        <?php echo stripslashes($item_desc2); ?><br />
        <br />
        <div align="center"> <a href="<?php echo "$directory"; ?>/review_form.php?item_id=<?php
echo "$item_id"; ?>&amp;$back=<?php echo $_SERVER['REQUEST_URI']; ?>&amp;<?php echo htmlspecialchars(SID); ?>"><img src="<?php echo "$directory"; ?>/images/write.gif" title="Submit a <?php echo "$item_name2"; ?> review"  width="35" height="35" border="0" /></a>
          <h3><a href="<?php echo "$directory"; ?>/review_form.php?item_id=<?php
echo "$item_id"; ?>&amp;back=<?php echo $_SERVER['REQUEST_URI']; ?>&amp;<?php echo htmlspecialchars(SID); ?>">Submit</a> a <?php echo "$item_name2"; ?> review </h3>
        </div></td>
      <td width="200" valign="top"><span class="index2-small">
        <div align="left">
          <!-- ADDTHIS BUTTON BEGIN -->
          <script type="text/javascript">
addthis_pub             = 'mousel'; 
addthis_logo            = 'http://www.review-script.com/images/logo_black.gif';
addthis_logo_background = 'EFEFFF';
addthis_logo_color      = '666699';
addthis_brand           = '<?php echo "$sitename"; ?>';
addthis_options         = 'delicious, digg, email, favorites, facebook, fark, furl, google, live, myweb, myspace, newsvine, reddit, slashdot, stumbleupon, technorati, twitter, more';
</script>
          <a href="http://www.addthis.com/bookmark.php" onMouseOver="return addthis_open(this, '', '[URL]', '[TITLE]')" onMouseOut="addthis_close()" onClick="return addthis_sendto()"><img src="http://s9.addthis.com/button1-share.gif" width="125" height="16" border="0" alt="" /></a>
          <script type="text/javascript" src="http://s7.addthis.com/js/152/addthis_widget.js"></script>
          <!-- ADDTHIS BUTTON END -->
        </div>
        <div align="left"><img src="<?php echo "$directory"; ?>/images/mail.gif" title="Email a Friend about this <?php echo "$item_type2"; ?>" width="16" height="16" border="0" /> <a href="<?php echo "$directory"; ?>/recommend.php?item_id=<?php echo "$item_id"; ?>&amp;<?php echo htmlspecialchars(SID); ?>">Recomend</a> to a friend</div>
        <div align="left"><img src="<?php echo "$directory"; ?>/images/print.gif" title="Printer friendly format" width="16" height="16" border="0" /> <a href="<?php echo "$directory"; ?>/printpage.php?item_id=<?php echo "$item_id"; ?>">Printer</a> friendly format</div>
        <div align="left"><img src="<?php echo "$directory"; ?>/images/save2.gif" title="Printer friendly format" width="16" height="16" border="0" /> <a href="<?php echo "$directory"; ?>/favorites/save.php?item_id=<?php echo "$item_id"; ?>&amp;<?php echo htmlspecialchars(SID); ?>">Save</a> to User Control Panel</div>
        <div align="left"> <img src="<?php echo "$directory"; ?>/images/subscribe.gif" title="Subscribe to <?php echo "$item_name2"; ?> reviews" width="16" height="16" /> <a href="subscriptions/save.php?item_id=<?php echo "$item_id"; ?>&amp;<?php echo htmlspecialchars(SID); ?>">Subscribe</a> to <?php echo stripslashes($description); ?> reviews</div>
        <?php if ($item_aff_url != "") { ?>
        <div align="left"><img src="<?php echo "$directory"; ?>/images/link.gif" title="More info on <?php echo "$item_name2"; ?>" width="16" height="16" /> <a href="<?php echo "$item_aff_url"; ?>" target="_blank"><?php echo "$item_aff_txt"; ?></a></div>
        <?php } elseif ($item_aff_code != "") { ?>
        <div align="left"><img src="<?php echo "$directory"; ?>/images/link.gif" title="More info on <?php echo "$item_name2"; ?>" width="16" height="16" /> <?php echo "$item_aff_code"; ?></div>
        </span>
        <?php } ?>
        <br />
        <?php
	//if there is a music file, show the music icon.

	//if there is an image, resize it and then display it.
	$filename = "images/items/$image";

	$ext = strrchr($filename, ".");
	if ($ext == ".mp3") {
		echo "<a href=$directory/$filename target=_blank ><img src=\"$directory/images/music.gif\" width=\"48\" height=\"48\" border=\"0\" title=\"Click here to listen to $item_name2\"><span class=small>Listen to $item_name2</span></a>";
	} elseif ($image != "") {
		$html="<a href=$directory/$filename target=_blank ><img src=\"$directory/resize_item_image.php?filename=$filename\" border=\"0\" title=\"Click here to see a larger image of $item_name2\"></a>";
		print $html; ?>
        <br />
        <span class="small"><?php echo "<a href=$directory/$filename target=_blank ><img src=\"images/view.png\" title=\"View Larger Image\" width=\"15\" height=\"15\" border=\"0\" />View Larger</a>"; ?></span>
        <?php	} ?>
      </td>
    </tr>
  </table>
</div>
<br />
<span class="index2-Orange">User Reviews</span><br />
<br />
<?php //display page navigation on top
$parsed = parse_url($_SERVER['PHP_SELF']);
$lastpage = $parsed['path'];
//do not display the pagination if there are no reviews
	if ($lastReview != "0"){
	?>
<table width="90%" height="40"  border="0" align="center" cellpadding="0" cellspacing="0" class="index2-pagination">
  <tr>
    <td valign="middle"  class="paginationframe"><form action="<?php echo $_SERVER['PHP_SELF']; ?>?<?php echo htmlspecialchars(SID); ?>&amp;item_id=<?php echo "$item_id"; ?>" method="get" name="form1" id="form1">
        <select name="sort" onchange="this.form.submit()">
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
        <input name="item_id" type="hidden" value="<?php echo "$item_id"; ?>" />
      </form></td>
    <td><div align="left"><font color="#0033CC">Showing
        <?php $begin = $start+1; echo "$begin"; ?>
        to <?php echo "$stop"; ?> of <?php echo "$lastReview"; ?> reviews </font></div></td>
  </tr>
</table>
<br />
<br />
<?php
	} //end pagination check
?>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0"  class="virtualpage3">
  <?php
//end top page navigation

	$item_id = makeStringSafe($_GET["item_id"]);

	if(!is_numeric($item_id)) {
		BodyHeader("Invalid item");
		echo "The item you are trying to view is not valid.";
		BodyFooter();
		exit;
	}


//set variable names.
$tempCount=0;
for ($i = $start; $i < $stop; $i++) {
	list($item_id, $description, $comment, $prod_group, $id, $rating, $summary, $review, $source, $location, $review_item_id, $visitorIP, $date_added, $useful, $notuseful, $approve, $sig_show, $username, $user_image) = split("\|",$AllReviews[$i]);
?>
  <tr class="paginationrow">
    <td valign="top"><span class="index2-summary"><?php echo stripslashes($summary) . ', ';?></span>
      <?php
	$tempdate = explode("-", "$date_added");
	$date_added = mktime(0,0,0,$tempdate[1],$tempdate[2],$tempdate[0]);
	$newdate = date("F d, Y", "$date_added"); ?>
      <span class="index2-small"><?php echo "$newdate"; ?></span> <br />
      <span class="index2-small">Reviewer: <a href="<?php echo "$directory"; ?>/reviewer_about.php?username=<?php echo "$username"; ?>"><?php echo ucfirst(stripslashes($source)); ?></a></b> from <?php echo stripslashes($location); ?> - <a href="<?php echo "$directory"; ?>/reviewer_about.php?username=<?php echo stripslashes($username); ?>">See all reviews by <?php echo ucfirst(stripslashes($source)); ?></a></span><br />
      <br />
      <?
	//print_r($_SESSION);
	//show users signature?
	if($sig_show == "y") {
		$sql = "SELECT sig FROM
			userdb
			WHERE
			username = '" . makeStringSafe($username) . "'";

		$sql_result = mysql_query($sql)	or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));

		while ($row = mysql_fetch_array($sql_result)) {
			$sig = stripslashes($row["sig"]);
		}
	//$_SESSION['sig'] = "$sig";
	}

	if ($use_bbcode == "yes") {
		include("bbcode.php");
  		$sig = @str_replace($bbcode, $htmlcode, $sig);
  		$sig = nl2br($sig);//second pass
  		$review = str_replace($bbcode, $htmlcode, $review);
		$review = nl2br($review);//second pass
	}
	?>
      <table width="70%" border="0" cellspacing="8" cellpadding="8">
        <tr>
          <td width="100" valign="top" nowrap="nowrap"><?php
		$flag1=0;
		$queryReview1="SELECT a.criteriaId,a.criteriaName,b.ratingValue FROM rating_criteria a,rating_details b,item_rating_criteria c,review d WHERE a.criteriaId=b.criteriaId AND c.item_id=d.review_item_id AND b.review_id=d.id AND a.criteriaId=c.criteriaId AND b.review_id=" . $id;
		$resultReview1=mysql_query($queryReview1) or die(mysql_error());
		if(mysql_num_rows($resultReview1)>0){
		?>
            <table cellpadding="0" cellspacing="0" class="index2-criteria-table">
              <tr>
                <td><table  cellspacing="2" cellpadding="2">
                    <?php
					$str1="";
					$flag1=1;
					while($rowReview1=mysql_fetch_array($resultReview1)){
						$st1="";
						$val=$rowReview1["ratingValue"];
						$sName=$rowReview1["criteriaName"];

						if($val>0){

							for($ii=1;$ii<=$val;$ii++){
								$st1.="<img src=\"$directory/images/orange.gif\" alt=\"Orange Star\" title=\"Orange Star\" \>";
							}

							for($jj=$val;$jj<5;$jj++){
								$st1.="<img src=\"$directory/images/gray.gif\" alt=\"Gray Star\" title=\"Gray Star\" \>";
							}
						}else{
							$st1="";
						}

						if($val > 0){
							$str1.="<tr><td class=index2-small-bold>$sName</td><td class=index2-small-bold>&nbsp;&nbsp;$st1</td></tr>";
						}else{
							$str1.="<tr><td class=index2-small-bold>$sName</td><td class=index2-small-bold>&nbsp;&nbsp;N/A&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td></tr>";
						}
					}
					echo $str1;
				?>
                  </table></td>
              </tr>
            </table>
            <?php	}

	   		if($flag1==0){
				if ($rating == ".5") {
					echo "<img src=\"$directory/images/stars-0-5.gif\" alt=\"1/2 Star\" title=\"1/2 Star\" \>";
				} elseif ($rating == "1") {
					echo "<img src=\"$directory/images/stars-1-0.gif\" alt=\"1 Star\" title=\"1 Star\" \>";
				} elseif ($rating == "1.5") {
					echo "<img src=\"$directory/images/stars-1-5.gif\" alt=\"1.5 Stars\" title=\"1.5 Stars\" \>";
				} elseif ($rating == "2") {
					echo "<img src=\"$directory/images/stars-2-0.gif\" alt=\"2 Stars\" title=\"2 Stars\" \>";
				} elseif ($rating == "2.5") {
					echo "<img src=\"$directory/images/stars-2-5.gif\" alt=\"2.5 Stars\" title=\"2.5 Stars\" \>";
				} elseif ($rating == "3") {
					echo "<img src=\"$directory/images/stars-3-0.gif\" alt=\"3 Stars\" title=\"3 Stars\" \>";
				} elseif ($rating == "3.5") {
					echo "<img src=\"$directory/images/stars-3-5.gif\" alt=\"3.5 Stars\"  title=\"3.5 Stars\" \>";
				} elseif ($rating == "4") {
					echo "<img src=\"$directory/images/stars-4-0.gif\" alt=\"4 Stars\" title=\"4 Stars\" \>";
				} elseif ($rating == "4.5") {
					echo "<img src=\"$directory/images/stars-4-5.gif\" alt=\"4.5 Stars\" title=\"4.5 Stars\" \>";
				} elseif ($rating == "5") {
					echo "<img src=\"$directory/images/stars-5-0.gif\" alt=\"5 Stars\" title=\"5 Stars\" \>";
				}
 		}
	 ?>
          </td>
          <td align="left" width="100%" valign="top"><div align="left"><span class="index2-small-bold"><?php echo "$useful of ";
	$total_useful = ($useful + $notuseful); echo "$total_useful";
	?> people found this review helpful</span><br />
              <br />
              <?php
			  //show review
			  echo stripslashes($review);

			  //show signature
			  if($sig_show == "y") { echo "<br /><br />__________________<br />$sig"; } ?>
            </div>
            <form action="<?php echo "$directory"; ?>/useful.php?item_id=<?php echo "$item_id"; ?>" method="post">
              <input name="id" type="hidden" value="<?php echo "$id"; ?>" />
              <input name="item_id" type="hidden" value="<?php echo "$item_id"; ?>" />
              <input name="description" type="hidden" value="<?php echo "$item_name2"; ?>" />
              <br />
              <span class="index2-small-bold">Was this review helpful to you?&nbsp;&nbsp;</span>
              <input type="image" value="1" name="useful" src="<?php echo "$directory"; ?>/images/yes.gif" border="0" width="34" height="16" align="middle" />
              <input type="image" value="1" name="notuseful" src="<?php echo "$directory"; ?>/images/no.gif" border="0" width="34" height="16" align="middle" />
              <br />
              <span class="index2-small"><a href="./comments/review_comments.php?item_id=<?php echo "$item_id"; ?>&amp;review_id=<?php echo "$id"; ?>&amp;<?php echo htmlspecialchars(SID); ?>">Comments</a> | (<a href="<?php echo "$directory"; ?>/report.php?id=<?php echo "$id"; ?>&amp;item_id=<?php echo "$item_id"; ?>&amp;<?php echo htmlspecialchars(SID); ?>">Report this</a>) </span><br />
              <div class="index2-underline"></div>
              <br />
              <br />
            </form>
            </div></td>
          <td align="left" width="100%" valign="top"><div align="center">
              <?php
		  if ($user_upload == "y") {

	//if there is an image uploaded by reviewer, resize it and then display it.
	$filename2 = "images/user_upload/$user_image";

if ($user_image != "") {
		$html="<a href=$directory/$filename2 target=_blank ><img src=\"$directory/resize_item_image.php?filename=$filename2\" border=\"0\" title=\"Click here to see a larger image of $item_name2\"></a>";
		print $html;  } }//end if user upload?>
            </div></td>
        </tr>
      </table></td>
  </tr>
  <tr>
    <td><?php
	if($tempCount==0){
		//code goes here for displaying ad sense ads
		$queryAd="SELECT * FROM review_adsense";
		$resultAd=mysql_query($queryAd) or die(mysql_error());
		if(mysql_num_rows($resultAd)> 0){
			$rowAd=mysql_fetch_array($resultAd);
			$adShare=$rowAd['ad_share'];
			$adPercent=$rowAd['ad_percent'];
			$adActive=$rowAd["ad_active"];
			$adClientId=$rowAd["ad_clientid"];
			$adChannelId=$rowAd["ad_channel"];

			if(strtolower($adShare)=="y" && $adPercent!="100"){
				//addshare is set to yes
				$cArray=array();
				$iAd=0;
				for($iAd=0;$iAd<$adPercent;$iAd++){
					$cArray[$iAd]="0";
				} //end if strtolower
				$queryAd="SELECT a.username FROM review a,userdb b WHERE a.username=b.username AND review_item_id=$item_id AND  b.adsense_clientid<>'' AND  b.adsense_channelid<>''";
				$resultShow=mysql_query($queryAd) or die(mysql_error());
				$countAd=mysql_num_rows($resultShow);
				if(! $countAd >0){
					//show admin ad as there is no user ads to  be shown
					for($iAd=$iAd;$iAd<100;$iAd++){
						$cArray[$iAd]="0";
					} //end if countAd
				}else{
					$pendingSeats=100-$iAd;
					$share=round($pendingSeats/$countAd);
					while($rowShow=mysql_fetch_array($resultShow)){
						for($sAds=$iAd;$sAds<$countAd*$share+$iAd;$sAds++ ){
							if($sAds<100){
								$cArray[$sAds]=$rowShow["username"];
							}else{
								break;
							} //end if sAds
						}  //end for sAds
					}  //end else

					//see if some slots are free, which may happen due to ronding off
					$checkCount=count($cArray);
					if($checkCount <99){
						for($inc=$checkCount;$inc<100;$inc++){
							$cArray[$inc]="0";
						}
					}
				} //end if

				//now we have the array, choose one index randomly
				$showAd="";//we will save -1[ for admin] or username[for user] in this variable
				$randomIndex=rand(0,99);
				if($cArray[$randomIndex]=="0"){
					//show admin ad








					?>
      <script type="text/javascript"><!--
						google_ad_client = "<?php echo "$adClientId"; ?>";
						google_ad_width = 728;
						google_ad_height = 90;
						google_ad_format = "728x90_as";
						google_ad_type = "text"; google_ad_channel ="<?php echo "$adChannelId"; ?>";
						google_color_border = "FFFFFF"; google_color_bg = "FFFFFF";
						google_color_link = "333333"; google_color_url = "0066CC";
						google_color_text = "333333";
					//--></script>
      <script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>
      <br />
      <br />
      <?php
				}else{
					//show user ad
						$query="SELECT adsense_clientid,adsense_channelid FROM userdb WHERE email='" .$cArray[$randomIndex] ."'";
						$resultUser=mysql_query($query) or die(mysql_error());
						if(mysql_num_rows($resultUser) >0) {
						$rowUser=mysql_fetch_array($resultUser);
						$adClientId=makeStringSafe($rowUser['adsense_clientid']);
						$adChannelId=makeStringSafe($rowUser['adsense_channelid']);
					?>
      <script type="text/javascript"><!--
								google_ad_client = "<?php echo "$adClientId"; ?>";
								google_ad_width = 728;
								google_ad_height = 90;
								google_ad_format = "728x90_as";
								google_ad_type = "text"; google_ad_channel ="<?php echo "$adChannelId"; ?>";
								google_color_border = "FFFFFF"; google_color_bg = "FFFFFF";
						google_color_link = "333333"; google_color_url = "0066CC";
						google_color_text = "333333";
							//--></script>
      <script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>
      <br />
      <br />
      <?php
					}
				}
			}else{
				//adshare is set to no, see if ad_active is yes

				if(strtolower($adActive)=="y"){
					//show admin ad 100% time
				?>
      <script type="text/javascript"><!--
						google_ad_client = "<?php echo "$adClientId"; ?>";
						google_ad_width = 728;
						google_ad_height = 90;
						google_ad_format = "728x90_as";
						google_ad_type = "text"; google_ad_channel ="<?php echo "$adChannelId"; ?>";
						google_color_border = "FFFFFF"; google_color_bg = "FFFFFF";
						google_color_link = "333333"; google_color_url = "0066CC";
						google_color_text = "333333";
					//--></script>
      <script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>
      <br />
      <br />
      <?php
				}
			}
		}
	}?>
      <div style="border-bottom: 1px dotted #878787"></div>
      <br />
      <?php
	$tempCount++;
	}
?></td>
  </tr>
  <tr>
    <td align="center"><div id="listingpaginate" align="center" class="paginationstyle"> <a href="#" rel="previous" class="imglinks"><img src="images/roundleft.gif" /></a>
        <select>
        </select>
        <a href="#" rel="next" class="imglinks"><img src="images/roundright.gif" /></a> </div></td>
  </tr>
</table>
<script type="text/javascript">
        var whatsnew=new virtualpaginate("virtualpage3", 2, "table");
        whatsnew.buildpagination("listingpaginate");
    </script>
<?php
BodyFooter();
exit;
?>
