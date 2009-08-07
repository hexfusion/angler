<?php
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

		// Print the heading
		//Display the header
BodyHeader("Linear Layout - $sitename","","");
		echo " <h2>Review Items</h2> ";
		
		// first, lets get a count of how many links are in the database
		$link_count = "SELECT count(*) as count FROM review_items WHERE category_id != -1";
		$result = mysql_query($link_count)
or die(sprintf("Couldn't execute sql_count, %s: %s", db_errno(), db_error()));
		$row=mysql_fetch_array($result);
		$num_links=$row['count'];
		
// if there aren't any links, then lets generate a user-friendly notice that there aren't any	
		if ($num_links <1){
			echo "There are no results for your search.";
			}
		else{

				// if there are links, let's display them!

$query = "select DISTINCT i.category_id, c.cat_id_cloud, c.catorder FROM review_category_list as c INNER JOIN review_items as i ON c.cat_id_cloud = i.category_id ORDER BY c.catorder ASC";

		$result = mysql_query($query) or die(sprintf("Couldn't execute sql_count, %s: %s", db_errno(), db_error())); 

		echo "<table width=100%>";
		$i=1; 
		while ( $row = mysql_fetch_array($result)) {
			$category_id = $row["category_id"];
			if ($i % 2) { 
				echo '<tr><td width=50%><a href='.$directory.'/review_categories_yahoo_cats2.php?category=', htmlentities(urlencode($category_id)), '>',getCategoryName($category_id),'</a></td>'; 
			}
			else { 
				echo '<td width=50%><a href='.$directory.'/review_categories_yahoo_cats2.php?category=', htmlentities(urlencode($category_id)), '>',getCategoryName($category_id),'</a></td></tr>'; 
			}
			$i++;
		}
		echo "</tr></table>";
	?>
<style type="text/css">
<!--
.style1 {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
}
-->
</style>
	
<div align="center"><span class="style1"><br />
  Didn't see the Item you would like to review? <a href="<?php echo "$directory"; ?>/user_add_item.php?<?php echo htmlspecialchars(SID); ?>">Suggest</a> one!</span></div><?php		
		}
		
BodyFooter();
?>
