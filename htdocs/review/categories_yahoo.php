<?php

// include the config files
include ('body.php');
include ('functions.php');
include ('f_secure.php');
include ('config.php');

$category = $_GET['category'];

		// Print the heading
		//Display the header
BodyHeader("Yahoo Style Layout - $sitename","","");
		echo " <h2>Review Items</h2> ";
		
		// get a count of how many links are in the database
		$link_count = "SELECT count(*) as count FROM review_items WHERE category = '$category'";
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
		$query = "SELECT DISTINCT category FROM review_items WHERE category = '$category' ORDER BY category";
		$result = mysql_query($query); 
		
		while ( $row = mysql_fetch_array($result))
			{
			$category = $row["category"];
						
			if ($category != "") {
			echo "<BR><B>$category</B><BR><BR>";
			}
			
			// then, lets get a new query and results set so we can display the links from each category
			$query2 = "SELECT * FROM review_items 
WHERE category='$category' 
AND category != '' 
ORDER BY sortorder";
			$result2 = mysql_query($query2); 
				$num_links = mysql_numrows($result2);
echo "<table width=100%>";

$i=1; 
while ( $row = mysql_fetch_array($result2))
				{
				 extract($row);

$item_desc = stripslashes($item_desc);	
$item_name = stripslashes($item_name);
	 
			//	 for ($i=1; $i<=$num_links; $i++) {			
if ($i % 2) { echo "<tr><td width=50%><a href=index2.php?item_id=$item_id>$item_name</a> - $item_desc</td>"; }
else { echo "<td width=50%><a href=index2.php?item_id=$item_id>$item_name</a> - $item_desc</td></tr>"; }

$i++;
			}
echo "</tr></table><BR><center>Click <a href=\"javascript:history.back(1)\">here</a> to go back</center>";
			
		}
		
		}
BodyFooter();
?>
