<?php
session_start();

//Prevent Cross-Site Request Forgeries//
if ($_POST['token'] != $_SESSION['token']) {
echo "invalid";
exit;
}
////

include ('body.php');
include ('functions.php');
include ('f_secure.php');
include ('config.php');

$item_id = mysql_real_escape_string($_POST['item_id']);

if(!is_numeric($item_id)) {
BodyHeader("Invalid item","","");
echo "The item you are attempting to view is not valid.";
BodyFooter();
exit;
}

$item_name = $_POST["item_name"];
$back = $_POST['back'];
$summary = $_POST["summary"];
$review = htmlentities($_POST["review"]);
$source = $_POST["source"];
$location = $_POST["location"];
$rating = @clean($_POST["rating"]);

if ($source == "Anonymous") {
$username = "Anonymous";
} elseif ($source != "Anonymous") {
$username = @$_SESSION['username_logged'];
} else {
$username = @$_SESSION["username"];
}

$sig_show = $_POST["sig_show"];
$visitorIP = $_SERVER["REMOTE_ADDR"]; 


	if ($review == "") {
BodyHeader("You need to write a review!","","");
?>
<link href="review.css" rel="stylesheet" type="text/css" />
<br />
You need to write a review!  Back to the <a href="<?php echo "$back"; ?>?item_id=<?php echo "$item_id"; ?>">review</a> page
<?php
BodyFooter();
exit;
}

//////////////////////////////////////////////////////////
//check to see if this user has already made a review on this item.
/////////////////////////////////////////////////////////
if ($multiple == "n") {
$sql = "SELECT visitorIP FROM 
			review
			WHERE 
			visitorIP = '$visitorIP'
			AND
			review_item_id='" . mysql_real_escape_string($item_id) . "'
			";

			$sql_result = mysql_query($sql)
		or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));
		
$num = mysql_numrows($sql_result);
	
	if ($num != 0) {
BodyHeader("You've already commented on this item!","","");
?>
<br />
According to our records, you've already reviewed this item.  Only one review per user is allowed.  Back to the <a href="index2.php?item_id=<?php echo "$item_id"; ?>">review</a> page
<?php
BodyFooter();
exit;
}
}
//////////////////////////////////////////////////////////

$date_added = date("Y-m-d",time());

//check user input and remove any reference to javascript.
$errjava = "<font color=red><BR><BR><B>No Javascript is allowed!  Please click edit and remove the offending code.<BR><BR></B></font>";

$summary = preg_replace("'<script[^>]*?>.*?</script>'si", "$errjava", $summary);
$review = preg_replace("'<script[^>]*?>.*?</script>'si", "$errjava", $review);
$source = preg_replace("'<script[^>]*?>.*?</script>'si", "$errjava", $source);
$location = preg_replace("'<script[^>]*?>.*?</script>'si", "$errjava", $location);

//security check...must be y or n
if ($approve != "y") {
$approve = "n";
}
	
if ($sig_show != "y") {
$sig_show = "n";
}
	
//if some idiot enters a word longer than 75 characters, break it up to fit on screen.
function cut_words( $txt , $limit, $html_nl = 0 )
{
   $str_nl = $html_nl == 1 ? "<br />" : ( $html_nl == 2 ? "<br />" : "\n" );
   $pseudo_words = explode( ' ',$txt );
   $txt = '';
   foreach( $pseudo_words as $v )
   {
       if( ( $tmp_len = strlen( $v ) ) > $limit )
       {
           $final_nl = is_int( $tmp_len / $limit );
           $txt .= chunk_split( $v, $limit, $str_nl );
           if( !$final_nl )
               $txt = substr( $txt, 0, - strlen( $str_nl ) );
           $txt .= ' ';
       }
       else
           $txt .= $v . ' ';
   }
   return substr( $txt, 0 , -1 );
}

$review = cut_words( "$review" , 75, 2 );

	
$sql = "INSERT INTO review
	SET rating='$rating',
summary='" . addslashes(stripslashes($summary)) . "',
review='" . mysql_real_escape_string($review) . "',
source='" . mysql_real_escape_string($source) . "',
location='" . mysql_real_escape_string($location) . "',
review_item_id='" . mysql_real_escape_string($item_id) . "',
visitorIP='" . mysql_real_escape_string($visitorIP) . "',
date_added='" . mysql_real_escape_string($date_added) . "',
username='" . mysql_real_escape_string($username) . "',
approve='" . mysql_real_escape_string($approve) . "',
sig_show='" . mysql_real_escape_string($sig_show) . "'
	";

$result = @mysql_query($sql,$connection)
	or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));

$review_id=mysql_insert_id();
//insert new rating criteria and values

$query="SELECT a.criteriaId,a.criteriaName FROM rating_criteria a,item_rating_criteria b WHERE a.criteriaId=b.criteriaId AND item_id='$item_id'";
$result=mysql_query($query) or die(mysql_error());
$flagR=0;
$totalR=0;
$countR=0;
while($row=mysql_fetch_array($result)){
	$flagR=1;
	$counter=$row["criteriaId"];
	
	if(isset($_POST["rad$counter"]) && $_POST["rad$counter"] !=""){
		$totalR+=$_POST["rad$counter"];
		$countR++;
		$query="INSERT INTO rating_details(review_id,criteriaId,ratingValue) VALUES(" . $review_id."," .$counter ."," . $_POST["rad$counter"].")";
		mysql_query($query) or die(mysql_error());
	}
}
if($flagR==1 && $totalR > 0 && $countR>0){
	$query="UPDATE review SET rating='" . number_format($totalR/$countR, 2, '.', '')."' WHERE id=" . $review_id;
	mysql_query($query) or die(mysql_error());
}elseif($flagR===1){
	$query="UPDATE review SET rating='0' WHERE id=" . $review_id;
	mysql_query($query) or die(mysql_error());
}	
//since data has already been saved with slashes, strip the slashes for a nice appearance...
$item_id = $_POST["item_id"];
$item_name = stripslashes($_POST["item_name"]);
$back = $_POST["back"];
$summary = stripslashes($_POST["summary"]);
$review = stripslashes($review);
$source = stripslashes($_POST["source"]);
$location = stripslashes($_POST["location"]);
$rating = clean($_POST["rating"]);
$sig = @$_POST["sig"];

if ($use_bbcode == "yes") {
include("bbcode.php");

  $review = str_replace($bbcode, $htmlcode, $review);
 // $review = nl2br($review);//second pass
}

	
//Send an email to the administrator with the users information.
$msg = "$sitename - Review Submission\n\n";
$msg .= "A review has been submitted.  Please log into the admin area to approve this submission.\n";
$msg .= "$url$directory/admin/\n\n";

$msg .= "Here is the review:\n\n";

 $review_mail = str_replace("<br />","\n",$review);

$msg .= "Review:  $review_mail\n\n";

$msg .= "Summary: $summary\n\n";

$msg .= "Visitor IP Address: $visitorIP\n\n";


$to = "$admin";
$subject = "$sitename - Review Submission";
$mailheaders = "From: $admin <$admin> \n";
$mailheaders .= "Reply-To: $admin\n\n";

mail($to, $subject, $msg, $mailheaders);

//send an email to subscriptions
if ($approve == "y") {
$sql = "SELECT username FROM review_subscriptions WHERE item_id_sub = '$item_id' AND username !=''
";

$sql_result = mysql_query($sql)
		or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));
		 while ($row = mysql_fetch_array($sql_result)) {
	$username = stripslashes($row["username"]);
} //end while
	$num = mysql_numrows($sql_result);

for ($i = 1; $i <= $num; $i++) {

$sql = "SELECT email, name FROM 
			review_users
			WHERE 
			username='" . mysql_real_escape_string($username) . "'
			";

			$sql_result = mysql_query($sql)
		or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));
		 while ($row = mysql_fetch_array($sql_result)) {
	$email = stripslashes($row["email"]);
$name = stripslashes($row["name"]);
} //end while

$mailsub = "./subscriptions/mail.php";
$fd = fopen ($mailsub, "r") or die("cannot open $mailsub");  
$msg= fread ($fd, filesize ($mailsub)); 
fclose ($fd); 
$msg = str_replace("\$username", $username, $msg);
$msg = str_replace("\$item_name", $item_name, $msg);
$msg = str_replace("\$admin", $admin, $msg);
$msg = str_replace("\$email", $email, $msg);
$msg = str_replace("\$sitename", $sitename, $msg);
$msg = str_replace("\$name", $name, $msg);
$msg = str_replace("\$item_id", $item_id, $msg);
$msg = str_replace("\$url", $url, $msg);
$msg = str_replace("\$directory", $directory, $msg);
$to = "$email";
$subject = "$sitename - New Review Notification";

$mailheaders = "From: $sitename <$admin> \n";
mail($to, $subject, $msg, $mailheaders);
}
} //end for

BodyHeader("$item_name Review Saved!","","");
?>
<p>Your review has been submitted successfully!
  <?php if ($approve == "n") { ?>
  Upon approval of the administrator 
  your comments will be added to <?php echo "$sitename."; } ?></p>
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
							$str.="<td><b></b></td>";							
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
			?>
      <?php 
	   		if($flag==0){
		//show stars if there is no review criteria
			 $display = ($rating/5)*100; ?>
      <div class="rating_bar">
        <div style="width:<?php echo "$display"; ?>%"></div>
      </div>
      <?php
			
			
		}	 
echo "<br />$summary, "; 
echo "$date_added"; ?>
      <br />
    </td>
  </tr>
  <tr>
    <td>Reviewer: <b>
      <?php if ($source == "") {
 $source = "Anonymous";
 } echo "$source"; ?>
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
	
	$review = nl2br(htmlentities("$review"));
	$review = str_replace('&amp;amp;', '&', $review);
	echo $review; if (isset($sig)) { echo "<BR><BR>$sig"; } ?></td>
  </tr>
</table>
<br />
<hr noshade="noshade" size="1" />
<?php //if it is enabled in config.php ask the user to upload an image to be displayed.
if ($user_upload == "y") { ?>
<p class="index2-Orange">Would you like to upload an image to be displayed with your review? <a href="user_image_upload.php?review_id=<?php echo "$review_id"; ?>&amp;<?php echo "item_id=$item_id"; ?>">Yes</a></p>
<?php } //end userupload ?>
<br />
<br />
Back to the <a href="<?php echo "index2.php?item_id=$item_id"; ?>">review</a> page
<?php
BodyFooter();
exit;
?>
