<?php
/*
//this file will show the users with the most reviews.
include ('functions.php');
include ('f_secure.php');
include ('body.php');
include ('config.php');
*/
//number of users to show on page
$show_num = 10;

$sql = "SELECT source, count(source) as Total, username
		FROM review WHERE username != ''
		GROUP BY username
		ORDER by Total DESC
		LIMIT $show_num";

	$result = mysql_query($sql)
		or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));

while($row = mysql_fetch_array($result)) {
$Total = $row['Total'];
$source = stripslashes($row['source']); 
$username2 = stripslashes($row['username']); ?>

<span><a href="reviewer/<?php echo "$username2"; ?>.php?<?php echo htmlspecialchars(SID); ?>"><?php echo ucfirst($source); ?></a> - <?php echo "$Total"; ?> Reviews<BR></span>

<?php } ?> 