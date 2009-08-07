<?php
session_start();

include ("../functions.php");
include ("../f_secure.php");
include ("../body.php");
include ("../config.php");

$name = $_SESSION['name'];

$sqlaccess = "SELECT *
		FROM review_users
		WHERE name='$name'
		";

	$resultaccess = mysql_query($sqlaccess)
		or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));

while($row = mysql_fetch_array($resultaccess)) {
$username = stripslashes($row['username']);
$city= stripslashes($row['city']);
$state= stripslashes($row['state']);
$zip= stripslashes($row['zip']);
$age= stripslashes($row['age']);
$profession= stripslashes($row['profession']);
$aboutme= stripslashes($row['aboutme']);
$sig= stripslashes($row['sig']);
$skype= stripslashes($row['skype']);
} //while

	$numaccess = mysql_numrows($resultaccess);

		if ($numaccess == 0) {
BodyHeader("User Not Found!");
?>

<P>The user was not found. Please push the back button in your browser to proceed.<br>
<?php BodyFooter();  
exit;  
}

BodyHeader("Profile");

if ($_POST['action'] == "edit_two") { 

/*
function is_valid_email ($email) { 
    return (preg_match( 
        '/^[-!#$%&\'*+\\.\/0-9=?A-Z^_`{|}~]+'.   // the user name 
        '@'.                                     // the ubiquitous at-sign 
        '([-0-9A-Z]+\.)+' .                      // host, sub-, and domain names 
        '([0-9A-Z]){2,4}$/i',                    // top-level domain (TLD) 
        trim($email))); 
}

$email = strtolower(trim($email)); 

if (is_valid_email($email)) { 
$email = "$email";
} else {
BodyHeader("Bad Email Address");
echo "You've entered a bad email address.";
exit;
}
*/
$city= $_POST['city'];
$state= $_POST['state'];
$zip= $_POST['zip'];
$age= $_POST['age'];
$profession= $_POST['profession'];
$aboutme= $_POST['aboutme'];
$sig= $_POST['sig'];

if ($use_bbcode == "yes") {
include("bbcode.php");

  $sig = str_replace($htmlcode, $bbcode, $sig);
  $sig = nl2br($sig);//second pass

  $review = str_replace($bbcode, $htmlcode, $review);
 $review = nl2br($review);//second pass
}

    $user = mysql_query("UPDATE review_users

	SET 

	name='".addslashes($name)."',

	city='".addslashes($city)."',

	state='$state',

	zip='".addslashes($zip)."',	

	age='$age',

	profession='$profession',

	aboutme='".addslashes($aboutme)."',
	sig='".addslashes($sig)."'

	WHERE username='$username'"); 


//BodyHeader("Update Success");

?>
<?php echo "<BR><BR><BR>" . ucfirst($name); ?>, You Have Successfully Edited Your Profile.<BR>
<BR>
<BR>
Click <a href="profile.php?<?=SID?>">here</a> to go to your Profile.
<?php 

//BodyFooter();  

} 



else { 



//Perform a query on each table to determine which table the user's profile is located.



$query = mysql_query("SELECT * FROM review_users WHERE(name='$name')"); 





if (mysql_num_rows($query) != 1) { 


BodyHeader("The username/password combination is incorrect.");

        echo "$username / $pass Combination is incorrect<P>Please Try Again."; 

  BodyFooter();  

        exit; 

  } 





    else { 

        $info = mysql_fetch_array($query); 


//BodyHeader("Edit ".$info['name']."'s Profile");







?>
<FORM METHOD=POST ACTION="<?php echo $_SERVER['PHP_SELF']; ?>">
  <INPUT TYPE="hidden" NAME="username" VALUE="<?php echo $info['username'] ?>">
  <INPUT TYPE="hidden" NAME="action" VALUE="edit_two">
  <table width="95%" border="0" cellspacing="0" cellpadding="0" align="center" bordercolor="#0821A5">
    <TR>
      <TD COLSPAN=2><CENTER>
          <font size="2"><b><font face="Verdana, Arial, Helvetica, sans-serif"> Please Update Your Profile Options </font> </b> </font>
        </CENTER></TD>
    </TR>
    <tr>
      <TD width="33%" bordercolor="#0821A5" bgcolor="#EAEAEA"><font size="2"><b><font face="Verdana, Arial, Helvetica, sans-serif">Your UserName</font></b></font></TD>
      <TD width="67%" bordercolor="#0821A5" bgcolor="#EAEAEA"><font size="2"><b><font face="Verdana, Arial, Helvetica, sans-serif"> <?php echo $info['username'] ?> </font></b></font></TD>
    </TR>
    <TR>
      <TD width="33%"><font size="2"><b><font face="Verdana, Arial, Helvetica, sans-serif">Choose Your Password</font></b></font></TD>
      <TD width="67%"><font size="2"><b><font face="Verdana, Arial, Helvetica, sans-serif">
        <INPUT TYPE="passtext" NAME="passtext" size="10" VALUE="<?php echo $info['passtext'] ?>">
        </font></b></font></TD>
    </TR>
    <TR>
      <TD width="33%"><font size="2"><b><font face="Verdana, Arial, Helvetica, sans-serif"> Name</font></b></font></TD>
      <TD width="67%"><font size="2"><b><font face="Verdana, Arial, Helvetica, sans-serif">
        <INPUT TYPE="text" NAME="name" size="10" VALUE="<?php echo $info['name'] ?>">
        </font></b></font></TD>
    </TR>
    <TR>
      <TD width="33%"><font size="2"><b><font face="Verdana, Arial, Helvetica, sans-serif">City</font></b></font></TD>
      <TD width="67%"><font size="2"><b><font face="Verdana, Arial, Helvetica, sans-serif">
        <INPUT TYPE="text" NAME="city" size="10" VALUE="<?php echo $info['city'] ?>">
        </font></b></font></TD>
    </TR>
    <TR>
      <TD width="33%" bgcolor="#EAEAEA"><font size="2"><b><font face="Verdana, Arial, Helvetica, sans-serif">State</font></b></font></TD>
      <TD width="67%" bgcolor="#EAEAEA"><font size="2"><b><font face="Verdana, Arial, Helvetica, sans-serif">
        <SELECT NAME="state">
          <OPTION VALUE="AL"<?php if ($info['state'] == "AL") { echo " SELECTED"; } ?>>Alabama</OPTION>
          <OPTION VALUE="AK"<?php if ($info['state'] == "AK") { echo " SELECTED"; } ?>>Alaska</OPTION>
          <OPTION VALUE="AZ"<?php if ($info['state'] == "AZ") { echo " SELECTED"; } ?>>Arizona</OPTION>
          <OPTION VALUE="AR"<?php if ($info['state'] == "AR") { echo " SELECTED"; } ?>>Arkansas</OPTION>
          <OPTION VALUE="CA"<?php if ($info['state'] == "CA") { echo " SELECTED"; } ?>>California</OPTION>
          <OPTION VALUE="CO"<?php if ($info['state'] == "CO") { echo " SELECTED"; } ?>>Colorado</OPTION>
          <OPTION VALUE="CT"<?php if ($info['state'] == "CT") { echo " SELECTED"; } ?>>Connecticut</OPTION>
          <OPTION VALUE="DE"<?php if ($info['state'] == "DE") { echo " SELECTED"; } ?>>Delaware</OPTION>
          <OPTION VALUE="FL"<?php if ($info['state'] == "FL") { echo " SELECTED"; } ?>>Florida</OPTION>
          <OPTION VALUE="GA"<?php if ($info['state'] == "GA") { echo " SELECTED"; } ?>>Georgia</OPTION>
          <OPTION VALUE="HI"<?php if ($info['state'] == "HI") { echo " SELECTED"; } ?>>Hawaii</OPTION>
          <OPTION VALUE="ID"<?php if ($info['state'] == "ID") { echo " SELECTED"; } ?>>Idaho</OPTION>
          <OPTION VALUE="IL"<?php if ($info['state'] == "IL") { echo " SELECTED"; } ?>>Illinois</OPTION>
          <OPTION VALUE="IN"<?php if ($info['state'] == "IN") { echo " SELECTED"; } ?>>Indiana</OPTION>
          <OPTION VALUE="IA"<?php if ($info['state'] == "IA") { echo " SELECTED"; } ?>>Iowa</OPTION>
          <OPTION VALUE="KS"<?php if ($info['state'] == "KS") { echo " SELECTED"; } ?>>Kansas</OPTION>
          <OPTION VALUE="KY"<?php if ($info['state'] == "KY") { echo " SELECTED"; } ?>>Kentucky</OPTION>
          <OPTION VALUE="LA"<?php if ($info['state'] == "LA") { echo " SELECTED"; } ?>>Louisiana</OPTION>
          <OPTION VALUE="ME"<?php if ($info['state'] == "ME") { echo " SELECTED"; } ?>>Maine</OPTION>
          <OPTION VALUE="MD"<?php if ($info['state'] == "MD") { echo " SELECTED"; } ?>>Maryland</OPTION>
          <OPTION VALUE="MA"<?php if ($info['state'] == "MA") { echo " SELECTED"; } ?>>Massachusetts</OPTION>
          <OPTION VALUE="MI"<?php if ($info['state'] == "MI") { echo " SELECTED"; } ?>>Michigan</OPTION>
          <OPTION VALUE="MN"<?php if ($info['state'] == "MN") { echo " SELECTED"; } ?>>Minnesota</OPTION>
          <OPTION VALUE="MS"<?php if ($info['state'] == "MS") { echo " SELECTED"; } ?>>Mississippi</OPTION>
          <OPTION VALUE="MO"<?php if ($info['state'] == "MO") { echo " SELECTED"; } ?>>Missouri</OPTION>
          <OPTION VALUE="MT"<?php if ($info['state'] == "MT") { echo " SELECTED"; } ?>>Montana</OPTION>
          <OPTION VALUE="NE"<?php if ($info['state'] == "NE") { echo " SELECTED"; } ?>>Nebraska</OPTION>
          <OPTION VALUE="NV"<?php if ($info['state'] == "NV") { echo " SELECTED"; } ?>>Nevada</OPTION>
          <OPTION VALUE="NH"<?php if ($info['state'] == "NH") { echo " SELECTED"; } ?>>New Hampshire</OPTION>
          <OPTION VALUE="NJ"<?php if ($info['state'] == "NJ") { echo " SELECTED"; } ?>>New Jersey</OPTION>
          <OPTION VALUE="NM"<?php if ($info['state'] == "NM") { echo " SELECTED"; } ?>>New Mexico</OPTION>
          <OPTION VALUE="NY"<?php if ($info['state'] == "NY") { echo " SELECTED"; } ?>>New York</OPTION>
          <OPTION VALUE="NC"<?php if ($info['state'] == "NC") { echo " SELECTED"; } ?>>North Carolina</OPTION>
          <OPTION VALUE="ND"<?php if ($info['state'] == "ND") { echo " SELECTED"; } ?>>North Dakota</OPTION>
          <OPTION VALUE="OH"<?php if ($info['state'] == "OH") { echo " SELECTED"; } ?>>Ohio</OPTION>
          <OPTION VALUE="OK"<?php if ($info['state'] == "OK") { echo " SELECTED"; } ?>>Oklahoma</OPTION>
          <OPTION VALUE="OR"<?php if ($info['state'] == "OR") { echo " SELECTED"; } ?>>Oregon</OPTION>
          <OPTION VALUE="PA"<?php if ($info['state'] == "PA") { echo " SELECTED"; } ?>>Pennsylvania</OPTION>
          <OPTION VALUE="RI"<?php if ($info['state'] == "RI") { echo " SELECTED"; } ?>>Rhode Island</OPTION>
          <OPTION VALUE="SC"<?php if ($info['state'] == "SC") { echo " SELECTED"; } ?>>South Carolina</OPTION>
          <OPTION VALUE="SD"<?php if ($info['state'] == "SD") { echo " SELECTED"; } ?>>South Dakota</OPTION>
          <OPTION VALUE="TN"<?php if ($info['state'] == "TN") { echo " SELECTED"; } ?>>Tennessee</OPTION>
          <OPTION VALUE="TX"<?php if ($info['state'] == "TX") { echo " SELECTED"; } ?>>Texas</OPTION>
          <OPTION VALUE="UT"<?php if ($info['state'] == "UT") { echo " SELECTED"; } ?>>Utah</OPTION>
          <OPTION VALUE="VT"<?php if ($info['state'] == "VT") { echo " SELECTED"; } ?>>Vermont</OPTION>
          <OPTION VALUE="VA"<?php if ($info['state'] == "VA") { echo " SELECTED"; } ?>>Virginia</OPTION>
          <OPTION VALUE="WA"<?php if ($info['state'] == "WA") { echo " SELECTED"; } ?>>Washington</OPTION>
          <OPTION VALUE="WV"<?php if ($info['state'] == "WV") { echo " SELECTED"; } ?>>West Virginia</OPTION>
          <OPTION VALUE="WI"<?php if ($info['state'] == "WI") { echo " SELECTED"; } ?>>Wisconsin</OPTION>
          <OPTION VALUE="WY"<?php if ($info['state'] == "WY") { echo " SELECTED"; } ?>>Wyoming</OPTION>
        </SELECT>
        </font></b></font></TD>
    </TR>
    <TR>
      <TD width="33%"><font size="2"><b><font face="Verdana, Arial, Helvetica, sans-serif">Zip</font></b></font></TD>
      <TD width="67%"><font size="2"><b><font face="Verdana, Arial, Helvetica, sans-serif">
        <INPUT TYPE="text" NAME="zip" size="10" VALUE="<?php echo $info['zip'] ?>">
        </font></b></font></TD>
    </TR>
    <TR>
      <TD height="33" width="33%"><font size="2"><b><font face="Verdana, Arial, Helvetica, sans-serif">Age</font></b></font></TD>
      <TD height="33" width="67%"><font size="2"><b><font face="Verdana, Arial, Helvetica, sans-serif">
        <INPUT TYPE="text" NAME="age" size="3" VALUE="<?php echo $info['age'] ?>">
        </font></b></font></TD>
    </TR>
    <tr >
      <td width="33%"><font size="2"><b><font face="Verdana, Arial, Helvetica, sans-serif">Profession:</font></b></font></td>
      <td width="67%"><font size="2"><b><font face="Verdana, Arial, Helvetica, sans-serif">
        <SELECT NAME="profession">
          <OPTION VALUE="Accounting"<?php if ($info['profession'] == "Accounting") { echo " SELECTED"; } ?>>Accounting/Finance</OPTION>
          <OPTION VALUE="ComputerISMISDP"<?php if ($info['profession'] == "ComputerISMISDP") { echo " SELECTED"; } ?>>Computer related (IS, MIS, DP)</OPTION>
          <OPTION VALUE="ComputerWWW"<?php if ($info['profession'] == "ComputerWWW") { echo " SELECTED"; } ?>>Computer related (WWW)</OPTION>
          <OPTION VALUE="Consulting"<?php if ($info['profession'] == "Consulting") { echo " SELECTED"; } ?>>Consulting</OPTION>
          <OPTION VALUE="CustomerSvc"<?php if ($info['profession'] == "CustomerSvc") { echo " SELECTED"; } ?>>Customer service/support</OPTION>
          <OPTION VALUE="Education"<?php if ($info['profession'] == "Education") { echo " SELECTED"; } ?>>Education/training</OPTION>
          <OPTION VALUE="Engineering"<?php if ($info['profession'] == "Engineering") { echo " SELECTED"; } ?>>Engineering</OPTION>
          <OPTION VALUE="Executive"<?php if ($info['profession'] == "Executive") { echo " SELECTED"; } ?>>Executive/senior management</OPTION>
          <OPTION VALUE="GenAdminSupe"<?php if ($info['profession'] == "GenAdminSupe") { echo " SELECTED"; } ?>>General administrative/supervisory</OPTION>
          <OPTION VALUE="GovMil"<?php if ($info['profession'] == "GovMil") { echo " SELECTED"; } ?>>Government/Military</OPTION>
          <OPTION VALUE="Manufacturing"<?php if ($info['profession'] == "Manufacturing") { echo " SELECTED"; } ?>>Manufacturing/production/operations</OPTION>
          <OPTION VALUE="Professional"<?php if ($info['profession'] == "Professional") { echo " SELECTED"; } ?>>Professional services (eg. medical)</OPTION>
          <OPTION VALUE="RandD"<?php if ($info['profession'] == "RandD") { echo " SELECTED"; } ?>>Research and development</OPTION>
          <OPTION VALUE="Retired"<?php if ($info['profession'] == "Retired") { echo " SELECTED"; } ?>>Retired</OPTION>
          <OPTION VALUE="Sales"<?php if ($info['profession'] == "Sales") { echo " SELECTED"; } ?>>Sales/marketing/advertising</OPTION>
          <OPTION VALUE="Student"<?php if ($info['profession'] == "Student") { echo " SELECTED"; } ?>>Student</OPTION>
          <OPTION VALUE="Unemployed"<?php if ($info['profession'] == "Unemployed") { echo " SELECTED"; } ?>>Unemployed/Between Jobs</OPTION>
        </SELECT>
        </font></b></font></td>
    </tr>
    <tr>
      <td><font size="2"><b><font face="Verdana, Arial, Helvetica, sans-serif">Signature</font></b></font>:<br>
<a href="faq_bbcode.php?<?=SID?>" target="_blank">BBCode</a> is 
<? if ($use_bbcode == "yes") { echo "<B>ON</B>"; } else { echo "<B>OFF</B>"; } ?>.</td>

<?
//if bbcode is being used, strip the html tags and convert them to bbcode.  Opposite of what is used for input.

if ($use_bbcode == "yes") {

$sig = stripslashes($sig);

//bbcode
$htmlcode = array(//"<", ">",
                "[list]", "[*]", "[/list]", 
                "[img]", "[/img]", 
                "[b]", "[/b]", 
                "[u]", "[/u]", 
                "[i]", "[/i]",
                '[color="', "[/color]",
                "[size=\"", "[/size]",
                '[url="', "[/url]",
                "[mail=\"", "[/mail]",
                "[code]", "[/code]",
                "[quote]", "[/quote]",
                '"]');
$bbcode = array(//"&lt;", "&gt;",
                "<ul>", "<li>", "</ul>", 
                "<img src=\"", "\">", 
                "<b>", "</b>", 
                "<u>", "</u>", 
                "<i>", "</i>",
                "<span style=\"color:", "</span>",
                "<span style=\"font-size:", "</span>",
                '<a href="', "</a>",
                "<a href=\"mailto:", "</a>",
                "<code>", "</code>",
                "<table width=100% bgcolor=lightgray><tr><td bgcolor=white>Quote:  ", "</td></tr></table>",
                '">');

  $sig = str_replace($bbcode, $htmlcode, $sig);
  $sig = nl2br($sig);//second pass
}

?>

      <td><textarea name="sig" id="sig"><?php echo "$sig"; ?></textarea>      </td>
    </tr>
    <tr>
      <td><font size="2"><b><font face="Verdana, Arial, Helvetica, sans-serif"><a href="http://www.skype.com" target="_blank">Skype</a> Name: </font></b></font></td>
      <td><font size="2"><b><font face="Verdana, Arial, Helvetica, sans-serif">
        <input type="text" name="zip" size="10" value="<?php echo $info['skype'] ?>" />
      </font></b></font></td>
    </tr>
    <tr >
      <td width="33%" bgcolor="#EAEAEA"><font size="2"><b><font face="Verdana, Arial, Helvetica, sans-serif">About Me :</font></b></font></td>
      <td width="67%" bgcolor="#EAEAEA"><font size="2"><b><font face="Verdana, Arial, Helvetica, sans-serif">
        <textarea name="aboutme" cols="40" rows="5" id="aboutme" value="<?php echo $info['description'] ?>"><?php echo $info['aboutme'] ?></textarea>
        </font></b></font></td>
    </tr>
    <tr >
      <td bgcolor="#EAEAEA"><strong><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Google AdSense Client ID:</font></strong>       <br />
        <br />
        If you have an AdSense account (if not, click the blue image below to get   your free account), you can enter your client ID here. 50% of the revenue   generated from the AdSense ads clicked on are credited to your account on reviews in which you contribute to.<br /></td>
      <td><p>&nbsp;</p>
      <p>
        <input id="userfield[field6]" maxlength="20" size="25" value="pub-4166677013602529" name="adsense_clientid" />
      </p></td>
    </tr>
    <tr >
      <td bgcolor="#EAEAEA"><strong><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Google AdSense Channel ID:</font></strong><br />
        <br />
	  If you choose to enter your AdSense client ID, you can specify a channel ID as well.

It would be a good idea to have a unique channel ID for this site (then you can track stats specifically for the reviews)     </td>
      <td><input id="ctb_field6" maxlength="20" size="25" name="adsense_channelid" /></td>
    </tr>
    <tr >
      <td colspan="2" bgcolor="#EAEAEA"><div align="center"><script type="text/javascript"><!--
google_ad_client = "pub-4166677013602529";
google_ad_width = 180;
google_ad_height = 60;
google_ad_format = "180x60_as_rimg";
google_cpa_choice = "CAAQr_Wy0gEaCPfBz19CTlSHKL3D93M";
//--></script>
<script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script></div></td>
    </tr>
    <?

}
include("user_cp_links.php");
?>
    <TR>
      <TD COLSPAN=2><CENTER>
          <font size="2"><b><font face="Verdana, Arial, Helvetica, sans-serif">
          <INPUT TYPE="submit" Value="Edit Profile!">
          </font> </b> </font>
        </CENTER></TD>
    </TR>
  </TABLE>
</FORM>
<?php 
} 

BodyFooter();
exit;
?>
