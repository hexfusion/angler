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

$sql = 'SELECT * FROM 
			review';

			$sql_result = mysql_query($sql)
		or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));

		if (!$sql_result) {  
   
			echo "<P>Couldn't get list!";

		} else {

		BodyHeader("Edit Review"); 
        ?>
<link href="tablesort/table.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.style1 {
	font-size: 95%
}
-->
</style>
  <br />
  <h3 align="center">Select a Review 
    from the <?php echo "$sitename"; ?> Database for Editing</h3>
<br />

<link rel="stylesheet" type="text/css" href="<?php echo "$directory"; ?>/admin/tablesort/table.css" media="all" />

<script type="text/javascript" src="<?php echo "$directory"; ?>/admin/tablesort/table.js"></script>

<form method="post" action="admin_edit2.php?<?php echo htmlspecialchars(SID); ?>">
  <div align="center">
    <table width="800" class="example table-autosort table-autofilter table-autopage:10 table-stripeclass:alternate table-page-number:t1page table-page-count:t1pages table-filtered-rowcount:t1filtercount table-rowcount:t1allcount" id="t1">
      <thead>
        <tr>
          <th width="25">&nbsp;</th>
          <th width="40" class="table-filterable table-sortable:numeric style1">ID</th>
          <th width="40" class="table-filterable table-sortable:date">Date Added</th>
          <th width="150" class="table-filterable table-sortable:default">Source</th>
          <th width="220" class="table-filterable table-sortable:default">Summary</th>
        </tr>
      </thead>
      <tbody>
        <?php
while ($row = mysql_fetch_array($sql_result)) {

	$summary = $row["summary"];
	$source = $row["source"];
	$date_added = $row["date_added"];
	$id = $row["id"];

if ($id != '') {
?>
        <tr>
          <td width="25" align="center"><a href="admin_edit2.php?sel_record=<?php echo "$id"; ?>&amp;<?php echo htmlspecialchars(SID); ?>">Edit</a></td>
          <td width="40" align="center"><?php echo "$id"; ?></td>
          <td width="40" align="center"><?php echo "$date_added"; ?></td>
          <td width="150"><?php echo "$source"; ?></td>
          <td width="220"><?php echo "$summary"; ?></td>
        </tr>
        <?php
}
}
?>
      </tbody>
      <tfoot>
        <tr>
          <td width="25">&nbsp;</td>
          <td width="40">&nbsp;</td>
          <td width="40">&nbsp;</td>
          <td width="150">&nbsp;</td>
          <td width="220">&nbsp;</td>
        </tr>
	<tr>
		<td colspan="2" class="table-page:previous" style="cursor:pointer; color:#0000FF;">&lt; &lt; Previous</td>
		<td colspan="2" style="text-align:center; font-weight:bold;">Page <span id="t1page"></span>&nbsp;of <span id="t1pages"></span></td>

		<td width="220 "class="table-page:next" style="cursor:pointer; color:#0000FF;">Next &gt; &gt;</td>
	</tr>
	<tr>
		<td colspan="5"><span id="t1filtercount"></span>&nbsp;of <span id="t1allcount"></span>&nbsp;rows match filter(s)</td>
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
