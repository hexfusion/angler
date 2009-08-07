<?php
//this file will show the users with the most reviews.
include_once ("functions.php");
include_once ("f_secure.php");
include_once ("body.php");
include_once ("config.php");

//number of users to show on page
$show_num = 10;

//find number of useful responses.
$sql = "SELECT source, sum(useful) AS num_useful, username 
		FROM review 
		WHERE useful >= 1
		GROUP BY source
		ORDER by num_useful DESC
		LIMIT $show_num";

			$sql_result = mysql_query($sql)
		or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));

//$i = mysql_fetch_array($sql_result); 
//$num_useful = $i['num_useful']; 


while($row = mysql_fetch_array($sql_result)) {
$num_useful = $row['num_useful'];
$source = stripslashes($row['source']);
$username2 = stripslashes($row['username']);
?>
<span class="style1"><a href="reviewer/<?php echo "$username2"; ?>.php?<?php echo htmlspecialchars(SID); ?>"><?php echo ucfirst($source); ?></a> - <?php echo "$num_useful"; ?> Useful Reviews<BR></span>

<?php } ?> 