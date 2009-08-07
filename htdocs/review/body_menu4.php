<?php include ("config.php"); ?>


<table width="100%" align="center">
  <tr><td width="100%"><p align="center"><span> | <a href="<?php echo "$directory"; ?>/index.php?<?=@SID?>">Home</a>&nbsp;
          |<? if (@$_SESSION['signedin'] != "y") { ?>
          <a href="<?php echo "$directory"; ?>/login/signin.php?<?=@SID?>">Login</a><? } else { 
@$back = $_SERVER['PHP_SELF'];
?>
          <a href="<?php echo "$directory"; ?>/logout.php?<?=SID?>">Logout</a>
          <? } ?>
          <? 
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
	<? 
		if($temp_item!=""){
	?>
		<a href="<?php echo "$directory"; ?>/login/register.php?item_id=<? echo @$temp_item; ?>">Register</a>
	<? }else{?>
		<a href="<?php echo "$directory"; ?>/login/register.php">Register</a>
	<? }
	//changes ends here
	?>
<? } else { 
$back = $_SERVER['PHP_SELF'];
?>
<? } ?>
 | </span><a href="<?php echo "$directory"; ?>/index2.php?item_id=1&<?=@SID?>">Review</a> | <a href="<?php echo "$directory"; ?>/usercp/index.php?<?=@SID?>">User CP</a> |
 <a href="<?php echo "$directory"; ?>/rss/rss.xml" title="rss xml reviews" alt="rss xml reviews" class="rss-button rss">RSS</a> |
     <? 
 	$words="";
	if (isset($_GET['words']) && $_GET['words']!="")
	{
		@$words=stripslashes($_GET['words']);
	}
 ?>

	  <form method="get" action="<?php echo "$directory"; ?>/search/index.php?<?=@SID?>">
	    <div align="center">
	      <input type="hidden" name="cmd" value="search" />
	      Search for: 
	      <input name="words" type="text" value="<?php echo "$words"; ?>" size="10" maxlength="255" />
 &nbsp;in &nbsp;
 <select name="searchWhere">
 	<option value="0" <? if(@$_GET['searchWhere']=="0") echo "selected" ?>>Reviews</option>
 	<option value="1" <? if(@$_GET['searchWhere']=="1") echo "selected" ?>>Summary</option>
	<option value="2" <? if(@$_GET['searchWhere']=="2") echo "selected" ?>>Item Name</option>
	<option value="3" <? if(@$_GET['searchWhere']=="3") echo "selected" ?>>Categories</option>
 </select>
 <input name="mode" type="hidden" value="normal">
 &nbsp;
 <input type="submit" class="submit-button" value="Search" />
	    </div>
	  </form>
      </td>
  </tr></table>
