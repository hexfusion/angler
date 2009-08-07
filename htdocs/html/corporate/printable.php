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

$HD_CURPAGE = $HD_URL_PRINTABLE;

if( $_SESSION[login_type] != $LOGIN_INVALID )
{
  $exists = get_row_count( "SELECT COUNT(*) FROM {$pre}ticket AS ticket, {$pre}privilege AS priv WHERE ( ticket.ticket_id = '{$_GET[id]}' && priv.user_id = '{$_SESSION[user][id]}' && (priv.dept_id = ticket.dept_id || priv.dept_id = 0) )" );
}
else
  $exists = get_row_count( "SELECT COUNT(*) FROM {$pre}ticket WHERE ( ticket_id = '{$_GET[id]}' && email = '{$_GET[email]}' )" );

if( !$exists )
  exit;

$res = mysql_query( "SELECT * FROM {$pre}ticket WHERE ( ticket_id = '{$_GET[id]}' )" );
$row = mysql_fetch_array( $res );
$ticket = $row[0];

$options = array( "url", "title", "tags" );
$data = get_options( $options );

/********************************************************** PHP */?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
td { font-family: Verdana, Arial, Helvetica; font-size: 10pt }
</style>
<title><?= field( $data[title] ) ?></title>
</head>
<body>
<div style="font-family: Verdana, Arial, Helvetica; font-size: 10pt">
<div style="font-size: 14pt; font-weight: bold"><?= field( $data[title] ) ?></div>
<br />
<table border="0" cellspacing="5" cellpadding="0">
<tr><td align="right"><b>Subject:</td><td><?= field( $row[subject] ) ?></tr>
<tr><td align="right"><b>Ticket#:</td><td><?= $_GET[id] ?></tr>
<tr><td align="right"><b>Created On:</td><td><?= date( "F j, Y", $row[date] ) ?></tr>
</table>
<br />
<table width="700" border="0" cellspacing="2" cellpadding="0">
<?/* PHP **********************************************************/
$res_post = mysql_query( "SELECT * FROM {$pre}post AS post WHERE ( ticket_id = '$ticket' ) ORDER BY date DESC" );
while( $row_post = mysql_fetch_array( $res_post ) )
{
/********************************************************** PHP */?>
<tr><td colspan="2"><hr size="1" /></td></tr>
<tr><td align="left" valign="top">
<?/* PHP **********************************************************/
  if( $row_post[user_id] == -1 )
    echo "<b>" . field( $row[name] ) . "</b><br />{$row[email]}";
  else
  {
    $res_temp = mysql_query( "SELECT name, signature FROM {$pre}user WHERE ( id = '{$row_post[user_id]}' )" );
    $row_temp = mysql_fetch_array( $res_temp );
    echo "<b>" . field( $row_temp[name] ) . "</b><br />Staff";

    if( trim( $row_temp[signature] ) != "" )
      $row_post[message] .= "\n\n{$row_temp[signature]}";
  }

  echo "<br /><br /><font size=\"1\">" . date( "F j, Y g:ia T", $row_post[date] ) . "</font>";
/********************************************************** PHP */?>
</td>
<td align="left" valign="top" width="475"><? if( $data[tags] ) echo parse_tags( $row_post[message] ); else echo parse_no_tags( $row_post[message] ); ?></td>
</tr>
<?/* PHP **********************************************************/
}
/********************************************************** PHP */?>
</table>
</div>
</body>
</html>