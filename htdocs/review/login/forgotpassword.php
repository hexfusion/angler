<?php
session_start();
session_destroy();
session_start();

//Prevent Cross-Site Request Forgeries//
$token = md5(uniqid(rand(), TRUE));
$_SESSION['token'] = $token;
$_SESSION['token_timestamp'] = time();
////

include ("../body.php");
include ("../functions.php");
include ("../f_secure.php");
include ("../config.php");

$item_id = $_GET['item_id'];
$back = $_GET['back'];

if(!is_numeric($item_id)) {
BodyHeader("Invalid item");
echo "The item you are attempting to view is not valid.";
BodyFooter();
exit;
}

BodyHeader("Password Retrieval - $sitename"); 
?>
<table width="90%" border="0" align="center">
  <TR>
      <TD VALIGN=bottom COLSPAN=3>
         <P><FONT FACE="Arial"><BR>
      </FONT>
      <FORM method=post ACTION="forgotpassword2.php?<?php echo htmlspecialchars(SID); ?>&item_id=<?php if ($item_id == "") {
$item_id = "1";
}
echo "$item_id"; ?>&back=<?php echo "$back"; ?>">
        <P> 
        <P><FONT SIZE="-1" FACE="Arial">Enter your registered email address below. 
          The password for the account will be reset and mailed to the e-mail address you 
          used when you created the account. The email will contain instructions on how to reset your password. If your e-mail address has changed 
          since you registered, let us know at </FONT><font face="Arial"><A HREF="mailto:<?php echo "$admin"; ?>"><FONT SIZE="-1"><?php echo "$admin"; ?></FONT></A><FONT SIZE="-1"> 
          and we will change our records after confirming it with you.</FONT></font> 
        <P> 
        <TABLE align="center">
          <TR> 
            <TD> <P><FONT SIZE="-1" FACE="Arial">Email Address:</FONT> </TD>
            <TD> <P><FONT size="-1" FACE="Arial"> 
                <INPUT NAME=email TYPE=text id="email" SIZE=30 MAXLENGTH=50>
                </FONT> </TD>
          </TR>
        </TABLE>
        <font size="-1" face="Arial"><br />
        <BR>
        <BR>
        </font> 
        <CENTER>
          <FONT SIZE="-1" FACE="Arial">
          <INPUT TYPE=submit NAME=SUBMIT VALUE="E-Mail My Password" class=submit>
          </FONT>
        </CENTER>
        <input type="hidden" name="token" value="<?php echo $token; ?>" />
      </FORM>
      <FONT FACE="Arial"><BR>
      <BR>
      </FONT> </TD>
   </TR>
   </table>
<?php
BodyFooter(); 
exit;
?>

