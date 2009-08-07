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
BodyHeader("Yahoo Style Layout - $sitename","","");
		echo " <h1>Review Items</h1> ";
		
		// first, lets get a count of how many links are in the database
		$link_count = "SELECT count(*) as count FROM review_items WHERE category_id != -1";
		$result = mysql_query($link_count);
		$row=mysql_fetch_array($result);
		$num_cat=$row['count'];

		// if there aren't any links, then lets generate a user-friendly notice that there aren't any	
		if ($num_cat <1){
			echo "There are no results for your search.";
			}

		else{
		
		// if there are links, let's display them!

		// first step is to set up the header for each category
		//	$query = "select DISTINCT i.category, c.category, c.catorder FROM review_category_list as c 
		//	INNER JOIN review_items as i ON c.category = i.category 
		//	ORDER BY c.catorder ASC ";

		$query = "select DISTINCT i.category_id, c.cat_id_cloud, c.catorder FROM review_category_list as c INNER JOIN review_items as i ON c.cat_id_cloud = i.category_id ORDER BY c.catorder";
	
		$result = mysql_query($query); 
		
		while ( $row = mysql_fetch_array($result))
			{
			$category_id = $row["category_id"];
						
			if ($category_id != -1) {
			echo "<BR><BR><B>" . getCategoryName($category) . "</B><BR><BR>";
			}
			
			// then, lets get a new query and results set so we can display the links from each category
			$query2 = "SELECT * FROM review_items WHERE category_id=" . mysql_real_escape_string($category_id) . " AND category_id != -1 ORDER BY sortorder";
			$result2 = mysql_query($query2); 
				$num_links = mysql_numrows($result2);
echo "<table width=98% cellpadding=1>";
$i=1; 
while ( $row = mysql_fetch_array($result2))
				{
	$item_name = stripslashes($row["item_name"]);
	$item_id = $row["item_id"];
	$item_desc = stripslashes($row["item_desc"]);			 
			//	 for ($i=1; $i<=$num_links; $i++) {			
if ($i % 2) { echo "<tr><td width=48%><a href=index2.php?item_id=$item_id>$item_name</a> - $item_desc</td>"; }
else { echo "<td width=48%><a href=index2.php?item_id=$item_id>$item_name</a> - $item_desc</td></tr>"; }

$i++;
			}
echo "</tr></table>";
			
		}
		?>
<style type="text/css">
<!--
.style1 {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
}
-->
</style>
<div align="center"> 
<span class="style1"><br /> 
Didn't see the Item you would like to review? <a href="user_add_item.php?<?php echo htmlspecialchars(SID); ?>">Suggest</a> one!</span></div> 
<?php
		}
BodyFooter();
?> 
