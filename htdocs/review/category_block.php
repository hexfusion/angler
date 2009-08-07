<?php
//session_start();
// include the config files
//include ("body.php");
//include ("functions.php");
//include ("f_secure.php");
//include ("config.php");

		//get list of authors from database
		$link_count = "SELECT DISTINCT category FROM review_category_list WHERE category != '' ORDER BY category ASC";
		$result = mysql_query($link_count) or die(sprintf("Couldn't execute sql_count, %s: %s", db_errno(), db_error()));
		?>
<script language="JavaScript" type="text/javascript">
	function submitIt(){
		document.formCat.action="<?php echo "$directory"; ?>/review-category/" + document.formCat.category.value + ".php?<?php echo htmlspecialchars(SID); ?>";
		document.formCat.submit();
		return true;		
	}
</script>
<form name="formCat" id="formCat" action="" method="post">
  <div align="center">
    <select name="category" class="select" onchange="javascript: return submitIt()">
      <option value=""> -- Select a Category -- </option>
      <?php
		function displayCat($catId, $dashes = 0){
			$st = "";
			$query = 'select * from review_category_list where parent_id = ' .$catId. ' order by catorder';
			for($i=0; $i<$dashes; $i++){
				$st .= "--";
			}
			
			$dashes += 2;
			$result = mysql_query($query);
			while($row = mysql_fetch_array($result)){
				echo "<option value='" .$row["cat_id_cloud"]."'>" .$st . $row["category"] . "</option>";
				displayCat($row["cat_id_cloud"], $dashes);
			}
		}
		displayCat(-1);
	?>
    </select>
    <noscript>
    <input type="submit" value="Submit" />
    </noscript>
  </div>
</form>
