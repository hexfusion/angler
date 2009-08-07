<?php
//this will show a list of the different categories.

// include the config files
include_once ("functions.php");
include_once ("f_secure.php");
include_once ("body.php");
include_once ("config.php");

		//get a count of how many links are in the database
		$link_count = "SELECT count(*) as count FROM review_items WHERE category_id != -1";
		$result = mysql_query($link_count) or die(sprintf("Couldn't execute sql_count, %s: %s", db_errno(), db_error()));
		$row=mysql_fetch_array($result);
		$num_links=$row['count'];
		
// if there aren't any links, generate a user-friendly notice	
		if ($num_links <1){
			echo "There are no categories.";
			}
		else{
		
		$res=mysql_query('Select * from review_category_list where parent_id = -1 ORDER BY category');

		while($row=mysql_fetch_array($res)){
			$tres=mysql_query('Select * from review_items where category_id = "'.$row['cat_id_cloud'].'" limit 1');
			$tres1=mysql_query('Select * from review_category_list where parent_id ="'.$row['cat_id_cloud'].'"');
			$subcatnum=mysql_num_rows($tres1);
			if(mysql_num_rows($tres)>0||$subcatnum>0){
				echo '<b><a href="review-category/'.urlencode($row['cat_id_cloud']).'.php?' . htmlspecialchars(SID) . '">'.$row['category'].'</a></b><br />';
				$i=0;
				while(($row1=mysql_fetch_array($tres1))&&$i<3){
				//show subcategories
				
				//put 5 spaces in front of the first one.
				if ($i==0) {
				echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="review-category/'.urlencode($row1['cat_id_cloud']).'.php?' . htmlspecialchars(SID) . '">'.$row1['category'].'</a>'; 
				
				if($i==0&&$i<$subcatnum-1)
						echo ', ';
					$i++;
					continue;
				} //end 5 spaces
				
					echo '<a href="review-category/'.urlencode($row1['cat_id_cloud']).'.php?' . htmlspecialchars(SID) . '">'.$row1['category'].'</a>';
					if($i<2&&$i<$subcatnum-1)
						echo ', ';
					$i++;
				}
				if($subcatnum>3)
					echo '...';
				echo '<br />';
				if($i>0)
					echo '<br />';
			}
		}

	}
?>