<?
session_start();
session_register('signedin');

include('../../config.php'); 
include ("../../body.php");

$item_id = $_GET["item_id"];
$back = $_GET["back"];
$signedin = $_COOKIE["signedin"];

BodyHeader("Please Register");
?>
<form action="register2.php?<?=SID?>"  method="post">
<table border="0">
  <tr>
    <td><img src="/images/write.gif" width=35 height=35 alt="review_icon" align=left></td>
    <td><font face=verdana,arial,helvetica size=4><b>Write Your Own Review</b></font><br></td>
  </tr>
</table>
<table border="0" cellpadding="3" cellspacing="1" width="100%" class="forumline">
	<tr>
		<th class="thHead" colspan="2" height="25" valign="middle"><div align="left"><strong><font face=verdana,arial,helvetica color=#CC6600>New to <? echo $sitename; ?>? Register Below.</font></strong></div></th>
	</tr>
	<tr>
		<td class="row2" colspan="2">Items marked with a <font color="#FF0000">*</font> are required unless stated otherwise.</td>
	</tr>
	<tr>
		<td class="row1" width="55%"><strong>Enter your desired username: <span class="row2"><font color="#FF0000">*</font></strong></td>
		<td width="45%" class="row2"><input type="text" class="post" style="width:200px" name="username" size="25" maxlength="25" value="" /></td>
	</tr>
	<tr>
		<td class="row1"><b>My e-mail address</b>: <span class="row2"><font color="#FF0000">*</font></td>
		<td class="row2"><input type="text" class="post" style="width:200px" name="email" size="25" maxlength="255" value="" /></td>
	</tr>
	<tr>
	  <td class="row1"><dl>
	    <dd>
        <dt><strong><font face=verdana,arial,helvetica color=#CC6600>Protect your information with a password</font></strong><br>
          <font size="2">          (This will be your only <strong><font color=#CC6600>          <? echo $sitename; ?></font></strong> password. )
</font>
		
	  </dl>	    </td>
	  <td class="row2">
		<input name="password" type="password" class="post" id="password" style="width: 200px" value="" size="25" maxlength="100" />
	  </td>
	</tr>
	<tr>
		<td class="catBottom" colspan="2" align="center" height="28"><input type="submit" name="submit" value="Submit" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="Reset" name="reset" class="liteoption" /></td>
	</tr>
</table>
<input type=hidden name="back" value="<? echo "$back"; ?>">
<input type=hidden name="item_id" value="<? echo "$item_id"; ?>">
</form>
<?
BodyFooter();
exit;
?>