<?php
session_start();

include ('../functions.php');
include ('../f_secure.php');
include ('../body.php');
include ('../config.php');


$name = makeStringSafe($_SESSION['name_logged']);
$username = makeStringSafe($_SESSION['username_logged']);

if (!$username) {
BodyHeader("User Not Found!","","");
?>
<link href="../review.css" rel="stylesheet" type="text/css" />
<p align="center"></p>
<P align="center">The user was not found. Click to <a href=../login/register.php?item_id=$item_id&back=$back>Register</a> or if you've already registered, <a href=../login/signin.php?item_id=$item_id&back=$back>Login In</a>.
<P align="center"><br />
  <?php BodyFooter();  
exit;  
}

$sqlaccess = "SELECT *
		FROM review_users
		WHERE username='" . makeStringSafe($username) . "'
		";

	$resultaccess = mysql_query($sqlaccess)
		or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));

while($row = mysql_fetch_array($resultaccess)) {
$name = stripslashes($row['name']);
$email = stripslashes($row['email']);
$username = stripslashes($row['username']);
$passtext= stripslashes($row['passtext']);
$city= stripslashes($row['city']);
$state= stripslashes($row['state']);
$zip= stripslashes($row['zip']);
$age= stripslashes($row['age']);
$profession= stripslashes($row['profession']);
$aboutme= stripslashes($row['aboutme']);
$sig= stripslashes($row['sig']);
$skype= stripslashes($row['skype']);
$adsense_clientid= stripslashes($row['adsense_clientid']);
$adsense_channelid= stripslashes($row['adsense_channelid']);

} //while

	$numaccess = mysql_numrows($resultaccess);

		if ($numaccess == 0) {
BodyHeader("User Not Found!","","");
?>
<P align="center">The user was not found. Please push the back button in your browser to proceed.
<P align="center"><br />
  <?php BodyFooter();  
exit;  
}

BodyHeader("Profile","","");
//BodyHeader("Edit ".$info['name']."'s Profile");
?>
<FORM METHOD=POST ACTION="profile_edit2.php?<?php echo htmlspecialchars(SID); ?>">
  <INPUT TYPE="hidden" NAME="username" VALUE="<?php echo $username; ?>">
  <table width="95%" border="0" cellspacing="0" cellpadding="0" align="center" bordercolor="#0821A5">
    <TR>
      <TD COLSPAN=2><CENTER>
          <span class="index2-Orange"><b><font face="Verdana, Arial, Helvetica, sans-serif"> Please Update Your Profile Options </font> </b> </span>
        </CENTER></TD>
    </TR>
    <tr bgcolor="#FFFFFF">
      <TD width="33%" bordercolor="#0821A5"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><b>Your UserName</b></font></TD>
      <TD width="67%" bordercolor="#0821A5"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><b> <?php echo "$username"; ?> </b></font></TD>
    </TR>
    <TR bgcolor="#FFFFFF">
      <TD width="33%"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><b>Update Your Password</b></font></TD>
      <TD width="67%"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><b>
        <INPUT TYPE="password" NAME="passtext" size="10" />
        </b></font></TD>
    </TR>
    <TR bgcolor="#FFFFFF">
      <TD width="33%"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><b> Name</b></font></TD>
      <TD width="67%"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><b>
        <INPUT TYPE="text" NAME="name" size="10" VALUE="<?php echo "$name"; ?>" />
        </b></font></TD>
    </TR>
    <TR bgcolor="#FFFFFF">
      <TD width="33%"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><b>City</b></font></TD>
      <TD width="67%"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><b>
        <INPUT TYPE="text" NAME="city" size="10" VALUE="<?php echo "$city"; ?>" />
        </b></font></TD>
    </TR>
    <TR bgcolor="#FFFFFF">
      <TD width="33%"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><b>State</b></font></TD>
      <TD width="67%"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><b>
        <SELECT NAME="state">
          <OPTION VALUE="AL"<?php if ($state == "AL") { echo " SELECTED"; } ?>>Alabama</OPTION>
          <OPTION VALUE="AK"<?php if ($state == "AK") { echo " SELECTED"; } ?>>Alaska</OPTION>
          <OPTION VALUE="AZ"<?php if ($state == "AZ") { echo " SELECTED"; } ?>>Arizona</OPTION>
          <OPTION VALUE="AR"<?php if ($state == "AR") { echo " SELECTED"; } ?>>Arkansas</OPTION>
          <OPTION VALUE="CA"<?php if ($state == "CA") { echo " SELECTED"; } ?>>California</OPTION>
          <OPTION VALUE="CO"<?php if ($state == "CO") { echo " SELECTED"; } ?>>Colorado</OPTION>
          <OPTION VALUE="CT"<?php if ($state == "CT") { echo " SELECTED"; } ?>>Connecticut</OPTION>
          <OPTION VALUE="DE"<?php if ($state == "DE") { echo " SELECTED"; } ?>>Delaware</OPTION>
          <OPTION VALUE="FL"<?php if ($state == "FL") { echo " SELECTED"; } ?>>Florida</OPTION>
          <OPTION VALUE="GA"<?php if ($state == "GA") { echo " SELECTED"; } ?>>Georgia</OPTION>
          <OPTION VALUE="HI"<?php if ($state == "HI") { echo " SELECTED"; } ?>>Hawaii</OPTION>
          <OPTION VALUE="ID"<?php if ($state == "ID") { echo " SELECTED"; } ?>>Idaho</OPTION>
          <OPTION VALUE="IL"<?php if ($state == "IL") { echo " SELECTED"; } ?>>Illinois</OPTION>
          <OPTION VALUE="IN"<?php if ($state == "IN") { echo " SELECTED"; } ?>>Indiana</OPTION>
          <OPTION VALUE="IA"<?php if ($state == "IA") { echo " SELECTED"; } ?>>Iowa</OPTION>
          <OPTION VALUE="KS"<?php if ($state == "KS") { echo " SELECTED"; } ?>>Kansas</OPTION>
          <OPTION VALUE="KY"<?php if ($state == "KY") { echo " SELECTED"; } ?>>Kentucky</OPTION>
          <OPTION VALUE="LA"<?php if ($state == "LA") { echo " SELECTED"; } ?>>Louisiana</OPTION>
          <OPTION VALUE="ME"<?php if ($state == "ME") { echo " SELECTED"; } ?>>Maine</OPTION>
          <OPTION VALUE="MD"<?php if ($state == "MD") { echo " SELECTED"; } ?>>Maryland</OPTION>
          <OPTION VALUE="MA"<?php if ($state == "MA") { echo " SELECTED"; } ?>>Massachusetts</OPTION>
          <OPTION VALUE="MI"<?php if ($state == "MI") { echo " SELECTED"; } ?>>Michigan</OPTION>
          <OPTION VALUE="MN"<?php if ($state == "MN") { echo " SELECTED"; } ?>>Minnesota</OPTION>
          <OPTION VALUE="MS"<?php if ($state == "MS") { echo " SELECTED"; } ?>>Mississippi</OPTION>
          <OPTION VALUE="MO"<?php if ($state == "MO") { echo " SELECTED"; } ?>>Missouri</OPTION>
          <OPTION VALUE="MT"<?php if ($state == "MT") { echo " SELECTED"; } ?>>Montana</OPTION>
          <OPTION VALUE="NE"<?php if ($state == "NE") { echo " SELECTED"; } ?>>Nebraska</OPTION>
          <OPTION VALUE="NV"<?php if ($state == "NV") { echo " SELECTED"; } ?>>Nevada</OPTION>
          <OPTION VALUE="NH"<?php if ($state == "NH") { echo " SELECTED"; } ?>>New Hampshire</OPTION>
          <OPTION VALUE="NJ"<?php if ($state == "NJ") { echo " SELECTED"; } ?>>New Jersey</OPTION>
          <OPTION VALUE="NM"<?php if ($state == "NM") { echo " SELECTED"; } ?>>New Mexico</OPTION>
          <OPTION VALUE="NY"<?php if ($state == "NY") { echo " SELECTED"; } ?>>New York</OPTION>
          <OPTION VALUE="NC"<?php if ($state == "NC") { echo " SELECTED"; } ?>>North Carolina</OPTION>
          <OPTION VALUE="ND"<?php if ($state == "ND") { echo " SELECTED"; } ?>>North Dakota</OPTION>
          <OPTION VALUE="OH"<?php if ($state == "OH") { echo " SELECTED"; } ?>>Ohio</OPTION>
          <OPTION VALUE="OK"<?php if ($state == "OK") { echo " SELECTED"; } ?>>Oklahoma</OPTION>
          <OPTION VALUE="OR"<?php if ($state == "OR") { echo " SELECTED"; } ?>>Oregon</OPTION>
          <OPTION VALUE="PA"<?php if ($state == "PA") { echo " SELECTED"; } ?>>Pennsylvania</OPTION>
          <OPTION VALUE="RI"<?php if ($state == "RI") { echo " SELECTED"; } ?>>Rhode Island</OPTION>
          <OPTION VALUE="SC"<?php if ($state == "SC") { echo " SELECTED"; } ?>>South Carolina</OPTION>
          <OPTION VALUE="SD"<?php if ($state == "SD") { echo " SELECTED"; } ?>>South Dakota</OPTION>
          <OPTION VALUE="TN"<?php if ($state == "TN") { echo " SELECTED"; } ?>>Tennessee</OPTION>
          <OPTION VALUE="TX"<?php if ($state == "TX") { echo " SELECTED"; } ?>>Texas</OPTION>
          <OPTION VALUE="UT"<?php if ($state == "UT") { echo " SELECTED"; } ?>>Utah</OPTION>
          <OPTION VALUE="VT"<?php if ($state == "VT") { echo " SELECTED"; } ?>>Vermont</OPTION>
          <OPTION VALUE="VA"<?php if ($state == "VA") { echo " SELECTED"; } ?>>Virginia</OPTION>
          <OPTION VALUE="WA"<?php if ($state == "WA") { echo " SELECTED"; } ?>>Washington</OPTION>
          <OPTION VALUE="WV"<?php if ($state == "WV") { echo " SELECTED"; } ?>>West Virginia</OPTION>
          <OPTION VALUE="WI"<?php if ($state == "WI") { echo " SELECTED"; } ?>>Wisconsin</OPTION>
          <OPTION VALUE="WY"<?php if ($state == "WY") { echo " SELECTED"; } ?>>Wyoming</OPTION>
          <OPTGROUP LABEL="Province">
          <OPTION VALUE="AB"<?php if ($state == "AB") { echo " SELECTED"; } ?>>Alberta</OPTION>
          <OPTION VALUE="BC"<?php if ($state == "BC") { echo " SELECTED"; } ?>>B.C.</OPTION>
          <OPTION VALUE="MAN"<?php if ($state == "MAN") { echo " SELECTED"; } ?>>Manitoba</OPTION>
          <OPTION VALUE="NB"<?php if ($state == "NB") { echo " SELECTED"; } ?>>New Brunswick</OPTION>
          <OPTION VALUE="NF"<?php if ($state == "NF") { echo " SELECTED"; } ?>>Newfoundland</OPTION>
          <OPTION VALUE="NS"<?php if ($state == "NS") { echo " SELECTED"; } ?>>Nova Scotia</OPTION>
          <OPTION VALUE="ON"<?php if ($state == "ON") { echo " SELECTED"; } ?>>Ontario</OPTION>
          <OPTION VALUE="PEI"<?php if ($state == "PEI") { echo " SELECTED"; } ?>>P.E.I.</OPTION>
          <OPTION VALUE="QB"<?php if ($state == "QB") { echo " SELECTED"; } ?>>Quebec</OPTION>
          <OPTION VALUE="SK"<?php if ($state == "SK") { echo " SELECTED"; } ?>>Saskatchewan</OPTION>
          </optgroup>
          <OPTGROUP LABEL="Other">
          <OPTION VALUE="OTHER"<?php if ($state == "OTHER") { echo " SELECTED"; } ?>>Other</OPTION>
        </SELECT>
        </b></font></TD>
    </TR>
    <TR bgcolor="#FFFFFF">
      <TD width="33%"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><b>Zip</b></font></TD>
      <TD width="67%"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><b>
        <INPUT TYPE="text" NAME="zip" size="10" VALUE="<?php echo "$zip"; ?>" />
        </b></font></TD>
    </TR>
    <TR bgcolor="#FFFFFF">
      <TD height="33"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><b>Email</b></font></TD>
      <TD height="33"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><b>
        <INPUT TYPE="text" NAME="email" size="10" VALUE="<?php echo "$email"; ?>" />
        </b></font></TD>
    </TR>
    <TR bgcolor="#FFFFFF">
      <TD width="33%" height="33"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><b>Age</b></font></TD>
      <TD width="67%" height="33"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><b>
        <INPUT TYPE="text" NAME="age" size="3" VALUE="<?php echo "$age"; ?>" />
        </b></font></TD>
    </TR>
    <tr bgcolor="#FFFFFF" >
      <td width="33%"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><b>Profession:</b></font></td>
      <td width="67%"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><b>
        <SELECT NAME="profession">
          <OPTION VALUE="Accounting"<?php if ($profession == "Accounting") { echo " SELECTED"; } ?>>Accounting/Finance</OPTION>
          <OPTION VALUE="ComputerISMISDP"<?php if ($profession == "ComputerISMISDP") { echo " SELECTED"; } ?>>Computer related (IS, MIS, DP)</OPTION>
          <OPTION VALUE="ComputerWWW"<?php if ($profession == "ComputerWWW") { echo " SELECTED"; } ?>>Computer related (WWW)</OPTION>
          <OPTION VALUE="Consulting"<?php if ($profession == "Consulting") { echo " SELECTED"; } ?>>Consulting</OPTION>
          <OPTION VALUE="CustomerSvc"<?php if ($profession == "CustomerSvc") { echo " SELECTED"; } ?>>Customer service/support</OPTION>
          <OPTION VALUE="Education"<?php if ($profession == "Education") { echo " SELECTED"; } ?>>Education/training</OPTION>
          <OPTION VALUE="Engineering"<?php if ($profession == "Engineering") { echo " SELECTED"; } ?>>Engineering</OPTION>
          <OPTION VALUE="Executive"<?php if ($profession == "Executive") { echo " SELECTED"; } ?>>Executive/senior management</OPTION>
          <OPTION VALUE="GenAdminSupe"<?php if ($profession == "GenAdminSupe") { echo " SELECTED"; } ?>>General administrative/supervisory</OPTION>
          <OPTION VALUE="GovMil"<?php if ($profession == "GovMil") { echo " SELECTED"; } ?>>Government/Military</OPTION>
          <OPTION VALUE="Manufacturing"<?php if ($profession == "Manufacturing") { echo " SELECTED"; } ?>>Manufacturing/production/operations</OPTION>
          <OPTION VALUE="Professional"<?php if ($profession == "Professional") { echo " SELECTED"; } ?>>Professional services (eg. medical)</OPTION>
          <OPTION VALUE="RandD"<?php if ($profession == "RandD") { echo " SELECTED"; } ?>>Research and development</OPTION>
          <OPTION VALUE="Retired"<?php if ($profession == "Retired") { echo " SELECTED"; } ?>>Retired</OPTION>
          <OPTION VALUE="Sales"<?php if ($profession == "Sales") { echo " SELECTED"; } ?>>Sales/marketing/advertising</OPTION>
          <OPTION VALUE="Student"<?php if ($profession == "Student") { echo " SELECTED"; } ?>>Student</OPTION>
          <OPTION VALUE="Unemployed"<?php if ($profession == "Unemployed") { echo " SELECTED"; } ?>>Unemployed/Between Jobs</OPTION>
        </SELECT>
        </b></font></td>
    </tr>
    <tr bgcolor="#FFFFFF">
      <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><b>Signature</b></font>:<br />
        <a href="faq_bbcode.php?<?php echo htmlspecialchars(SID); ?>" target="_blank">BBCode</a> is
        <?php if ($use_bbcode == "yes") { echo "<B>ON</B>"; } else { echo "<B>OFF</B>"; } ?>
        . </td>
  
      <td><textarea name="sig" id="sig"><?php echo "$sig"; ?></textarea>
      </td>
    </tr>
    <tr>
      <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><b><a href="http://www.skype.com" target="_blank">Skype</a> Name: </b></font></td>
      <td bordercolor="#0821A5"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><b>
        <input type="text" name="skype" size="10" value="<?php echo @$info['skype'] ?>" />
        </b></font></td>
    </tr>
    <tr bgcolor="#FFFFFF" >
      <td width="33%"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><b>About Me :</b></font></td>
      <td width="67%"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><b>
        <textarea name="aboutme" cols="40" rows="5" id="aboutme" value="<?php echo "$description"; ?>"><?php echo "$aboutme"; ?></textarea>
        </b></font></td>
    </tr>
    <tr>
      <td><strong><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Google AdSense Client ID:</font></strong> <br />
        <br />
        If you have an AdSense account (if not, click the blue image below to get   your free account), you can enter your client ID here. 50% of the revenue   generated from the AdSense ads is split among those who contribute to the review in which the ad was clicked.<br /></td>
      <td><p>&nbsp;</p>
        <p>
          <input id="adsense_clientid" maxlength="20" size="25" value="<?php if ($adsense_clientid != "") { echo "$adsense_clientid"; } ?>" name="adsense_clientid" />
        </p></td>
    </tr>
    <tr>
      <td><strong><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><br />
        Google AdSense Channel ID:</font></strong><br />
        <br />
        If you choose to enter your AdSense client ID, you can specify a channel ID as well.
        
        It would be a good idea to have a unique channel ID for this site (then you can track stats specifically for the reviews) </td>
      <td><input name="adsense_channelid" id="adsense_channelid" value="<?php if ($adsense_channelid != "") { echo "$adsense_channelid"; } ?>" size="25" maxlength="20" /></td>
    </tr>
    <tr >
      <td colspan="2"><div align="center">
          <script type="text/javascript"><!--
google_ad_client = "pub-4166677013602529";
google_ad_width = 180;
google_ad_height = 60;
google_ad_format = "180x60_as_rimg";
google_cpa_choice = "CAAQr_Wy0gEaCPfBz19CTlSHKL3D93M";
//--></script>
          <script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
    </script>
        </div></td>
    </tr>
    <TR bgcolor="#FFFFFF">
      <TD COLSPAN=2><CENTER>
          <font size="2"><b><font face="Verdana, Arial, Helvetica, sans-serif"> <br />
          <INPUT TYPE="submit" Value="Edit Profile!">
          </font> </b> </font><BR>
          <BR>
          <?php //show navigation links on the bottom
include('user_cp_links.php'); ?>
        </CENTER></TD>
    </TR>
  </TABLE>
</FORM>
<?php 
BodyFooter();
exit;
?>
