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

		$sql = "SELECT *, SUBSTRING_INDEX(comment, ' ', 3) as comment  FROM review_comment
			 ORDER BY time DESC";
			
			$sql_result = mysql_query($sql)
		or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));

		if (!$sql_result) {  
   
			echo "<P>Couldn't get list!";

		} else {

		BodyHeader("$sitename: Delete a Comment"); 
        ?>

<center>
  <h2><font face="Verdana, Arial, Helvetica, sans-serif" size="3">Select a Comment for Deletion </font></h2>
</center>
<FORM method="POST" action="admin_del_comments2.php?<?php echo htmlspecialchars(SID); ?>">
  <table width="52%" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td width="100%"><p align="center"><strong>Author/Comment/Date Added:</strong></td>
    </tr>
    <tr>
      <td width="100%"><p align="center">
          <select name="sel_record">
            <option value=""> -- Select a Comment -- </option>
            <?php
while ($row = mysql_fetch_array($sql_result)) {

	$author = clean($row["author"]);
	$time = clean($row["time"]);
	$comment = $row["comment"];
	$id = $row["id"];

echo "
	<option value=\"$id\">$id  :  $author : $comment : $time </option>
";
}
?>
          </select>
      </td>
    </tr>
    <tr>
      <td align=center colspan=2><BR>
        <INPUT type="submit" value="Select Comment">
      </td>
    </tr>
  </table>
</FORM>
<div align=center>Back to <a href=admin_menu.php?<?php echo htmlspecialchars(SID); ?>>Menu</a><br />
</div>
<?php
        BodyFooter();
		exit;
}
?>
