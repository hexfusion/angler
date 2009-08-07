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

$HD_CURPAGE = $HD_URL_SURVEY;

$options = array( "header", "footer", "logo", "title", "background", "outsidebackground", "border", "topbar", "menu", "styles", "email", "url", "emailheader", "emailfooter" );
$data = get_options( $options );

if( isset( $_POST[id] ) )
{
  $_GET[id] = $_POST[id];
  $_GET[email] = $_POST[email];
}

$ticketexists = 0;

if( isset( $_GET[id] ) )
{
  $exists = get_row_count( "SELECT COUNT(*) FROM {$pre}ticket WHERE ( ticket_id = '{$_GET[id]}' && email = '{$_GET[email]}' )" );
  if( !$exists )
  {
    $msg = "<div class=\"normal\"><font color=\"#FF0000\">";
    eval( "\$msg .= \"$LANG[no_find_ticket]\";" );
    $msg .= "</font></div><br />";

    $ticketexists = 0;
  }
  else
  {
    $res = mysql_query( "SELECT id FROM {$pre}ticket WHERE ( ticket_id = '{$_GET[id]}' )" );
    $row = mysql_fetch_array( $res );
    $id = $row[0];
    $ticketexists = 1;
  }
}

if( $ticketexists )
{
  if( isset( $_POST[comments] ) )
  {
    $exists = get_row_count( "SELECT COUNT(*) FROM {$pre}survey WHERE ( ticket_id = '$id' )" );
    if( !$exists )
      mysql_query( "INSERT INTO {$pre}survey ( ticket_id, rating1, rating2, rating3, rating4, rating5, rating6, rating7, rating8, rating9, rating10, comments, date, email ) VALUES ( '$id', '{$_POST[survey1]}', '{$_POST[survey2]}', '{$_POST[survey3]}', '{$_POST[survey4]}', '{$_POST[survey5]}', '{$_POST[survey6]}', '{$_POST[survey7]}', '{$_POST[survey8]}', '{$_POST[survey9]}', '{$_POST[survey10]}', '{$_POST[comments]}', '" . time( ) . "', '{$_GET[email]}' )" );
  }
}

if( trim( $data[header] ) == "" )
{
/********************************************************** PHP */?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title><?= field( $data[title] ) ?> &gt;&gt; Survey - InverseFlow Help Desk</title>
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
<div class="title"><?= $LANG[survey] ?></div><br /><?= $msg ?>
<?/* PHP **********************************************************/
if( $ticketexists )
{
  if( isset( $_POST[comments] ) )
  {
/********************************************************** PHP */?>
<div class="normal">
<?= $LANG[survey_thanks] ?>
</div>
<?/* PHP **********************************************************/
  }
  else
  {
/********************************************************** PHP */?>
<div class="normal">
<? eval( "echo \"$LANG[survey_header]\";" ); ?>
<form action="<?= $CURPAGE ?>" method="post">
<input type="hidden" name="id" value="<?= $_GET[id] ?>" />
<input type="hidden" name="email" value="<?= $_GET[email] ?>" />
<table border="0" cellspacing="10" cellpadding="0">
<?/* PHP **********************************************************/
    $res = mysql_query( "SELECT name, text FROM {$pre}options WHERE ( name LIKE 'survey%' ) ORDER BY num" );
    while( $row = mysql_fetch_array( $res ) )
    {
/********************************************************** PHP */?>
<tr>
  <td><div class="normal"><b><?= field( $row[text] ) ?></b></div></td>
  <td>
    <div class="normal">
    <i><?= $LANG[survey_poor] ?></i>
    <input type="radio" value="1" name="<?= field( $row[name] ) ?>" /> 1
    <input type="radio" value="2" name="<?= field( $row[name] ) ?>" /> 2
    <input type="radio" value="3" name="<?= field( $row[name] ) ?>" checked /> 3
    <input type="radio" value="4" name="<?= field( $row[name] ) ?>" /> 4
    <input type="radio" value="5" name="<?= field( $row[name] ) ?>" /> 5
    <i><?= $LANG[survey_excellent] ?></i>
    </div>
  </td>
</tr>
<?/* PHP **********************************************************/
    }
/********************************************************** PHP */?>
</table>
<br /><br />
<b><?= $LANG[survey_comments] ?></b><br />
<textarea name="comments" rows="8" cols="45"></textarea>
<br /><br />
<input type="submit" value="<?= $LANG[survey_submit] ?>" />
</div>
</form>
<?/* PHP **********************************************************/
  }
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