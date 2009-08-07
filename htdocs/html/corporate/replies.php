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

$HD_CURPAGE = $HD_URL_REPLIES;

if( $_SESSION[login_type] == $LOGIN_INVALID )
  Header( "Location: {$HD_URL_LOGIN}?redirect=" . urlencode( $HD_CURPAGE ) );

$global_priv = get_row_count( "SELECT COUNT(*) FROM {$pre}privilege WHERE ( user_id = '{$_SESSION[user][id]}' && dept_id = '0' && admin = '1' )" );

if( $_GET[cmd] == "del" )
{
  $priv = get_row_count( "SELECT COUNT(*) FROM {$pre}privilege WHERE ( user_id = '{$_SESSION[user][id]}' && dept_id = '{$_GET[dept_id]}' && admin = '1' )" );
  if( $priv || $global_priv )
  {
    mysql_query( "DELETE FROM {$pre}reply WHERE ( id = '{$_GET[id]}' && dept_id = '{$_GET[dept_id]}' )" );
    $msg = "<div class=\"successbox\">Successfully deleted auto-reply.</div><br />";
  }
}

include "header.php";
/********************************************************** PHP */?>
<div class="title"><?= $script_name ?> Auto-Replies</div><br /><?= $msg ?>
<table width="100%" bgcolor="#EEEEEE" border="0" cellpadding="5">
<tr><td>
  <div class="graycontainer">
    You can view auto-replies in this area.  Below are a list of the departments and their corresponding auto-replies.  You
    can create or delete auto-replies for departments in which you have administrative privileges.
  </div>
</td></tr>
</table>
<br />
<?/* PHP **********************************************************/
$res = mysql_query( "SELECT * FROM {$pre}dept" );
while( $row = mysql_fetch_array( $res ) )
{
/********************************************************** PHP */?>
<table width="100%" bgcolor="#9CC2A3" border="0" cellspacing="1" cellpadding="2">
<tr><td bgcolor="#9CC2A3">
<div class="tableheader"><?= field( $row[name] ) ?></div>
</td></tr>
<tr><td bgcolor="#FFFFFF">
<table width="100%" border="0" cellspacing="0" cellpadding="5"><tr><td>
  <table bgcolor="#FFFFFF" width="100%" border="0" cellspacing="0" cellpadding="4">
  <tr><td bgcolor="#DDDDDD" colspan="2"><div class="graycontainer">Auto-replies for the following phrases will be used in this department:</div></td></tr>
  <tr><td><img src="blank.gif" height="2" /></td></tr>
<?/* PHP **********************************************************/
  $res_reply = mysql_query( "SELECT * FROM {$pre}reply WHERE ( dept_id = '{$row[id]}' )" );
  $priv = get_row_count( "SELECT COUNT(id) FROM {$pre}privilege WHERE ( dept_id = '{$row[id]}' && admin = '1' && user_id = '{$_SESSION[user][id]}' )" );

  if( mysql_num_rows( $res_reply ) )
  {
    while( $row_reply = mysql_fetch_array( $res_reply ) )
    {
      if( $global_priv || $priv )
        echo "<tr><td><a href=\"javascript:if(confirm('Are you sure you want to delete this auto-reply?')) window.location.href = '$HD_CURPAGE?cmd=del&id={$row_reply[0]}&dept_id={$row_reply[dept_id]}'\"><img src=\"trash.gif\" border=\"0\" hspace=\"2\" alt=\"Delete\" /></a><a href=\"$HD_URL_REPLIESVIEW?cmd=edit&id={$row_reply[0]}\"><img src=\"edit.gif\" border=\"0\" hspace=\"2\" alt=\"View/Edit\" /></a></td>";
      else
        echo "<tr><td><img src=\"no.gif\" border=\"0\" hspace=\"2\" /></a><a href=\"$HD_URL_REPLIESVIEW?cmd=edit&id={$row_reply[0]}\"></a></td>";

      echo "<td width=\"100%\"><div class=\"normal\"><a href=\"{$HD_URL_REPLIESVIEW}?cmd=edit&id={$row_reply[0]}\">" . (($row_reply[phrase] == "") ? "[Global Auto-Reply - All Phrases]" : field( $row_reply[phrase] )) . "</a>";
    }
  }

  if( $priv || $global_priv )
    echo "<tr><td colspan=\"2\"><div class=\"normal\"><b><a href=\"$HD_URL_REPLIESVIEW\">Create A New Auto-Reply</a></b></div></td></tr>";
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