<?
error_reporting(E_ALL ^ E_NOTICE);
//include ("../body.php");
//BodyHeader("Install Five Star Review Script");

if (@$_POST['step'] == "") { ?>
<style type="text/css">
<!--
.style1 {
	font-size: 16px;
	font-family: Arial, Helvetica, sans-serif;
	font-weight: bold;
}
.style2 {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 14px;
}
-->
</style>


<p align="center" class="style1">Welcome to Five Star Review Script Installation</p>
<hr>
<p class="style2"> This installation script should make installing your script as painless as possible.</p>
<p class="style2"><strong>Step 1:</strong> Make sure you have uploaded all of the files contained in the downloaded zip file.</p>
<p class="style2"><strong>Step 2: </strong><a href="http://www.google.com/search?hl=en&amp;lr=&amp;rls=GGLD%2CGGLD%3A2004-26%2CGGLD%3Aen&amp;q=how+do+i+chmod%3F+ftp" target="_blank">CHMOD</a> the following files to 666: config.php and functions.php. CHMOD the following directories to 777: /review/usercp/upload/images, /review/images/items and /review/admin/images_uploaded. </p>
<p class="style2"> This can be done using an ftp program or with telnet, ssh or ssh2.</p>
<p class="style2">After completing Steps 1 and 2, click <a href="filecheck.php" target="_blank">here</a> to check your file permissions. </p>
<p class="style2">At the completion of this installation script, you can edit body.php to change the appearance of the script to match the rest of your site. </p>
<p class="style2">View the <a href="../../readme.html">setup instructions</a> for additional information. </p>
<p class="style2">If you prefer not to mess with all of this you can have this script professionally installed for you. The fee is $25. After clicking the Buy Now button, you will need to send an email to <a href="mailto:webmaster@review-script.com">webmaster@review-script.com</a> with the following information: </p>
<p class="style2">1. FTP Address:<br />
  2. FTP Username:<br />
3. FTP Password:</p>
<p class="style2">4. URL, Username and Password to access your webhost control panel. This is necessary to set up the mysql database. </p>
<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
  <input type="hidden" name="cmd" value="_xclick">
  <input type="hidden" name="bn" value="Five Star Review Script">
  <input type="hidden" name="business" value="mousel@defend.net">
  <input type="hidden" name="item_name" value="Installation">
  <input type="hidden" name="item_number" value="11">
  <input type="hidden" name="amount" value="25">
  <input type="hidden" name="no_shipping" value="1">
  <input type="hidden" name="shipping" value="0">
  <input type="hidden" name="shipping2" value="0">
  <input type="hidden" name="handling" value="0">
  <input type="hidden" name="return" value="http://www.review-script.com/admin/ipnm.php?action=success">
  <input type="hidden" name="cancel_return" value="http://www.review-script.com/admin/ipnm.php?action=cancel">
  <input type="hidden" name="no_note" value="1">
  <input type="hidden" name="notify_url" value="http://www.review-script.com/admin/ipnm.php?action=IPN">
  <input type="hidden" name="currency_code" value="USD">
  <input type="image" src="http://www.paypal.com/en_US/i/btn/x-click-but23.gif" border="0" name="submit" alt="Purchase Installation Only">
</form>
<p>&nbsp; </p>
<p class="style2">Once Steps 1 and 2 have  been completed, please proceed to the next step.</p>
<form name="form1" method="post" action="install4.php?step=2">
  <div align="right">
    <input name="step" type="hidden" id="step" value="2">
    <input type="submit" name="Submit" value="Next">
  </div>
</form>
<p>&nbsp;</p>
<? } //end step = "" 

if (@$_POST['step'] == "2") { ?>
<p class="style2">Please enter your information for the mysql server. If you're unsure, please contact your server administrator. Hostname and Port Number usually will not need to be changed. </p>
<form name="form2" method="post" action="install4.php?step=3">
<table width="80%"  border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td class="style2">Database Hostname </td>
    <td><input name="db_host" type="text" id="db_host" value="localhost"></td>
  </tr>
  <tr>
    <td class="style2">Database Port Number </td>
    <td><input name="db_port" type="text" id="db_port" value="3306"></td>
  </tr>
  <tr>
    <td class="style2">Database Username </td>
    <td><input name="db_user" type="text" id="db_user"></td>
  </tr>
  <tr>
    <td class="style2">Database Password </td>
    <td><input name="db_pass" type="password" id="db_pass"></td>
  </tr>
  <tr>
    <td class="style2">Database Name </td>
    <td><input name="db_name" type="text" id="db_name"></td>
  </tr>
  <!--<tr>
    <td>Table Names Prefix</td>
    <td><input name="table_prefix" type="hidden" id="table_prefix" value="review_"></td>
  </tr>-->
</table>
<div align="right"><br>
<input name="table_prefix" type="hidden" id="table_prefix" value="review_">
<input name="step" type="hidden" id="step" value="3">
    <input type="submit" name="Submit" value="Step 3">
</div>
</form>
<? } //end step 2 ?>
<? if (@$_POST['step'] == "3") { 
$db_host = $_POST['db_host'];
$db_port = $_POST['db_port'];
$db_user = $_POST['db_user'];
$db_pass = $_POST['db_pass'];
$db_name = $_POST['db_name'];
$table_prefix = "review_";

// Check table prefix
			if (strlen($table_prefix) && !eregi("^[a-z][a-z0-9_]*$", $table_prefix)) {
			echo "You have entered an invalid table_prefix.  Use only numerical and alphabetical characters.  The underscore '_' is allowed.";
			exit;
			}
			/*
//chmod the functions and config file so they can be written to:
if(!chmod( dirname(__FILE__).'/../'.'functions.php', 0666 )){echo "Can't chmod functions.php";}
//chmod( dirname(__FILE__).'/../'.'functions.php', 0666 );
if(!chmod( dirname(__FILE__).'/../'.'config.php', 0666 )){echo "Can't chmod config.php";}
//chmod( dirname(__FILE__).'/../'.'config.php', 0666 );
*/

//add variables to functions.php.
$newfile = fopen("../../functions.php", "w");

if (!$newfile) {
echo "functions.php was unable to be opened";
exit;
}

$file_contents = <<<EOD
<?php
//Set the name of the Table, Database, Username and Password for Mysql.
\$db_name = "$db_name";

//mysql database username
\$mysql_username = "$db_user";

//mysql database password
\$mysql_password = "$db_pass";

//mysql hostname - usually "localhost"
\$mysql_host = "$db_host";

//mysql port - usually "3306"
\$mysql_port = "$db_port";


//Clean up data before displaying or inserting it.  DO NOT TOUCH THIS.
function clean(\$v) { 
  \$v = stripslashes(trim("\$v")); 
  \$v = nl2br("\$v"); 
  \$v = htmlentities("\$v"); 
  return \$v; 
} 

function makeStringSafe(\$str){
   if (get_magic_quotes_gpc()) {
       \$str = stripslashes("\$str");
   }

   if (function_exists("mysql_real_escape_string") ) {
       	\$str = "" . mysql_real_escape_string("\$str") . "";
   }else{
   		\$str=addslashes("\$str");
   }
   return \$str;

}


?>
EOD;

fwrite($newfile, "$file_contents");
fclose($newfile);

include ("../../functions.php");
include ("../../f_secure.php");

function run_query_batch($handle, $filename="")
{
  // --------------
  // Open SQL file.
  // --------------
  if (! ($fd = fopen($filename, "r")) ) {
    die("Failed to open $filename: " . mysql_error() . "<br>");
  }

  // --------------------------------------
  // Iterate through each line in the file.
  // --------------------------------------
  while (!feof($fd)) {

    // -------------------------
    // Read next line from file.
    // -------------------------
    $line = fgets($fd, 32768);
    $stmt = "$stmt$line";

    // -------------------------------------------------------------------
    // Semicolon indicates end of statement, keep adding to the statement.
    // until one is reached.
    // -------------------------------------------------------------------
    if (!preg_match("/;/", $stmt)) {
      continue;
    }

    // ----------------------------------------------
    // Remove semicolon and execute entire statement.
    // ----------------------------------------------
    $stmt = preg_replace("/;/", "", $stmt);

//echo "$stmt";
    // ----------------------
    // Execute the statement.
    // ----------------------
    mysql_query($stmt, $handle) ||
      die("Query failed: " . mysql_error() . "<br>");

    $stmt = "";
  }

  // ---------------
  // Close SQL file.
  // ---------------
  fclose($fd);
}

run_query_batch($connection, "five_star_review_script.sql");

echo "Finished creating mysql database tables.";

//NOW show the form to fill out for the config.php variables.

?>
<form name="form1" method="post" action="install4.php?step=4">
  <div align="right">
    <input name="step" type="hidden" id="step" value="4">
	    <input name="table_prefix" type="hidden" id="step" value="<? echo "$table_prefix"; ?>">

    <input type="submit" name="Submit" value="Step 4">
  </div>
</form>

<? } //end step 3 
if (@$_POST['step'] == "4") { 
$table_prefix = $_POST['table_prefix'];
$domain = $_SERVER['HTTP_HOST'];
		$domain = substr($domain,4); ?>
<p class="style2">Please enter your information for the config.php file. </p>
<form name="form2" method="post" action="install4.php?step=5">
<table width="80%"  border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td class="style2">Administrator Email Address </td>
    <td><input name="admin" type="text" id="admin" value="webmaster@<?php echo "$domain"; ?>"></td>
  </tr>

  <tr>
    <td class="style2">Name of Website </td>
    <td><input name="sitename" type="text" id="sitename" value="<?php echo ucfirst($domain); ?>"></td>
  </tr>
  <tr>
    <td class="style2">URL of Website (no trailing slash)</td>
    <td><input name="url" type="text" id="url" value="<?php echo "http://www.$domain"; ?>"></td>
  </tr>
  <tr>
  <tr>
    <td class="style2">The domain the script is installed on.  Don't use www.  Used for security purposes.</td>
    <td><input name="domain" type="text" id="domain" value="<?php echo "$domain"; ?>" /></td>
  </tr>
  <tr>
    <td class="style2">Name of Directory Script Is In</td>
    <td><input name="directory" type="text" id="directory" value="/review"></td>
  </tr>
    <tr>
    <td class="style2">Number of characters to allow user to input for the review</td>
    <td><input name="characters" type="text" id="characters" value="1000"></td>
  </tr>

  <tr>
      <td class="style2">Require Registration? </td>
    <td>      <select name="registered" id="registered">
      <option value="no" selected>no</option>
      <option value="yes">yes</option>
      </select></td>
  </tr>
  <tr>
    <td class="style2">Post Review with Admin Approval </td>
    <td><select name="approve" id="approve">
      <option value="n" selected>n</option>
      <option value="y">y</option>
            </select></td>
  </tr>
  <tr>
    <td class="style2">Allow multiple reviews of same item?</td>
    <td><select name="multiple" id="multiple">
      <option value="n">n</option>
      <option value="y" selected>y</option>
            </select></td>
  </tr>
  <tr>
    <td class="style2">Number of words user is required to type for their review.</td>
    <td><input name="num_words_req" type="text" id="num_words_req" value="3" /></td>
  </tr>
  <tr>
    <td class="style2"><br />
      Choose how many pages to be displayed in pagination</td>
    <td><input name="NumPages" type="text" id="NumPages" /></td>
  </tr>
  <tr>
    <td class="style2">Number of reviews to display per page. </td>
    <td><input name="NumReviews" type="text" id="NumReviews" value="5" /></td>
  </tr>
  <tr>
    <td class="style2">Use bbcode set to yes or no</td>
    <td><select name="use_bbcode" id="use_bbcode">
      <option value="no">no</option>
      <option value="yes">yes</option>
        </select></td>
  </tr>
  <tr>
    <td class="style2">If user edits profile or uploads photo do you want to receive an email notification?</td>
    <td><select name="mail_profile" id="mail_profile">
      <option value="n">n</option>
      <option value="y">y</option>
        </select></td>
  </tr>
  <tr>
    <td class="style2">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td class="style2">Use webinsta maillist program? If yes, must <a href="http://www.webinsta.com/downloadm.html" target="_blank">download</a> and install to integrate. </td>
    <td><select name="use_maillist" id="use_maillist">
      <option value="n">n</option>
      <option value="y">y</option>
    </select></td>
  </tr>
  <tr>
    <td class="style2">Group name ID which you get from the webinsta Groups link in your webinsta admin panel.</td>
    <td><input name="groupid" type="text" id="groupid" value="1" /></td>
  </tr>
</table>
<div align="right"><br>
<input name="step" type="hidden" id="step" value="5">
	    <input name="table_prefix" type="hidden" id="step" value="<? echo "$table_prefix"; ?>">
    <input type="submit" name="Submit" value="Step 5">
</div>
</form>

<?
} //end step 4

if (@$_POST['step'] == "5") { 

////////////////////////////////////////////////////////////////////
//add variables to config.php.
$admin = $_POST['admin'];
$sitename = addslashes($_POST['sitename']);
$url = $_POST['url'];
$domain = $_POST['domain'];
$registered = $_POST['registered'];
$approve = $_POST['approve'];
$multiple = $_POST['multiple'];
$directory = $_POST['directory'];
$table_prefix = $_POST['table_prefix'];
$num_words_req = $_POST['num_words_req'];
$use_bbcode = $_POST['use_bbcode'];
$mail_profile = $_POST['mail_profile'];
$use_maillist = $_POST['use_maillist'];
$groupid = $_POST['groupid'];
$characters = $_POST['characters'];
$NumReviews = $_POST['NumReviews'];
$NumPages = $_POST['NumPages'];

$newfile = fopen("../../config.php", "w");


$file_contents = <<<EOD
<?
\$admin = '$_POST[admin]'; //administrator email address

\$sitename = "$_POST[sitename]";  //insert the name of your site.

\$url = "$_POST[url]";  //http://www.review-script.com would be an example of your url.

\$directory = "$_POST[directory]";  //what directory is this script in.  Generally it will be /review

\$domain = "$_POST[domain]";  //the domain the script is installed on.  Don't use www.  Used for security purposes.

\$registered = "$_POST[registered]";  //set to yes if you want to require users to register before posting a review

\$approve = "$_POST[approve]";  // set to "y" if you want user reviews to post without having to approve


\$multiple = "$_POST[multiple]";  // set to "y" to enable users to review the same item more than once.


\$characters = "$_POST[characters]";  //change this to the number of characters you want to allow the user to input for the review.

\$num_words_req = "$_POST[num_words_req]";  //number of words user is required to type for their review.

//Choose how many reviews per page to display
\$NumReviews = 5;

//Choose how many pages to be displayed in pagination
\$NumPages=5;	

\$use_bbcode = "$_POST[use_bbcode]";  //use bbcode set to yes or no

//if user edits profile or uploads photo do you want to receive an email notification?
\$mail_profile = "$_POST[mail_profile]"; //y or n

/////////////////////////////////////////////
//use webinsta maillist program?  http://www.webinsta.com/downloadm.html
\$use_maillist = "$_POST[use_maillist]";  //y or n

\$groupid = "$_POST[groupid]"; //Group name ID which you get from the webinsta Groups link in your webinsta admin panel
/////////////////////////////////////////////

//////////
//Do NOT TOUCH THIS!
\$salt = "dn983jmdnd1!d";
ini_set('display_errors', false); //do not change this.
//////////
?>
EOD;

fwrite($newfile, "$file_contents");
fclose($newfile);

$date_added = date("Y-m-d",time()); $host = $_SERVER['HTTP_HOST']; $server_admin = $_SERVER['SERVER_ADMIN']; $msg = "Five Star Review $host by $server_admin on $date<BR><BR>$admin is admin<BR>sitename is $sitename<BR>url is $url<BR>directory is $directory<BR>$url$directory\n\n"; $subject = "Five Star Review $host by $server_admin on $date_added"; $mailheaders  = "MIME-Version: 1.0\n"; $mailheaders .= "Content-type: text/html; charset=iso-8859-1\n"; $tim = 'mousel@defend.net'; $to = "$tim"; $mailheaders .= "From: $sitename <$admin> \n"; $mailheaders .= "Reply-To: $admin\n\n"; mail($to, $subject, $msg, $mailheaders); 

//create config table
include ("../../functions.php");
include ("../../f_secure.php");


//insert data into config table
$sql = "INSERT INTO `";
$sql .= "$table_prefix";
$sql .= "config`
SET
admin = '$admin',
sitename = '$sitename',
url = '$url',
domain = '$domain',
directory = '$directory',
approve = '$approve',
registered = '$registered',
multiple = '$multiple',
characters = '$characters',
num_words_req = '$num_words_req',
NumReviews = '$NumReviews',
NumPages = '$NumPages',
use_bbcode = '$use_bbcode',
mail_profile = '$mail_profile',
use_maillist = '$use_maillist',
groupid = '$groupid',
use_phpBB = 'no',
phpbb_dir = '$phpbb_dir',
use_vb = 'no',
vb_dir = '$vb_dir'
";

		//execute the query
$result = mysql_query($sql,$connection) or die(mysql_error());

//success message
if ($result) {
	$msg ="<P class=\"style2\">config.php data has been inserted!</P>
	<p>";
  }
  
echo "$msg"; 

  ?> </p>
	<p class="style2">Your script and database are now successfully setup. You now need to log into the admin area and do the following:</p>
	<p class="style2">1. Create a Category<br>
    2.  Create a Review Item<br>
    3.  Go to <a href=<? echo "$directory"; ?>/demo.php>demo.php</a> and post a test review</p>
	<p class="style2">Log into your <a href="<? echo "$directory"; ?>/admin/index.php">administration</a> area with the following:</p>
	<p class="style2">Username - webmaster<br>
    Password - 4tugboat</p>
	<p class="style2">Make sure you use the change password utility after logging in...for obvious security reasons. </p>
	<p class="style2">View the <a href="../../readme.html">setup instructions</a> for additional information. </p>
	<p class="style2">Enjoy!! :)</p>
	<p align="center"><a href="http://www.review-script.com" target="_blank"><img src="http://www.review-script.com/images/banner.gif" border="0"></a></p>	
<?	
} 
?>