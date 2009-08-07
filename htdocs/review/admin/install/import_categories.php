<?php
//import the categories from the review_category_list table into the review items table.

include ("../../body.php");
include ("../../functions.php");
include ("../../f_secure.php");
include ("../../config.php");


$sql = " SELECT DISTINCT review_category_list.category, review_category_list.cat_id_cloud AS category_id FROM review_category_list
";
			$sql_result = mysql_query($sql)
		or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));
		
		while ( $row = mysql_fetch_array($sql_result) ) {
$category = $row["category"];
$category_id = $row["category_id"];

$sql2 = "UPDATE review_items SET
category_id = '$category_id'
WHERE category = '$category'";
			$sql_result2 = mysql_query($sql2)
		or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));
		
}
echo "You are done!";

 
?>