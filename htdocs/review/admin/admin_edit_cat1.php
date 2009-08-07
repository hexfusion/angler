<?php
//if a session does not yet exist for this user, start one
session_start();

//make sure user has been logged in.
$validU="";

if (isset($_SESSION['valid_user']) && $_SESSION['valid_user']!="")$validU=$_SESSION['valid_user']; 

if ($validU=="")
	{
	// User not logged in, redirect to login page
	Header("Location: index.php");
	}

include ("../body.php");
include ("../functions.php");
include ("../f_secure.php");
include ("../config.php");

$sql = 'SELECT category FROM 
			review_category_list ORDER BY category ASC';

			$sql_result = mysql_query($sql)
		or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));

		if (!$sql_result) {  
   
			echo "<P>Couldn't get list!";

		} else {

		BodyHeader("Edit a Category","",""); 
        ?>
<br />
  <h3 align="center">Select a Category 
    from the <?php echo "$sitename"; ?> Database for Editing</h3>
<br />

  
<link rel="stylesheet" type="text/css" href="<?php echo "$directory"; ?>/admin/tablesort/table.css" media="all" />
<script type="text/javascript" src="<?php echo "$directory"; ?>/admin/tablesort/table.js"></script>
  
<FORM name="editFrm" method="POST" action="admin_edit_cat2.php?<?php echo htmlspecialchars(SID); ?>" onsubmit="javascript: return handleSubmit();">

<div align="center">
    <table width="800" class="example table-autosort table-autofilter table-autopage:10 table-stripeclass:alternate table-page-number:t1page table-page-count:t1pages table-filtered-rowcount:t1filtercount table-rowcount:t1allcount" id="t1">
      <thead>
        <tr>
          <th width="25">&nbsp;</th>
          <th width="40" class="table-filterable table-sortable:numeric style1">ID</th>
          <th width="40" class="table-filterable table-sortable:default">Category</th>
        </tr>
      </thead>
      <tbody>

<?php
	function displayCat($catId, $dashes = 0){
		$st = "";
		$query = 'select * from review_category_list where parent_id = ' .$catId. ' ORDER BY category ASC';
		for($i=0; $i<$dashes; $i++){
			$st .= "--";
		}
		
		$dashes += 2;
		$result = mysql_query($query);
		while($row = mysql_fetch_array($result)){
			?>
           
           <tr>
          <td width="25" align="center"><a href="admin_edit_cat2.php?sel_record=<?php echo $row["cat_id_cloud"]; ?>&amp;<?php echo htmlspecialchars(SID); ?>">Edit</a></td>
          <td width="40" align="center"><?php echo $row["cat_id_cloud"]; ?></td>
          <td width="40" align="center"><?php echo "$st" . $row["category"]; ?></td>

        </tr>
        <?php
				displayCat($row["cat_id_cloud"], $dashes);
		}
	}
	displayCat(-1);
?>
</tbody>
      <tfoot>
        <tr>
          <td width="25">&nbsp;</td>
          <td width="40">&nbsp;</td>
          <td width="40">&nbsp;</td>
        </tr>
	<tr>
		<td colspan="1" class="table-page:previous" style="cursor:pointer; color:#0000FF;">&lt; &lt; Previous</td>
		<td colspan="1" style="text-align:center; font-weight:bold;">Page <span id="t1page"></span>&nbsp;of <span id="t1pages"></span></td>

		<td width="220 "class="table-page:next" style="cursor:pointer; color:#0000FF;">Next &gt; &gt;</td>
	</tr>
	<tr>
		<td colspan="3"><span id="t1filtercount"></span>&nbsp;of <span id="t1allcount"></span>&nbsp;rows match filter(s)</td>
</tfoot>
</table>
  </div>
</form>
<br />
<div align="center">Back to <a href="admin_menu.php?<?php echo htmlspecialchars(SID); ?>">Menu</a><br />
</div>
<?php
 
        BodyFooter();
		exit;
}
?>

