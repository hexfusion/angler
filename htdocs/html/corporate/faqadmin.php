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

$HD_CURPAGE = $HD_URL_FAQADMIN;

if( $_SESSION[login_type] == $LOGIN_INVALID )
  Header( "Location: {$HD_URL_LOGIN}?redirect=" . urlencode( $HD_CURPAGE ) );

$global_priv = get_row_count( "SELECT COUNT(*) FROM {$pre}privilege WHERE ( user_id = '{$_SESSION[user][id]}' && dept_id = '0' && admin = '1' )" );

if( $_POST[cmd] == "newcategory" )
{
  if( $global_priv )
  {
    if( trim( $_POST[name] ) != "" )
    {
      if( !get_row_count( "SELECT COUNT(*) FROM {$pre}faq WHERE ( parent = '{$_POST[parent]}' && description = '{$_POST[name]}' )" ) )
        mysql_query( "INSERT INTO {$pre}faq ( description, symptoms, category, parent, date ) VALUES ( '{$_POST[name]}', '{$_POST[description]}', '-1', '{$_POST[parent]}', '" . time( ) . "' )" );
      else
        $msg = "<div class=\"errorbox\">A category with that name already exists.</div><br />";
    }
  }
}
else if( $_GET[cmd] == "deletecat" )
{
  if( $global_priv )
  {
    $exists = get_row_count( "SELECT COUNT(*) FROM {$pre}faq WHERE ( parent = '{$_GET[id]}' )" );
    if( !$exists )
      mysql_query( "DELETE FROM {$pre}faq WHERE ( category = '{$_GET[id]}' || id = '{$_GET[id]}' )" );
    else
      $msg = "<div class=\"errorbox\">You must delete this category's subcategories first.</div><br />";
  }
}
else if( $_GET[cmd] == "deleteentry" )
{
  if( $global_priv )
    mysql_query( "DELETE FROM {$pre}faq WHERE ( id = '{$_GET[id]}' && parent = '-1' )" );

  unset( $_GET[cmd] );
}
else if( $_POST[cmd] == "edit" )
{
  if( $global_priv )
  {
    if( trim( $_POST[description] ) != "" )
    {
      if( isset( $_POST[id] ) )
        mysql_query( "UPDATE {$pre}faq SET description = '{$_POST[description]}', symptoms = '{$_POST[symptoms]}', solution = '{$_POST[solution]}' WHERE ( id = '{$_POST[id]}' )" );
      else
        mysql_query( "INSERT INTO {$pre}faq ( description, symptoms, solution, date, category, parent ) VALUES ( '{$_POST[description]}', '{$_POST[symptoms]}', '{$_POST[solution]}', '" . time( ) . "', '{$_POST[parent]}', '-1' )" );
    }
  }

  unset( $_POST[cmd] );
}


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

include "header.php";
/********************************************************** PHP */?>
<div class="title"><?= $script_name ?> Knowledge Base</div><br /><?= $msg ?>
<div class="normal">
<form action="<?= $HD_CURPAGE ?>" method="get">
<input type="hidden" name="cmd" value="search">
<b>Search for:</b> <input type="text" name="search" /> <input type="submit" value="Search" />
</form>
<?/* PHP **********************************************************/
if( !isset( $_POST[cmd] ) || $_POST[cmd] == "deletecat" || $_POST[cmd] == "newcategory" )
{
  if( $global_priv )
  {
/********************************************************** PHP */?>
<table bgcolor="#FF8A00" border="0" cellspacing="0" cellpadding="0"><tr><td align="left"><img src="leftuptransparent.gif" align="top" />&nbsp;</td><td><div class="containertitle">Create New Category</div></td><td align="right">&nbsp;<img src="rightuptransparent.gif" align="top" /></td></tr></table>
<table width="100%" bgcolor="#FF8A00" border="0" cellspacing="1" cellpadding="4">
<form action="<?= $HD_CURPAGE ?>" method="post">
<input type="hidden" name="cmd" value="newcategory" />
<input type="hidden" name="parent" value="<?= $_POST[parent] ?>" />
<tr><td bgcolor="#EEEEEE">
  <table border="0" width="100%" cellspacing="0" cellpadding="0">
    <tr>
      <td align="right"><div class="topinfo">Category Name:&nbsp;</div></td>
      <td><input type="text" name="name" size="30" /></td>
      <td width="50%" rowspan="3" align="center" valign="middle">
        <div class="topinfo">
<?/* PHP **********************************************************/
    if( isset( $_POST[parent] ) && $_POST[parent] != "-1" )
      echo "* This will be created as a subcategory for the current category";
/********************************************************** PHP */?>
        </div>
      </td>
    </tr>
    <tr>
      <td align="right"><div class="topinfo">Description:&nbsp;</div></td>
      <td><input type="text" name="description" size="30" /></td>
    </tr>     
    <tr><td></td><td><img src="blank.gif" width="1" height="5" /><br /><input type="submit" value="Create" /></td></tr>
  </table>
</td></tr>
</form>
</table>
<br />
<?/* PHP **********************************************************/
  }
/********************************************************** PHP */?>
<? if( $row_cat[parent] != -1 ) echo "&lt;&lt; <a href=\"{$HD_CURPAGE}\">Main Category</a> &lt; <a href=\"{$HD_CURPAGE}?parent={$row_cat[parent]}\">Parent Category</a> | "; ?> <b> Browsing '<?= field( $row_cat[description] ) ?>'</b><br /><br />
<?/* PHP **********************************************************/
  if( $global_priv )
  {
/********************************************************** PHP */?>
<table border="0" cellspacing="2" cellpadding="0">
<tr><td><a href="<?= $HD_CURPAGE ?>?cmd=new"><img src="edit.gif" border="0" /></a>&nbsp;</td><td><div class="normal"><a href="<?= $HD_CURPAGE ?>?cmd=edit&parent=<?= $_POST[parent] ?>">Create New Knowledge Base Entry In This Category</a></div></td></tr>
<?/* PHP **********************************************************/
    if( $_POST[parent] != 0 )
    {
/********************************************************** PHP */?>
<tr><td><a href="javascript:if(confirm('This will delete this category and all its entries.  Are you sure you want to do this?')) window.location = '<?= $HD_CURPAGE ?>?cmd=deletecat&id=<?= $_POST[parent] ?>&parent=<?= $_POST[parent] ?>'"><img src="trash.gif" border="0" /></a>&nbsp;</td><td><div class="normal"><a href="javascript:if(confirm('This will delete this category and all its entries.  Are you sure you want to do this?')) window.location = '<?= $HD_CURPAGE ?>?cmd=deletecat&id=<?= $_POST[parent] ?>&parent=<?= $_POST[parent] ?>'">Delete This Category</a></div></td></tr>
<?/* PHP **********************************************************/
    }
/********************************************************** PHP */?>
</table><br />
</div>
<?/* PHP **********************************************************/
  }
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
      echo "<td><div class=\"normal\"><b><a href=\"$HD_CURPAGE?parent={$row[id]}\">" . field( $row[description] ) . "</b></a> (<b>$subcats</b> subcategories, <b>$items</b> entries)<br /><img src=\"blank.gif\" width=\"1\" height=\"5\"><br />" . ((trim( $row[symptoms] ) != "") ? field( $row[symptoms] ) : "No description") . "</div>";

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

  echo "&lt;&lt; <a href=\"{$HD_CURPAGE}\">Main Category</a> &lt; <a href=\"{$HD_CURPAGE}?parent={$row[category]}\">Parent Category</a><br /><br />";

  if( $global_priv )
    echo "<table border=\"0\" cellspacing=\"2\" cellpadding=\"0\"><tr><td><a href=\"{$HD_CURPAGE}?cmd=edit&parent={$row[category]}&id={$row[id]}\"><img src=\"edit.gif\" border=\"0\" /></a>&nbsp;</td><td><div class=\"normal\"><a href=\"{$HD_CURPAGE}?cmd=edit&parent={$row[category]}&id={$row[id]}\">Edit This Entry</a></div></td></tr><tr><td><a href=\"{$HD_CURPAGE}?cmd=deleteentry&parent={$row[category]}&id={$row[id]}\"><img src=\"trash.gif\" border=\"0\" /></a>&nbsp;</td><td><div class=\"normal\"><a href=\"{$HD_CURPAGE}?cmd=deleteentry&parent={$row[category]}&id={$row[id]}\">Delete This Entry</a></div></td></tr></table><br />";

  echo "<div class=\"subtitle\">" . field( $row[description] ) . "</div><br />";
  echo "<b>SYMPTOMS</b><br /><hr height=\"1\" size=\"1\" />";

  if( trim( $row[symptoms] ) == "" )
    echo "No symptoms.";
  else
    echo parse_tags( $row[symptoms] );

  echo "<br /><br /><b>SOLUTION</b><br /><hr height=\"1\" size=\"1\" />";

  if( trim( $row[solution] ) == "" )
    echo "No solution available.";
  else
    echo parse_tags( $row[solution] );
}
else if( $_POST[cmd] == "edit" )
{
  if( isset( $_GET[id] ) )
  {
    $res = mysql_query( "SELECT * FROM {$pre}faq WHERE ( id = '{$_GET[id]}' )" );
    $row = mysql_fetch_array( $res );

    while( list( $key, $val ) = each( $row ) )
      $_POST[$key] = $val;
  }

  echo "<a href=\"{$HD_CURPAGE}?parent={$row[category]}\"><b>Back to category</b></a><br /><br />";

  if( $global_priv )
  {
  /********************************************************** PHP */?>
<table bgcolor="#FF8A00" border="0" cellspacing="0" cellpadding="0"><tr><td align="left"><img src="leftuptransparent.gif" align="top" />&nbsp;</td><td><div class="containertitle">Knowledge Base Entry</div></td><td align="right">&nbsp;<img src="rightuptransparent.gif" align="top" /></td></tr></table>
<table width="100%" bgcolor="#FF8A00" border="0" cellspacing="1" cellpadding="4">
<form action="<?= $HD_CURPAGE ?>" method="post">
<?/* PHP **********************************************************/
    if( isset( $_GET[id] ) )
      echo "<input type=\"hidden\" name=\"id\" value=\"{$_GET[id]}\">";

    echo "<input type=\"hidden\" name=\"cmd\" value=\"edit\" />";
    echo "<input type=\"hidden\" name=\"parent\" value=\"{$_POST[parent]}\" />";
/********************************************************** PHP */?>
<tr><td bgcolor="#EEEEEE">
  <table align="center" border="0" cellspacing="2" cellpadding="0">
    <tr><td colspan="2" align="center"><div class="subtitle">- General -</div><img src="blank.gif" width="1" height="12" />    
    <tr><td colspan="2" align="center"><img src="blank.gif" width="1" height="12" /><br />
    <table width="500" border="0" cellspacing="0" cellpadding="10" bgcolor="#FFFFFF"><tr><td>
      <div class="normal">Please fill in the information below.  The description is required.  Please use a short description,
      such as 'How do I locate my billing information?'.  You can use <a href="tickettags.php" target="_blank">message tags</a> in the
      symptoms and solution fields.</div>
    </td></tr></table>
    <img src="blank.gif" width="1" height="12" />
    </td></tr>
    <tr valign="top">
      <td align="right"><div class="topinfo">Description:&nbsp;</div></td>
      <td><input type="text" name="description" size="30" value="<?= field( $_POST[description] ) ?>" /></td>
    </tr>
    <tr valign="top">
      <td align="right"><div class="topinfo">Symptoms:&nbsp;</div></td>
      <td><textarea name="symptoms" rows="5" cols="40"><?= field( $_POST[symptoms] ) ?></textarea></td>
    </tr>
    <tr valign="top">
      <td align="right"><div class="topinfo">Solution:&nbsp;</div></td>
      <td><textarea name="solution" rows="5" cols="40"><?= field( $_POST[solution] ) ?></textarea><br /><img src="blank.gif" width="1" height="12" /></td>
    </tr>
    <tr><td colspan="2" align="center"><input type="submit" value="Update">&nbsp;&nbsp;<input type="reset"><br /><img src="blank.gif" width="1" height="12" /></td></tr>
  </table>
</td></tr>
</form>
</table>
<?/* PHP **********************************************************/
  }
}
else if( $_POST[cmd] == "search" )
{
  echo "<a href=\"{$HD_CURPAGE}\"><b>Back to categories</b></a><br /><br />";
  $res = mysql_query( "SELECT * FROM {$pre}faq WHERE ( description LIKE '%{$_GET[search]}%' || symptoms LIKE '%{$_GET[search]}%' || solution LIKE '%{$_GET[search]}%' ) ORDER BY date DESC" );
  if( !mysql_num_rows( $res ) )
    echo "No results were found for your search.";
  else
  {
    echo "<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">";
    
    while( $row = mysql_fetch_array( $res ) )
    {
      $res_cat = mysql_query( "SELECT description FROM {$pre}faq WHERE ( id = '{$row[category]}' )" );
      $row_cat = mysql_fetch_array( $res_cat );

      $bgcolor = ($bgcolor == "#DDDDDD") ? "#EEEEEE" : "#DDDDDD";
      echo "<tr bgcolor=\"$bgcolor\"><td><div class=\"normal\"><a href=\"{$HD_CURPAGE}?parent={$_POST[parent]}&cmd=view&id={$row[id]}\">" . field( $row[description] ) . "</a>" . ((trim( $row_cat[description] ) != "") ? "&nbsp;&nbsp;<span class=\"smallinfo\">[{$row_cat[description]}]</span>" : "") . "</div></td></tr>";
    }

    echo "</table>";
  }
}
/********************************************************** PHP */?>
<?/* PHP **********************************************************/
include "footer.php";
/********************************************************** PHP */?>