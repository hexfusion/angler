<?php
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

<h2>Review Items</h2>
<div align="left">
  <?
		
		// get a count of how many links are in the database
		$link_count = "SELECT count(*) as count FROM products WHERE category != ''";
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

$query = "select DISTINCT i.category, c.category, c.catorder
FROM review_category_list as c
INNER JOIN products as i 
ON c.category = i.category
ORDER BY c.category ASC
";

		$result = mysql_query($query); 
		
		while ( $row = mysql_fetch_array($result))
			{
			$category = stripslashes(ucfirst($row["category"]));
						
			if ($category != "") {			
echo "<BR><img src=images/folder_open.gif> <B><span class=category>$category</span></B><BR>";
			}
			
			// then, lets get a new query and results set so we can display the links from each category
			$query2 = "SELECT * FROM products 
WHERE category='$category' 
AND category != '' 
ORDER BY category ASC
";
			$result2 = mysql_query($query2); 
			while ( $row = mysql_fetch_array($result2))
				{
	$description = stripslashes($row["description"]);
	$item_id = $row["item_id"];
	$comment = stripslashes($row["comment"]);
				// now print the links in this category
				
				?>
  <span class="maintext"><a href=index2.php?item_id=<?php echo "$item_id"; ?>&<?php echo htmlspecialchars(SID); ?>><?php echo ucfirst($description); ?></a> - <?php echo "$comment"; ?></span><BR>
  <?php
					
				}
		}
	?>
</div>
<div align="center"><span class="style1"><br />
  <span class="maintext">Didn't see the Item you would like to review? <a href="user_add_item.php?<?php echo htmlspecialchars(SID); ?>">Suggest</a> one!</span></span> </div>
<?php
		}
//BodyFooter();
?>
