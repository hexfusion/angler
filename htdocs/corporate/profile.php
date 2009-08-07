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

$HD_CURPAGE = $HD_URL_PROFILE;

if( $_SESSION[login_type] == $LOGIN_INVALID )
  Header( "Location: {$HD_URL_LOGIN}?redirect=" . urlencode( $HD_CURPAGE ) );

if( isset( $_POST[name] ) )
{
  if( trim( $_POST[name] ) == "" ||
      trim( $_POST[email] ) == "" ||
      ( (trim( $_POST[password1] ) != "") && ($_POST[password1] != $_POST[password2]) ) )
    $msg = "<div class=\"errorbox\">Please completely fill the name and email fields, and make sure your passwords (if specified) match.</div><br />";
  else
  {
    if( trim( $_POST[password1] ) == "" )
      $password = $_SESSION[user][password];
    else
      $password = $_POST[password1];

    $_POST[notify] = 0;

    if( $_POST[notifycreation] == "on" )
      $_POST[notify] |= $HD_NOTIFY_CREATION;
    if( $_POST[notifyreply] == "on" )
      $_POST[notify] |= $HD_NOTIFY_REPLY;
    if( $_POST[savelogin] == "on" )
      $_POST[notify] |= $HD_NOTIFY_SAVELOGIN;

    mysql_query( "UPDATE {$pre}user SET name = '{$_POST[name]}', password = '$password', email = '{$_POST[email]}', sms = '{$_POST[sms]}', signature = '{$_POST[signature]}', notify = '{$_POST[notify]}' WHERE ( id = '{$_SESSION[user][id]}' )" );

    $row = mysql_fetch_array( mysql_query( "SELECT * FROM {$pre}user WHERE ( id = '{$_SESSION[user][id]}' )" ) );
    $_SESSION[user] = $row;
    $_SESSION[login_type] = $LOGIN_USER;
    $_SESSION[login] = $row[email];
    $_SESSION[password] = $row[password];

    $msg = "<div class=\"successbox\">Your user profile and options have been updated.</div><br />";
  }
}
else
  while( list( $key, $val ) = each( $_SESSION[user] ) )
    $_POST[$key] = $val;

include "header.php";
/********************************************************** PHP */?>
<div class="title"><?= $script_name ?> Profile & Options</div><br /><?= $msg ?>
<table bgcolor="#FF8A00" border="0" cellspacing="0" cellpadding="0"><tr><td align="left"><img src="leftuptransparent.gif" align="top" />&nbsp;</td><td><div class="containertitle">Your Profile & Options</div></td><td align="right">&nbsp;<img src="rightuptransparent.gif" align="top" /></td></tr></table>
<table width="100%" bgcolor="#FF8A00" border="0" cellspacing="1" cellpadding="4">
<form action="<?= $HD_CURPAGE ?>" method="post">
<input type="hidden" name="cmd" value="add" />
<tr><td bgcolor="#EEEEEE">
  <table align="center" border="0" cellspacing="2" cellpadding="0">
    <tr><td colspan="2" align="center"><div class="subtitle">- General Settings -</div><img src="blank.gif" width="1" height="12" /></td></tr>
    <tr valign="top">
      <td align="right"><div class="topinfo">Name:&nbsp;</div></td>
      <td><input type="text" name="name" size="30" value="<?= field( $_POST[name] ) ?>" /></td>
    </tr>
    <tr valign="top">
      <td align="right"><div class="topinfo">Email:&nbsp;</div></td>
      <td><input type="text" name="email" size="30" value="<?= field( $_POST[email] ) ?>" /></td>
    </tr>
    <tr valign="top">
      <td align="right"><div class="topinfo">SMS Email:&nbsp;</div></td>
      <td><input type="text" name="sms" size="30" value="<?= field( $_POST[sms] ) ?>" /><br /><img src="blank.gif" width="1" height="12" /></td>
    </tr>    
    <tr valign="top">
      <td></td><td><div class="normal">(Leave blank to keep same password)</div></td>
    </tr>
    <tr valign="top">
      <td align="right"><div class="topinfo">Password:&nbsp;</div></td>
      <td><input type="password" name="password1" size="30" /></td>
    </tr>
    <tr valign="top">
      <td align="right"><div class="topinfo">Password Again:&nbsp;</div></td>
      <td><input type="password" name="password2" size="30" /><br /><img src="blank.gif" width="1" height="12" /></td>
    </tr>
    <tr><td colspan="2" align="center"><div class="subtitle">- Email Notifications -</div><img src="blank.gif" width="1" height="12" /><br />
    <table border="0" cellspacing="0" cellpadding="10" bgcolor="#FFFFFF"><tr><td>
      <div class="normal">Notifications will be sent to your email and SMS email (if specified).</div>
    </td></tr></table>
    <img src="blank.gif" width="1" height="12" /></td></tr>
    <tr valign="top">
      <td></td>
      <td>
        <div class="topinfo">
          <input type="checkbox" name="notifycreation" <?= ($_POST[notify] & $HD_NOTIFY_CREATION) ? "checked" : "" ?> /> Notify me when new tickets are created<br />
          <input type="checkbox" name="notifyreply" <?= ($_POST[notify] & $HD_NOTIFY_REPLY) ? "checked" : "" ?> /> Notify me when customers reply to tickets I've handled<br />
        </div>
        <img src="blank.gif" width="1" height="12" />
      </td>
    </tr>
    <tr><td colspan="2" align="center"><div class="subtitle">- Other Options -</div><img src="blank.gif" width="1" height="12" /></td></tr>
    <tr valign="top">
      <td></td>
      <td>
        <div class="topinfo">
          <input type="checkbox" name="savelogin" <?= ($_POST[notify] & $HD_NOTIFY_SAVELOGIN) ? "checked" : "" ?> /> Save my login information<br />
        </div>
        <img src="blank.gif" width="1" height="12" />
      </td>
    </tr>
    <tr><td colspan="2" align="center"><div class="subtitle">- Signature -</div><img src="blank.gif" width="1" height="12" /><br />
    <table border="0" cellspacing="0" cellpadding="10" bgcolor="#FFFFFF"><tr><td>
      <div class="normal">Your signature (if specified) will be displayed<br />at the bottom of each post you make when responding to tickets.</div>
    </td></tr></table>
    <img src="blank.gif" width="1" height="12" /></td></tr>
    <tr valign="top">
      <td colspan="2" align="center"><textarea name="signature" rows="5" cols="40"><?= field( $_POST[signature] ) ?></textarea><br /><img src="blank.gif" width="1" height="12" /></td>
    </tr>
    <tr><td colspan="2" align="center"><input type="submit" value="Update">&nbsp;&nbsp;<input type="reset"><br /><img src="blank.gif" width="1" height="12" /></td></tr>
  </table>
</td></tr>
</form>
</table>
<br />
<?/* PHP **********************************************************/
include "footer.php";
/********************************************************** PHP */?>