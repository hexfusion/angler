<?/* PHP **********************************************************/

////////////////////////////////////////////////////////////////////
// InverseFlow Help Desk v2.2
// -----------------------------------------------------------------
// 
// LICENSE INFO:
// -----------------------------------------------------------------
// This file can be modified and used only within
// the domain name(s) for which this help desk was purchased.
// You may not distribute or sell this help desk in its
// present form or after making your own
// modifications.  Please contact InverseFlow
// with any questions.
// -----------------------------------------------------------------
// Copyright © 2002-2003 InverseFlow
////////////////////////////////////////////////////////////////////

include "settings.php";
include "include.php";

$HD_CURPAGE = $HD_URL_DEPARTMENT;

if( $_SESSION[login_type] == $LOGIN_INVALID )
  Header( "Location: {$HD_URL_LOGIN}?redirect=" . urlencode( $HD_CURPAGE ) );

$global_priv = get_row_count( "SELECT COUNT(*) FROM {$pre}privilege WHERE ( user_id = '{$_SESSION[user][id]}' && dept_id = '0' && admin = '1' )" );

if( $_POST[cmd] == "add" )
{
  if( $global_priv )
  {
    if( !get_row_count( "SELECT COUNT(*) FROM {$pre}dept WHERE ( name = '{$_POST[name]}' )" ) )
      mysql_query( "INSERT INTO {$pre}dept ( name ) VALUES ( '{$_POST[name]}' )" );
    else
      $msg = "<div class=\"errorbox\">A department with that name already exists.</div><br />";
  }
}
else if( $_POST[cmd] == "adduser" )
{
  if( $global_priv )
  {
    mysql_query( "DELETE FROM {$pre}privilege WHERE ( user_id = '{$_POST[user]}' && dept_id = '{$_POST[dept_id]}' )" );

    if( $_POST[admin] == "on" )
      $admin = 1;
    else
      $admin = 0;

    mysql_query( "INSERT INTO {$pre}privilege ( dept_id, user_id, admin ) VALUES ( '{$_POST[dept_id]}', '{$_POST[user]}', '$admin' )" );
  }
}   
else if( $_GET[cmd] == "del" && $global_priv && $_GET[id] != 0 )
{
  mysql_query( "DELETE FROM {$pre}dept WHERE ( id = '{$_GET[id]}' )" );
  mysql_query( "DELETE FROM {$pre}post, {$pre}ticket WHERE ( {$pre}post.ticket_id = {$pre}ticket.id && {$pre}ticket.dept_id = '{$_GET[id]}' )" );
  mysql_query( "DELETE FROM {$pre}privilege WHERE ( dept_id = '{$_GET[id]}' )" );

  $res = mysql_query( "SELECT id FROM {$pre}ticket WHERE ( dept_id = '{$_GET[id]}' )" );
  while( $row = mysql_fetch_array( $res ) )
  {
    if( is_dir( "{$HD_TICKET_FILES}/{$row[id]}" ) )
      system( "rm -rf {$HD_TICKET_FILES}/{$row[id]}" );
  }

  mysql_query( "DELETE FROM {$pre}ticket WHERE ( dept_id = '{$_GET[id]}' )" );
  mysql_query( "DELETE FROM {$pre}reply WHERE ( dept_id = '{$_GET[id]}' )" );
  mysql_query( "DELETE FROM {$pre}pop WHERE ( dept_id = '{$_GET[id]}' )" );
}
else if( $_GET[cmd] == "unassign" )
{
  $priv = get_row_count( "SELECT COUNT(*) FROM {$pre}privilege WHERE ( user_id = '{$_SESSION[user][id]}' && dept_id = '{$_GET[dept_id]}' && admin = '1' )" );
  if( $priv || $global_priv )
    mysql_query( "DELETE FROM {$pre}privilege WHERE ( user_id = '{$_GET[id]}' && dept_id = '{$_GET[dept_id]}' )" );
}
else if( $_POST[cmd] == "options" )
{
  $options = ($_POST[invisible] == "on");

  if( $global_priv )
    mysql_query( "UPDATE {$pre}dept SET options = '$options' WHERE ( id = '{$_POST[dept_id]}' )" );
}

include "header.php";
/********************************************************** PHP */?>
<div class="title"><?= $script_name ?> Department Management</div><br /><?= $msg ?>
<?/* PHP **********************************************************/
if( $global_priv )
{
/********************************************************** PHP */?>
<table bgcolor="#FF8A00" border="0" cellspacing="0" cellpadding="0"><tr><td align="left"><img src="leftuptransparent.gif" align="top" />&nbsp;</td><td><div class="containertitle">Create New Department</div></td><td align="right">&nbsp;<img src="rightuptransparent.gif" align="top" /></td></tr></table>
<table width="100%" bgcolor="#FF8A00" border="0" cellspacing="1" cellpadding="4">
<form action="<?= $HD_CURPAGE ?>" method="post">
<input type="hidden" name="cmd" value="add" />
<tr><td bgcolor="#EEEEEE">
  <div class="topinfo">
    Department Name:&nbsp;<input type="text" name="name" />
    <input type="submit" value="Create">
  </div>
</td></tr>
</form>
</table>
<br />
<?/* PHP **********************************************************/
}

$res = mysql_query( "SELECT * FROM {$pre}dept" );
while( $row = mysql_fetch_array( $res ) )
{
/********************************************************** PHP */?>
<table width="100%" bgcolor="#9CC2A3" border="0" cellspacing="1" cellpadding="2">
<tr><td bgcolor="#9CC2A3">
<div class="tableheader">
<?/* PHP **********************************************************/
  if( $row[id] == 0 || !$global_priv )
    echo "<img src=\"no.gif\" align=\"absmiddle\" /> " . field( $row[name] );
  else
    echo "<a href=\"javascript:if(confirm('All tickets and subjects associated with this department will be deleted.  Are you sure you want to do this?')) window.location.href = '$HD_CURPAGE?cmd=del&id={$row[id]}'\"><img src=\"trash.gif\" border=\"0\" align=\"absmiddle\" alt=\"Delete\" /></a> " . field( $row[name] );
/********************************************************** PHP */?>
</div>
</td></tr>
<tr><td bgcolor="#FFFFFF">
<table width="100%" border="0" cellspacing="0" cellpadding="5"><tr><td>
  <table bgcolor="#FFFFFF" width="100%" border="0" cellspacing="0" cellpadding="4">
  <tr><td bgcolor="#DDDDDD" colspan="2"><div class="graycontainer">Department options:</div></td></tr>
  <tr><td><img src="blank.gif" height="2" /></td></tr>
<?/* PHP **********************************************************/
  if( $global_priv )
  {
/********************************************************** PHP */?>
  <form action="<?= $HD_CURPAGE ?>" method="post">
  <input type="hidden" name="dept_id" value="<?= $row[id] ?>">
  <input type="hidden" name="cmd" value="options">
  <tr><td colspan="2">
    <div class="normal">
      <input type="checkbox" name="invisible" <? if( $row[options] & $HD_DEPARTMENT_INVISIBLE ) echo "checked" ?>/> Make department invisible to clients (department used by staff only)&nbsp;
      <input type="submit" value="Update" />
    </div>
  </td></tr>
  </form>
<?/* PHP **********************************************************/
  }
/********************************************************** PHP */?>
  <tr><td><img src="blank.gif" height="2" /></td></tr>
  <tr><td bgcolor="#DDDDDD" colspan="2"><div class="graycontainer">Users assigned to this department:</div></td></tr>
  <tr><td><img src="blank.gif" height="2" /></td></tr>
<?/* PHP **********************************************************/
  $res_user = mysql_query( "SELECT user.id, user.name, user.admin, priv.admin, priv.id FROM {$pre}user AS user, {$pre}privilege AS priv WHERE ( priv.user_id = user.id && priv.dept_id = '{$row[id]}' )" );
  if( mysql_num_rows( $res_user ) )
  {
    while( $row_user = mysql_fetch_array( $res_user ) )
    {
      if( !$row_user[2] ) // Not an admin
        echo "<tr><td><a href=\"$HD_CURPAGE?cmd=unassign&id={$row_user[0]}&dept_id={$row[id]}\"><img src=\"trash.gif\" border=\"0\" alt=\"Unassign User\" /></a></td>";
      else
        echo "<tr><td><img src=\"no.gif\" /></td>";

      echo "<td width=\"100%\"><div class=\"normal\"><a href=\"{$HD_URL_USER}?cmd=view&id={$row_user[0]}\">" . field( $row_user[name] ) . "</a>&nbsp;&nbsp;";
      
      if( $row_user[2] )
        echo "<span class=\"smallinfo\">[Master Admin]</span>";
      else if( $row_user[3] )
        echo "<span class=\"smallinfo\">[Admin]</span>";

      echo "</div></td></tr>";
    }
  }

  if( $global_priv )
  {
/********************************************************** PHP */?>
  <form action="<?= $HD_CURPAGE ?>" method="post">
  <input type="hidden" name="dept_id" value="<?= $row[id] ?>">
  <input type="hidden" name="cmd" value="adduser">
  <tr><td colspan="2">
    <div class="normal">
      <b>Add another user to this department:</b>&nbsp;
<?/* PHP **********************************************************/
    echo "<select name=\"user\">\n";
    echo "<option>Select A User</option>\n";
    echo "<option>----------------------------</option>\n";

    $res_allusers = mysql_query( "SELECT id, name, admin FROM {$pre}user" );
    while( $row_allusers = mysql_fetch_array( $res_allusers ) )
    {
      if( !$row_allusers[admin] )
        echo "<option value=\"{$row_allusers[id]}\">{$row_allusers[name]}</option>\n";
    }

    echo "</select>";
/********************************************************** PHP */?>
      <input type="checkbox" name="admin">Department Administrator&nbsp;
      <input type="submit" value="Add">
    </div>
  </td></tr>
  </form>
<?/* PHP **********************************************************/
  }
/********************************************************** PHP */?>
  </table>
</td></tr></table>
</td></tr>
</table>
<?/* PHP **********************************************************/
}

/********************************************************** PHP */?>
<?/* PHP **********************************************************/
include "footer.php";
/********************************************************** PHP */?>