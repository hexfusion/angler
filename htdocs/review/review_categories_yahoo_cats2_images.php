<?php
session_start();

// include the config files
include ('body.php');
include ('functions.php');
include ('f_secure.php');
include ('config.php');

	function getCategoryName($catId){
		$sql='Select * from review_category_list where cat_id_cloud = ' . $catId;
		$sql_result = mysql_query($sql);
		while ($row = mysql_fetch_array($sql_result)) {
			return stripslashes($row["category"]);	
		}	
	}
	$category = getCategoryName(mysql_real_escape_string(@$_GET['category']));
	$category_id = mysql_real_escape_string(@$_GET['category']);
	if ($category_id == ''){
		$category_id = -1;
	}

			// Print the heading
	BodyHeader("$category","$category Reviews","$category");

?>
<div id="borderbox">
<table width="100%"  border="0" align="center" cellpadding="6" cellspacing="0">
  <tr>
    <td><?php


		echo " <h2>Review Items for $category</h2> ";
		?>
       </td>
        <?php 
		// get a count of how many links are in the database
		$link_count = "SELECT count(*) as count FROM review_items WHERE category_id = " . $category_id;

		$result = mysql_query($link_count);
		$row=mysql_fetch_array($result);
		$num_cat=$row['count'];
		//RBM
		//$rbm=$parent=$_GET['category'];
		$rbm = $category;
		$parent_id = $category_id;
		while($parent_id != -1){
			$res = mysql_query('Select parent_id from review_category_list where cat_id_cloud = ' . $parent_id) or die(mysql_error());
			$parent_id = mysql_result($res,0,0);
			$rbm = '<a href="'.$directory.'/review-category/'.urlencode($parent_id).'.php?' . htmlspecialchars(SID) . '">'. getCategoryName($parent_id) .'</a> / '.$rbm;
		}
		$rbm = '<a href="'.$directory.'/review_categories_yahoo_cats2.php?' . htmlspecialchars(SID) . '">Start</a> ' . $rbm;
		echo $rbm . '<br /><br />';
		// if there aren't any links, then lets generate a user-friendly notice that there aren't any
		if ($num_cat <1 && $category_id != -1){
			echo "<b>Sorry, there are no reviews for items in this category</b>.<br /><br />";
		}
		
		//SUBCATEGORIES
		
		//$res=mysql_query('Select * from review_category_list where parent="'.$_GET['category'].'"');
		$res=mysql_query('Select * from review_category_list where parent_id = '.$category_id);
		while($row=mysql_fetch_array($res)){
			echo '<img src="'.$directory.'/images/folder_open.gif" border="0" hspace="5"><a href="'.$directory.'/review-category/'.urlencode($row['cat_id_cloud']).'.php?' . htmlspecialchars(SID) . '">'.$row['category'].'</a><br /><br />';
		}
		echo "<table width=100%>";
		// if there are links, let's display them!

		// first step is to set up the header for each category
		$query = "SELECT DISTINCT category_id FROM review_items WHERE category_id = ".$category_id." ORDER BY category";
		$result = mysql_query($query); 
		
		while ( $row = mysql_fetch_array($result))
			{
			$category_id = $row["category_id"];
			$catQ = $category_id;		
			if ($category_id != -1) {
				echo "<td colspan=2><B>". getCategoryName($category_id) . "</B></td>";
			}
			
			$category = addslashes(getCategoryName($category_id));			
			// then, lets get a new query and results set so we can display the links from each category
			
			//how many reviews to show
			if($NumReviews > 0){
				$NumReviews=10;
			}
			
			//how many pages to show	
			if($NumPages > 0){
				$NumPages=5;
			}
												
			$pg = 1;
			
			if(isset($_GET['pg']) && $_GET['pg']!=""){
				$pg=$_GET['pg'];
			}
							
			$allPages = ceil($num_cat/$NumReviews);
			
			if($pg > $allPages) $pg=$allPages;
			
			if($pg > 1) {
				$start = ($pg-1)*$NumReviews;
			}else{
				$start = 0;
			}

			$PHP_SELF = $_SERVER['PHP_SELF'];
							
			$stop = $NumReviews;
			if($stop >= $num_cat) { 
				$stop = $num_cat;
			}
						
			$nextPage=cPaging($allPages,$pg,$NumReviews,$NumPages);										
						
			if ($num_cat != 0 && $catQ!=-1) {
				$nextPage .= " |";
			}
			
			echo "<td colspan=2 align=center>" . $nextPage . "<br />&nbsp;</td>";

			$query2 = "SELECT * FROM review_items 	WHERE category_id = " . $category_id ." AND category_id != -1 ORDER BY sortorder LIMIT $start,$stop";
			$result2 = mysql_query($query2); 
			$num_links = mysql_numrows($result2);

			$i=1; 
			while ( $row = mysql_fetch_array($result2)){
				$item_name = stripslashes($row["item_name"]);
				$item_id = $row["item_id"];
				$item_image = $row["item_image"];

				$item_desc = stripslashes($row["item_desc"]);
				//	 for ($i=1; $i<=$num_links; $i++) {		
			
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


			
		if ($i % 2) { 
			
						//if there is an image, resize it and then display it.
	$filename = "images/items/$item_image";

	$ext = strrchr($filename, ".");
			
			
			?>
  <tr>
    <td width="90" valign="middle"><?php if ($item_image != '') { ?>
      <img src="<?php echo "$directory/resize_item_image.php?filename=$filename"; ?>" border="0" />
      <?php }  else { ?>
      <img src="<?php echo "$directory"; ?>/images/noimage80x80.gif" alt="No Image Available" height="80" width="80" border="0" /> <br />
      <?php } ?>    </td>
    <td width=50% valign=top><a href="<?php echo "$directory/review-item/$item_id.php?"; ?>&amp;<?php echo htmlspecialchars(SID); ?>"><?php echo "$item_name"; ?></a>
      <?php
			if ($average != "10.0") { ?>
    
    <br />
   <span class="ad_title"> <b>Avg. Customer Review</b></span>
    <?php } //if average
			$average_for = sprintf ("%01.1f", $average); 
			if ($average <= "5.0") { echo " ($average_for Stars):";  } ?>

    <br />
    <?php  		 $display = ($average/5)*100; ?>
              <div class="rating_bar">
                <div style="width:<?php echo "$display"; ?>%"></div>
              </div> <?php
			echo "out of $total reviews.<br />$item_desc<br /></td>"; }
			else { 
			
			//if there is an image, resize it and then display it.
	$filename = "images/items/$item_image";
	$ext = strrchr($filename, ".");
		?>
    <td width="80" valign="middle"><?php 
			if ($item_image != '') { ?>
      <img src="<?php echo "$directory/resize_item_image.php?filename=$filename"; ?>" border="0" />
      <?php }  else { ?>
      <img src="<?php echo "$directory"; ?>/images/noimage80x80.gif" alt="No Image Available" height="80" width="80" border="0" /> <br />
      <?php } ?></td>
    <td width=50% valign="top"><a href="<?php echo "$directory/review-item/$item_id.php?"; ?>&amp;<?php echo htmlspecialchars(SID); ?>"><?php echo "$item_name"; ?></a> <br />
    <span class="ad_title">  <b>Avg. Customer Review</b></span>
      <?php 
$average_for = sprintf ("%01.1f", $average); 
if ($average != "10.0") { echo " ($average_for Stars):";  } ?>
 <br />
      <?php    		 $display = ($average/5)*100; ?>
              <div class="rating_bar">
                <div style="width:<?php echo "$display"; ?>%"></div>
              </div> <?php


echo "out of $total reviews.<br />$item_desc<br />"; ?>
    </td>
    <?php }
$i++;
			}
?>
  </tr>
  <?php
		}
	
		?>
</table>
<br />
    <br />
    <br />
    <br />
  <div align="center">Click <a href="javascript: history.back(-1);">here</a> to go back</div>
  <br />
    <br />
    </td>
    </tr>

</table></div>
<?php
		
BodyFooter();

function cPaging($count,$start,$pageSize,$displayPages){
	global  $PHP_SELF,$catQ;
	if ($catQ==-1) return "";
	$tempStr="";
	$flag=1;
	$center=$start+($displayPages>>1);
	if($center < 0) $center=0;
	for($i=1;$i<=$count;$i++){
		
		if($i<=($displayPages>>1) && $i>=$start-($displayPages>>1) ){
				if($i==$start){
					$tempStr .= " | $i";		
				}else{
					$tempStr .= " | <a href='$PHP_SELF?pg=" . $i ."&category=$catQ'>$i</a> ";		
				}
				$flag++;		
			
		}elseif($flag<=$displayPages && ($i>=$start-($displayPages>>1) && $i<=$start+($displayPages>>1))){
				if($i==$start){
					$tempStr .= " | $i";		
				}else{
					$tempStr .= " | <a href='$PHP_SELF?pg=" . $i ."&category=$catQ'>$i</a> ";		
				}
				$flag++;		
		}elseif($flag<=$displayPages && $i>=$start-($displayPages>>1)){
				if($i==$start){
					$tempStr .= " | $i";		
				}else{
					$tempStr .= " | <a href='$PHP_SELF?pg=" . $i ."&category=$catQ'>$i</a> ";		
				}
				$flag++;		
		
		}elseif($i==1){
			if($i==$start){
				$tempStr.="  $i";			
			}else{
				$tempStr .= "  <a href='$PHP_SELF?pg=" . $i ."&category=$catQ'>[$i ....]</a>&nbsp;&nbsp;";			
			}
			
		}	elseif($i==$count){
			if($i==$start){
				$tempStr.=" | $i";			
			}else{
				$tempStr .= " | &nbsp;&nbsp;<a href='$PHP_SELF?pg=" . $i ."&category=$catQ'>[$i ....]</a> ";			
			}
		}		
	}			
	//calculate previous next
	if($start < $count){
		$tempStr .= " &nbsp;&nbsp;&nbsp;&nbsp;<a href='$PHP_SELF?pg=" . ($start+1) ."&category=$catQ'><b>Next</b></a> ";
	}
	
	if($start > 1){
		$tempStr .= " &nbsp;&nbsp;&nbsp;&nbsp;<a href='$PHP_SELF?pg=" . ($start-1) ."&category=$catQ'><b>Prev</b></a> ";	
	}
	
	return $tempStr;
}


?>
