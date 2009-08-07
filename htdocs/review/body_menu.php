<?php include ("config.php"); ?>


<table width="100%" align="center">
  <tr><td width="100%"><p align="center"><span> | <a href="<?php echo "$directory"; ?>/index.php?<?php echo htmlspecialchars(SID); ?>">Home</a>&nbsp;
          |<?php if (@$_SESSION['signedin'] != "y") { ?>
          <a href="<?php echo "$directory"; ?>/login/signin.php?<?php echo htmlspecialchars(SID); ?>">Login</a><?php } else { 
@$back = $_SERVER['PHP_SELF'];
?>
          <a href="<?php echo "$directory"; ?>/logout.php?<?php echo htmlspecialchars(SID); ?>">Logout</a>
          <?php } ?>
          <?php 
		  if (@$_SESSION['signedin'] != "y") { 
		 
		 
		  	//changes done on 23 Jan 2007
			$temp_item=@$_GET['item_id'];
			$temp_item = htmlspecialchars($temp_item, ENT_QUOTES);
			$temp_item = makeStringSafe($temp_item);
			if($temp_item!=""){
				if(! is_numeric($temp_item)) $temp_item="";
			}
						
		  ?>
	&nbsp;|
	<?php 
		if($temp_item!=""){
	?>
		<a href="<?php echo "$directory"; ?>/login/register.php?item_id=<?php echo @$temp_item; ?>">Register</a>
	<?php }else{?>
		<a href="<?php echo "$directory"; ?>/login/register.php">Register</a>
	<?php }
	//changes ends here
	?>
<?php } else { 
$back = $_SERVER['PHP_SELF'];
?>
<?php } ?>
 | </span><a href="<?php echo "$directory"; ?>/index2.php?item_id=1&<?php echo htmlspecialchars(SID); ?>">Review</a> | <a href="<?php echo "$directory"; ?>/usercp/index.php?<?php echo htmlspecialchars(SID); ?>">User CP</a> |
 <a href="<?php echo "$directory"; ?>/rss/rss.xml" title="rss xml reviews" alt="rss xml reviews" class="rss-button rss">RSS</a> |
     <?php 
 	$words="";
	if (isset($_GET['words']) && $_GET['words']!="")
	{
		@$words=stripslashes($_GET['words']);
	}
 ?>

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
      </td>
  </tr></table>