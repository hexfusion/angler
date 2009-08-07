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
if ($_POST['token'] != $_SESSION['token']) {
echo "invalid";
exit;
}
////

$token = $_SESSION['token'];

include ('body.php');
include ('functions.php');
include ('f_secure.php');
include ('config.php');

//allow back button to be used.
header("Cache-control: private");

$item_id = makeStringSafe($_GET['item_id']);

if(!is_numeric($item_id)) {
BodyHeader("Invalid item");
echo "The item you are attempting to view is not valid.";
BodyFooter();
exit;
}

$item_name = clean($_POST["item_name"]);
$back = clean($_POST["back"]);
$summary = clean($_POST["summary"]);
$review = clean($_POST["review"]);
$source = clean($_POST["source"]);
$location = clean($_POST["location"]);
$rating = @clean($_POST["rating"]);
$sig_show = @clean($_POST["sig_show"]);
$username = @clean($_SESSION['username']);


//make sure user has entered a review.
$review_done = strlen($review);

if ($review_done <= 6) {
BodyHeader("Please add your review!","Add a review for $item_name","$item_name");
echo "Please click the back button in your browser and be sure to add your review!  Thank you...";
?>

<form method="post" action="review_form.php?<?php echo htmlspecialchars(SID); ?>">
  <input type="image" value="edit" src="images/edit-large.gif" width="42" height="20" border="0" />
  <input type="hidden" name="review" value="<?php echo htmlspecialchars($review); ?>" />
  <input type="hidden" name="rating" value="<?php echo "$rating"; ?>" />
  <input type="hidden" name="location" value="<?php echo htmlspecialchars($location); ?>" />
  <input type="hidden" name="summary" value="<?php echo htmlspecialchars($summary); ?>" />
  <input type="hidden" name="source" value="<?php echo htmlspecialchars($source); ?>" />
  <input type="hidden" name="id" value="<?php echo "$id"; ?>" />
  <input type="hidden" name="item_name" value="<?php echo "$item_name"; ?>" />
  <input type="hidden" name="item_id" value="<?php echo "$item_id"; ?>" />
  <input type="hidden" name="sig_show" value="<?php echo "$sig_show"; ?>" />
  <input type="hidden" name="back" value="<?php echo $_POST['back']; ?>" />
</form>
<?php
BodyFooter();
exit;
}


//check user input and remove any reference to javascript.
$errjava = "<font color=red><BR><BR><B>No Javascript is allowed!  Please click edit and remove the offending code.<BR><BR></B></font>";

$summary = preg_replace("'<script[^>]*?>.*?</script>'si", "$errjava", $summary);
$review = preg_replace("'<script[^>]*?>.*?</script>'si", "$errjava", $review);
$source = preg_replace("'<script[^>]*?>.*?</script>'si", "$errjava", $source);
$location = preg_replace("'<script[^>]*?>.*?</script>'si", "$errjava", $location);

//replace bad words
$sql_filter = "select badword, goodword
from review_badwords
";

$sql_result_filter = mysql_query($sql_filter)
		or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));

$review_orig = htmlspecialchars($_POST["review"]);

//show users signature?
if(isset($_POST['sig_show']) && $_POST['sig_show'] == "y") { 
$sql = "SELECT sig FROM 
			review_users
			WHERE 
			username='" . mysql_real_escape_string($username) . "'";
		
			$sql_result = mysql_query($sql)
		or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));
		
	while ($row = mysql_fetch_array($sql_result)) { 
$sig = stripslashes($row["sig"]); 
}
$_SESSION['sig'] = @"$sig";
}

if ($use_bbcode == "yes") {
include("bbcode.php");

  $sig_temp = @str_replace($bbcode, $htmlcode, $sig);
  $sig_temp = nl2br($sig_temp);//second pass

  $review_temp = str_replace($bbcode, $htmlcode, $review);
  //$review_temp = nl2br($review_temp);//second pass
}


//set_magic_quotes_runtime(0);
BodyHeader("Confirm $item_name Review - $sitename","","");
?>
<br />
<p><b class="h1">Check Your Review of</b> <b class="sans"> <?php echo "$item_name"; ?></b> <br />
  <br />
  Here is your review the way it will appear:</p>
<hr noshade="noshade" size="1" />
<table width="95%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td><?php		  
		$flag=0;
		$tempStr="";
		
			//$item_id=0;
			
			//$item_id=$_REQUEST["item_id"];
			$query="SELECT a.criteriaId,a.criteriaName FROM rating_criteria a,item_rating_criteria b WHERE a.criteriaId=b.criteriaId AND item_id=" . $item_id;
			$result=mysql_query($query) or die(mysql_error());
			$str="";
			$total=0;
			$count=0;
			if(mysql_num_rows($result)> 0){
			?>
      <table width="300" cellpadding="0" cellspacing="0" class="index2-criteria-table">
        <tr>
          <td><table  cellspacing="2" cellpadding="2">
              <?php
				while($row=mysql_fetch_array($result)){
					$flag=1;
					$counter=$row["criteriaId"];
					$str.="<tr>";
					if(isset($_POST["rad$counter"]) && $_POST["rad$counter"]!=""){
						
						$total=$total + $_POST["rad$counter"];
						if($_POST["rad$counter"]>0)	$count=$count+1;
						$tempStr.="<input type=hidden name=rad$counter value=" .$_POST["rad$counter"] .">";
						$str.="<tr><td colspan=2>" . $row["criteriaName"]."</td>";
						$str.="<td colspan=2>";
												$dis = ($_POST["rad$counter"]/5)*100;
 						$str.= "<div class=\"rating_bar\"><div style=\"width:".$dis."%\"></div></div>";
						$str.="</td>";
						if($_POST["rad$counter"]>0){
							$str.="<td><b>" . $_POST["rad$counter"] ."</b></td>";							
						}else{
							$str.="<td><b>N/A</b></td>";	
						}

					}
					$str.="</tr>";
				}
			$st="";
			if($total>0){
				
			
			}else{
				$st="<b>N/A</b>";
			}
			$str.="<tr height=1><td colspan=6 bgcolor=black></td></tr>";
			
			$rat = number_format($total/$count, 2, '.', '');
			$display = ($rat/5)*100;
				$str.="<tr><td colspan=2><b>Average Rating</td><td colspan=2> <div class=\"rating_bar\">
        <div style=\"width:$display%\"></div>
      </div></td><td colspan=2><b>" . number_format($total/$count, 2, '.', '')."</b></td></tr>";
			
			
			echo $str;
			?>
            </table></td>
        </tr>
      </table>
      <?php 
			}
		if($flag==0){
				//show stars if there is no review criteria
			 $display = ($rating/5)*100; ?>
      <div class="rating_bar">
        <div style="width:<?php echo "$display"; ?>%"></div>
      </div>
      <?php
		}
		echo "<br />$summary,"; ?>
      <?php $date_added = date("M d, Y"); echo "$date_added"; ?>
      <br />
      <br />
    </td>
  </tr>
  <tr>
    <td>Reviewer: <b>
      <?php if ($source == "") {
 $source = "Anonymous";
 } echo ucfirst($source); ?>
      </b> from
      <?php if ($location == "") {
 $location = "Anytown, AT 55555";
 }
 echo "$location"; ?>
      <br />
      <br />
    </td>
  </tr>
  <tr>
    <td><?php 
	
//	$review_orig = wordwrap($review_orig, 30, "\n", 1);
	
//	$review = htmlspecialchars("$review", ENT_QUOTES);

	if (isset($review_temp)) { 
	//if some clown enters a word longer than 30 characters, break it up to fit on screen.
	$review_temp = wordwrap($review_temp, 30, "\n", 1);
	echo "$review_temp";; } else { 
	$review = wordwrap($review, 30, "\n", 1);
	$review = html_entity_decode($review);
	echo "$review"; }  if(isset($_POST['sig_show']) && $_POST['sig_show'] == "y" && $use_bbcode != "yes") { echo "<BR><BR>$sig"; } elseif (isset($_POST['sig_show']) && $_POST['sig_show'] == "y" && $use_bbcode == "yes") { echo "<BR><BR>$sig_temp"; } ?></td>
  </tr>
</table>
<br />
<hr noshade="noshade" size="1" />
<br />
<br />
<table border="0" cellspacing="5" cellpadding="8">
  <tr>
    <td valign="top"><form method="post" action="review_form.php?<?php echo htmlspecialchars(SID); ?>">
        <input type="image" value="edit" src="images/edit-large.gif" width="42" height="20" border="0" />
        <input type="hidden" name="review" value="<?php echo "$review_orig"; ?>" />
        <input type="hidden" name="rating" value="<?php echo "$rating"; ?>" />
        <input type="hidden" name="summary" value="<?php echo htmlspecialchars($summary); ?>" />
        <input type="hidden" name="location" value="<?php echo htmlspecialchars($location); ?>" />
        <input type="hidden" name="source" value="<?php echo htmlspecialchars($source); ?>" />
        <input type="hidden" name="id" value="<?php echo "$id"; ?>" />
        <input type="hidden" name="item_name" value="<?php echo "$item_name"; ?>" />
        <input type="hidden" name="item_id" value="<?php echo "$item_id"; ?>" />
        <input type="hidden" name="token" value="<?php echo "$token"; ?>" />
        <?php 
			if($tempStr!="") echo $tempStr;
		?>
        <input type="hidden" name="back" value="<?php echo $_POST['back']; ?>" />
        <input type="hidden" name="sig_show" value="<?php echo "$sig_show"; ?>" />
      </form></td>
    <td align="center" valign="top"><b>OR</b></td>
    <td valign="top"><form method="post" action="save.php?<?php echo htmlspecialchars(SID); ?>">
        <input type="image" value="save" src="images/save.gif" width="57" height="19" border="0" />
        <input type="hidden" name="review" value="<?php echo htmlspecialchars($review_orig); ?>" />
        <input type="hidden" name="rating" value="<?php echo "$rating"; ?>" />
        <input type="hidden" name="summary" value="<?php echo htmlspecialchars($summary); ?>" />
        <input type="hidden" name="location" value="<?php echo htmlspecialchars($location); ?>" />
        <input type="hidden" name="source" value="<?php echo htmlspecialchars($source); ?>" />
        <input type="hidden" name="item_id" value="<?php echo "$item_id"; ?>" />
        <input type="hidden" name="item_name" value="<?php echo "$item_name"; ?>" />
        <input type="hidden" name="token" value="<?php echo "$token"; ?>" />
        <?php 
			if($tempStr!="") echo $tempStr;
		?>
        <input type="hidden" name="back" value="<?php echo $_POST['back']; ?>" />
        <input type="hidden" name="sig_show" value="<?php echo "$sig_show"; ?>" />
      </form></td>
    <td valign="top"><small><b>Note:</b> By saving this review, you attest that 
      you are at least 13 years of age.<br />
      </small> </td>
  </tr>
</table>
<hr noshade="noshade" size="1" />
<p><b class="small"><i>Submissions become the property of <a href="<?php echo "$url"; ?>"><?php echo "$sitename"; ?></a>.</i></b></p>
<p><b class="h1">The Fine Print:</b> </p>
<ul>
  <li>All submitted reviews are subject to the license terms set forth in our <a href="conditions.php?<?php echo htmlspecialchars(SID); ?>">Conditions of Use</a>. </li>
  <li>Your reviews will be posted within five to seven business days. </li>
  <li>Submissions that do not follow our <a href="guidelines.php?<?php echo htmlspecialchars(SID); ?>">review guidelines</a> will not be posted.</li>
</ul>
<?php
BodyFooter();
exit;
?>
