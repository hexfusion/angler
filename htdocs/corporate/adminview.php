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

$HD_CURPAGE = $HD_URL_ADMINVIEW;

function attach_sort( $a, $b )
{
  if( $a[0] == $b[0] )
    return 0;
  else
    return ($a[0] > $b[0]) ? -1 : 1;
}

if( $_SESSION[login_type] == $LOGIN_INVALID )
  Header( "Location: {$HD_URL_LOGIN}?redirect=" . urlencode( $HD_CURPAGE . "?id={$_GET[id]}" ) );

$options = array( "email", "url", "title", "emailheader", "emailfooter", "tags", "uploads", "email_ticket_notify", "email_ticket_notify_subject", "autosurvey" );
$data = get_options( $options );

if( isset( $_POST[id] ) )
  $_GET[id] = $_POST[id];

$ticketexists = 0;

if( $_GET[id][0] == 'M' )
  $is_ticket = 0;
else
  $is_ticket = 1;

if( $is_ticket )
{
  $exists = get_row_count( "SELECT COUNT(*) FROM {$pre}ticket AS ticket, {$pre}privilege AS priv WHERE ( ticket.ticket_id = '{$_GET[id]}' && priv.user_id = '{$_SESSION[user][id]}' && (priv.dept_id = ticket.dept_id || priv.dept_id = 0) )" );
  if( !$exists )
  {
    $msg = "<div class=\"errorbox\">Either you are not assigned to the department that this ticket was routed to or this ticket no longer exists.</div><br />";
    $ticketexists = 0;
  }
  else
    $ticketexists = 1;
}
else
{
  $res = mysql_query( "SELECT id FROM {$pre}ticket WHERE ( ticket_id = '{$_GET[id]}' )" );
  $row = mysql_fetch_array( $res );
  $message_id = $row[0];

  $exists = get_row_count( "SELECT COUNT(*) FROM {$pre}message WHERE ( ticket_id = '$message_id' && user_id = '{$_SESSION[user][id]}' )" );    
  if( !$exists )
  {
    $msg = "<div class=\"errorbox\">Either you are not a recipient of this message or it no longer exists.</div><br />";
    $ticketexists = 0;
  }
  else
  {
    mysql_query( "UPDATE {$pre}message SET viewed = '1' WHERE ( ticket_id = '$message_id' )" );
    $ticketexists = 1;
  }
}

if( $ticketexists )
{
  // Get row before updates
  $row = mysql_fetch_array( mysql_query( "SELECT * FROM {$pre}ticket WHERE ( ticket_id = '{$_GET[id]}' )" ) );

  if( $_POST[cmd] == "reply" )
  {
    if( trim( $_POST[message] ) == "" )
      $msg = "<div class=\"errorbox\">You must specify a message in your reply (subjects are optional).</div><br />";
    else
    {
      $private = ($_POST[private] == "on");

      if( $is_ticket )
      {
        if( !$private )
        {
          // Send notification if necessary
          if( $row[notify] )
          {
            $ticket = $_GET[id];
            $email = $row[email];
            $name = stripslashes( $row[name] );
            $subject = stripslashes( $row[subject] );
            $message = stripslashes( $_POST[message] );

            eval( "\$sub = \"{$data[email_ticket_notify_subject]}\";" );
            eval( "\$mes = \"{$data[email_ticket_notify]}\";" );
            mail( $row[email], $sub, $mes, "From: {$data[email]}" );
          }
        }
      }

      mysql_query( "INSERT INTO {$pre}post ( ticket_id, user_id, date, subject, message, private ) VALUES ( '{$row[id]}', '{$_SESSION[user][id]}', '" . time( ) . "', '{$_POST[subject]}', '$_POST[message]', '$private' )" );

      mysql_query( "UPDATE {$pre}message SET viewed = '0' WHERE ( ticket_id = '{$row[id]}' && user_id != '{$_SESSION[user][id]}' )" );

      if( $_POST[close] == "on" )
      {
        if( $data[autosurvey] )
          send_survey( $row[id] );

        mysql_query( "UPDATE {$pre}ticket SET lastactivity = '" . time( ) . "', status = '$HD_STATUS_CLOSED', lastpost = '{$_SESSION[user][id]}' WHERE ( ticket_id = '{$_GET[id]}' )" );
      }
      else
        mysql_query( "UPDATE {$pre}ticket SET lastactivity = '" . time( ) . "', lastpost = '{$_SESSION[user][id]}' WHERE ( ticket_id = '{$_GET[id]}' )" );

      if( $_POST[save] == "on" )
      {
        if( trim( $_POST[replyname] ) != "" )
        {
          if( get_row_count( "SELECT COUNT(*) FROM {$pre}reply WHERE ( phrase = '{$_POST[replyname]}' && dept_id = '-1' )" ) )
            mysql_query( "UPDATE {$pre}reply SET reply = '{$_POST[message]}' WHERE ( phrase = '{$_POST[replyname]}' )" );
          else
            mysql_query( "INSERT INTO {$pre}reply ( dept_id, reply, phrase ) VALUES ( '-1', '{$_POST[message]}', '{$_POST[replyname]}' )" );
        }
      }
    }
  }
  else if( $_POST[cmd] == "update" )
  {
    if(  $_POST[department] == -1 )
      $_POST[department] = $row[dept_id];

    mysql_query( "UPDATE {$pre}ticket SET lastactivity = '" . time( ) . "', status = '{$_POST[status]}', dept_id = '{$_POST[department]}', priority = '{$_POST[priority]}' WHERE ( ticket_id = '{$_POST[id]}' )" );

    if( ($_POST[status] == $HD_STATUS_CLOSED) && $data[autosurvey] )
      send_survey( $row[id] );
  }
  else if( $_POST[cmd] == "flag" )
  {
    mysql_query( "UPDATE {$pre}ticket SET lastactivity = '" . time( ) . "', flag = '{$_POST[flag]}' WHERE ( ticket_id = '{$_POST[id]}' )" );
  }
  else if( $_GET[cmd] == "delete" )
  {
    if( $is_ticket )
    {
      mysql_query( "DELETE FROM {$pre}ticket WHERE ( ticket_id = '{$_GET[id]}' )" );
      mysql_query( "DELETE FROM {$pre}post WHERE ( ticket_id = '{$row[id]}' )" );
      mysql_query( "DELETE FROM {$pre}message WHERE ( ticket_id = '{$row[id]}' )" );

      if( is_dir( "{$HD_TICKET_FILES}/{$row[id]}" ) )
        system( "rm -rf {$HD_TICKET_FILES}/{$row[id]}" );

      if( $is_ticket )
        $msg = "<div class=\"successbox\">Ticket deleted successfully.</div><br />";
      else
        $msg = "<div class=\"successbox\">Thread deleted successfully.</div><br />";

      $ticketexists = 0;
    }
    else
    {
      mysql_query( "DELETE FROM {$pre}message WHERE ( ticket_id = '{$row[id]}' && user_id = '{$_SESSION[user][id]}' )" );
      if( !get_row_count( "SELECT COUNT(*) FROM {$pre}message WHERE ( ticket_id = '{$row[id]}' )" ) )
      {
        mysql_query( "DELETE FROM {$pre}ticket WHERE ( ticket_id = '{$_GET[id]}' )" );
        mysql_query( "DELETE FROM {$pre}post WHERE ( ticket_id = '{$row[id]}' )" );
        mysql_query( "DELETE FROM {$pre}message WHERE ( ticket_id = '{$row[id]}' )" );

        if( is_dir( "{$HD_TICKET_FILES}/{$row[id]}" ) )
          system( "rm -rf {$HD_TICKET_FILES}/{$row[id]}" );
      }

      $msg = "<div class=\"successbox\">You have removed yourself from this thread.</div><br />";
    }
  }
  else if( $_GET[cmd] == "deletepost" )
    mysql_query( "DELETE FROM {$pre}post WHERE ( id = '{$_GET[postid]}' && ticket_id = '{$row[id]}' )" );
  else if( $_GET[cmd] == "close" )
    mysql_query( "UPDATE {$pre}ticket SET lastactivity = '" . time( ) . "', status = '{$HD_STATUS_CLOSED}' WHERE ( ticket_id = '{$_GET[id]}' )" );
  else if( $_POST[cmd] == "deletereply" )
    mysql_query( "DELETE FROM {$pre}reply WHERE ( phrase = '{$_POST[replyname]}' && dept_id = '-1' )" );
  else if( $_POST[cmd] == "attach" && (trim( $HTTP_POST_FILES["userfile"]["name"] ) != "") )
  {
    if( !is_dir( "{$HD_TICKET_FILES}/{$row[id]}" ) )
    {
      $oldumask = umask( 0 ); 
      mkdir( "{$HD_TICKET_FILES}/{$row[id]}", 0777 );
      umask( $oldumask );
    }

    copy( $HTTP_POST_FILES["userfile"]["tmp_name"], "{$HD_TICKET_FILES}/{$row[id]}/" . basename( $HTTP_POST_FILES["userfile"]["name"] ) );
  }

  // Get row after possible updates
  $row = mysql_fetch_array( mysql_query( "SELECT * FROM {$pre}ticket WHERE ( ticket_id = '{$_GET[id]}' )" ) );

  if( $is_ticket )
    $res_others = mysql_query( "SELECT ticket.* FROM {$pre}ticket AS ticket, {$pre}privilege AS priv WHERE ( ticket.email = '{$row[email]}' && ticket.id != '{$row[id]}' && priv.user_id = '{$_SESSION[user][id]}' && (priv.dept_id = ticket.dept_id || priv.dept_id = 0) ) ORDER BY date DESC" );
}

include "header.php";
/********************************************************** PHP */?>
<div class="title"><?= $script_name ?> Viewing Ticket</div><br /><?= $msg ?>
<?/* PHP **********************************************************/
if( $ticketexists )
{
/********************************************************** PHP */?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#AAAAAA"><tr><td><img src="blank.gif" width="1" height="1" /></td></tr></table>
<img src="blank.gif" width="1" height="5" /><br />
<table border="0" cellspacing="0" cellpadding="0">
<tr>
<td><a href="#reply"><img src="edit.gif" alt="Post Reply" border="0" hspace="5" valign="middle" /></a></td><td><div class="normal"><a href="#reply">Post Reply</a>&nbsp;&nbsp;&nbsp;</div></td>
<td><a href="printable.php?id=<?= $_GET[id] ?>" target="_blank"><img src="print.gif" alt="Print" border="0" hspace="5" valign="middle" /></a></td><td><div class="normal"><a href="printable.php?id=<?= $_GET[id] ?>" target="_blank">Print</a>&nbsp;&nbsp;&nbsp;</div></td>
<?/* PHP **********************************************************/
  if( $data[uploads] )
  {
/********************************************************** PHP */?>
<td><a href="#attach"><img src="attach.gif" alt="Attach File" border="0" hspace="5" valign="middle" /></a></td><td><div class="normal"><a href="#attach">Attach File</a>&nbsp;&nbsp;&nbsp;</div></td>
<?/* PHP **********************************************************/
  }
/********************************************************** PHP */?>
<td><a href="javascript:if(confirm('Are you sure?')) window.location.href = '<?= $HD_CURPAGE . "?cmd=delete&id={$_GET[id]}" ?>'"><img src="trash.gif" alt="Delete<? if( $is_ticket ) echo " Ticket" ?>" border="0" hspace="5" valign="middle" /></a></td><td><div class="normal"><a href="javascript:if(confirm('Are you sure?')) window.location.href = '<?= $HD_CURPAGE . "?cmd=delete&id={$_GET[id]}" ?>'">Delete<? if( $is_ticket ) echo " Ticket" ?></a>&nbsp;&nbsp;&nbsp;</div></td>
<?/* PHP **********************************************************/
  if( $is_ticket && mysql_num_rows( $res_others ) )
  {
/********************************************************** PHP */?>
<td><a href="#others"><img src="browse_newreply.gif" alt="Other Tickets" border="0" hspace="5" valign="middle" /></a></td><td><div class="normal"><a href="#others">Ticket History</a>&nbsp;&nbsp;&nbsp;</div></td>
<?/* PHP **********************************************************/
  }
/********************************************************** PHP */?>
</tr>
</table>

<?/* PHP **********************************************************/
  if( $is_ticket )
  {
/********************************************************** PHP */?>
<img src="blank.gif" width="1" height="8" /><br />
<table border="0" cellspacing="0" cellpadding="0">
<form action="<?= $HD_CURPAGE ?>" method="post">
<input type="hidden" name="id" value="<?= $_GET[id] ?>" />
<input type="hidden" name="cmd" value="update" />
<tr>
<td><div class="normal">Move To:&nbsp;
<select name="department">
<option value="-1">(No Move)</option>
<?/* PHP **********************************************************/
    $res_dept = mysql_query( "SELECT id, name FROM {$pre}dept" );
    while( $row_dept = mysql_fetch_array( $res_dept ) )
    {
      if( $row_dept[id] != 0 && $row_dept[id] != $row[dept_id] )
        echo "<option value=\"{$row_dept[id]}\">" . field( $row_dept[name] ) . "</option>\n";
    }
/********************************************************** PHP */?>
</select>&nbsp;&nbsp;
Priority:&nbsp;
<select name="priority">
<option value="<?= $PRIORITY_LOW ?>" <?= ($row[priority] == $PRIORITY_LOW) ? "selected" : "" ?>>Low</option>
<option value="<?= $PRIORITY_MEDIUM ?>" <?= ($row[priority] == $PRIORITY_MEDIUM) ? "selected" : "" ?>>Medium</option>
<option value="<?= $PRIORITY_HIGH ?>" <?= ($row[priority] == $PRIORITY_HIGH) ? "selected" : "" ?>>High</option>
</select>&nbsp;&nbsp;
Status:&nbsp;
<select name="status">
<option value="<?= $HD_STATUS_OPEN ?>" <?= ($row[status] == $HD_STATUS_OPEN) ? "selected" : "" ?>>Open</option>
<option value="<?= $HD_STATUS_CLOSED ?>" <?= ($row[status] == $HD_STATUS_CLOSED) ? "selected" : "" ?>>Closed</option>
<option value="<?= $HD_STATUS_HELD ?>" <?= ($row[status] == $HD_STATUS_HELD) ? "selected" : "" ?>>Held</option>
</select>&nbsp;&nbsp;
<input type="submit" value="Update" />
</div></td>
</tr>
</form>
</table>
<?/* PHP **********************************************************/
  }
/********************************************************** PHP */?>

<img src="blank.gif" width="1" height="5" /><br />

<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#AAAAAA"><tr><td><img src="blank.gif" width="1" height="1" /></td></tr></table>
<br />

<?/* PHP **********************************************************/
  if( $is_ticket )
  {
/********************************************************** PHP */?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<form action="<?= $HD_CURPAGE ?>" method="post">
<input type="hidden" name="id" value="<?= $_GET[id] ?>" />
<input type="hidden" name="cmd" value="flag" />
<tr>
<td align="right">
<div class="normal"><img src="browse_flag.gif" /> Flag For:&nbsp;
<select name="flag">
<option value="-1" <? if( $row[flag] == -1 ) echo "selected" ?>>(No Flag)</option>
<option value="0" <? if( $row[flag] == 0 ) echo "selected" ?>>(All Users)</option>
<?/* PHP **********************************************************/
    $res_users = mysql_query( "SELECT name, id FROM {$pre}user" );
    while( $row_users = mysql_fetch_array( $res_users ) )
      echo "<option value=\"{$row_users[id]}\"" . (($row[flag] == $row_users[id]) ? " selected" : "") . ">" . field( $row_users[name] ) . "</option>\n";
/********************************************************** PHP */?>
</select>
<input type="submit" value="OK" />
</div></td>
</tr>
</form>
</table>
<?/* PHP **********************************************************/
  }
/********************************************************** PHP */?>

<table border="0" cellspacing="0" cellpadding="2">
<tr><td align="right"><div class="normal"><b>Subject:</b></div></td><td><div class="normal"><?= field( $row[subject] ) ?></div></td></tr>
<tr><td align="right"><div class="normal"><b>Created On:</b></div></td><td><div class="normal"><?= date( "F j, Y g:ia T", $row[date] ) ?></div></td></tr>
<?/* PHP **********************************************************/
  if( $is_ticket )
  {
/********************************************************** PHP */?>
<tr><td align="right"><div class="normal"><b>Department:</b></div></td><td><div class="normal">
<?/* PHP **********************************************************/
    $res_dept = mysql_query( "SELECT name FROM {$pre}dept WHERE ( id = '{$row[dept_id]}' )" );
    $row_dept = mysql_fetch_array( $res_dept );
    echo field( $row_dept[0] );
/********************************************************** PHP */?>
</div></td></tr>
<tr><td align="right"><div class="normal"><b>Priority:</b></div></td><td><div class="normal"><? if( $row[priority] == $PRIORITY_LOW ) echo "Low"; else if( $row[priority] == $PRIORITY_MEDIUM ) echo "Medium"; else echo "High"; ?></div></td></tr>
<?/* PHP **********************************************************/
    if( trim( $row[custom] ) != "" )
    {
      echo "<tr><td><br /></td><td></td></tr>";
      echo "<tr bgcolor=\"#DDDDDD\"><td colspan=\"2\"><div class=\"normal\"><i>Custom Fields:</i></div></td></tr>";

      $fields = split( "\n", $row[custom] );
      for( $i = 0; $i < count( $fields ); $i += 2 )
      {
        if( trim( $fields[$i] ) != "" )
          echo "<tr bgcolor=\"#EEEEEE\"><td align=\"right\"><div class=\"normal\"><b>" . field( $fields[$i] ) . ":</b></div></td><td><div class=\"normal\">" . field( $fields[$i+1] ) . "</div></td></tr>\n";
      }
    }
  }
/********************************************************** PHP */?>
</table>
<br />
<?/* PHP **********************************************************/
  if( $dir = @opendir( "{$HD_TICKET_FILES}/{$row[id]}" ) )
  {
    $files = array( );

    echo "<div class=\"normal\"><font size=\"1\">Attachments: ";
    while( $file = readdir( $dir ) )
    {
      if( $file != "." && $file != ".." )
        array_push( $files, array( filectime( "{$HD_TICKET_FILES}/{$row[id]}/{$file}" ), $file ) );
    }

    usort( $files, "attach_sort" );

    for( $i = 0; $i < count( $files ); $i++ )
      echo "<a href=\"{$HD_URL_ATTACHMENT}?id={$_GET[id]}&email={$row[email]}&file=" . urlencode( $files[$i][1] ) . "\" target=\"_blank\">{$files[$i][1]}</a>&nbsp;&nbsp;";

    echo "</font></div><img src=\"blank.gif\" width=\"1\" height=\"5\" /><br /><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#AAAAAA\"><tr><td><img src=\"blank.gif\" width=\"1\" height=\"1\" /></td></tr></table><img src=\"blank.gif\" width=\"1\" height=\"5\" />";
  }
/********************************************************** PHP */?>


<table width="100%" border="0" cellspacing="0" cellpadding="8">
<?/* PHP **********************************************************/
  $res_temp = mysql_query( "SELECT id FROM {$pre}post WHERE ( ticket_id = '{$row[id]}' ) ORDER BY date LIMIT 1" );
  $row_temp = mysql_fetch_array( $res_temp );
  $first_id = $row_temp[0];
  
  $res_post = mysql_query( "SELECT * FROM {$pre}post WHERE ( ticket_id = '{$row[id]}' ) ORDER BY date DESC" );

  $priv = get_row_count( "SELECT COUNT(*) FROM {$pre}privilege WHERE ( user_id = '{$_SESSION[user][id]}' && admin = '1' && (dept_id = '{$row[dept_id]}' || dept_id = '0') )" );

  while( $row_post = mysql_fetch_array( $res_post ) )
  {
    if( trim( $row_post[subject] ) == "" )
      $row_post[subject] = "No subject";

    if( !$row_post[private] )
    {
      $bgcolor = ($bgcolor == "#DDDDDD") ? "#EEEEEE" : "#DDDDDD";
      echo "<tr bgcolor=\"$bgcolor\"><td>";
    }
    else
    {
      $private_bgcolor = ($private_bgcolor == "#FFE0BC") ? "#FFEEDA" : "#FFE0BC";
      echo "<tr bgcolor=\"{$private_bgcolor}\"><td>";
    }

    if( $row_post[user_id] == -1 )
    {
      $ip = (trim( $row_post[ip] ) != "") ? " ({$row_post[ip]})" : "";

      echo "<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\"><tr><td><div class=\"normal\"><font size=\"1\"><b>Subject:</b> " . field( $row_post[subject] ) . "</font></div></td><td><img src=\"blank.gif\" width=\"10\" height=\"1\" /></td><td align=\"right\"><div class=\"normal\"><font size=\"1\"><b>Posted by <a href=\"mailto:{$row[email]}\">" . field( $row[name] ) . "</a>$ip</b></font></div></td></tr></table>";
    }
    else
    {
      $res_user = mysql_query( "SELECT name, signature FROM {$pre}user WHERE ( id = '{$row_post[user_id]}' )" );
      $row_user = mysql_fetch_array( $res_user );

      if( trim( $row_user[signature] ) != "" )
        $row_post[message] .= "\n\n{$row_user[signature]}";

      echo "<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\"><tr><td><div class=\"normal\"><font size=\"1\"><b>Subject:</b> " . field( $row_post[subject] ) . "</font></div></td><td><img src=\"blank.gif\" width=\"10\" height=\"1\" /></td><td align=\"right\"><div class=\"normal\"><font size=\"1\"><b>Posted by " . field( $row_user[name] ) . " </b><i>(Staff)</i></font></div></td></tr></table>";
    }

    if( $data[tags] )
      $row_post[message] = parse_tags( $row_post[message] );
    else
      $row_post[message] = parse_no_tags( $row_post[message] );

    echo "<img src=\"blank.gif\" width=\"1\" height=\"5\" /><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#AAAAAA\"><tr><td><img src=\"blank.gif\" width=\"1\" height=\"1\" /></td></tr></table><img src=\"blank.gif\" width=\"1\" height=\"15\" /><br />";

    echo "<div class=\"normal\">$row_post[message]</div>";

    echo "<img src=\"blank.gif\" width=\"1\" height=\"15\" /><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#AAAAAA\"><tr><td><img src=\"blank.gif\" width=\"1\" height=\"1\" /></td></tr></table><img src=\"blank.gif\" width=\"1\" height=\"5\" />";
 
    echo "<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\"><tr><td width=\"25\">";
  
    if( ($is_ticket && $priv && ($row_post[id] != $first_id)) || (!$is_ticket && ($row_post[user_id] == $_SESSION[user][id]) && ($row_post[id] != $first_id)) )
      echo "<a href=\"javascript:if(confirm('Are you sure you want to delete this post?')) window.location.href = '{$HD_CURPAGE}?cmd=deletepost&postid={$row_post[id]}&id={$_GET[id]}'\"><img src=\"trash.gif\" border=\"0\" alt=\"Delete Post\" /></a></td><td><div class=\"normal\"><font size=\"1\"><a href=\"javascript:if(confirm('Are you sure you want to delete this post?')) window.location.href = '{$HD_CURPAGE}?cmd=deletepost&postid={$row_post[id]}&id={$_GET[id]}'\">Delete Post</a></font><img src=\"blank.gif\" width=\"10\" height=\"1\" /></div>";
      
    echo "</td><td align=\"right\"><div class=\"normal\"><font size=\"1\"><i>Date: " . date( "m-j-Y g:ia T", $row_post[date] ) . "</i></font></div></td></tr></table>";
  }
/********************************************************** PHP */?>
</table>
<br />

<a name="#reply"></a>
<div class="title">Post Reply</div><br />
<table width="100%" border="0" cellspacing="3" cellpadding="0">
<?/* PHP **********************************************************/
  if( $is_ticket )
  {
    $res_reply = mysql_query( "SELECT * FROM {$pre}reply WHERE ( dept_id = '-1' )" );
    if( mysql_num_rows( $res_reply ) )
    {
/********************************************************** PHP */?>
<form name="predefineddelete" action="<?= $HD_CURPAGE ?>" method="post">
<tr><td width="150" align="right"><div class="normal">Predefined Reply:</div></td><td>
<input type="hidden" name="id" value="<?= $_GET[id] ?>" />
<input type="hidden" name="replyname" value="" />
<input type="hidden" name="cmd" value="deletereply" />
<select name="reply" onchange="document.predefinedreply.message.value = this.options[selectedIndex].value; if( this.options[selectedIndex].value != '' )  { document.predefinedreply.replyname.value = this.options[selectedIndex].text; document.predefineddelete.replyname.value = this.options[selectedIndex].text; } else { document.predefinedreply.replyname.value = ''; document.predefineddelete.replyname.value = ''; }">
<option value="">(None)</option>
<?/* PHP **********************************************************/
      while( $row_reply = mysql_fetch_array( $res_reply ) )
        echo "<option value=\"" . field( $row_reply[reply] ) . "\">" . field( $row_reply[phrase] ) . "</option>\n";  
/********************************************************** PHP */?>
</select>
<input type="submit" value="Delete Selected Reply" />
</td></tr>
</form>
<?/* PHP **********************************************************/
    }
  }
/********************************************************** PHP */?>
<form name="predefinedreply" action="<?= $HD_CURPAGE ?>" method="post">
<input type="hidden" name="id" value="<?= $_GET[id] ?>" />
<input type="hidden" name="cmd" value="reply" />
<tr><td colspan="2">&nbsp;</td></tr>
<tr><td width="150" align="right"><div class="normal">Subject:</div></td><td><input type="text" name="subject" value="<?= field( $_POST[subject] ) ?>" size="30" /></td></tr>
<tr><td width="150" align="right"><div class="normal">Message:<font color="#FF0000">*</font></div></td><td><? if( $data[tags] ) echo "<br /><div class=\"normal\"><font size=\"-2\"><b>You can use <a href=\"$HD_URL_TICKET_TAGS\" target=\"_blank\">message tags</a></b></font></div><img src=\"blank.gif\" width=\"1\" height=\"5\" /><br />"; ?><textarea name="message" rows="8" cols="45"><?= field( $_POST[message] ) ?></textarea></td></tr>
<tr><td></td><td>
<?/* PHP **********************************************************/
  if( $is_ticket )
  {
/********************************************************** PHP */?>
<img src="blank.gif" width="1" height="12" /><br />
<div class="normal">
<input type="checkbox" name="save" /> Save as a predefined reply named <input type="text" name="replyname" /><br />
<input type="checkbox" name="close" /> Close this ticket after replying<br />
<input type="checkbox" name="private" /> Post as private note (only staff can view)
</div>
<?/* PHP **********************************************************/
  }
/********************************************************** PHP */?>
</td></tr>
<tr><td></td><td><img src="blank.gif" width="1" height="12" /><br /><input type="submit" value="Post Reply" /> <input type="reset" /></td></tr>
</form>
</table>

<?/* PHP **********************************************************/
  if( $data[uploads] )
  {
/********************************************************** PHP */?>
<br />
<a name="#attach"></a>
<div class="title">Attach File</div><br />
<table border="0" cellspacing="0" cellpadding="0">
<form enctype="multipart/form-data" action="<?= $HD_CURPAGE ?>" method="post">
<tr><td>
  <div class="normal">
  <input type="hidden" name="cmd" value="attach">
  <input type="hidden" name="id" value="<?= $_GET[id] ?>">
  Attach File: <input name="userfile" type="file"> <input type="submit" value="Attach">
  </div>
</td></tr>
</form>
</table>
<?/* PHP **********************************************************/
  }
/********************************************************** PHP */?>

<?/* PHP **********************************************************/
  if( $is_ticket && mysql_num_rows( $res_others ) )
  { 
/********************************************************** PHP */?>
<br />
<a name="#others"></a>
<div class="title">Ticket History</div><br />
<table width="100%" border="0" cellspacing="1" cellpadding="3">
<tr bgcolor="#FDD6AA"><td width="100"><div class="tableheader">Ticket#</div></td><td width="40%"><div class="tableheader">Subject</div></td><td width="30%"><div class="tableheader">Department</div></td><td><div class="tableheader">Date</div></td></tr>
<?/* PHP **********************************************************/
    while( $row_others = mysql_fetch_array( $res_others ) )
    {
      $res_dept = mysql_query( "SELECT name FROM {$pre}dept WHERE ( id = '{$row_others[dept_id]}' )" );
      $row_dept = mysql_fetch_array( $res_dept );

      $bgcolor = ($bgcolor == "#DDDDDD") ? "#EEEEEE" : "#DDDDDD";
/********************************************************** PHP */?>
<tr bgcolor="<?= $bgcolor ?>">
<td><div class="normal"><a href="<?= "{$CURPAGE}?cmd=view&id={$row_others[ticket_id]}" ?>"><?= $row_others[ticket_id] ?></a></div></td>
<td><div class="normal"><a href="<?= "{$CURPAGE}?cmd=view&id={$row_others[ticket_id]}" ?>"><?= field( $row_others[subject] ) ?></a></div></td>
<td><div class="normal"><?= field( $row_dept[name] ) ?></a></div></td>
<td><div class="normal"><?= date( "F j, Y", $row_others[date] ) ?></a></div></td>
</tr>
<?/* PHP **********************************************************/
    }
/********************************************************** PHP */?>
</table>
<?/* PHP **********************************************************/
  }
}
/********************************************************** PHP */?>

<?/* PHP **********************************************************/
include "footer.php";
/********************************************************** PHP */?>