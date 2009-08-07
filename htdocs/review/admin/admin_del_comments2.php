<?php
//if a session does not yet exist for this user, start one
session_start();

//make sure user has been logged in.
$validU = "";

if (isset($_SESSION['valid_user']) && $_SESSION['valid_user'] != "")
    $validU = $_SESSION['valid_user'];

if ($validU == "") {
    // User not logged in, redirect to login page
    Header("Location: index.php");
}

include ("../body.php");
include ("../functions.php");
include ("../f_secure.php");
include ("../config.php");

$sel_record = $_POST['sel_record'];

if (!$sel_record) {
    header("Location: http://$url$directory/admin_del1.php?" . sid);
    exit;
}

$sql = "select *
from review_comment
where
id = '$sel_record'
";

$sql_result = mysql_query($sql) or die(sprintf("Couldn't execute query, %s: %s",
    db_errno(), db_error()));


if (!$sql_result) {

    echo "<P>Couldn't get item!";

} else {

    while ($row = mysql_fetch_array($sql_result)) {
        $id = $row["id"];
        $author = $row["author"];
        $time = $row["time"];
        $comment = $row["comment"];
    }

    BodyHeader("Delete Comment!","","");
?>
<center>
  <h1><font face="Verdana, Arial, Helvetica, sans-serif" size="3">You have selected 
    the following comment to DELETE:</font></h1>
</center>
<FORM method="POST" action="admin_del_comments3.php?<?php echo htmlspecialchars(SID); ?>">
  <input type="hidden" name="sel_record" value=<?php echo $sel_record ?>>
  <table cellspacing=5 cellpadding=5>
    <tr> 
      <td valign=top><strong>Author:</strong></td>
      <td valign=top> <?php echo "$author" ?> 
      </td>
    </tr>
    <tr> 
      <td valign=top><strong>Date:</strong></td>
      <td valign=top><?php echo $time ?></td>
      </td>
    </tr>
    <tr> 
      <td valign=top><strong>Comment:</strong></td>
      <td valign=top><?php echo $comment ?></td>
     </td>
    </tr>
    
  
    <tr> 
      <td align=center colspan=2>   
	  <input type=hidden name="author" value="<?php echo htmlspecialchars($author); ?>">
  <input type=hidden name="comment" value="<?php echo htmlspecialchars($comment); ?>">
  <input type=hidden name="time" value="<?php echo htmlspecialchars($time); ?>">
  <br /><br /><INPUT type="submit" value="Delete Comment"> 
      </td>
    </tr>
  </table>
</FORM>
  <div align="center">Back to <a href="admin_menu.php?<?php echo
    htmlspecialchars(SID); ?>">Menu</a></div>
<?php
    BodyFooter();
}
?>
