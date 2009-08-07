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

$HD_CURPAGE = $HD_URL_TICKET_LOST;

$options = array( "header", "footer", "logo", "title", "background", "outsidebackground", "border", "topbar", "menu", "styles", "email", "url", "emailheader", "emailfooter" );
$data = get_options( $options );

$success = 0;

if( $_GET[cmd] == "lost" && isset( $_GET[email] ) )
{
  $res = mysql_query( "SELECT subject, ticket_id FROM {$pre}ticket WHERE ( email = '{$_GET[email]}' ) ORDER BY date DESC" );
  if( mysql_num_rows( $res ) )
  {
    $email = "{$data[emailheader]}";
    $email .= "Here are the tickets you requested to be looked-up.  The latest tickets are shown first:\n\n";

    while( $row = mysql_fetch_array( $res ) )
    {
      $email .= "Ticket ID: {$row[ticket_id]}\n";
      $email .= "Subject of ticket: {$row[subject]}\n";
      $email .= "Link to view ticket: " . $PATH_TO_HELPDESK . $HD_URL_TICKET_VIEW . "?cmd=view&id={$row[ticket_id]}&email={$_GET[email]}\n\n";
    }

    $email .= "{$data[emailfooter]}";

    mail( $_GET[email], "Ticket lookup", $email, "From: {$data[email]}" );

    $success = 1;
  }
  else
    $msg = "<div class=\"normal\"><div class=\"normal\"><font color=\"#FF0000\">{$LANG[no_ticket_address]}</font></div><br />";
}

if( trim( $data[header] ) == "" )
{
/********************************************************** PHP */?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title><?= field( $data[title] ) ?> &gt;&gt; Tickets - InverseFlow Help Desk</title>
</head>
<body bgcolor="<?= $data[outsidebackground] ?>" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="100%" bgcolor="<?= $data[background] ?>" border="0" cellspacing="0" cellpadding="0">
<tr><td><img src="<?= (trim( $data[logo] ) != "") ? $data[logo] : "logo.gif" ?>" /></td></tr>
<tr><td bgcolor="<?= $data[topbar] ?>" height="15"><img src="blank.gif" width="1" height="15" /></td></tr>
<tr><td bgcolor="<?= $data[border] ?>" height="6"><img src="blank.gif" width="1" height="6" /></td></tr>
</table>
<table width="700" bgcolor="<?= $data[background] ?>" height="400" border="0" cellspacing="0" cellpadding="0">
<tr>
<td valign="top">
<table width="100%" bgcolor="<?= $data[menu] ?>" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="20%" align="center"><div class="normal"><a href="<?= $HD_URL_TICKET_HOME ?>"><?= $LANG[link_home] ?></a></div></td><td bgcolor="<?= $data[border] ?>" width="1"><img src="blank.gif" width="1" height="1" /></td>
<td width="20%" align="center"><div class="normal"><a href="<?= $HD_URL_TICKET_VIEW ?>"><?= $LANG[link_view_ticket] ?></a></div></td><td bgcolor="<?= $data[border] ?>" width="1"><img src="blank.gif" width="1" height="22" /></td>
<td width="20%" align="center"><div class="normal"><a href="<?= $HD_URL_TICKET_LOST ?>?cmd=lost"><?= $LANG[link_lost_ticket] ?></a></div></td><td bgcolor="<?= $data[border] ?>" width="1"><img src="blank.gif" width="1" height="22" /></td>
<td width="20%" align="center"><div class="normal"><a href="<?= $HD_URL_FAQ ?>"><?= $LANG[link_faq] ?></a></div></td><td bgcolor="<?= $data[border] ?>" width="1"><img src="blank.gif" width="1" height="22" /></td>
<td width="20%" align="center"><div class="normal"><a href="<?= $HD_URL_LOGIN ?>"><?= $LANG[link_staff_login] ?></a></div></td>
</tr>
<tr><td bgcolor="<?= $data[border] ?>" height="1" colspan="9"><img src="blank.gif" width="1" height="1" /></td></tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="15">
<tr>
<td>
<?/* PHP **********************************************************/
}
else
  eval( "?> {$data[header]} <?" );
/********************************************************** PHP */?>
<style type="text/css">
<?= $data[styles] ?>
</style>
<div class="title"><?= $LANG[retrieve_lost_ticket] ?></div><br /><?= $msg ?>
<?/* PHP **********************************************************/
if( $success )
  echo "<div class=\"normal\">{$LANG[ticket_info_sent]}</div>";
else
{
/********************************************************** PHP */?>
<div class="normal">
<?= $LANG[email_address_used] ?>
<br /><br />
<table width="100%" border="0" cellspacing="3" cellpadding="0">
<form action="<?= $HD_CURPAGE ?>" method="get">
<input type="hidden" name="cmd" value="lost" />
<tr>
<td width="200" align="right"><div class="normal">Email:</div></td>
<td><input type="text" name="email" size="30" value="<?= field( $_GET[email] ) ?>" />&nbsp;<input type="submit" value="<?= $LANG[retrieve_lookup_button] ?>" />
</tr>
</form>
</table>
</div>
<?/* PHP **********************************************************/
}
if( trim( $data[header] ) == "" )
{
/********************************************************** PHP */?>
</td>
</tr>
</table>
</td>
<td valign="top" bgcolor="<?= $data[border] ?>" width="3"><img src="blank.gif" height="1" width="3" /></td>
</tr>
</table>
<table width="700" border="0" cellspacing="0" cellpadding="0">
<tr><td bgcolor="<?= $data[border] ?>" height="3"><img src="blank.gif" width="1" height="3" /></td></tr>
<tr><td align="center"><br />
<font face="Verdana, Arial, Helvetica" size="1">
<a href="http://www.inverseflow.com">
Powered by <?= $script_name ?><br />
Copyright &copy; 2002 InverseFlow
</font></td></tr>
</table>
</body>
</html>
<?/* PHP **********************************************************/
}
else
  eval( "?> {$data[footer]} <?" );
/********************************************************** PHP */?>