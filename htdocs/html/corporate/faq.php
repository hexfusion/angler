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

$HD_CURPAGE = $HD_URL_FAQ;

$options = array( "header", "footer", "logo", "title", "background", "outsidebackground", "border", "topbar", "menu", "styles", "email", "url", "emailheader", "emailfooter" );
$data = get_options( $options );

$success = 0;

if( !isset( $_POST[parent] ) )
{
  if( isset( $_GET[parent] ) )
    $_POST[parent] = $_GET[parent];
  else
    $_POST[parent] = 0;
}

$res = mysql_query( "SELECT description, parent FROM {$pre}faq WHERE ( id = '{$_POST[parent]}' )" );
if( mysql_num_rows( $res ) )
  $row_cat = mysql_fetch_array( $res );
else
{
  $row_cat[description] = "Main";
  $row_cat[parent] = -1;
  $_POST[parent] = 0;
}

if( isset( $_GET[cmd] ) )
  $_POST[cmd] = $_GET[cmd];

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
<div class="title"><?= $LANG[knowledge_base] ?></div><br /><?= $msg ?>
<div class="normal">
<form action="<?= $HD_CURPAGE ?>" method="get">
<input type="hidden" name="cmd" value="search">
<b><?= $LANG[search_for] ?></b> <input type="text" name="search" /> <input type="submit" value="<?= $LANG[faq_search_button] ?>" />
</form>
<?/* PHP **********************************************************/
if( !isset( $_POST[cmd] ) )
{
/********************************************************** PHP */?>
<? if( $row_cat[parent] != -1 ) echo "&lt;&lt; <a href=\"{$HD_CURPAGE}\">{$LANG[faq_main_category]}</a> &lt; <a href=\"{$HD_CURPAGE}?parent={$row_cat[parent]}\">{$LANG[faq_parent_category]}</a> | "; ?> <b> <?= $LANG[faq_browsing] ?> '<?= field( $row_cat[description] ) ?>'</b><br /><br />
<?/* PHP **********************************************************/
  $res = mysql_query( "SELECT id, description, symptoms FROM {$pre}faq WHERE ( parent = '{$_POST[parent]}' )" );
  if( mysql_num_rows( $res ) )
  {
    echo "<table width=\"100%\" border=\"0\" cellspacing=\"5\" cellpadding=\"0\">";
    
    $i = 0;
    while( $row = mysql_fetch_array( $res ) )
    {
      if( $i++ % 2 == 0 )
        echo "<tr>";
      
      $items = get_row_count( "SELECT COUNT(*) FROM {$pre}faq WHERE ( category = '{$row[id]}' )" );
      $subcats = get_row_count( "SELECT COUNT(*) FROM {$pre}faq WHERE ( parent = '{$row[id]}' )" );
      echo "<td><div class=\"normal\"><b><a href=\"$HD_CURPAGE?parent={$row[id]}\">" . field( $row[description] ) . "</b></a> (<b>$subcats</b> {$LANG[faq_subcategories]}, <b>$items</b> {$LANG[faq_entries]})<br /><img src=\"blank.gif\" width=\"1\" height=\"5\"><br />" . ((trim( $row[symptoms] ) != "") ? field( $row[symptoms] ) : "{$LANG[faq_no_description]}") . "</div>";

      if( $i % 2 == 1 )
        echo "</tr>";
    }

    echo "</table><br />";
  }

  $res = mysql_query( "SELECT id, description FROM {$pre}faq WHERE ( category = '{$_POST[parent]}' ) ORDER BY date DESC" );
  if( mysql_num_rows( $res ) )
  {
    echo "<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">";
    $res = mysql_query( "SELECT id, description FROM {$pre}faq WHERE ( category = '{$_POST[parent]}' )" );

    while( $row = mysql_fetch_array( $res ) )
    {
      $bgcolor = ($bgcolor == "#DDDDDD") ? "#EEEEEE" : "#DDDDDD";
      echo "<tr bgcolor=\"$bgcolor\"><td><div class=\"normal\"><a href=\"{$HD_CURPAGE}?parent={$_POST[parent]}&cmd=view&id={$row[id]}\">" . field( $row[description] ) . "</a></div></td></tr>";
    }
    echo "</table>";
  }
}
else if( $_POST[cmd] == "view" )
{
  $res = mysql_query( "SELECT * FROM {$pre}faq WHERE ( id = '{$_GET[id]}' ) ORDER BY date DESC" );
  $row = mysql_fetch_array( $res );

  echo "&lt;&lt; <a href=\"{$HD_CURPAGE}\">{$LANG[faq_main_category]}</a> &lt; <a href=\"{$HD_CURPAGE}?parent={$row[category]}\">{$LANG[faq_parent_category]}</a><br /><br />";

  echo "<div class=\"normal\"><b>" . field( $row[description] ) . "</b></div><br />";
  echo "<b>{$LANG[faq_symptoms]}</b><br /><hr height=\"1\" size=\"1\" />";

  if( trim( $row[symptoms] ) == "" )
    echo "{$LANG[faq_no_symptoms]}";
  else
    echo parse_tags( $row[symptoms] );

  echo "<br /><br /><b>{$LANG[faq_solution]}</b><br /><hr height=\"1\" size=\"1\" />";

  if( trim( $row[solution] ) == "" )
    echo "{$LANG[faq_no_solution]}";
  else
    echo parse_tags( $row[solution] );
}
else if( $_POST[cmd] == "search" )
{
  echo "<a href=\"{$HD_CURPAGE}\"><b>{$LANG[faq_categories]}</b></a><br /><br />";
  $res = mysql_query( "SELECT * FROM {$pre}faq WHERE ( description LIKE '%{$_GET[search]}%' || symptoms LIKE '%{$_GET[search]}%' || solution LIKE '%{$_GET[search]}%' ) ORDER BY date DESC" );
  if( !mysql_num_rows( $res ) )
    echo "{$LANG[faq_no_results]}";
  else
  {
    echo "<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">";
    
    while( $row = mysql_fetch_array( $res ) )
    {
      $res_cat = mysql_query( "SELECT description FROM {$pre}faq WHERE ( id = '{$row[category]}' )" );
      $row_cat = mysql_fetch_array( $res_cat );

      $bgcolor = ($bgcolor == "#DDDDDD") ? "#EEEEEE" : "#DDDDDD";
      echo "<tr bgcolor=\"$bgcolor\"><td><div class=\"normal\"><a href=\"{$HD_CURPAGE}?parent={$_POST[parent]}&cmd=view&id={$row[id]}\">" . field( $row[description] ) . "</a>" . ((trim( $row_cat[description] ) != "") ? "&nbsp;&nbsp;[{$row_cat[description]}]" : "") . "</div></td></tr>";
    }

    echo "</table>";
  }
}
/********************************************************** PHP */?>
<?/* PHP **********************************************************/
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