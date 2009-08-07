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

$HD_CURPAGE = $HD_URL_ADMINTICKET;

if( $_SESSION[login_type] == $LOGIN_INVALID )
  Header( "Location: {$HD_URL_LOGIN}?redirect=" . urlencode( $HD_CURPAGE ) );

$options = array( "header", "footer", "logo", "title", "background", "outsidebackground", "border", "topbar", "menu", "styles", "email", "url", "emailheader", "emailfooter", "tags", "email_ticket_created", "email_ticket_created_subject", "email_ticket_notify", "email_ticket_notify_subject" );
$data = get_options( $options );

if( isset( $_GET[subject] ) )
  $_POST[subject] = $_GET[subject];
if( isset( $_GET[department] ) )
  $_POST[department] = $_GET[department];

$success = 0;

if( isset( $_POST[name] ) )
{
  $error = 0;

  if( trim( $_POST[name] ) == "" ||
      trim( $_POST[subject] ) == "" ||
      trim( $_POST[message] ) == "" ||
      !eregi( "^[_a-z0-9-]+(\.[_a-z0-9-]+)*@([0-9a-z](-?[0-9a-z])*\.)+[a-z]{2}([zmuvtg]|fo|me)?$", $_POST[email] ) )
    $error = 1;

  if( !$error )
  {
    $res = mysql_query( "SELECT * FROM {$pre}options WHERE ( name LIKE 'custom%' )" );
    while( $row = mysql_fetch_array( $res ) )
    {
      if( $row[num] && trim( $_POST[$row[name]] ) == "" )
      {
        $error = 1;
        break;
      }
    }
  }

  if( $error == 1 )
    $msg = "<div class=\"normal\"><font color=\"#FF0000\">{$LANG[fields_not_filled]}</font></div><br />";
  else
  {
    $ticket = strtoupper( base_convert( time( ), 10, 16 ) );
    if( get_row_count( "SELECT COUNT(*) FROM {$pre}ticket WHERE ( ticket_id = '$ticket' )" ) )
    {
      $res = mysql_query( "SELECT ticket_id FROM {$pre}ticket ORDER BY ticket_id DESC LIMIT 1" );
      $row = mysql_fetch_array( $res );
      $ticket = strtoupper( base_convert( base_convert( $row[0], 16, 10 ) + 1, 10, 16 ) );
    }

    $res = mysql_query( "SELECT name, text FROM {$pre}options WHERE ( name LIKE 'custom%' )" );
    $custom = "";
    while( $row = mysql_fetch_array( $res ) )
      $custom .= "{$row[text]}\n" . $_POST[$row[name]] . "\n";

    mysql_query( "INSERT INTO {$pre}ticket ( ticket_id, dept_id, email, name, subject, date, status, notify, priority, custom, lastactivity ) VALUES ( '$ticket', '{$_POST[department]}', '{$_POST[email]}', '{$_POST[name]}', '{$_POST[subject]}', '" . time( ) . "', '$HD_STATUS_OPEN', '1', '{$_POST[priority]}', '$custom', '" . time( ) . "' )" );

    $id = mysql_insert_id( );

    mysql_query( "INSERT INTO {$pre}post ( ticket_id, user_id, date, subject, message ) VALUES ( '$id', '-1', '" . time( ) . "', '{$_POST[subject]}', '{$_POST[message]}' )" );

    $subject = $_POST[subject];
    $message = $_POST[replymessage];
    $res = mysql_query( "SELECT name FROM {$pre}dept WHERE ( id = '{$_POST[department]}' )" );
    $row = mysql_fetch_array( $res );
    $department = $row[0];

    eval( "\$sub = \"{$data[email_ticket_created_subject]}\";" );
    eval( "\$mes = \"{$data[email_ticket_created]}\";" );
    mail( $_POST[email], $sub, $mes, "From: {$data[email]}" );

    if( trim( $_POST[replymessage] ) != "" )
    {
      // (time() + 1) makes sure this post follows the previous
      mysql_query( "INSERT INTO {$pre}post ( ticket_id, user_id, date, subject, message ) VALUES ( '$id', '{$_SESSION[user][id]}', '" . (time( ) + 1) . "', '{$_POST[replysubject]}', '{$_POST[replymessage]}' )" );

      mysql_query( "UPDATE {$pre}ticket SET lastpost = '{$_SESSION[user][id]}' WHERE ( id = '$id' )" );

      eval( "\$sub = \"{$data[email_ticket_notify_subject]}\";" );
      eval( "\$mes = \"{$data[email_ticket_notify]}\";" );
      mail( $_POST[email], $sub, $mes, "From: {$data[email]}" );
    }

    $autoreply = "";
    $res = mysql_query( "SELECT reply, phrase FROM {$pre}reply WHERE ( dept_id = '0' || dept_id = '{$_POST[department]}' )" );
    while( $row = mysql_fetch_array( $res ) )
    {
      if( $row[phrase] == "" )
      {
        $autoreply = "{$row[reply]}\n\n";
        break;
      }
      else if( strstr( strtoupper( $_POST[subject] ), strtoupper( $row[phrase] ) ) )
      {
        $autoreply = "{$row[reply]}\n\n";
        break;
      }
    }

    $success = 1;
  }
}

include "header.php";
/********************************************************** PHP */?>
<div class="title"><?= $script_name ?> Staff Ticket Creation</div><br /><?= $msg ?>
<table width="100%" bgcolor="#EEEEEE" border="0" cellpadding="5">
<tr><td>
  <div class="graycontainer">
    Use this form to create a ticket for a user. (For instance when a staff member
    is helping a user over the phone and needs to create a ticket for that user.)  Simply
    fill out the ticket form below and optionally give the ticket a reply.  You can
    reply to the ticket later if you omit one now.
  </div>
</td></tr>
</table>
<br />
<div class="subtitle">Ticket Information</div>
<table width="100%" border="0" cellspacing="0" cellpadding="15">
<tr>
<td>
<div class="normal">
<?/* PHP **********************************************************/
if( $success )
{
  echo "The ticket <a href=\"{$HD_URL_ADMINVIEW}?id=$ticket\">$ticket</a> has been created.<br /><br />";
}
else
{
/********************************************************** PHP */?>
<table width="100%" border="0" cellspacing="3" cellpadding="0">
<form action="<?= $HD_CURPAGE ?>" method="post">
<tr><td width="200" align="right"><div class="normal"><?= $LANG[field_name] ?><font color="#FF0000">*</font></div></td><td><input type="text" name="name" value="<?= field( $_POST[name] ) ?>" size="30" /></td></tr>
<tr><td width="200" align="right"><div class="normal"><?= $LANG[field_email] ?><font color="#FF0000">*</font></div></td><td><input type="text" name="email" value="<?= field( $_POST[email] ) ?>" size="30" /></td></tr>
<tr><td colspan="2"><br /></td></tr>
<tr><td width="200" align="right"><div class="normal"><?= $LANG[field_department] ?><font color="#FF0000">*</font></div></td>
<td>
<select name="department">
<?/* PHP **********************************************************/
  $res = mysql_query( "SELECT * FROM {$pre}dept" );

  echo "<option value=\"0\">Global</option>\n";
  while( $row = mysql_fetch_array( $res ) )
  {
    if( $row[id] != 0 )
      echo "<option value=\"{$row[id]}\" " . (($_POST[department] == $row[id] || $_POST[department] == $row[name]) ? "selected" : "") . ">" . field( $row[name] ) . "</option>\n";
  }
/********************************************************** PHP */?>
</select>
</td>
</tr>
<tr><td colspan="2"><br /></td></tr>
<tr><td width="200" align="right"><div class="normal"><?= $LANG[field_subject] ?><font color="#FF0000">*</font></div></td><td><input type="text" name="subject" value="<?= field( $_POST[subject] ) ?>" size="30" /></td></tr>
<tr><td width="200" align="right"><div class="normal"><?= $LANG[field_message] ?><font color="#FF0000">*</font></div></td><td><? if( $data[tags] ) echo "<br /><div class=\"normal\"><font size=\"-2\"><b>You can use <a href=\"$HD_URL_TICKET_TAGS\" target=\"_blank\">message tags</a></b></font></div><img src=\"blank.gif\" width=\"1\" height=\"5\" /><br />"; ?><textarea name="message" rows="8" cols="45"><?= field( $_POST[message] ) ?></textarea></td></tr>
<tr><td colspan="2"><br /></td></tr>
<tr><td width="200" align="right"><div class="normal"><?= $LANG[field_priority] ?><font color="#FF0000">*</font></div></td>
<td><select name="priority"><option value="<?= $PRIORITY_LOW ?>"><?= $LANG[field_priority_low] ?></option><option value="<?= $PRIORITY_MEDIUM ?>"><?= $LANG[field_priority_medium] ?></option><option value="<?= $PRIORITY_HIGH ?>"><?= $LANG[field_priority_high] ?></option></select></td>
</tr>
<tr><td colspan="2"><br /></td></tr>
<?/* PHP **********************************************************/
  $res = mysql_query( "SELECT * FROM {$pre}options WHERE ( name LIKE 'custom%' ) ORDER BY num DESC" );
  if( mysql_num_rows( $res ) )
  {
    while( $row = mysql_fetch_array( $res ) )
      echo "<tr><td width=\"200\" align=\"right\"><div class=\"normal\">" . field( $row[text] ) . ":" . ($row[num] ? "<font color=\"#FF0000\">*</font>" : "") . "</div></td><td><input type=\"text\" name=\"{$row[name]}\" value=\"" . field( $_POST[$row[name]] ) . "\" size=\"30\" /></td></tr>";

    echo "<tr><td colspan=\"2\"><br /></td></tr>";
  }
/********************************************************** PHP */?>
<tr><td><div class="subtitle">Reply Information</div></td><td></td></tr>
<tr><td width="200" align="right"><div class="normal"><?= $LANG[field_subject] ?></div></td><td><input type="text" name="replysubject" value="<?= field( $_POST[replysubject] ) ?>" size="30" /></td></tr>
<tr><td width="200" align="right"><div class="normal"><?= $LANG[field_message] ?></div></td><td><? if( $data[tags] ) echo "<br /><div class=\"normal\"><font size=\"-2\"><b>You can use <a href=\"$HD_URL_TICKET_TAGS\" target=\"_blank\">message tags</a></b></font></div><img src=\"blank.gif\" width=\"1\" height=\"5\" /><br />"; ?><textarea name="replymessage" rows="8" cols="45"><?= field( $_POST[replymessage] ) ?></textarea></td></tr>
<tr><td></td><td><br /><input type="submit" value="Create Ticket" />&nbsp;<input type="Reset" /></td></tr>
</form>
</table>
<?/* PHP **********************************************************/
}
/********************************************************** PHP */?>
</td></tr></table>
<?/* PHP **********************************************************/
include "footer.php";
/********************************************************** PHP */?>