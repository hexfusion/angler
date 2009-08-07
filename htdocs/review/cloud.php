<?php
session_start();
include ('functions.php');
include ('f_secure.php');
include ('body.php');
include ('config.php');

function getCategoryName($catId){
	$sql='Select * from review_category_list where cat_id_cloud = ' . $catId;
	$sql_result = mysql_query($sql);
	while ($row = mysql_fetch_array($sql_result)) {
		return stripslashes($row["category"]);	
	}	
}

BodyHeader("Amazon Style Review Script - $sitename", "This php script allows users to add reviews of your products and services to your website", "review, amazon review, review script");

// connect to database at some point

// In the SQL below, change these three things:
// thing is the column name that you are making a tag cloud for
// id is the primary key
// my_table is the name of the database table

$query = "SELECT category_id AS tag, COUNT(cat_id_cloud) AS quantity
  FROM review_category_list
  GROUP BY category_id
  ORDER BY category ASC";

$result = mysql_query($query);

// here we loop through the results and put them into a simple array:
// $tag['thing1'] = 12;
// $tag['thing2'] = 25;
// etc. so we can use all the nifty array functions
// to calculate the font-size of each tag




while ($row = mysql_fetch_array($result)) {

    $tags[$row['tag']] = $row['quantity'];
	
	//echo  $tags[$row['tag']];
}


// change these font sizes if you will
$max_size = 250; // max font size in %
$min_size = 100; // min font size in %

// get the largest and smallest array values
$max_qty = max(array_values($tags));
$min_qty = min(array_values($tags));

// find the range of values
$spread = $max_qty - $min_qty;
if (0 == $spread) { // we don't want to divide by zero
    $spread = 1;
}

// determine the font-size increment
// this is the increase per tag quantity (times used)
$step = ($max_size - $min_size)/($spread);

//echo "$step = ($max_size - $min_size)/($spread);<BR>spread is $spread, $max_qty is max_qty and $min_qty is min_qty<BR>";

// loop through our tag array
foreach ($tags as $key => $value) {

    // calculate CSS font-size
    // find the $value in excess of $min_qty
    // multiply by the font-size increment ($size)
    // and add the $min_size set above
    $size = $min_size + (($value - $min_qty) * $step);
	
	echo "$size = $min_size + (($value - $min_qty) * $step);<br /><br />";
    // uncomment if you want sizes in whole %:
    // $size = ceil($size);

    // you'll need to put the link destination in place of the #
    // (assuming your tag links to some sort of details page)
    echo '<a href="#" style="font-size: '.$size.'%"';
    // perhaps adjust this title attribute for the things that are tagged
    echo ' title="'.$value.' things tagged with '.$key.'"';
    echo '>'.getCategoryName($key).'</a> ';
    // notice the space at the end of the link
}

BodyFooter();
?>