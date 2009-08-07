<?php
session_start();
if (get_magic_quotes_gpc()) {
    function stripslashes_array($array) {
        return is_array($array) ? array_map('stripslashes_array', $array) : stripslashes($array);
    }

    $_COOKIE = stripslashes_array($_COOKIE);
    $_FILES = stripslashes_array($_FILES);
    $_GET = stripslashes_array($_GET);
    $_POST = stripslashes_array($_POST);
    $_REQUEST = stripslashes_array($_REQUEST);
}

//Prevent Cross-Site Request Forgeries//
$token = md5(uniqid(rand(), TRUE));
$_SESSION['token'] = $token;
$_SESSION['token_timestamp'] = time();
////

$item_id = "";

include ('body.php');
include ('functions.php');
include ('f_secure.php');
include ('config.php');

//this deals with making sure this page doesn't set the back value from review_form_submit.php when someone edits a review.

if ((@!$_POST['back']) && (!@$_GET['back'])) {
$back = $_SERVER['HTTP_REFERER'];
} elseif (isset($_GET['back']) && (!$_POST['back'])) {
$back = $_GET['back'];
} elseif (isset($_POST['back']) && @!$_GET['back']) {
$back = $_POST['back'];
}

$_SESSION['back_orig'] = $_SERVER['HTTP_REFERER'];

if (isset($_GET['item_id']) && (!$_POST['item_id'])) {
$item_id = makeStringSafe($_GET['item_id']);
} elseif (isset($_POST['item_id']) && @!$_GET['item_id']) {
$item_id = makeStringSafe($_POST['item_id']);
}


//$item_id = makeStringSafe($_GET['item_id']);

if(!is_numeric($item_id)) {
BodyHeader("Invalid item");
echo "The item you are attempting to view is not valid.";
BodyFooter();
exit;
}

if (isset($_SESSION['signedin'])) {
$signedin = $_SESSION["signedin"];
}

if (isset($_SESSION['registered'])) {
$registered = $_SESSION["registered"];
}

if (isset($_SESSION['name_logged'])) {
$name = $_SESSION['name_logged'];
}

if (isset($_SESSION['username_logged'])) {
$username_logged = $_SESSION['username_logged'];
}

if (isset($_SESSION['city'])) {
$city = stripslashes($_SESSION['city']);
}
if (isset($_SESSION['state'])) {
$state = stripslashes($_SESSION["state"]);
}
if (isset($_SESSION['zip'])) {
$zip = stripslashes($_SESSION["zip"]);
}
if (isset($_SESSION['source'])) {
$source = stripslashes($_SESSION["source"]);
}

//echo "$signedin is signed in and active is $active and registered is $registered";

if (($registered == "yes") && (@$signedin != "y") || (isset($_SESSION['active']) && $_SESSION['active'] == "n")) {
BodyHeader("Free Registration Required!");
echo "<br />
Free registration is required to submit a review.  <br /><br />

Click to <a href=./login/register.php?item_id=$item_id&back=$back>Register</a> or if you've already registered, <a href= ./login/signin.php?item_id=$item_id&back=$back>Login In</a>. <br />
<br />
 You will be unable to log-in until you activate your account by following the link in the email you received upon your initial registration.  ";
BodyFooter();
exit;
}
/*
if ($use_phpBB == "yes" && (!$userdata['username'])) {
include_once("phpbb_include.php");
} elseif ($use_vb == "yes" && (!$bbuserinfo['username'])) {
include_once("vbulletin_include.php");
}//end use_vb
*/
$sql = "SELECT * FROM
			review_items
			WHERE
			item_id = '$item_id'";

			$sql_result = mysql_query($sql)
		or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));

	while ($row = mysql_fetch_array($sql_result)) {
$item_name = stripslashes($row["item_name"]);
$item_desc = stripslashes($row["item_desc"]);
$item_type = stripslashes($row["item_type"]);
}

@BodyHeader("Review $item_name","Submit a review for $item_name","$item_name");
?>
<script language="JavaScript" type="text/javascript">
<!--
function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_validateForm() { //v4.0
  var i,p,q,nm,test,num,min,max,errors='',args=MM_validateForm.arguments;
  for (i=0; i<(args.length-2); i+=3) { test=args[i+2]; val=MM_findObj(args[i]);
    if (val) { nm=val.name; if ((val=val.value)!="") {
      if (test.indexOf('isEmail')!=-1) { p=val.indexOf('@');
        if (p<1 || p==(val.length-1)) errors+='- '+nm+' must contain an e-mail address.\n';
      } else if (test!='R') { num = parseFloat(val);
        if (isNaN(val)) errors+='- '+nm+' must contain a number.\n';
        if (test.indexOf('inRange') != -1) { p=test.indexOf(':');
          min=test.substring(8,p); max=test.substring(p+1);
          if (num<min || max<num) errors+='- '+nm+' must contain a number between '+min+' and '+max+'.\n';
    } } } else if (test.charAt(0) == 'R') errors += '- '+nm+' is required.\n'; }
  } if (errors) alert('The following error(s) occurred:\n'+errors);
  document.MM_returnValue = (errors == '');
}
function textCounter(field, countfield, maxlimit) {
if (field.value.length > maxlimit)
field.value = field.value.substring(0, maxlimit);
else
countfield.value = maxlimit - field.value.length;
}
//-->
</script>
<link href="review.css" rel="stylesheet" type="text/css" />

<!--// documentation resources //-->
<script src="javascript/starrating/jquery-1.2.6.js" type="text/javascript"></script>
<script src="javascript/starrating/documentation.js" type="text/javascript"></script>



<!--// plugin-specific resources //-->
<script src="javascript/starrating/jquery.MetaData.js" type="text/javascript" language="javascript"></script>
<script src="javascript/starrating/jquery.rating.js" type="text/javascript" language="javascript"></script>
<link href="css/starrating/jquery.rating.css" type="text/css" rel="stylesheet"/>

<div class="container">
  </p>
  <span class="index2-Orange">Rate and Write a Review: <?php echo "$item_name"; ?> </span><br />
  <br />
  <span class="category">Tell us what you thought about the <em><?php echo "$item_name"; ?></em> and why.  Please be descriptive and write it as if you were talking to a friend. </span>
  <div class="left-element"><br />
    <br />
    <form method="post" action="review_form_submit.php?item_id=<?php echo "$item_id"; ?>">
      <p>
        <input name="item_type" type="hidden" value="<?php echo "$item_type"; ?>" />
        <input type="hidden" name="item_id" value="<?php echo "$item_id"; ?>" />
        <input name="item_name" type="hidden" value="<?php echo "$item_name"; ?>" />
        <input name="back" type="hidden" value="<?php echo "$back"; ?>" />
        <input type="hidden" name="token" value="<?php echo $token; ?>" />
      <fieldset>
      <p>
        <legend><img src="images/write.gif" alt="Private Messaging" />Rate and Write a Review for <?php echo ucfirst("$item_name"); ?><br />
        <br />
        </legend>
      </p>
      <div align="right"><span class="index2">All fields are Required! </span></div>
      <p>&nbsp;</p>
      <?php
		$flag=0;

		?>
      <?php
			$item_id=0;

			$item_id=$_REQUEST["item_id"];
			$query="SELECT a.criteriaId,a.criteriaName FROM rating_criteria a,item_rating_criteria b WHERE a.criteriaId=b.criteriaId AND item_id=" . $item_id;
			$result=mysql_query($query) or die(mysql_error());
			$str="";
			if(mysql_num_rows($result)> 0){
			?>
      <p>
        <label for="name">1.Please rate on the following criteria:</label>
        <br />
        <span class="small">(1 is the lowest score and 5 is the highest)&nbsp;</span> </p>
      <table width="60%">
        <?php
				while($row=mysql_fetch_array($result)){
					$flag=1;
					$counter=$row["criteriaId"];
					$val=0;
					if(isset($_POST["rad$counter"]) && $_POST["rad$counter"]!="") $val=$_POST["rad$counter"];
					$str.="<tr><td bgcolor=gray><font color=white><b>&nbsp;".$row["criteriaName"] ."</b></font></td></tr>";
					$str.="<tr>";

$str .= "<td>";
	$str .= "<input class='star {half:true}' type=radio name=rad" . $counter . " value=0.5 "; 
	if($val == "0.5") {$str .= "checked";}
	$str .= ">";
	
	$str .= "<input class='star {half:true}' type=radio name=rad" . $counter . " value=1 "; 
	if($val == "1") {$str .= "checked";}
	$str .= ">";
	
	$str .= "<input class='star {half:true}' type=radio name=rad" . $counter . " value=1.5 "; 
	if($val == "1.5") {$str .= "checked";}
	$str .= ">";
	
	$str .= "<input class='star {half:true}' type=radio name=rad" . $counter . " value=2 "; 
	if($val == "2") {$str .= "checked";}
	$str .= ">";
	
	$str .= "<input class='star {half:true}' type=radio name=rad" . $counter . " value=2.5 "; 
	if($val == "2.5") {$str .= "checked";}
	$str .= ">";
	
	$str .= "<input class='star {half:true}' type=radio name=rad" . $counter . " value=3 "; 
	if($val == "3") {$str .= "checked";}
	$str .= ">";
	
	$str .= "<input class='star {half:true}' type=radio name=rad" . $counter . " value=3.5 "; 
	if($val == "3.5") {$str .= "checked";}
	$str .= ">";
	
	$str .= "<input class='star {half:true}' type=radio name=rad" . $counter . " value=4 "; 
	if($val == "4") {$str .= "checked";}
	$str .= ">";
	
	$str .= "<input class='star {half:true}' type=radio name=rad" . $counter . " value=4.5 "; 
	if($val == "4.5") {$str .= "checked";}
	$str .= ">";
	
	$str .= "<input class='star {half:true}' type=radio name=rad" . $counter . " value=5 "; 
	if($val == "5") {$str .= "checked";}
	$str .= ">";
$str .= "</td>";

					$str.="</tr>";
				}

			$str.="<tr><td>&nbsp;</td></tr>";
			echo $str;
			?>
      </table>
      <?php
			} //end if > 0
		$rating="";
		if (isset($_POST['rating'])) {
			$rating = makeStringSafe($_POST["rating"]);
		}
		if (isset($_POST['summary'])) {
			$summary = makeStringSafe($_REQUEST["summary"]);
		}
		if (isset($_POST['review'])) {
			$review = $_POST["review"];
		}

		if (isset($_POST['source'])) {
			$source = makeStringSafe($_POST["source"]);
		}

		if (isset($_POST['location'])) {
			$location = makeStringSafe($_POST["location"]);
		}
			if($flag==0){
		?>
      <span>On a scale of 1 to 5 stars, with 5 stars being the best,</span><br />
      <br />
   
      <p>
        <label for="name">1. How do you rate this <?php echo "$item_type"; ?>?</label>
        
        <div id="rating_div">
			<input class='star {half:true}' type=radio name='rating' value=0.5 <?php if($rating == "0.5") {echo 'checked';} ?>>
	        <input class='star {half:true}' type=radio name='rating' value=1 <?php if($rating == "1") {echo 'checked';} ?>>
            <input class='star {half:true}' type=radio name='rating' value=1.5 <?php if($rating == "1.5") {echo 'checked';} ?>>
            <input class='star {half:true}' type=radio name='rating' value=2 <?php if($rating == "2") {echo 'checked';} ?>>
	        <input class='star {half:true}' type=radio name='rating' value=2.5 <?php if($rating == "2.5") {echo 'checked';} ?>>
            <input class='star {half:true}' type=radio name='rating' value=3 <?php if($rating == "3") {echo 'checked';} ?>>
            <input class='star {half:true}' type=radio name='rating' value=3.5 <?php if($rating == "3.5") {echo 'checked';} ?>>
	        <input class='star {half:true}' type=radio name='rating' value=4 <?php if($rating == "4") {echo 'checked';} ?>>
            <input class='star {half:true}' type=radio name='rating' value=4.5 <?php if($rating == "4.5") {echo 'checked';} ?>>
            <input class='star {half:true}' type=radio name='rating' value=5 <?php if($rating == "5") {echo 'checked';} ?> >
        </div>
        
        <select name="rating2" style="display:none;">
          <option value=""<?php if ($rating == "") { echo " SELECTED"; } ?>>Select Rating</option>
          <option value="0"<?php if ($rating == "0") { echo " SELECTED"; } ?>> 0 Stars </option>
          <option value="0.5"<?php if ($rating == "0.5") { echo " SELECTED"; } ?>>.5
          Star</option>
          <option value="1"<?php if ($rating == "1") { echo " SELECTED"; } ?>>1
          Star</option>
          <option value="1.5"<?php if ($rating == "1.5") { echo " SELECTED"; } ?>>1.5
          Stars</option>
          <option value="2"<?php if ($rating == "2") { echo " SELECTED"; } ?>>2
          Stars</option>
          <option value="2.5"<?php if ($rating == "2.5") { echo " SELECTED"; } ?>>2.5
          Stars</option>
          <option value="3"<?php if ($rating == "3") { echo " SELECTED"; } ?>>3
          Stars</option>
          <option value="3.5"<?php if ($rating == "3.5") { echo " SELECTED"; } ?>>3.5
          Stars</option>
          <option value="4"<?php if ($rating == "4") { echo " SELECTED"; } ?>>4
          Stars</option>
          <option value="4.5"<?php if ($rating == "4.5") { echo " SELECTED"; } ?>>4.5
          Stars</option>
          <option value="5"<?php if ($rating == "5") { echo " SELECTED"; } ?>>5
          Stars</option>
        </select>
      </p>
      <?php
			}
		?>
        

        
      <p>
        <label for="summary">2. Please enter a title for your review:</label>
      </p>
      <br />
      <input name="summary" type="text" id="summary" value="<?php if (isset($summary)) { echo stripslashes("$summary"); } ?>" size="35" maxlength="60" />
      <br />
      <br />
      <p>
        <label for="name">3. Type your review in the space below:</label>
        <span class="small"><br />
        ( You may enter up to
        <?php if (($characters == "")) { $characters = "1000"; } echo "$characters"; ?>
        characters and <a href="usercp/faq_bbcode.php?<?php echo htmlspecialchars(SID); ?>" target="_blank">BBCode</a> is
        <?php if ($use_bbcode == "yes") { echo "<B>ON</B>"; } else { echo "<B>OFF</B>"; } ?>
        . )</span>
        <link rel="stylesheet" type="text/css" href="<?php echo "$directory"; ?>/spell_checker/css/spell_checker.css" />
        <script src="<?php echo "$directory"; ?>/spell_checker/cpaint/cpaint2.inc.compressed.js" type="text/javascript"></script>
        <script src="<?php echo "$directory"; ?>/spell_checker/js/spell_checker_compressed.js" type="text/javascript"></script>
        <!--table is here to prevent error in IE7 -->
      <table width="90%" border="0">
        <tr>
          <td><textarea title="spellcheck" accesskey="<?php echo "$directory"; ?>/spell_checker/spell_checker.php" name="review" wrap="physical" cols="50" rows="9" onkeydown="textCounter(this.form.review,this.form.remLen,<?php echo "$characters"; ?>);" onkeyup="textCounter(this.form.review,this.form.remLen,<?php echo "$characters"; ?>);" id="spell_checker" style="width: 400px; height: 150px;" /><?php if (isset($review)) { echo "$review"; } ?></textarea>
            <br />
            <input readonly="readonly" type="text" name="remLen" size="3" maxlength="3" value="<?php echo "$characters"; ?>" />
            <span class="small">characters left</span>
            <input name="sig_show" type="checkbox" id="sig_show" value="y" <?php if (isset($_POST['sig_show']) && $_POST['sig_show'] == "y") { echo " checked"; } ?> />
            <span class="small">Show your Signature</span>
            </p>
          </td>
        </tr>
      </table>
      <br />
      <p>
        <label for="source">4. Your name:</label>
        <input name="source" id="source" type="text" value="<?php if (isset($username_logged)) { echo stripslashes("$username_logged"); } ?>" size="35" maxlength="150" />
      </p>
      <br />
      <p>
        <label for="location">5. Where in the world are you?</label>
        <br />
        <?php
if ($registered == "yes" && (isset($_SESSION['city']) && $_SESSION['city'] == "")) {
$sql = "SELECT city, state, zip FROM
			review_users
			WHERE
			username = '$username'";

			$sql_result = mysql_query($sql)
		or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));

	while ($row = mysql_fetch_array($sql_result)) {
$city = stripslashes($row["city"]);
$state = stripslashes($row["state"]);
$zip = stripslashes($row["zip"]);
}

}//end if registered

if ((@$city != "") && ($state != "") && ($zip != "")) { echo  "<b>$city, $state $zip</b>"; ?>
        <input name="location" type="hidden" value="<?php echo "$city, $state $zip"; ?>" />
        <?php
} else

{ ?>
        <input name="location" id="location" type="text" value="<?php
if (isset($city)) { echo "$city"; }
if (isset($state)) { echo ", $state"; }
if (isset($zip)) { echo " $zip"; }
 ?>" size="35" maxlength="120" />
        <?php }//end else ?>
        <br />
        <span class="small">(Example: Seattle, WA USA)</span> </p>
      <br />
      <br />
      <br />
      <div align="center">
        <p>
          <input type="image" value="edit" src="images/preview-your-review.gif" width="141" height="19" border="0" onclick="MM_validateForm('summary','','R','review','','R','rating','','R');return document.MM_returnValue">
        </p>
        <p align="left"><b class="index2"><br />
          The Fine Print:</b><br />
</p>
        <div align="left">
          <ul>
            <li>All submitted reviews are subject to the license terms set forth in our <a href="conditions.php?<?php echo htmlspecialchars(SID); ?>">Conditions of Use</a>. </li>
            <li>Your reviews will be posted within five to seven business days. </li>
            <li>Submissions that do not follow our <a href="guidelines.php?<?php echo htmlspecialchars(SID); ?>">review guidelines</a> will not be posted.</li>
          </ul>
        </div>
      </div>
      </fieldset>
    </form>
    <div align="center"><br />
      Back to <a href="<?php echo "$directory"; ?>/review-item/<?php echo "$item_id"; ?>.php?<?php echo htmlspecialchars(SID); ?>"><?php echo ucfirst("$item_name"); ?></a><br />
      <br />
    </div>
  </div>
  <br />
  <br />
  <br />
  <br />
  <br />
  <br />
  <br />
  <div class="right-element">
    <div>
      <h3 class="index2-Orange">Here are some review tips:</h3>
      <ul>
        <li>Would you purchase the <?php echo "$item_name"; ?> again? </li>
        <li>What do you like about the <?php echo "$item_name"; ?>? </li>
        <li>What do you dislike and why? </li>
        <li>Do you recommend the <?php echo "$item_name"; ?> to others?</li>
        <li>Avoid giving personal information like your full name, address, phone number, etc..</li>
        <li>Write as if you're telling a friend about the <?php echo "$item_name"; ?>.</li>
        <li>Do not use profanity.</li>
        <li>Be honest! </li>
      </ul>
      <h3 class="index2-Orange">About <?php echo "$sitename"; ?> Reviews</h3>
      <p><?php echo "$sitename"; ?> reserves the right to refuse or remove any review that does not comply with these Guidelines or the <a href="conditions.php?<?php echo htmlspecialchars(SID); ?>">Conditions of Use </a> and terminate your  account  for a violation. </p>
      <p><?php echo "$sitename"; ?> is not responsible or liable in any way for ratings and reviews posted by its users/visitors.</p>
    </div>
  </div>
</div>
<?php
BodyFooter();
exit;
?>
