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

$HD_CURPAGE = $HD_URL_MASSREPLY;

if( $_SESSION[login_type] == $LOGIN_INVALID )
  Header( "Location: {$HD_URL_LOGIN}?redirect=" . urlencode( $HD_CURPAGE . "?id={$_GET[id]}" ) );

$options = array( "email", "url", "emailheader", "emailfooter" );
$data = get_options( $options );

if( isset( $_POST[tickets] ) )
  $_GET[tickets] = $_POST[tickets];

$tickets = split( ";", $_GET[tickets] );

$res = mysql_query( "SELECT num FROM {$pre}options WHERE ( name = 'tags' )" );
$row = mysql_fetch_array( $res );
if( !$row )
  $data[tags] = 0;
else
  $data[tags] = $row[0];

if( $_POST[cmd] == "reply" )
{
  if( trim( $_POST[message] ) == "" )
    $msg = "<div class=\"normal\"><font color=\"#FF0000\">You must specify a message in your reply (subjects are optional).</font></div><br />";
  else
  {
    $tickets_replied = 0;
    for( $i = 0; $i < count( $tickets ); $i++ )
    {
      $res = mysql_query( "SELECT * FROM {$pre}ticket WHERE ( id = '{$tickets[$i]}' )" );
      $row = mysql_fetch_array( $res );
      if( $row )
      {
        $tickets_replied++;
         
        // Send notification if necessary
        if( $row[notify] )
        {
          $ticket = $row[ticket_id];
          $email = $row[email];
          $name = stripslashes( $row[name] );
          $subject = stripslashes( $row[subject] );
          $message = stripslashes( $_POST[message] );

          include $HD_URL_EMAILS;
          mail( $row[email], $EMAIL_TICKETNOTIFY_SUBJECT, $EMAIL_TICKETNOTIFY_MESSAGE, "From: {$data[email]}" );
        }

        mysql_query( "INSERT INTO {$pre}post ( ticket_id, user_id, date, subject, message ) VALUES ( '{$row[id]}', '{$_SESSION[user][id]}', '" . time( ) . "', '{$_POST[subject]}', '$_POST[message]' )" );

        mysql_query( "UPDATE {$pre}ticket SET lastactivity = '" . time( ) . "' WHERE ( id = '{$tickets[$i]}' )" );

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

    $msg = "<div class=\"successbox\">Your mass reply has been successfully posted to {$tickets_replied} tickets.</div><br />";
  }
}

include "header.php";
/********************************************************** PHP */?>
<div class="title"><?= $script_name ?> Mass Reply</div><br /><?= $msg ?>
<table width="100%" border="0" cellspacing="3" cellpadding="0">
<?/* PHP **********************************************************/
$res = mysql_query( "SELECT * FROM {$pre}reply WHERE ( dept_id = '-1' )" );
if( mysql_num_rows( $res ) )
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
  while( $row = mysql_fetch_array( $res ) )
    echo "<option value=\"" . field( $row[reply] ) . "\">" . field( $row[phrase] ) . "</option>\n";  
/********************************************************** PHP */?>
</select>
<input type="submit" value="Delete Selected Reply" />
</td></tr>
</form>
<?/* PHP **********************************************************/
}
/********************************************************** PHP */?>
<form name="predefinedreply" action="<?= $HD_CURPAGE ?>" method="post">
<input type="hidden" name="cmd" value="reply" />
<input type="hidden" name="tickets" value="<?= $_GET[tickets] ?>" />
<tr><td colspan="2">&nbsp;</td></tr>
<tr><td width="150" align="right"><div class="normal">Subject:</div></td><td><input type="text" name="subject" value="<?= field( $_POST[subject] ) ?>" size="30" /></td></tr>
<tr><td width="150" align="right"><div class="normal">Message:<font color="#FF0000">*</font></div></td><td><? if( $data[tags] ) echo "<br /><div class=\"normal\"><font size=\"-2\"><b>You can use <a href=\"$HD_URL_TICKET_TAGS\" target=\"_blank\">message tags</a></b></font></div><img src=\"blank.gif\" width=\"1\" height=\"5\" /><br />"; ?><textarea name="message" rows="8" cols="45"><?= field( $_POST[message] ) ?></textarea></td></tr>
<tr><td></td><td><img src="blank.gif" width="1" height="12" /><br />
<div class="normal">
<input type="checkbox" name="save" /> Save as a predefined reply named <input type="text" name="replyname" />
</div>
</td></tr>
<tr><td></td><td><img src="blank.gif" width="1" height="12" /><br /><input type="submit" value="Post Reply" /> <input type="reset" /></td></tr>
</form>
</table>
<br />
<?/* PHP **********************************************************/
include "footer.php";
/********************************************************** PHP */?>