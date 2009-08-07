<?php

//Show all errors, except for notices
//error_reporting(E_ALL & ~E_NOTICE);
//ini_set('error_reporting', E_ALL);

ini_set('display_errors', false);


// Full-Text Search Example
// Conect to the database.
session_start();

include ("../functions.php");
include ("../f_secure.php");
include ("../body.php");
include ("../config.php");


if ($_REQUEST["mode"] == "") {
	$_REQUEST["mode"] = "normal";
//	$_GET["mode"] = "normal";
}
//BodyHeader("Search the Reviews - $sitename");

$words = clean($_REQUEST['words']);

if($words!=""){
	$words=makeStringSafe($words);
}
$search_term = stripslashes($words);
$search_term=trim($search_term);
$search_term = clean($search_term);
//check user input and remove any reference to javascript.
$errjava = "<font color=red><br /><br /><B>No Javascript is allowed!  Please click edit and remove the offending code.<br /><br /></B></font>";
$words = preg_replace("'<script[^>]*?>.*?</script>'si", "$errjava", $words);
$words=trim($words);

//make sure user has entered a search term of a specified length.

$search_len = strlen($words);
$min_search = 3;


if ($words == "") {
	BodyHeader("Please enter your search term","","");
?>
	Please enter a search term.  You must enter more than <?php echo "$min_search"; ?> search characters.<br /><br />
    	  <form method="get" action="<?php echo "$directory"; ?>/search/index.php"><input type="hidden" name="cmd" value="search" />Search for: <input name="words" type="text" value="<?php echo "$search_term"; ?>" size="10" maxlength="255" />
 	&nbsp;in &nbsp;
 <select name="searchWhere">
 	<option value="0" <?php if(@$_GET['searchWhere']=="0") echo "selected" ?>>Reviews</option>
 	<option value="1" <?php if(@$_GET['searchWhere']=="1") echo "selected" ?>>Summary</option>
	<option value="2" <?php if(@$_GET['searchWhere']=="2") echo "selected" ?>>Item Name</option>
	<option value="3" <?php if(@$_GET['searchWhere']=="3") echo "selected" ?>>Categories</option>
    
<?php

///////////////////////////////////////////////////////////////////////////////////////
	
	$sql = "SELECT * FROM review_items_supplement order by id asc";
	$sql_result = mysql_query($sql)	or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));
	while ($row = mysql_fetch_array($sql_result)) {
		$option_value = "ri_" . $row['id'];
		$option_label = $row['itemname'];
		$selected = "";
		if($option_value == @$_GET['searchWhere']) $selected = " selected ";
		echo "<option value=\"".$option_value."\" ".$selected." >".$row['itemname']."</option>" ;
	}

///////////////////////////////////////////////////////////////////////////////////////

?>
    
 </select> 
  <input name="mode" type="hidden" value="<?php echo makeStringSafe($_REQUEST['mode']); ?>">&nbsp;<input type="submit" value="Search" /></form>
  
<?php
	BodyFooter();
	exit;
}




if ($search_len <= $min_search) {
	BodyHeader("Enter a longer search term - $sitename","","");
?>
	Please enter a longer search term.  You must enter more than <?php echo "$min_search"; ?> search characters.<br /><br />
    	  <form method="get" action="<?php echo "$directory"; ?>/search/index.php"><input type="hidden" name="cmd" value="search" />Search for: <input name="words" type="text" value="<?php echo "$search_term"; ?>" size="10" maxlength="255" />
 	&nbsp;in &nbsp;
 <select name="searchWhere">
 	<option value="0" <?php if(@$_GET['searchWhere']=="0") echo "selected" ?>>Reviews</option>
 	<option value="1" <?php if(@$_GET['searchWhere']=="1") echo "selected" ?>>Summary</option>
	<option value="2" <?php if(@$_GET['searchWhere']=="2") echo "selected" ?>>Item Name</option>
	<option value="3" <?php if(@$_GET['searchWhere']=="3") echo "selected" ?>>Categories</option>
    
<?php

///////////////////////////////////////////////////////////////////////////////////////
	
	$sql = "SELECT * FROM review_items_supplement order by id asc";
	$sql_result = mysql_query($sql)	or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));
	while ($row = mysql_fetch_array($sql_result)) {
		$option_value = "ri_" . $row['id'];
		$option_label = $row['itemname'];
		$selected = "";
		if($option_value == @$_GET['searchWhere']) $selected = " selected ";
		echo "<option value=\"".$option_value."\" ".$selected." >".$row['itemname']."</option>" ;
	}

///////////////////////////////////////////////////////////////////////////////////////

?>
   
 </select> 
  <input name="mode" type="hidden" value="<?php echo $_REQUEST['mode']; ?>">&nbsp;<input type="submit" value="Search" /></form>
  
<?php
	BodyFooter();
	exit;
}
$searchWh= makeStringSafe($_GET['searchWhere']);


$query = "select count(*) from review,review_items,review_category_list WHERE review.review_item_id = review_items.item_id and  review_items.category_id = review_category_list.cat_id_cloud ";

if($searchWh=="0"){
	$query.=" AND review.review LIKE '%".$words."%'";	
}elseif($searchWh=="1"){
	$query.=" AND review.summary LIKE '%".$words."%'";
}elseif($searchWh=="2"){
	$query.=" AND review_items.item_name LIKE '%".$words."%'";
}elseif($searchWh=="3"){
	$query.=" AND review_category_list.category LIKE '%".$words."%'";
}elseif(strpos($searchWh, "ri_") == 0){
	$item_id = str_replace("ri_", "", $searchWh);
	$query = "select count(*) 
				FROM review,review_items,review_category_list,review_items_supplement_data 
				WHERE review_items_supplement_data.review_item_id = review_items.item_id 
				AND review_items_supplement_data.item_supplement_id=" . $item_id . " 
				AND review_items_supplement_data.value like '%" . $words . "%'
				AND review.review_item_id = review_items.item_id 
				AND review_items.category_id = review_category_list.cat_id_cloud ";
	//echo $query;
}else{
	$query.=" AND review.review LIKE '%".$words."%'";
}

$result = mysql_query($query) or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));
$total = mysql_result($result, 0, 0);  
if ($total <= 0) {
	$flagYResult=false;
	include("search_without_reviews.php");
	if($flagYResult==true){
		exit;
	}else{
		BodyHeader("No Results for $search_term","","");
?>
	No results found for <?php echo "$search_term"; ?>.  Please try a different search.<br />
	<form method="get" action="<?php echo "$directory"; ?>/search/index.php"><input type="hidden" name="cmd" value="search" />Search for: <input name="words" type="text" value="<?php echo "$search_term"; ?>" size="10" maxlength="255" />
 	&nbsp;in &nbsp;
 <select name="searchWhere">
 	<option value="0" <?php if(@$_GET['searchWhere']=="0") echo "selected" ?>>Reviews</option>
 	<option value="1" <?php if(@$_GET['searchWhere']=="1") echo "selected" ?>>Summary</option>
	<option value="2" <?php if(@$_GET['searchWhere']=="2") echo "selected" ?>>Item Name</option>
	<option value="3" <?php if(@$_GET['searchWhere']=="3") echo "selected" ?>>Categories</option>
    
<?php

///////////////////////////////////////////////////////////////////////////////////////
	
	$sql = "SELECT * FROM review_items_supplement order by id asc";
	$sql_result = mysql_query($sql)	or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));
	while ($row = mysql_fetch_array($sql_result)) {
		$option_value = "ri_" . $row['id'];
		$option_label = $row['itemname'];
		$selected = "";
		if($option_value == @$_GET['searchWhere']) $selected = " selected ";
		echo "<option value=\"".$option_value."\" ".$selected." >".$row['itemname']."</option>" ;
	}

///////////////////////////////////////////////////////////////////////////////////////

?>
    
 </select> 
  <input name="mode" type="hidden" value="<?php echo makeStringSafe($_REQUEST['mode']); ?>">
 &nbsp;<input type="submit" value="Search" /></form>
<?php
	BodyFooter();
	exit;
}
}


// work out the pager values 
if(isset($_REQUEST['limit']) && $_REQUEST['limit']!="" && is_numeric($_REQUEST['limit']) && $_REQUEST['limit']>=0){
	$NumReviews=makeStringSafe($_REQUEST['limit']);
	$limit=makeStringSafe($_REQUEST['limit']);
}else{
	$limit=$NumReviews;
}
//$NumPages
$pg=1;

if(isset($_REQUEST['pg']) && $_REQUEST['pg']!="" && is_numeric($_REQUEST['pg']) && $_REQUEST['pg']>=1){
	$pg=makeStringSafe($_REQUEST['pg']);
}

if($pg > 1) {
	$start = ($pg-1)*$NumReviews;
}else{
	$start = 0;
}

$stop = $NumReviews;

$totalRecords=$total;

if(isset($_REQUEST['limit']) && $_REQUEST['limit']!="" && is_numeric($_REQUEST['limit']) && $_REQUEST['limit']>=0 && $_REQUEST['limit']==$totalRecords){
	$NumReviews=$totalRecords;	
}

if($stop >= $totalRecords) { $stop = $totalRecords; }

$allPages=ceil($totalRecords/$NumReviews);


$cmd="search";
if(isset($_GET['cmd']) && $_GET['cmd']!=""){
	$cmd=makeStringSafe($_GET['cmd']);
}
$default_sort = 'score';

$allowed_sort = array ('item_name','category','rating','source','review','summary','score');
/* if sort is not set, or it is not in the allowed list, then set it to a default value. Otherwise, set it to what was passed in. */
if (!isset ($_GET['sort']) ||!in_array ($_GET['sort'], $allowed_sort)) {
    $sort = $default_sort;
} else {
    $sort = makeStringSafe($_GET['sort']);
}

$order = makeStringSafe($_REQUEST['order']);

if ($order == "") {
	$order= "DESC";
}


// Create the search function:

function searchForm(){
	// Re-usable form
  	// variable setup for the form.
  	$search_term = (isset($_GET['words']) ? htmlspecialchars(stripslashes($_REQUEST['words'])) : '');
  	$search_term=trim($search_term);
	$search_term=makeStringSafe($search_term);
  	$normal = (($_GET['mode'] == 'normal') ? ' selected="selected"' : '' );
  	$boolean = (($_GET['mode'] == 'boolean') ? ' selected="selected"' : '' );
  
  	echo '<form method="get" action="'.$_SERVER['PHP_SELF'].'">';
  	echo '<input type="hidden" name="cmd" value="search" />';
  	echo 'Search for: <input type="text" name="words" value="'.$search_term.'" />&nbsp;';
  	echo 'Mode: ';
  	echo '<select name="mode">';
  	echo '<option value="normal"'.$normal.'>Normal</option>';
  	echo '<option value="boolean"'.$boolean.'>Boolean</option>';
  	echo '</select>&nbsp;';
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
  //  searchForm();
  //  echo '<h3>Search Results:</h3><br />';
  		$search_term = makeStringSafe($_GET['words']);
    	$search_term=trim($search_term);
		switch($_GET['mode']){
      			case "normal":
					$query_main = "SELECT review_items.category_id,review.id, SUBSTRING_INDEX(review, ' ', 6) as review, source, SUBSTRING_INDEX(summary, ' ', 5) as summary, review_item_id, review_items.item_name, date_format(date_added, '%M %d, %Y') as date_added, rating, review.username, 
									MATCH(review.review, review.summary) AGAINST ('$search_term') AS score 
									FROM review_items, review 
									WHERE review.review_item_id = review_items.item_id ";
					
					
					//AND MATCH(review.review, review.summary) AGAINST ('$search_term')
					if($searchWh=="0"){
						$query_main.=" AND MATCH(review.review,review.summary) AGAINST ('$search_term')";	
					}elseif($searchWh=="1"){
						$query_main.=" AND MATCH(review.review,review.summary) AGAINST ('$search_term')";
					}elseif($searchWh=="2"){
						$query_main.=" AND review_items.item_name like '%$search_term%'";
					}elseif($searchWh=="3"){
						$query_main.=" AND review_items.category like '%$search_term%'";
					}elseif(strpos($searchWh, "ri_") == 0){
						$item_id = str_replace("ri_", "", $searchWh);
						$query_main = "select *, review_items_supplement_data.value as searchresult,MATCH(review_items_supplement_data.value) AGAINST ('$search_term') AS score  
									FROM review,review_items,review_category_list,review_items_supplement_data 
									WHERE review_items_supplement_data.review_item_id = review_items.item_id 
									AND review_items_supplement_data.item_supplement_id=" . $item_id . " 
									AND review.review_item_id = review_items.item_id 
									AND review_items.category_id = review_category_list.cat_id_cloud ";
						//echo $query;
					}else{
						$query_main.=" AND MATCH(review.review, review.summary) AGAINST ('$search_term')";
					}
					
					if (isset($limit_last) && (@$_GET['allPages'] == @$_GET['pg'])) { 
							$query = "$query_main AND approve='y' order by $sort $order limit $start, $stop "; 
					}else {
							$query = "$query_main AND approve='y' order by $sort $order limit $start, $stop ";
					}
					//echo "<br />";
					
					//echo $query;
      				//die();
					break;
      			case "boolean":
					if(strpos($searchWh, "ri_") == 0){
						$item_id = str_replace("ri_", "", $searchWh);
						$query = "select *, review_items_supplement_data.value as searchresult,MATCH(review_items_supplement_data.value) AGAINST ('$search_term' IN BOOLEAN MODE) AS score  
									FROM review,review_items,review_category_list,review_items_supplement_data 
									WHERE review_items_supplement_data.review_item_id = review_items.item_id 
									AND review_items_supplement_data.item_supplement_id=" . $item_id . " 
									AND review.review_item_id = review_items.item_id 
									AND review_items.category_id = review_category_list.cat_id_cloud ";
					}else{
						if (isset($limit_last) && ($_GET['allPages'] == $_GET['pg'])) { 
							$query = "SELECT review.id, SUBSTRING_INDEX(review, ' ', 6) as review, source, SUBSTRING_INDEX(summary, ' ', 5) as summary, review_item_id, review_items.item_name, date_format(date_added, '%M %d, %Y') as date_added, rating, review.username, MATCH(review.review, review.summary) 
								AGAINST ('$search_term' IN BOOLEAN MODE) AS score 
								FROM review_items, review 
								WHERE review.review_item_id = review_items.item_id AND 
								MATCH(review.review, review.summary) AGAINST ('$search_term' IN BOOLEAN MODE)
								AND approve='y' order by $sort $order limit $start, $stop"; 
						}else {
							$query = "SELECT review.id, SUBSTRING_INDEX(review, ' ', 6) as review, source, SUBSTRING_INDEX(summary, ' ', 5) as summary, review_item_id, review_items.item_name, date_format(date_added, '%M %d, %Y') as date_added, rating, review.username, MATCH(review.review, review.summary) 
								AGAINST ('$search_term' IN BOOLEAN MODE) AS score 
								FROM review_items, review 
								WHERE review.review_item_id = review_items.item_id AND 
								MATCH(review.review, review.summary) AGAINST ('$search_term' IN BOOLEAN MODE)
								AND approve='y' order by $sort $order limit $start, $stop";
						}
					}
      				break;
    		} 
 			//set limit back to the original value.
			if (isset($limit_orig)) {
				$limit = $limit_orig;
			}   
			//echo $query;
			//die();
			$result = mysql_query($query) or die (mysql_error());
			//get total number of results 
			$total =  mysql_num_rows($result);
			$flagResult=0;
			
			if ($total <= 0) {
					$flagResult=1;
					$query_main = "SELECT review_items.category_id,review.id, SUBSTRING_INDEX(review, ' ', 6) as review, source, SUBSTRING_INDEX(summary, ' ', 5) as summary, review_item_id, review_items.item_name, date_format(date_added, '%M %d, %Y') as date_added, rating, review.username, MATCH(review.review, review.summary) 
									AGAINST ('$search_term') AS score 
									FROM review_items, review 
									WHERE review.review_item_id = review_items.item_id ";
					
					
					//AND MATCH(review.review, review.summary) AGAINST ('$search_term')
					if($searchWh=="0"){
						$query_main.=" AND review.review like '%$search_term%'";	
					}elseif($searchWh=="1"){
						$query_main.=" AND review.summary like '%$search_term%'";
					}elseif($searchWh=="2"){
						$query_main.=" AND review_items.item_name like '%$search_term%'";
					}elseif($searchWh=="3"){
						$query_main.=" AND review_items.category like '%$search_term%'";
					}elseif(strpos($searchWh, "ri_") == 0){
						$item_id = str_replace("ri_", "", $searchWh);
						$query_main = "select *, review_items_supplement_data.value as searchresult,MATCH(review_items_supplement_data.value) AGAINST ('$search_term') AS score  
									FROM review,review_items,review_category_list,review_items_supplement_data 
									WHERE review_items_supplement_data.review_item_id = review_items.item_id 
									AND review_items_supplement_data.item_supplement_id=" . $item_id . " 
									AND review.review_item_id = review_items.item_id 
									AND review_items.category_id = review_category_list.cat_id_cloud ";
					}else{
						$query_main.=" AND MATCH(review.review, review.summary) AGAINST ('$search_term')";
					}
					
					if (isset($limit_last) && (@$_GET['allPages'] == @$_GET['pg'])) { 
							$query = "$query_main AND approve='y' order by $sort $order limit $start, $stop "; 
					}else {
							$query = "$query_main AND approve='y' order by $sort $order limit $start, $stop ";
					}
					
					$result = mysql_query($query) or die (mysql_error());
					//get total number of results 
					$total =  mysql_num_rows($result);
					
					//echo $query;
					//die();
					
				if($total==0){
					$flagYResult=false;
					include("search_without_reviews.php");
					if($flagYResult==true){
						exit;
					}else{
						BodyHeader("No Results for $search_term","","");
?>
						No results were found for <?php echo "$search_term"; ?>.  Please try a different search.<br />
						<form method="get" action="<?php echo "$directory"; ?>/search/index.php"><input type="hidden" name="cmd" value="search" />Search for: <input name="words" type="text" value="<?php echo "$search_term"; ?>" size="10" maxlength="255" />
 							&nbsp;in &nbsp;
 							<select name="searchWhere">
 							<option value="0" <?php if(@$_GET['searchWhere']=="0") echo "selected" ?>>Reviews</option>
 							<option value="1" <?php if(@$_GET['searchWhere']=="1") echo "selected" ?>>Summary</option>
							<option value="2" <?php if(@$_GET['searchWhere']=="2") echo "selected" ?>>Item Name</option>
							<option value="3" <?php if(@$_GET['searchWhere']=="3") echo "selected" ?>>Categories</option>
                            
<?php

///////////////////////////////////////////////////////////////////////////////////////

	$sql = "SELECT * FROM review_items_supplement order by id asc";
	$sql_result = mysql_query($sql)	or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));
	while ($row = mysql_fetch_array($sql_result)) {
		$option_value = "ri_" . $row['id'];
		$option_label = $row['itemname'];
		$selected = "";
		if($option_value == @$_GET['searchWhere']) $selected = " selected ";
		echo "<option value=\"".$option_value."\" ".$selected." >".$row['itemname']."</option>" ;
	}
			//echo "***********" . $sql;
			//die();
///////////////////////////////////////////////////////////////////////////////////////

?>
                            
 						</select> 
  						<input name="mode" type="hidden" value="<?php echo makeStringSafe($_REQUEST['mode']); ?>">
 						&nbsp;<input type="submit" value="Search" /></form>
<?php
						BodyFooter();
						exit;
					}
				}
			}

			while($row = mysql_fetch_array($result)) {
				$cat=stripslashes($row['category_id']);
				$id = stripslashes($row['id']);
				$review = stripslashes($row['review']);
				$source = stripslashes($row['source']);
				$review_item_id = stripslashes($row['review_item_id']);
				$rating = stripslashes($row['rating']);
				$date_added = stripslashes($row['date_added']);
				$summary = stripslashes($row['summary']);
				$item_id = stripslashes($row['review_item_id']);
				$item_name = stripslashes($row['item_name']);
				$username = stripslashes($row['username']);
				$score = stripslashes($row['score']);
			}
    		$result = mysql_query($query) or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));
			//Display the header
			BodyHeader("Search Results for $search_term","","");
		?>
		<script language="JavaScript" type="text/JavaScript">
		<!--
			function MM_openBrWindow(theURL,winName,features) { //v2.0
  				window.open(theURL,winName,features);
			}
		//-->
		</script>
		<LINK rel="stylesheet" type="text/css" name="style" href="../review.css">
		<div align="center">
  		<p><SPAN>We found <b><?php echo "$totalRecords" ?></b> results for "<b><?php echo ucfirst($search_term); ?></b>" 
		
			<?php 
				$current_item = "";
				if($searchWh=="0") echo " in <b>Reviews</b>";
				elseif($searchWh=="1") echo " in <b>Summary</b>"; 
				elseif($searchWh=="2") echo " in <b>Item Name</b>";
				elseif($searchWh=="3") echo " in <b>Categories</b>";
				else{
				///////////////////////////////////////////////////////////////////////////////////////
					
					$sql = "SELECT * FROM review_items_supplement order by id asc";
					$sql_result = mysql_query($sql)	or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));
					while ($row = mysql_fetch_array($sql_result)) {
						$option_value = "ri_" . $row['id'];
						$option_label = $row['itemname'];
						$selected = "";
						if($option_value == @$_GET['searchWhere']){
							$current_item = $option_label;
							echo " in <b>" . $option_label . "</b>";
							break;
						}
					}
				
				///////////////////////////////////////////////////////////////////////////////////////
				}
			?>
		
		</SPAN>
    	<br />
    	<SPAN class="small">Common or banned words were replaced with the &quot;x&quot;</SPAN> 
  		<p><br />
    	How many results would you like to show on each page? </p>
  		<form name="form1" method="post" action="<?php $_SERVER['PHP_SELF']; ?>?cmd=search&mode=<?php echo makeStringSafe($_GET['mode']); ?>&words=<?php echo "$search_term"; ?>&sort=<?php echo "$sort"; ?>&order=<?php echo "$order"; ?>&searchWhere=<?php echo "$searchWh"; ?>&<?php echo htmlspecialchars(SID); ?>">
		<select name="limit">
		<?php
			//this fancy select box will only display in increments of 5 and not exceed the total number of results.
			if ($limit == $totalRecords) { ?>
				<option value="<?php echo "$totalRecords" ?>"<?php if ($limit == $totalRecords) { echo "selected"; } ?>">All</option> 
			<?php }
			for($i = 0; $i < $totalRecords; $i += 5) {
				if ($totalRecords > "5") {
					if ($i >= 5) {
			?>
						<option value="<?php echo "$i"; ?>"<?php if ($limit == "$i") { echo " SELECTED"; } ?>><?php echo "$i"; ?></option> 
			<?php 
					}//if i >=5
				} 
				else { echo "<option value=\"--\" selected"; ?>>--</option> <?php } //if
			}//for 
			if ($totalRecords != $limit) { ?>      <option value="<?php echo "$totalRecords" ?>"<?php if ($limit == $totalRecords) { echo "selected"; } ?>">All</option> <?php } //end if total ?>
  		</select>
    	<input name="Submit" type="submit" value="Show Me">
    	<span class="small">(Current display: Up to <b><?php echo "$limit" ?></b> results) </span>
  	</form>
</div>
<hr noshade size=1>

<table width="100%" border="0" align="center" cellpadding="3" cellspacing="0">
  <tr>
    
	<td width="115">
	<?php //if there is no order set, set it to ASC
		if (isset($_GET['order']) && $_GET['order'] == "") { 
			$_GET['order'] = "ASC";
		} 
	//searchWhere=$searchWh&mode=$mode&limit=$limit
	?>
	
    <div align="left"><span class="small">Sort by</span><br />
    <b><a href="<?php echo $_SERVER['PHP_SELF']; ?>?searchWhere=<?php echo "$searchWh"; ?>&pg=<?php echo "$pg"; ?>&limit=<?php echo "$limit"; ?>&cmd=search&mode=<?php echo makeStringSafe($_GET['mode']); ?>&words=<?php echo "$search_term"; ?>&sort=category&order=<?php if (isset($_GET['order']) && $_GET['order'] == "DESC") { 
					echo "ASC"; 
				} elseif (isset($_GET['order']) && $_GET['order'] == "ASC") { 
					echo "DESC"; 
				}?>">
				Category</a>
            <?php if (isset($sort) && $sort=="category") { if (isset($_GET['order']) && $_GET['order'] == "DESC") { 
			echo " <img src=\"../images/sort_down.gif\">"; } elseif ($_GET['order'] == "ASC") { 
			echo " <img src=\"../images/sort_up.gif\">"; } }?>
        </b></div>
	</td>
	
	<td width="115">
	<?php //if there is no order set, set it to ASC
		if (isset($_GET['order']) && $_GET['order'] == "") { 
			$_GET['order'] = "ASC";
		} 
	?>
    <div align="left"><span class="small">Sort by</span><br />
    <b><a href="<?php echo $_SERVER['PHP_SELF']; ?>?searchWhere=<?php echo "$searchWh"; ?>&pg=<?php echo "$pg"; ?>&limit=<?php echo "$limit"; ?>&cmd=search&mode=<?php echo makeStringSafe($_GET['mode']); ?>&words=<?php echo "$search_term"; ?>&sort=item_name&order=<?php if (isset($_GET['order']) && $_GET['order'] == "DESC") { 
					echo "ASC"; 
				} elseif (isset($_GET['order']) && $_GET['order'] == "ASC") { 
					echo "DESC"; 
				}?>">
				Name</a>
            <?php if (isset($sort) && $sort=="item_name") { if (isset($_GET['order']) && $_GET['order'] == "DESC") { 
			echo " <img src=\"../images/sort_down.gif\">"; } elseif ($_GET['order'] == "ASC") { 
			echo " <img src=\"../images/sort_up.gif\">"; } }?>
        </b></div>
	</td>
		
    <td width="121"><div align="left"><span class="small">Sort by</span><br />
            <b><a href="<?php echo $_SERVER['PHP_SELF']; ?>?searchWhere=<?php echo "$searchWh"; ?>&pg=<?php echo "$pg"; ?>&limit=<?php echo "$limit"; ?>&cmd=search&mode=<?php echo makeStringSafe($_GET['mode']); ?>&words=<?php echo "$search_term"; ?>&sort=rating&order=<?php if (isset($_GET['order']) && $_GET['order'] == "DESC") { 
echo "ASC"; 
} elseif (isset($_GET['order']) && $_GET['order'] == "ASC") { 
echo "DESC"; 
}  ?>">Rating</a>
            <?php if ($sort=="rating") { if ($_GET['order'] == "DESC") { 
echo " <img src=\"../images/sort_down.gif\">"; } elseif ($_GET['order'] == "ASC") { 
echo " <img src=\"../images/sort_up.gif\">"; } }?>
        </b></div></td>
    <td width="107"><div align="left"><span class="small">Sort by<br />
        </span><b><a href="<?php echo $_SERVER['PHP_SELF']; ?>?searchWhere=<?php echo "$searchWh"; ?>&pg=<?php echo "$pg"; ?>&limit=<?php echo "$limit"; ?>&cmd=search&mode=<?php echo makeStringSafe($_GET['mode']); ?>&words=<?php echo "$search_term"; ?>&sort=summary&order=<?php if (isset($_GET['order']) && $_GET['order'] == "DESC") { 
echo "ASC"; 
} elseif (isset($_GET['order']) && $_GET['order'] == "ASC") { 
echo "DESC"; 
}  ?>">Summary</a>
        <?php if ($sort=="summary") { if ($_GET['order'] == "DESC") { 
echo " <img src=\"../images/sort_down.gif\">"; } elseif ($_GET['order'] == "ASC") { 
echo " <img src=\"../images/sort_up.gif\">"; } }?>
    </b></div></td>
    <td width="222"><div align="left"><span class="small">Sort by</span><b><a href="<?php echo $_SERVER['PHP_SELF']; ?>?searchWhere=<?php echo "$searchWh"; ?>&pg=<?php echo "$pg"; ?>&limit=<?php echo "$limit"; ?>&cmd=search&mode=<?php echo makeStringSafe($_GET['mode']); ?>&words=<?php echo "$search_term"; ?>&sort=review&order=<?php if (isset($_GET['order']) && $_GET['order'] == "DESC") { 
echo "ASC"; 
} elseif (isset($_GET['order']) && $_GET['order'] == "ASC") { 
echo "DESC"; 
}  ?>"><br />
        Review</a>
              <?php if ($sort=="review") { if ($_GET['order'] == "DESC") { 
echo " <img src=\"../images/sort_down.gif\">"; } elseif ($_GET['order'] == "ASC") { 
echo " <img src=\"../images/sort_up.gif\">"; } }?>
    </b></div></td>
    <td width="189"><div align="left"><span class="small">Sort by</span><b><br />
              <a href="<?php echo $_SERVER['PHP_SELF']; ?>?searchWhere=<?php echo "$searchWh"; ?>&pg=<?php echo "$pg"; ?>&limit=<?php echo "$limit"; ?>&cmd=search&mode=<?php echo makeStringSafe($_GET['mode']); ?>&words=<?php echo "$search_term"; ?>&sort=source&order=<?php if (isset($_GET['order']) && $_GET['order'] == "DESC") { 
echo "ASC"; 
} elseif (isset($_GET['order']) && $_GET['order'] == "ASC") { 
echo "DESC"; 
}  ?>">Reviewer</a>
              <?php if ($sort=="source") { if ($_GET['order'] == "DESC") { 
echo " <img src=\"../images/sort_down.gif\">"; } elseif ($_GET['order'] == "ASC") { 
echo " <img src=\"../images/sort_up.gif\">"; } }?>
    </b></div></td>
   <?php if(($searchWh=="0" || $searchWh=="1" ) && ($flagResult==0) ){?>
    <td width="189"><div align="left"><span class="small">Sort by</span><b><br />
              <a href="<?php echo $_SERVER['PHP_SELF']; ?>?searchWhere=<?php echo "$searchWh"; ?>&pg=<?php echo "$pg"; ?>&limit=<?php echo "$limit"; ?>&cmd=search&mode=<?php echo makeStringSafe($_GET['mode']); ?>&words=<?php echo "$search_term"; ?>&sort=score&order=<?php if (isset($_GET['order']) && $_GET['order'] == "DESC") { 
echo "ASC"; 
} elseif (isset($_GET['order']) && $_GET['order'] == "ASC") { 
echo "DESC"; 
}  ?>">Score</a>
              <?php if ($sort=="score") { if (isset($_GET['order']) && $_GET['order'] == "DESC") { 
echo " <img src=\"../images/sort_down.gif\">"; } elseif (isset($_GET['order']) && $_GET['order'] == "ASC") { 
echo " <img src=\"../images/sort_up.gif\">"; } }?>
              <a href="#"><img src="../images/info.gif" width="16" height="16" border="0" onclick="MM_openBrWindow('score.php','score','toolbar=no,scrollbars=yes,resizable=yes,width=500,height=250')"></a> </b></div>
  </td>
  
  
  
<?php }?>
<td width="100" align="left"><br><b><font color="#0000CC"><?php echo $current_item ?></font></b></td>  
  </tr>
  <?php 
  
	function getCategoryName($catId){
		$sql='Select * from review_category_list where cat_id_cloud = ' . $catId;
		$sql_result = mysql_query($sql);
		while ($row = mysql_fetch_array($sql_result)) {
			return stripslashes($row["category"]);	
		}	
	}
  
while ($row = mysql_fetch_array($result)) { 
	$cat=stripslashes($row['category_id']);
	$id = stripslashes($row['id']);
	$review = stripslashes($row['review']);
	$rating = stripslashes($row['rating']);
	$source = stripslashes($row['source']);
	$summary = stripslashes($row['summary']);
	$review_item_id = stripslashes($row['review_item_id']);
	$date_added = stripslashes($row['date_added']);
	$item_id = stripslashes($row['review_item_id']);
	$item_name = stripslashes($row['item_name']);
	$score = stripslashes($row['score']);
	if (isset($_GET['order']) && $_GET['order'] == "DESC") { 
		$order2 = "ASC"; 
	} elseif (isset($_GET['order']) && $_GET['order'] == "ASC") { 
		$order2 =  "DESC"; 
	}
	//Get total number of reviews for each item.
	$average = $rating;
?>
  	<tr>
    	<td colspan="8" valign="top" nowrap><hr noshade size=1></td>
  	</tr>
  	<tr>
  		<td height="45" valign="top" nowrap><?php $catdis = $cat; echo "<a href=../review_categories_yahoo_cats2.php?category=$catdis>". getCategoryName($cat) . "</a>"; ?><p></p></td>
    	<td height="45" valign="top" nowrap><?php echo "<a href=../review_single.php?item_id=$item_id&id=$id>$item_name </a>"; ?><p></p></td>
    	<td valign="top" nowrap><span>
    <?php 
	$display = ($average/5)*100; ?>
                    
<div class="rating_bar">
  <div style="width:<?php echo "$display"; ?>%"></div>
</div> <?php
	$item_id = "";
	if(isset($_GET["item_id"]) && $_GET["item_id"]!=""){
		$item_id=makeStringSafe($_GET["item_id"]);
	}
	$back = $_SERVER['PHP_SELF'];
	//put search_term into array to highlight search term.
	$keywords[] = $search_term; 

	//clean up bbcode and get it ready for display
	if ($use_bbcode == "yes") {
		include("../bbcode.php");
		//$review = search_highlight( "$review", $keywords );
  		$sig = @str_replace($htmlcode, $bbcode, $sig);
  		$sig = nl2br($sig);//second pass
  		$review = str_replace($bbcode, $htmlcode, $review);
  		$review = nl2br($review);//second pass
  		$review = html_entity_decode($review);
		//if the searchterm is in $review then do not highlight search words.
	}
?>
    <br />
    </span></td>
    <td valign="top" nowrap class="body"><?php echo "$summary"; ?><br /></td>
    <td valign="top" nowrap class="body">
	<?php 
	//disable keyword highlighting because of interference with bbcode.
	if ($use_bbcode == "no") {
		echo( search_highlight( "$review", $keywords ) ); 
	} else{
		 echo "$review";
	}
	?>
	...<br /></td>
    <td valign="top" nowrap class="body"><a href="<?php echo "$directory"; ?>/reviewer_about.php?username=<?php echo "$source"; ?>"><?php echo "$source"; ?></a><br /></td>
    <?php if(!(($searchWh=="0" || $searchWh=="1" ) && ($flagResult==0))){?>
    <td valign="top" width="100" align="left" nowrap="nowrap"><?php echo stripslashes($row['searchresult']) ?></td> 
    <?php }?>
    
	<?php if(($searchWh=="0" || $searchWh=="1" ) && ($flagResult==0)){?>
	<td valign="top" nowrap class="body"><?php echo number_format($score, 1);  ?><br /></td>
  	<?php }?>
	</tr>
  	<?php 
	}
  break;
}
 ?>
</table>
<br />
<br />
<br />
<br />
<br />
<br />

<hr noshade size=1>

<hr align=left noshade size=1 width="100%">

<?php
////////////////////////////////

$nextPage=cPaging($allPages,$pg,$NumReviews,$NumPages);

if(isset($_GET['pg_orig']) && $_GET['pg_orig']>=1)$nextPage.=" | <a href='$PHP_SELF?pg=" .$_GET['pg_orig'] ."&item_id=$item_id&sort=$sort&order=$order&PHPSESSID=$PHPSESSID'>Back To Paged View</a>";

$mode=makeStringSafe($_GET['mode']);

function cPaging($count,$start,$pageSize,$displayPages){
	global  $PHP_SELF,$search_term,$searchWh,$sort,$order,$PHPSESSID,$mode,$cmd,$limit;
	$mode=makeStringSafe($_GET['mode']);
	$tempStr="";
	$flag=1;
	$center=$start+($displayPages>>1);
	if($center < 0) $center=0;
	for($i=1;$i<=$count;$i++){
		
		if($i<=($displayPages>>1) && $i>=$start-($displayPages>>1) ){
				if($i==$start){
					$tempStr .= " | $i";		
				}else{
					$tempStr .= " | <a href='$PHP_SELF?pg=" . $i ."&words=$search_term&sort=$sort&order=$order&PHPSESSID=$PHPSESSID&searchWhere=$searchWh&mode=$mode&cmd=$cmd&limit=$limit'>$i</a> ";		
				}
				$flag++;		
			
		}elseif($flag<=$displayPages && ($i>=$start-($displayPages>>1) && $i<=$start+($displayPages>>1))){
				if($i==$start){
					$tempStr .= " | $i";		
				}else{
					$tempStr .= " | <a href='$PHP_SELF?pg=" . $i ."&words=$search_term&sort=$sort&order=$order&PHPSESSID=$PHPSESSID&searchWhere=$searchWh&mode=$mode&cmd=$cmd&limit=$limit'>$i</a> ";		
				}
				$flag++;		
		}elseif($flag<=$displayPages && $i>=$start-($displayPages>>1)){
				if($i==$start){
					$tempStr .= " | $i";		
				}else{
					$tempStr .= " | <a href='$PHP_SELF?pg=" . $i ."&words=$search_term&sort=$sort&order=$order&PHPSESSID=$PHPSESSID&searchWhere=$searchWh&mode=$mode&cmd=$cmd&limit=$limit'>$i</a> ";		
				}
				$flag++;		
		
		}elseif($i==1){
			if($i==$start){
				$tempStr.="  $i";			
			}else{
				$tempStr .= "  <a href='$PHP_SELF?pg=" . $i ."&words=$search_term&sort=$sort&order=$order&PHPSESSID=$PHPSESSID&searchWhere=$searchWh&mode=$mode&cmd=$cmd&limit=$limit'>[$i ....]</a>&nbsp;&nbsp;";			
			}
			
		}	elseif($i==$count){
			if($i==$start){
				$tempStr.=" | $i";			
			}else{
				$tempStr .= " | &nbsp;&nbsp;<a href='$PHP_SELF?pg=" . $i ."&words=$search_term&sort=$sort&order=$order&PHPSESSID=$PHPSESSID&searchWhere=$searchWh&mode=$mode&cmd=$cmd&limit=$limit'>[$i ....]</a> ";			
			}
		}		
	}			
	//calculate previous next
	if($start < $count){
				$tempStr .= " &nbsp;&nbsp;&nbsp;&nbsp;<a href='$PHP_SELF?pg=" . ($start+1) ."&words=$search_term&sort=$sort&order=$order&PHPSESSID=$PHPSESSID&searchWhere=$searchWh&mode=$mode&cmd=$cmd&limit=$limit'><b>Next</b></a> ";
	}
	
	if($start > 1){
				$tempStr .= " &nbsp;&nbsp;&nbsp;&nbsp;<a href='$PHP_SELF?pg=" . ($start-1) ."&words=$search_term&sort=$sort&order=$order&PHPSESSID=$PHPSESSID&searchWhere=$searchWh&mode=$mode&cmd=$cmd&limit=$limit'><b>Prev</b></a> ";	
	}
	return $tempStr;
}

if ($allPages != "0") {
	$nextPage .= " |";
}
?><div align="center"><?php
echo $nextPage; ?></div><?php
///////////////////////////////////////////////////////

//Highlight search words
function search_highlight( $review, $keywords ) 
{ 
    foreach( $keywords as $word ) { 
        $review = preg_replace( "/$word/i", '<span class="searchhigh">' . $word . '</span>', "$review" ); 
    } 
    return( "$review" ); 
}//end function 

BodyFooter();
exit;
?>