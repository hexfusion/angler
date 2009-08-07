<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<?= $EXTRA_HEADER ?>
<style type="text/css">
  a
  { 
    color: navy;  
    text-decoration: underline;
  }
	a:visited
  { 
    color: navy;  
    text-decoration: underline;
  }
	a:active
  {
    color: navy;  
    text-decoration: underline;
  }
  a:hover
  {
    color: maroon;  
    text-decoration: underline;
  }
  
  .infobar_01
  {
    font: bold 9pt Verdana, Arial, Helvetica;
    color: #FFFFFF;
  }
  
  .graycontainer
  {
    font: 9pt Arial, Helvetica;
    color: #000000;
  }

  .whitecontainer
  {
    font: 9pt Arial, Helvetica;
    color: #000000;
  }

  #mainmenu .title
  {
    font: bold 9pt Verdana, Arial, Helvetica;
    color: #FFFFFF;
  }
  #mainmenu .options
  {
    font: 9pt Arial, Helvetica;
    color: #000000;
  }

  .title
  {
    font: bold 14pt Arial, Helvetica, Verdana;
    color: #0B6E1D;
  }

  label
  {
    font: bold 10pt Arial, Helvetica, Verdana;
  }

  .errorbox
  {
    font: 10pt Arial, Helvetica, Verdana;
    padding: 5px;
    color: maroon;
    border: 1px solid maroon;
  }

  .successbox
  {
    font: 10pt Arial, Helvetica, Verdana;
    padding: 5px;
    color: #0B6E1D;
    border: 1px solid #0B6E1D;
  }

  .topinfo
  {
    font: bold 8pt Verdana, Arial, Helvetica;
    color: #000000;
  }

  .normal
  {
    font: 10pt Arial, Helvetica, Verdana;
    color: #000000;
  }

  .tableheader
  {
    font: bold 10pt Arial, Helvetica, Verdana;
    color: #000000;
  }

  .submenu
  {
    font: bold 8pt Verdana, Arial, Helvetica;
  }

  .containertitle
  {
    font: bold 9pt Verdana, Arial, Helvetica;
    color: #FFFFFF;
  }

  .smallinfo
  {
    font: bold 8pt Verdana, Arial, Helvetica;
    color: #000000;
  }

  .subtitle
  {
    font: bold 12pt Arial, Helvetica, Verdana;
    color: navy;
  }

</style>
<?/* PHP **********************************************************/
if( $INSTALLED )
  $global_priv = get_row_count( "SELECT COUNT(*) FROM {$pre}privilege WHERE ( user_id = '{$_SESSION[user][id]}' && dept_id = '0' && admin = '1' )" );
/********************************************************** PHP */?>
<title>InverseFlow Help Desk <?= $script_version ?></title>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<img src="logo.gif" alt="InverseFlow" /><br />
<table width="100%" bgcolor="#FFAD4D" border="0"><tr><td><div class="infobar_01"><?= $script_info ?></td></tr></table>
<table width="100%" bgcolor="#EEEEEE" border="0" cellpadding="5">
<tr><td>
  <div class="graycontainer">
    Welcome to InverseFlow Help Desk.  If you experience problems please select the 'InverseFlow Support Desk' option from the support menu. On behalf of the whole InverseFlow team, thank you for using InverseFlow Help Desk.
  </div>
</td></tr>
</table>
<table id="mainmenu" width="100%" cellpadding="4" cellspacing="1" border="0">
  <tr bgcolor="#FF8A00">
    <td class="title">Tickets</td>
    <td class="title">Site & User Management</td>
    <td class="title">Departments</td>
    <td class="title">Miscellaneous</td>
  </tr>
  <tr valign="top">
    <td class="options" width="25%">
      · <a href="<?= $HD_URL_BROWSE ?>">Browse</a><br />
      · <a href="<?= $HD_URL_STATS ?>">Statistics</a><br />
      · <a href="<?= $HD_URL_ADMINTICKET ?>">Create Ticket</a><br />
<?/* PHP **********************************************************/
if( $global_priv )
{
/********************************************************** PHP */?>
      · <a href="<?= $HD_URL_FORM ?>">Manage Ticket Form Template</a><br />
<?/* PHP **********************************************************/
}
/********************************************************** PHP */?>
      · <a href="<?= $HD_URL_TICKET_HOME ?>" target="_blank">Help Desk Home</a><br />
    </td>
    <td class="options" width="25%">
<?/* PHP **********************************************************/
if( $global_priv )
{
/********************************************************** PHP */?>
      · <a href="<?= $HD_URL_GENERAL ?>">General Help Desk Settings</a><br />
      · <a href="<?= $HD_URL_EMAILS ?>">Customize Emails</a><br />
<?/* PHP **********************************************************/
}
/********************************************************** PHP */?>
      · <a href="<?= $HD_URL_USER ?>">View/Manage Users</a><br />
      · <a href="<?= $HD_URL_PROFILE ?>">Edit Your Profile & Options</a><br />
      · <a href="<?= $HD_URL_FAQADMIN ?>">Knowledge Base</a><br />
<?/* PHP **********************************************************/
if( $global_priv )
{
/********************************************************** PHP */?>
      · <a href="<?= $HD_URL_BACKUP ?>">Help Desk Backup</a><br />
      · <a href="<?= $HD_URL_SURVEY ?>">Surveys</a><br />
<?/* PHP **********************************************************/
}
/********************************************************** PHP */?>
    </td>
    <td class="options" width="25%">
<?/* PHP **********************************************************/
if( $global_priv )
{
/********************************************************** PHP */?>
      · <a href="<?= $HD_URL_EMAIL ?>">Email Processing</a><br />
<?/* PHP **********************************************************/
}
/********************************************************** PHP */?>
      · <a href="<?= $HD_URL_DEPARTMENT ?>">View/Manage Departments</a><br />
      · <a href="<?= $HD_URL_REPLIES ?>">Department Auto-Replies</a><br />
    </td>
    <td class="options" width="25%">
      · <a href="<?= $HD_URL_MESSAGES ?>">Message Center</a><br />
      · <a href="<?= $HD_URL_MANUAL ?>">Manual</a><br />
      · <a href="http://inverseflow-support.ratestar.net/?subject=Help+Desk" target="_blank">InverseFlow Support Desk</a><br />
    </td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#FF8A00"><tr><td><img src="blank.gif" width="1" height="3"></td></tr></table>

<table align="center" width="90%" border="0" cellspacing="0" cellpadding="0">
<tr><td>
<br />
<?/* PHP **********************************************************/
if( $INSTALLED )
  if( $global_priv && $PATH_TO_HELPDESK == "" )
  {
    echo "<table align=\"center\" bgcolor=\"#DDDDDD\" width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"6\">\n";
    echo "<tr><td><div class=\"normal\">You must specify the URL to the help desk in the general settings in order for the help desk to be completely functional.  You can do this in the <a href=\"$HD_URL_GENERAL\">general settings</a> area.  This message will disappear once you have successfully set this value.</div></td></tr>\n";
    echo "</table><br />\n";
  }
/********************************************************** PHP */?>

<table bgcolor="#FDD6AA" width="100%" border="0" cellspacing="0" cellpadding="5">
  <tr>
    <tr valign="center">
<?/* PHP **********************************************************/
if( $_SESSION[login_type] == $LOGIN_INVALID )
  echo "<td><div class=\"smallinfo\">Not logged in.</div></td>";
else if( get_row_count( "SELECT COUNT(*) FROM {$pre}message WHERE ( user_id = '{$_SESSION[user][id]}' && viewed = '0' )" ) )
{
  echo "<td width=\"15\"><a href=\"{$HD_URL_MESSAGES}\"><img src=\"browse_newreply.gif\" border=\"0\"></a></td>\n";
  echo "<td><div class=\"smallinfo\"><a href=\"{$HD_URL_MESSAGES}\">You have new messages</a>.</div></td>";
}
else  
  echo "<td><div class=\"smallinfo\">You have no new <a href=\"{$HD_URL_MESSAGES}\">messages</a>.</div></td>"; 
/********************************************************** PHP */?>
    <td align="right">
      <div class="topinfo">
<?/* PHP **********************************************************/
if( $INSTALLED )
  if( $_SESSION[login_type] != $LOGIN_INVALID )
    echo "{$_SESSION[user][name]} logged in.  You can <a href=\"login.php?cmd=logout\">log out</a>.&nbsp;";
/********************************************************** PHP */?>
      </div>
    </td>
  </tr>
</table>
</div>
<br />