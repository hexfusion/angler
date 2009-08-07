<?
session_start();

include ('functions.php');
include ('f_secure.php');
include ('body.php');
include ('config.php');

$name = html_entity_decode($_GET['name']);

$sqlaccess = "SELECT name, username
		FROM review_users
		WHERE name='$name'
		";

	$resultaccess = mysql_query($sqlaccess)
		or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));

while($row = mysql_fetch_array($resultaccess)) {
$username = stripslashes($row['username']);
} //while


	$numaccess = mysql_numrows($resultaccess);

		if ($numaccess == 0) {
BodyHeader("User Not Found!");
?>
<P>The user was not found. Please push the back button in your browser to proceed.<br /> 

<?php BodyFooter();  
exit;  
}

BodyHeader("Profile - $sitename","","");
?>
<table width="80%" border="0" align="center" cellpadding="0" cellspacing="0"><tr>
    <td><p>&nbsp;</p>
      <p><B><FONT face=verdana,arial,helvetica color=#cc6600>About <?php echo ucfirst($name); ?></FONT></B> <SPAN class=small>- Add to your favorite people list</SPAN> </p>
      <TABLE cellSpacing=2 cellPadding=2 width="100%" border=0>
        <TBODY>
          <TR>
            <TD class=small vAlign=top><TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
                <TBODY>
                  <TR>
                    <TD class=small style="LINE-HEIGHT: 150%" vAlign=top><B>Name:</B> <?php echo ucfirst($name); ?><BR>                
                <B>E-mail:</B> <A 
href="mailto:<?php echo $username; ?>"><?php echo $username; ?></A><BR>
                <B>Reviewer rank:</B> 4814<BR>
                <B>About me:</B> I am a business consultant, magician, university guest lectuer,and I own a European / USA based ... <SPAN 
class=tiny>see&nbsp;more</SPAN> </TD>
                  <TR>
                    <TD class=small style="PADDING-TOP: 5px" vAlign=top><BR>
                        <IMG height=9 
src="http://g-images.amazon.com/images/G/01/x-locale/common/orange-arrow.gif" 
width=10 border=0>E-mail this page to a friend </TD>
                  </TR>
                </TBODY>
            </TABLE></TD>
          </TR>
        </TBODY>
      </TABLE>
      <p>&nbsp;</p></td>
  </tr></table>



<?
BodyFooter();
exit;
?>
