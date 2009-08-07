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

$action = @$_GET['action'];
$badword = @$_REQUEST['badword'];
$goodword = @$_REQUEST['goodword'];

if ($action == "add") {
//add badword
			$sql_filter = "INSERT INTO review_badwords SET
			badword = '$badword',
			goodword = '$goodword'
			";

$sql_result_filter = mysql_query($sql_filter)
		or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));
			Header("Location: admin_bad_words.php?".sid);	
}
elseif ($action == "edit") {
	if (isset($_GET['edit']) == "do") {
	
			$sql_filter = "UPDATE review_badwords SET
			badword = '$_POST[badword]',
			goodword = '$_POST[goodword]'
			WHERE
			badword = '$_GET[oldbadword]'
			AND
			goodword = '$_GET[oldgoodword]'
			";

$sql_result_filter = mysql_query($sql_filter)
		or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));
			
			header("Location: admin_bad_words.php?".sid);	
			
	} else {
		BodyHeader("Edit Badwords","","");
		?>

<form action="admin_bad_words.php?<?php echo htmlspecialchars(SID); ?>&amp;action=edit&amp;edit=do&amp;oldbadword=<?php echo($badword); ?>&amp;oldgoodword=<?php echo($goodword); ?>" method="post">
  <div align="center">
    <table width="60%" cellpadding="2" cellspacing="1" border="0">
      <tr>
        <td colspan="3"><div align="center"><strong>Edit Badword</strong></div></td>
      </tr>
      <tr>
        <td width="40%"><input type="text" name="badword" value="<?php echo (urldecode($badword)); ?>" maxlength="100" size="30" /></td>
        <td width="40%"><input type="text" name="goodword" value="<?php echo (urldecode($goodword)); ?>" maxlength="100" size="30" /></td>
        <td width="20%"><input type="submit" value="Edit"></td>
      </tr>
    </table>
  </div>
</form>
  <div align="center">Back to <a href="admin_menu.php?<?php echo htmlspecialchars(SID); ?>">Menu</a></div>

<?php
BodyFooter();
	}
}
elseif ($action == "delete") {
	$sql = "DELETE FROM review_badwords 
			WHERE badword = '".urldecode($badword)."'
			AND
			goodword = '".urldecode($goodword)."'
			LIMIT 1
			";

        $sql_result = mysql_query($sql) 
            or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error())); 

	if (!$sql_result) {  
   BodyHeader("Couldn't Delete Record!");
		echo "<P>Couldn't delete record!";
		echo "<div align=center>Back to <a href=\"admin_menu.php?<?php echo htmlspecialchars(SID); ?>\">Menu</a></div>";
		BodyFooter();
}
				Header("Location: admin_bad_words.php?".sid);	

}
else {
BodyHeader("Badword Filter - $sitename");
?>
<style type="text/css">
<!--
.style1 {
	font-size: 16px;
	font-weight: bold;
	font-family: Arial, Helvetica, sans-serif;
}
-->
</style>
<div align="center">
  <table width="70%" border="1" align="center" cellpadding="2" cellspacing="1">
    <tr>
      <td colspan="3"><div align="center" class="style1">Badword Filter</div></td>
    </tr>
    <tr>
      <td width="33%"><div align="center"><strong>Badword</strong></div></td>
      <td width="33%"><div align="center"><strong>Goodword</strong></div></td>
      <td width="33%"><div align="center"><strong>Options</strong></div></td>
    </tr>
    <?php
		$sql = "SELECT * FROM review_badwords ORDER BY badword ASC
			";

			$sql_result = mysql_query($sql)
		or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));
		
		$num = mysql_numrows($sql_result);

		while ( $row = mysql_fetch_array($sql_result))
			{
			$badword = $row["badword"];
			$goodword = $row["goodword"];			
?>
    <tr>
      <td width="33%" align="center"><?php echo "$badword"; ?></td>
      <td width="33%" align="center"><?php echo "$goodword"; ?></td>
      <td width="33%"><div align="center"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?<?php echo htmlspecialchars(SID); ?>&action=edit&badword=<?php echo (urlencode($badword)); ?>&goodword=<?php echo (urlencode($goodword)); ?>">Edit</a> - <a href="<?php echo $_SERVER['PHP_SELF']; ?>?<?php echo htmlspecialchars(SID); ?>&action=delete&badword=<?php echo (urlencode($badword)); ?>&goodword=<?php echo (urlencode($goodword)); ?>">Delete</a> </div>
    </tr>
    <?php		}
?>
  </table>
</div>
<p />
<form action="<?php echo $_SERVER['PHP_SELF']; ?>?<?php echo htmlspecialchars(SID); ?>&action=add" method="post">
  <div align="center">
    <table width="60%" cellpadding="2" cellspacing="1" border="0">
      <tr>
        <td colspan="3"><div align="center" class="style1">Add a Badword</div></td>
      </tr>
      <tr>
        <td colspan="3" align="center" width="100%"><div align="left">Enter the badwords you wish to have replaced with the goodwords should users &quot;<em>try</em>&quot; to use profanity in their reviews. Click for
            <SCRIPT LANGUAGE="JavaScript">
<!-- Begin
function popUp(URL) {
day = new Date();
id = day.getTime();
eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=0,width=500,height=500,left = 262,top = 134');");
}
// End -->
</script>
            <A HREF="javascript:popUp('admin_badword_tips.php')">tips</A>.<br /><br /> </div></td>
      </tr>
      <tr>
        <td width="40%"><input type="text" name="badword" value="badword" maxlength="100" size="30" /></td>
        <td width="40%"><input type="text" name="goodword" value="goodword" maxlength="100" size="30" /></td>
        <td width="20%"><input type="submit" value="Add"></td>
      </tr>
    </table>
  </div>
</form>   <br /><br />
  <div align="center">Back to <a href="admin_menu.php?<?php echo htmlspecialchars(SID); ?>">Menu</a></div>

<?php
BodyFooter();
}
?>
