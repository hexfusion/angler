<?php
$displayLimit=2;  //how many items to show in each category
$numshown = 8;  //how many categories to show
//the config files are blocked out since this file is being used as an include on index_sample.php and the includes can't be used twice on the same page.
// include the config files
//include ("body.php");
//include ("functions.php");
//include ("f_secure.php");
//include ("config.php");

// Print the heading
//Display the header
//BodyHeader("Five Star Review - Linear Layout");
?>
<link href="review.css" rel="stylesheet" type="text/css" />


<h2>Review Items</h2>
<div align="left">
<?php


	function getCategoryName($catId){
		$sql='Select * from review_category_list where cat_id_cloud = ' . $catId;
		$sql_result = mysql_query($sql);
		while ($row = mysql_fetch_array($sql_result)) {
			return stripslashes($row["category"]);	
		}	
	}
		
// get a count of how many links are in the database
$link_count = "SELECT count(*) as count FROM review_items WHERE category_id != -1";
$result = mysql_query($link_count);
$row=mysql_fetch_array($result);
$num_links=$row['count'];

// if there aren't any links, then lets generate a user-friendly notice that there aren't any	
if ($num_links <1){
	echo "There are no results for your search.";
}
else{
	// if there are links, display them...
	// set up the header for each category
	$query = "select DISTINCT i.category_id, c.cat_id_cloud, c.catorder FROM review_category_list as c 
	INNER JOIN review_items as i ON c.cat_id_cloud = i.category_id 
	ORDER BY c.catorder ASC limit $numshown ";
	
	$result = mysql_query($query); 

	while ( $row = mysql_fetch_array($result)){
		$category_id = stripslashes(ucfirst($row["category_id"]));
		if ($category_id != -1) {			
			echo "<br /><img src=\"$directory/images/folder_open.gif\" alt=\"" . getCategoryName($category_id) ." Reviews\" title=\"" .getCategoryName($category_id). " Reviews\"> <span class=category><strong>".getCategoryName($category_id)."</strong></span><br />";
		}
			
		// then, lets get a new query and results set so we can display the links from each category
		$query2 = "SELECT count(r.category_id) as ct FROM review_items as r, review_category_list as c WHERE r.category_id = c.cat_id_cloud and r.category_id = ".$category_id." AND r.category_id != -1 ORDER BY c.category ASC ";
		$result2 = mysql_query($query2); 
		$flagItem=0;
	/*$num_rows = mysql_num_rows($result2);

echo "$num_rows Rows\n"; */
		
		if(mysql_num_rows($result2) > 0){
			$row=mysql_fetch_array($result2);
			$flagItem=$row["ct"];
		}
		
		$query2 = "SELECT * FROM review_items WHERE category_id = " . $category_id . " AND category_id != -1 ORDER BY category ASC ";
//	echo "$flagItem";	
if($flagItem > $displayLimit){
			$query2.=" LIMIT 1,$displayLimit";
		}
		$result2 = mysql_query($query2); 
		$counterItem=1;
		while ( $row = mysql_fetch_array($result2)){
				$item_name = stripslashes($row["item_name"]);
				$item_id = $row["item_id"];
				$item_desc = stripslashes($row["item_desc"]);
				$cat=stripslashes($row["category_id"]);
				// now print the links in this category
				?>
                

  					&#8226; &nbsp;&nbsp;<span class="maintext"><a href="<?php echo "$directory"; ?>/review-item/<?php echo "$item_id"; ?>.php?<?php echo htmlspecialchars(SID); ?>"><?php echo ucfirst($item_name); ?></a> - <?php echo "$item_desc"; ?></span><br />
<?php
				if($flagItem > $displayLimit && $counterItem==$displayLimit){
				?>
					&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo "$directory"; ?>/review-category/<?php echo "$category_id"; ?>.php?<?php echo htmlspecialchars(SID); ?>" class="index2-small-bold">More <?php echo getCategoryName($category_id); ?>&gt;&gt;</a><br />
				<?php	
				}
			$counterItem=$counterItem+1;
		
		}
	}
	?>
</div>
	<div align="center"><span><br />
	    <a href="review_categories_yahoo_cats2.php?<?php echo htmlspecialchars(SID); ?>">View</a> All Categories<br />

Didn't see the Item you would like to review? <a href="<?php echo "$directory"; ?>/user_add_item.php?<?php echo htmlspecialchars(SID); ?>">Suggest</a> one!</span></div>




	<?php
}
//BodyFooter();
?>
