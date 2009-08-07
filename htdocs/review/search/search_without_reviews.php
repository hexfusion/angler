<?php
	function cPage($count,$start,$pageSize,$displayPages){
		global $words,$searchWh,$sort,$order,$PHPSESSID,$mode,$cmd,$limit;
		$search_term=$words;
		$tempStr="";
		$flag=1;
		$center=$start+($displayPages>>1);
		if($center < 0) $center=0;
		
		for($i=1;$i<=$count;$i++){
			if($i<=($displayPages>>1) && $i>=$start-($displayPages>>1) ){
					if($i==$start){
						$tempStr .= " | $i";		
					}else{
						$tempStr .= " | <a href='index.php?pg=" . $i ."&words=$search_term&sort=$sort&order=$order&PHPSESSID=$PHPSESSID&searchWhere=$searchWh&mode=$mode&cmd=$cmd&limit=$limit'>$i</a> ";		
					}
					$flag++;		
				
			}elseif($flag<=$displayPages && ($i>=$start-($displayPages>>1) && $i<=$start+($displayPages>>1))){
					if($i==$start){
						$tempStr .= " | $i";		
					}else{
						$tempStr .= " | <a href='index.php?pg=" . $i ."&words=$search_term&sort=$sort&order=$order&PHPSESSID=$PHPSESSID&searchWhere=$searchWh&mode=$mode&cmd=$cmd&limit=$limit'>$i</a> ";		
					}
					$flag++;		
			}elseif($flag<=$displayPages && $i>=$start-($displayPages>>1)){
					if($i==$start){
						$tempStr .= " | $i";		
					}else{
						$tempStr .= " | <a href='index.php?pg=" . $i ."&words=$search_term&sort=$sort&order=$order&PHPSESSID=$PHPSESSID&searchWhere=$searchWh&mode=$mode&cmd=$cmd&limit=$limit'>$i</a> ";		
					}
					$flag++;		
			
			}elseif($i==1){
				if($i==$start){
					$tempStr.="  $i";			
				}else{
					$tempStr .= "  <a href='index.php?pg=" . $i ."&words=$search_term&sort=$sort&order=$order&PHPSESSID=$PHPSESSID&searchWhere=$searchWh&mode=$mode&cmd=$cmd&limit=$limit'>[$i ....]</a>&nbsp;&nbsp;";			
				}
				
			}elseif($i==$count){
				if($i==$start){
					$tempStr.=" | $i";			
				}else{
					$tempStr .= " | &nbsp;&nbsp;<a href='index.php?pg=" . $i ."&words=$search_term&sort=$sort&order=$order&PHPSESSID=$PHPSESSID&searchWhere=$searchWh&mode=$mode&cmd=$cmd&limit=$limit'>[$i ....]</a> ";			
				}
			}		
		}			
		//calculate previous next
		if($start < $count){
			$tempStr .= " &nbsp;&nbsp;&nbsp;&nbsp;<a href='index.php?pg=" . ($start+1) ."&words=$search_term&sort=$sort&order=$order&PHPSESSID=$PHPSESSID&searchWhere=$searchWh&mode=$mode&cmd=$cmd&limit=$limit'><b>Next</b></a> ";
		}

		if($start > 1){
			$tempStr .= " &nbsp;&nbsp;&nbsp;&nbsp;<a href='index.php?pg=" . ($start-1) ."&words=$search_term&sort=$sort&order=$order&PHPSESSID=$PHPSESSID&searchWhere=$searchWh&mode=$mode&cmd=$cmd&limit=$limit'><b>Prev</b></a> ";	
		}
		return $tempStr;
	}

if($searchWh=="2" || $searchWh=="3" || strpos($searchWh, "ri_") == 0){

	if($searchWh=="2"){
		$query="SELECT count(*)  as cnt FROM  review_items WHERE item_name LIKE '%".$words."%'";
	}elseif($searchWh=="3"){
		$query=" SELECT count(*) as cnt FROM review_category_list WHERE category LIKE '%".$words."%'";
	}elseif(strpos($searchWh, "ri_") == 0){
		$item_id = str_replace("ri_", "", $searchWh);
		$query = "select count(*) as cnt 
					FROM review_items,review_items_supplement_data 
					WHERE review_items_supplement_data.review_item_id = review_items.item_id 
					AND review_items_supplement_data.item_supplement_id=" . $item_id . " 
					AND review_items_supplement_data.value like '%" . $words . "%'";
		//echo $query;
	}	
	
	$result=mysql_query($query) or die(mysql_error());
	$total1=0;
	if(mysql_num_rows($result) > 0){
		$row1=mysql_fetch_array($result);
		$total1=$row1["cnt"];
	}

if($total1 > 0){

	$flagYResult=true;

	if (!$NumReviews > 0)$NumReviews=5;
	if (!$NumPages > 0)$NumPages=5;
	
	$pg=1;

	if(isset($_REQUEST['pg']) && $_REQUEST['pg']!="" && is_numeric($_REQUEST['pg']) && $_REQUEST['pg']>=1){
		$pg=makeStringSafe($_REQUEST['pg']);
	}

	if($pg > 1) {
		$start = ($pg-1)*$NumReviews;
	}else{
		$start = 0;
	}

	$stop = $NumReviews;

	$totalRecords=$total1;
	if($stop >= $totalRecords) { $stop = $totalRecords; }

	$allPages=ceil($totalRecords/$NumReviews);

	//echo "*" . $allPages . "*" . $pg . "*" . $NumReviews . "*" . $NumPages;

	$nextPage=cPage($allPages,$pg,$NumReviews,$NumPages);
	if ($allPages != "0") {
		$nextPage .= " |";
	}



	if($searchWh=="2"){
		$query="SELECT item_id,item_name FROM  review_items WHERE item_name LIKE '%".$words."%' Limit $start,$stop";
	}elseif($searchWh=="3"){
		$query=" SELECT category, cat_id_cloud FROM review_category_list WHERE category LIKE '%".$words."%' Limit $start,$stop";
	}elseif(strpos($searchWh, "ri_") == 0){
		$item_id = str_replace("ri_", "", $searchWh);
		$query = "select item_id,item_name 
					FROM review_items,review_items_supplement_data 
					WHERE review_items_supplement_data.review_item_id = review_items.item_id 
					AND review_items_supplement_data.item_supplement_id=" . $item_id . " 
					AND review_items_supplement_data.value like '%" . $words . "%'";
		//echo $query;
	}	

	$result=mysql_query($query) or die(mysql_error());
	BodyHeader("Search Results for $search_term","Review search results for $search_term","$search_term");
	?>

		<LINK rel="stylesheet" type="text/css" name="style" href="../review.css">
  		<SPAN>We found <b><?php echo "$totalRecords" ?></b> results for "<b><?php echo ucfirst($search_term); ?></b>" 
		
			<?php 
				$current_item = "";
				if($searchWh=="0") echo " in <b>Reviews</b>";
				elseif($searchWh=="1") echo " in <b>Summary</b>"; 
				elseif($searchWh=="2") echo " in <b>Item Name</b>";
				elseif($searchWh=="3") echo " in <b>Categories</b>";
				else{
				///////////////////////////////////////////////////////////////////////////////////////
					
					$sql = "SELECT * FROM review_items_supplement order by id asc";
					$sql_result = mysql_query($sql)	or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));
					while ($row = mysql_fetch_array($sql_result)) {
						$option_value = "ri_" . $row['id'];
						$option_label = $row['itemname'];
						$selected = "";
						if($option_value == @$_GET['searchWhere']){
							$current_item = $option_label;
							echo " in <b>" . $option_label . "</b>";
							$current_item = $option_label;
							break;
						}
					}
				///////////////////////////////////////////////////////////////////////////////////////
		}	?>
		
		</SPAN>
    	<br />
    	<SPAN class="small">Common or banned words were replaced with the &quot;x&quot;</SPAN> 

	<?php	
	if($searchWh=="2"){
		?>
		<table cellspacing=4 cellpadding=4 align=left>
		<tr>
			<td colspan=2><?	echo $nextPage; ?></td>
		</tr>
		<tr>
			<td><b>Item Name</b></td>
			<td><b>Reviews Posted</b></td>
		</tr>
		
			
		<?php
		while($row=mysql_fetch_array($result)){
			echo "<tr><td><a href='$directory/index2.php?item_id=" .$row["item_id"] ."'>" . $row["item_name"] . "</a>&nbsp;&nbsp;&nbsp;</td><td>No Reviews</td></tr>";
		}
		?>
		</table>

<?php
	}elseif(strpos($searchWh, "ri_") == 0){	
?>

		<table cellspacing=4 cellpadding=4 align=left border="0">
		<tr>
			<td colspan=2><?php echo $nextPage; ?></td>
		</tr>

		<tr>
			<td><b><?php echo $current_item; ?></b></td>
			<td><b>Reviews Posted</b></td>
		</tr>
		<?php	
		while($row=mysql_fetch_array($result)){
			//echo "<tr><td><a href='../review_categories_yahoo_cats2.php?category=" .$row["cat_id_cloud"] ."'>" . $row["category"] . "</a></td><td>No Reviews</td></tr>";
			echo "<tr><td><a href='../index2.php?item_id=" .$row["item_id"] ."'>" . $row["item_name"] . "</a>&nbsp;&nbsp;&nbsp;</td><td>No Reviews</td></tr>";
		}
		?>
		</table>

		<?php
	}elseif($searchWh=="3"){
		?>
		<table cellspacing=4 cellpadding=4 align=left>
		<tr>
			<td colspan=2><?php	echo $nextPage; ?></td>
		</tr>

		<tr>
			<td><b>Category</b></td>
			<td><b>Reviews Posted</b></td>
		</tr>
		<?php	
		while($row=mysql_fetch_array($result)){
			echo "<tr><td><a href='../review_categories_yahoo_cats2.php?category=" .$row["cat_id_cloud"] ."'>" . $row["category"] . "</a>&nbsp;&nbsp;&nbsp;</td><td>No Reviews</td></tr>";
		}
		?>
		</table>
		<?php
	}
	BodyFooter();

}//end ouuter loop


}//end if
?>