<html>
<head><title>Search</title></head>
<body>
<?php
// Full-Text Search Example
// Conect to the database.
//$cnx = mysql_connect('localhost', 'phpfreaks', 'phpfreaks') or die ("Could not connect");
//mysql_select_db('phpfreaks_search', $cnx) or die (mysql_error());

include ("functions.php");
include ("f_secure.php");
include("config.php");
include("body.php");

// Create the search function:
function searchForm()
{
// Re-usable form
// variable setup for the form.
$searchwords = (isset($_GET['words']) ? htmlspecialchars(stripslashes($_REQUEST['words'])) : '');
$normal = (($_GET['mode'] == 'normal') ? ' selected="selected"' : '' );
$boolean = (($_GET['mode'] == 'boolean') ? ' selected="selected"' : '' );
echo '<form method="get" action="'.$_SERVER['PHP_SELF'].'">';
echo '<input type="hidden" name="cmd" value="search" />';
echo 'Search for: <input type="text" name="words" value="'.$searchwords.'" /> ';
echo 'Mode: ';
echo '<select name="mode">';
echo '<option value="normal"'.$normal.'>Normal</option>';
echo '<option value="boolean"'.$boolean.'>Boolean</option>';
echo '</select> ';
echo '<input type="submit" value="Search" />';
echo '</form>';
}
// Create the navigation switch
$cmd = (isset($_GET['cmd']) ? $_GET['cmd'] : '');
switch($cmd)
{
default:
echo '<h1>Search Database!</h1>';
searchForm();
break;
case "search":
searchForm();
echo '<h3>Search Results:</h3><br />';
$searchstring = mysql_escape_string($_GET['words']);
switch($_GET['mode'])
{
case "normal":
$sql = "SELECT id, summary, review, review_item_id
MATCH(review)
AGAINST ('$searchstring') AS score FROM review
WHERE MATCH(review)
AGAINST ('$searchstring') ORDER BY score DESC";
break;
case "boolean":
$sql = "SELECT id, summary, review, review_item_id
MATCH(review)
AGAINST ('$searchstring' IN BOOLEAN MODE) AS score FROM review
WHERE MATCH(review)
AGAINST ('$searchstring' IN BOOLEAN MODE) ORDER BY score DESC";
break;
}

// echo $sql;
$result = mysql_query($sql) or die (mysql_error());
while($row = mysql_fetch_object($result))
{
echo '<strong>Title: '.stripslashes(htmlspecialchars($row->review_item_id)).'</strong><br />';
echo 'Score:'. number_format($row->score, 1).' Date: '.date('m/d/y', $row->mytable_dts).'<br
/>';
echo '<p>'.stripslashes(htmlspecialchars($row->review)).'</p>';
echo '<hr size="1" />';
}
break;
}
?>
</body>
</html>