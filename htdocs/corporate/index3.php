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

$HD_CURPAGE = $HD_URL_TICKET_HOME;

$options = array( "header", "footer", "logo", "title", "background", "outsidebackground", "border", "topbar", "menu", "styles", "email", "url", "emailheader", "emailfooter", "tags", "email_ticket_created", "email_ticket_created_subject", "email_notify_create_subject", "email_notify_create", "email_notify_reply_subject", "email_notify_reply", "floodcontrol", "email_notifysms_create_subject", "email_notifysms_create", "email_notifysms_reply_subject", "email_notifysms_reply" );
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
    // Determine if this user is banned
    if( get_row_count( "SELECT COUNT(*) FROM {$pre}options WHERE ( (name = 'banned_emails' && text LIKE '%{$_POST[email]}%') || (name = 'banned_ips' && text LIKE '%{$_SERVER[REMOTE_ADDR]}%') ) " ) )
    {
      echo $LANG[banned];
      exit;
    }

    // Checks for a duplicate ticket if flood control is enabled
    if( $data[floodcontrol] )
    {
      $res_check = mysql_query( "SELECT id, ticket_id FROM {$pre}ticket WHERE ( name = '{$_POST[name]}' && email = '{$_POST[email]}' && subject = '{$_POST[subject]}' )" );
      while( $row_check = mysql_fetch_array( $res_check ) )
      {
        $res_check_post = mysql_query( "SELECT message FROM {$pre}post WHERE ( ticket_id = '{$row_check[id]}' && user_id = '-1' ) ORDER BY date LIMIT 1" );
        $row_check_post = mysql_fetch_array( $res_check_post );

        if( trim( $row_check_post[message] ) == trim( stripslashes( $_POST[message] ) ) )
        {
          Header( "Location: {$HD_URL_TICKET_VIEW}?id={$row_check[ticket_id]}&email={$_POST[email]}" );
          exit;
        }
      }
    }

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

    mysql_query( "INSERT INTO {$pre}ticket ( ticket_id, dept_id, email, name, subject, date, status, notify, priority, custom, lastactivity ) VALUES ( '$ticket', '{$_POST[department]}', '{$_POST[email]}', '{$_POST[name]}', '{$_POST[subject]}', '" . time( ) . "', '$HD_STATUS_OPEN', '" . ($_POST[notify] == "on" ? "1" : "0") . "', '{$_POST[priority]}', '$custom', '" . time( ) . "' )" );

    $id = mysql_insert_id( );

    mysql_query( "INSERT INTO {$pre}post ( ticket_id, user_id, date, subject, message, ip ) VALUES ( '$id', '-1', '" . time( ) . "', '{$_POST[subject]}', '{$_POST[message]}', '{$_SERVER[REMOTE_ADDR]}' )" );

    $res = mysql_query( "SELECT name FROM {$pre}dept WHERE ( id = '{$_POST[department]}' )" );
    $row = mysql_fetch_array( $res );
    $department = $row[0];

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

    eval( "\$email_subject = \"{$data[email_ticket_created_subject]}\";" );
    eval( "\$email_message = \"{$data[email_ticket_created]}\";" );
    mail( $_POST[email], $email_subject, $email_message, "From: {$data[email]}" );

    // Notification messages
    $res_user = mysql_query( "SELECT DISTINCT user.email, user.sms FROM {$pre}user AS user, {$pre}privilege AS priv WHERE ( user.id = priv.user_id && (priv.dept_id = '0' || priv.dept_id = '{$_POST[department]}') && user.notify & {$HD_NOTIFY_CREATION} > '0' )" );
    while( $row_user = mysql_fetch_array( $res_user ) )
    {
      $message = $_POST[message];

      eval( "\$email_subject = \"{$data[email_notify_create_subject]}\";" );
      eval( "\$email_message = \"{$data[email_notify_create]}\";" );
      mail( $row_user[email], $email_subject, $email_message, "From: {$data[email]}" );

      if( trim( $row_user[sms] ) != "" )
      {
        eval( "\$email_subject = \"{$data[email_notifysms_create_subject]}\";" );
        eval( "\$email_message = \"{$data[email_notifysms_create]}\";" );
        mail( $row_user[sms], $email_subject, $email_message, "From: {$data[email]}" );
      }
    }

    $success = 1;
  }
}      

if( trim( $data[header] ) == "" )
{
/********************************************************** PHP */?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title><?= field( $data[title] ) ?> &gt;&gt</title>
</head>
<body marginheight="0" marginwidth="0" background="http://www.westbranchresort.com/images/new-header/background.gif">
<table width="1000" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><table width="1000" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="653" height="111" valign="top"><img src="http://www.westbranchresort.com/images/new-header/header.jpg" alt="d" width="653" height="111" /></td>
        <td width="347" valign="top" background="http://www.westbranchresort.com/images/new-header/header-right.jpg">
		<form action="http://reservations.westbranchresort.com:8080/step1.asp" method="GET" name="a">
		<input type="hidden" name="formatDate" value="Week">
        <br>
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="16%"><font color="#FFFFFF" size="2">Arrive:</font></td>
            <td width="23%" valign="top"><input name="StartDate" type="text" id="StartDate" value="05/01/2008" size="11" />
              <a href="javascript:cal5.popup();"></a><a href="javascript:cal5.popup();"></a></td>
            <td width="24%" valign="top"><div align="center"><a href="javascript:cal5.popup();"><img src="http://www.westbranchresort.com/images/img/cal.gif" width="16" height="16" border="0" alt="Click Here to Pick up the date"></a></div></td>
            <td width="15%"><div align="center"><font color="#FFFFFF" size="2">Adults:</font></div></td>
            <td width="22%" valign="top"><font color="#FFFFFF" size="2">
              <select name="adults" id="adults">
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
                <option value="6">6</option>
              </select>
            </font></td>
          </tr>
          <tr>
            <td colspan="5"><img src="http://www.westbranchresort.com/images/new-header/spacer.gif" width="1" height="3" alt="."></td>
            </tr>
          <tr>
            <td><font color="#FFFFFF" size="2">Depart:</font></td>
            <td valign="top"><input name="EndDate" type="text"  id="EndDate" value="05/03/2008" size="11" />
              <a href="javascript:cal6.popup();"></a><a href="javascript:cal6.popup();"></a></td>
            <td valign="top"><div align="center"><a href="javascript:cal6.popup();"><img src="http://www.westbranchresort.com/images/img/cal.gif" width="16" height="16" border="0" alt="Click Here to Pick up the date"></a></div></td>
            <td><div align="center"><font color="#FFFFFF" size="2">Children:</font></div></td>
            <td><select name="children" id="children">
              <option value="0">0</option>
              <option value="1">1</option>
              <option value="2">2</option>
              <option value="3">3</option>
            </select></td>
          </tr>
          <tr>
            <td colspan="5"><img src="http://www.westbranchresort.com/images/new-header/spacer.gif" width="1" height="5" alt="."></td>
            </tr>
          <tr>
            <td>&nbsp;</td>
            <td colspan="4"><div align="center">
			<input type="hidden" name="cabinStyle" value="0">
              <input name="submit" type="submit" class="inputSubmit" id="submit" value="Search" />
            </div></td>
          </tr>
        </table>
<script language="JavaScript">
			<!-- // create calendar object(s) just after form tag closed
				 // specify form element as the only parameter (document.forms['formname'].elements['inputname']);
				 // note: you can have as many calendar objects as you need for your application	
				var cal5 = new calendar2(document.forms['a'].elements['StartDate']);
				cal5.year_scroll = true;
				cal5.time_comp = false;
				var cal6 = new calendar2(document.forms['a'].elements['EndDate']);
				cal6.year_scroll = true;
				cal6.time_comp = false;
				
			//-->
			</script>
		</form>
		   
 		  </td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td>
<table border="0" cellpadding="0" cellspacing="0" width="1000">
<!-- fwtable fwsrc="menu.png" fwbase="menu.jpg" fwstyle="Dreamweaver" fwdocid = "1419584046" fwnested="0" -->
  <tr>
   <td><img src="images/spacer.gif" width="89" height="1" border="0" alt="" /></td>
   <td><img src="images/spacer.gif" width="118" height="1" border="0" alt="" /></td>
   <td><img src="images/spacer.gif" width="120" height="1" border="0" alt="" /></td>
   <td><img src="images/spacer.gif" width="127" height="1" border="0" alt="" /></td>
   <td><img src="images/spacer.gif" width="99" height="1" border="0" alt="" /></td>
   <td><img src="images/spacer.gif" width="91" height="1" border="0" alt="" /></td>
   <td><img src="images/spacer.gif" width="116" height="1" border="0" alt="" /></td>
   <td><img src="images/spacer.gif" width="111" height="1" border="0" alt="" /></td>
   <td><img src="images/spacer.gif" width="129" height="1" border="0" alt="" /></td>
   <td><img src="images/spacer.gif" width="1" height="1" border="0" alt="" /></td>
  </tr>

  <tr>
   <td><a href="http://www.westbranchresort.com/"><img name="menu_r1_c1" src="http://www.westbranchresort.com/images/new-header/menu_r1_c1.jpg" width="89" height="20" border="0" id="menu_r1_c1" alt="Home" /></a></td>
   <td><a href="http://www.westbranchresort.com/aboutus.html"><img name="menu_r1_c2" src="http://www.westbranchresort.com/images/new-header/menu_r1_c2.jpg" width="118" height="20" border="0" alt="About Us" /></a></td>
   <td><a href="http://www.westbranchresort.com/accomidations.html"><img name="menu_r1_c3" src="http://www.westbranchresort.com/images/new-header/menu_r1_c3.jpg" width="120" height="20" border="0" alt="Lodging" /></a></td>
   <td><a href="http://www.westbranchresort.com/index_flyshop.html"><img name="menu_r1_c4" src="http://www.westbranchresort.com/images/new-header/menu_r1_c4.jpg" width="127" height="20" border="0" alt="Online Fly Shop"/></a></td>
   <td><a href="http://www.westbranchresort.com/cgi-bin/forum/YaBB.pl" target="_blank"><img name="menu_r1_c5" src="http://www.westbranchresort.com/images/new-header/menu_r1_c5.jpg" width="99" height="20" border="0" alt="Forum" /></a></td>
   <td><a href="http://www.westbranchresort.com/gallery/main.php" target="_blank"><img name="menu_r1_c6" src="http://www.westbranchresort.com/images/new-header/menu_r1_c6.jpg" width="91" height="20" border="0" alt="Gallery" /></a></td>
   <td><a href="http://www.westbranchresort.com/finedining.html"><img name="menu_r1_c7" src="http://www.westbranchresort.com/images/new-header/menu_r1_c7.jpg" width="116" height="20" border="0" id="menu_r1_c7" alt="Restaurant" /></a></td>
   <td><a href="http://www.westbranchresort.com/directions.html"><img name="menu_r1_c8" src="http://www.westbranchresort.com/images/new-header/menu_r1_c8.jpg" width="111" height="20" border="0" id="menu_r1_c8" alt="Directions" /></a></td>
   <td><a href="http://www.westbranchresort.com/corporate/"><img name="menu_r1_c9" src="http://www.westbranchresort.com/images/new-header/menu_r1_c9.jpg" width="129" height="20" border="0" id="menu_r1_c9" alt="Contact Us" /></a></td>
   <td><img src="images/spacer.gif" width="1" height="20" border="0" alt="" /></td>
  </tr>
</table>
</td>
  </tr>
</table>

<table width="780" height="1122" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#000000" bgcolor="#FFFFFF">
  <tr>
    <td width="778
" height="1120" valign="top"> 
      <table width="100%" bgcolor="<?= $data[menu] ?>" border="0" cellspacing="0" cellpadding="0">
        <tr>
<td width="20%" align="center"><div class="normal"></div></td><td bgcolor="<?= $data[border] ?>" width="1"><img src="blank.gif" width="1" height="1" /></td>
<td width="20%" align="center"><div class="normal"><a href="<?= $HD_URL_TICKET_VIEW ?>"><?= $LANG[link_view_ticket] ?></a></div></td><td bgcolor="<?= $data[border] ?>" width="1"><img src="blank.gif" width="1" height="22" /></td>
<td width="20%" align="center"><div class="normal"><a href="<?= $HD_URL_TICKET_LOST ?>?cmd=lost"><?= $LANG[link_lost_ticket] ?></a></div></td><td bgcolor="<?= $data[border] ?>" width="1"><img src="blank.gif" width="1" height="22" /></td>
<td width="20%" align="center"><div class="normal"><a href="<?= $HD_URL_LOGIN ?>"><?= $LANG[link_staff_login] ?></a></div></td><td bgcolor="<?= $data[border] ?>" width="1"><img src="blank.gif" width="1" height="22" /></td>
<td width="20%" align="center"><div class="normal"></div></td>
</tr>
<tr><td bgcolor="<?= $data[border] ?>" height="1" colspan="9"><img src="blank.gif" width="1" height="1" /></td></tr>
</table>
      <table width="100%" border="0" cellpadding="15" cellspacing="0" bordercolor="#333333">
        <tr>
          <td height="1183">
<table width="95%" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td width="95%" height="183" valign="top"> 
                  <p align="center"><img src="../images/information-request-sysytem.gif" width="283" height="50"></p>
                  <p>Welcome to the West Branch Resort Information Request System. 
                    The Information Request System allows for documented coresondance 
                    between customers and the staff at West Branch Resort. Unlike 
                    general email corespondenece all communications are documented 
                    through our web based system. This way you can login at anytime 
                    and see the status of your reservations. To get started enter 
                    the type of services you are looking for along with all the 
                    details for your request. Our system will then dispatch the 
                    request to the proper department and get you the information 
                    that you request ASAP. </p>
                  <p><font color="#CC0000">**We do not take online reservations 
                    this system is for information gathering. </font></p>
                  </td>
        
              </tr>
            </table> 
            <?/* PHP **********************************************************/
}
else
  eval( "?>
            <p>{$data[header]} 
            <?" );
/********************************************************** PHP */?>
            <style type="text/css">
<?= $data[styles] ?>
</style>
</p><div class="title"><?= $LANG[create_new_ticket] ?></div><br /><?= $msg ?>
<div class="normal">
<?/* PHP **********************************************************/
if( $success )
{
  eval( "echo \"{$LANG[ticket_created]}<br /><br /><br />\";" );
}
else
{
/********************************************************** PHP */?>
<?= $LANG[fill_in_form] ?><br /><br />
<font color="#FF0000"><?= $LANG[required_field] ?></font><br /><br />

              <table width="79%" border="0" align="center" cellpadding="0" cellspacing="3">
                <form action="<?= $HD_CURPAGE ?>" method="post">
<tr><td width="276" align="right"><div class="normal"><?= $LANG[field_name] ?><font color="#FF0000">*</font></div></td><td width="307"><input type="text" name="name" value="<?= field( $_POST[name] ) ?>" size="30" /></td></tr>
<tr><td width="276" align="right"><div class="normal"><?= $LANG[field_email] ?><font color="#FF0000">*</font></div></td><td><input type="text" name="email" value="<?= field( $_POST[email] ) ?>" size="30" /></td></tr>
<tr><td colspan="2"><?/* PHP **********************************************************/
  $res = mysql_query( "SELECT * FROM {$pre}options WHERE ( name LIKE 'custom%' ) ORDER BY num DESC" );
  if( mysql_num_rows( $res ) )
  {
    while( $row = mysql_fetch_array( $res ) )
      echo "<tr><td width=\"200\" align=\"right\"><div class=\"normal\">" . field( $row[text] ) . ":" . ($row[num] ? "<font color=\"#FF0000\">*</font>" : "") . "</div></td><td><input type=\"text\" name=\"{$row[name]}\" value=\"" . field( $_POST[$row[name]] ) . "\" size=\"30\" /></td></tr>";

    echo "<tr><td colspan=\"2\"><br /></td></tr>";
  }
/********************************************************** PHP */?><br /></td></tr>
<tr><td width="276" align="right"><div class="normal"><?= $LANG[field_department] ?><font color="#FF0000">*</font></div></td>
<td>
<select name="department">
<?/* PHP **********************************************************/
  $res = mysql_query( "SELECT * FROM {$pre}dept WHERE ( !(options & {$HD_DEPARTMENT_INVISIBLE}) )" );

  if( mysql_num_rows( $res ) == 1 )
    echo "<option value=\"0\">Global</option>\n";
  else
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
<tr><td width="276" align="right"><div class="normal"><?= $LANG[field_subject] ?><font color="#FF0000">*</font></div></td><td><input type="text" name="subject" value="<?= field( $_POST[subject] ) ?>" size="30" /></td></tr>
<tr><td width="276" align="right"><div class="normal"><?= $LANG[field_message] ?><font color="#FF0000">*</font></div></td><td><? if( $data[tags] ) echo "<br /><div class=\"normal\"><font size=\"-2\"><b>You can use <a href=\"$HD_URL_TICKET_TAGS\" target=\"_blank\">message tags</a></b></font></div><img src=\"blank.gif\" width=\"1\" height=\"5\" /><br />"; ?><textarea name="message" rows="8" cols="45"><?= field( $_POST[message] ) ?></textarea></td></tr>
<tr><td colspan="2"><br /></td></tr>
<tr><td width="276" align="right"><div class="normal"><?= $LANG[field_priority] ?><font color="#FF0000">*</font></div></td>
<td><select name="priority"><option value="<?= $PRIORITY_LOW ?>"><?= $LANG[field_priority_low] ?></option><option value="<?= $PRIORITY_MEDIUM ?>"><?= $LANG[field_priority_medium] ?></option><option value="<?= $PRIORITY_HIGH ?>"><?= $LANG[field_priority_high] ?></option></select></td>
</tr>
<tr><td colspan="2"><br /></td></tr>

<tr><td></td><td><div class="normal"><input type="checkbox" name="notify" checked /> <?= $LANG[ticket_notify] ?></div></td>

<tr><td></td><td><br /><input type="submit" value="Create Ticket" />&nbsp;<input type="Reset" /></td></tr>
</form>
</table>
<?/* PHP **********************************************************/
}
/********************************************************** PHP */?>
</div>
            <?/* PHP **********************************************************/
if( trim( $data[header] ) == "" )
{
/********************************************************** PHP */?>
            <p>You can also contact us the following ways:</p>
            <p><font color="#CC0000">Mailing Address:</font><br>
              West Branch Angler Resort<br>
              PO Box 102<br>
              Deposit, NY 13754<br>
              <font color="#CC0000"><br>
              Shipping Address:</font><br>
              150 Faulkner Rd<br>
              Hancock, NY 13783</p>
            <p>Phone: (607) 467-5525<br>
              Fax: (607) 467-2215<br>
              Toll-free: (800) 201-2557<br>
              e-mail: <a href="mailto:wbangler@westbranchangler.com">wbangler@westbranchangler.com</a></p></td>
</tr>
</table>
</td>
</tr>
</table>
<table width="700" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr><td " height="3"><img src="blank.gif" width="1" height="3" /></td></tr>
  <tr> 
    <td align="center"><br />
      Copyright &copy; 1991 - 2004 West Branch Resort</font></td>
  </tr>
</table>
</body>
</html>
<?/* PHP **********************************************************/
}
else
  eval( "?> {$data[footer]} <?" );
/********************************************************** PHP */?>