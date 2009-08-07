<?php
session_start();
include ('body.php');
include ('functions.php');
include ('f_secure.php');
include ('config.php');

//$source = @$_GET['source'];
$source = htmlspecialchars($_GET['source']);
$username = htmlspecialchars($_GET['username']);
$PHP_SELF = $_SERVER['PHP_SELF'];


//get user info
$sqlaccess = "SELECT * FROM review_users WHERE username='" . mysql_real_escape_string($username) . "'";
$resultaccess = mysql_query($sqlaccess)	or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));

while($row = mysql_fetch_array($resultaccess)) {
	//$username = stripslashes($row['username']);
	$name = stripslashes($row['name']);
	$city= stripslashes($row['city']);
	$state= stripslashes($row['state']);
	$zip= stripslashes($row['zip']);
	$age= stripslashes($row['age']);
	$profession= stripslashes($row['profession']);
	$aboutme= stripslashes($row['aboutme']);
	$skype= stripslashes($row['skype']);
	$sig= stripslashes($row['sig']);
} //while

$numaccess = mysql_numrows($resultaccess);

if ($numaccess == 0) {
	BodyHeader("User Not Found!","","");
	?>
<link href="<?php echo "$directory"; ?>/review.css" rel="stylesheet" type="text/css" />
<link href="<?php echo "$directory"; ?>/default.css" rel="stylesheet" type="text/css" />
<p><br />
  The user was not found. The most likely reason is that the reviewer did not register and therefore does not have an account. Please push the back button in your browser to proceed.<br />
  <br />
  <?php BodyFooter();  
	exit;  
}
//find number of useful responses.
$sql = "SELECT sum(useful) AS num_useful FROM review WHERE username='" . mysql_real_escape_string($username) . "'
";
$sql_result = mysql_query($sql)	or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));

$i = mysql_fetch_array($sql_result); 
$num_useful = $i['num_useful']; 

//main select statement
$sel_com = "SELECT * 
FROM review_items
LEFT JOIN review ON ( review.review_item_id = review_items.item_id ) 
WHERE review.username = '" . mysql_real_escape_string($username) . "'
AND approve = 'y'
ORDER BY review.id DESC
LIMIT 0 , 30 
";

$AllReviews = array();
$sResult = mysql_query("$sel_com");
$sRows = mysql_numrows( $sResult );


while($row = mysql_fetch_array($sResult)) {
	array_push($AllReviews,"$row[item_id]|$row[item_name]|$row[item_desc]|$row[item_type]|$row[id]|$row[rating]|$row[summary]|$row[review]|$row[source]|$row[location]|$row[review_item_id]|$row[visitorIP]|$row[date_added]|$row[useful]|$row[notuseful]|$row[approve]|");
	$location = stripslashes($row["location"]);
	//$name = stripslashes($row["name"]);
	$source = stripslashes($row["source"]);
	$item_id = stripslashes($row["item_id"]);
} //while


if ($use_bbcode == "yes") {
	include("bbcode.php");

	$sig = str_replace($bbcode, $htmlcode, $sig);
	$sig = nl2br($sig);//second pass

	$review = str_replace($bbcode, $htmlcode, $review);
	//  $review = nl2br($review);//second pass

	$aboutme = str_replace($bbcode, $htmlcode, $aboutme);
	$aboutme = nl2br($aboutme);//second pass
}


//Set the number of reviews to display per page in the /info/functions.php file
$lastReview = sizeof($AllReviews);
if(isset($_GET['view']) && $_GET['view']=="all")$NumReviews=$lastReview;
$pg = @$_REQUEST['pg'];

if($pg > 1) {
	$start = ($pg-1)*$NumReviews;
	$npg = $pg + 1; 
}else {
	$start = 0;
	$pg = 1; 
	$npg = 2; 
}

//show header
BodyHeader("All About $source","Information about reviewer $source","$source");
?>
  <script type="text/javascript" src="http://download.skype.com/share/skypebuttons/js/skypeCheck.js">
</script>
</p>
<div id="borderbox">
  <p class="index2-Orange"><b>About <?php echo ucfirst($name); ?></b> </p>
  <div>
    <?php 
			//if there is an image, resize it and then display it.
     	$filename = "usercp/upload/images/$username.jpg";
					
			if (file_exists($filename)) { 
				$html="<img src=$directory/usercp/upload/images/resize.php?filename=$username.jpg>";
				print $html;
			} else { ?>
    <div align="center">
      <?php
			// choose one of five dude images
$imNum = rand(1, 18);

// randomly select which dude image to show.
$user_image = "dude$imNum.png";
?>
      <img src="<?php echo "$directory"; ?>/images/dudes/<?php echo "$user_image"; ?>" width="48" height="48" /></div>
    <?php } ?>
    <div><b>Name:</b> <span><?php echo ucfirst($name); ?></span><br />
      <b>Location: </b><span>
      <?php if (isset($city)) { ?>
      <a href="http://maps.google.com/maps?q=<?php echo "$city"; ?>%2C%20<?php echo "$state"; ?>%20<?php echo "$zip"; ?>" target="_blank"><?php echo "$city, $state $zip"; ?></a>
      <?php } //end if city ?>
      <br />
      <b>Profession: </b><span><?php echo "$profession"; ?><br />
      <b>Age: </b><?php echo "$age"; ?><br />
      <b>About Me: </b><?php echo "$aboutme"; ?></span><br />
      <!--Skype 'Call me!' button http://www.skype.com/go/skypebuttons-->
      <?php 
						//only allow logged in members to call reviewer.  Also only show button if the user has a skype username.
					if ($skype != "") { 
						if ($username_logged) {
					?>
      <a href="skype:<?php echo "$skype"; ?>?call" onclick="return skypeCheck();"><img src="<?php echo "$directory"; ?>/images/skype_call_transparent_70x23.png" alt="Call me on Skype!" width="70" height="23" style="border: none;" /></a>
      <?php 
						} else { ?>
      <img src="<?php echo "$directory"; ?>/images/skype_call_transparent_70x23.png" alt="Call me on Skype!" width="70" height="23" style="border: none;" /> - You must be logged in to call this user.
      <?php
						} //end else
					} //end if skype ?>
    </div>
  </div>
  <br />
  <br />
  <h3 class="index2-Orange"><b>Reviews Written by <?php echo ucfirst($name); ?>: 
    &nbsp;</b></h3>
  <hr align="center" size="6" />
  <?php

if (isset($_GET['sort'])) {
$sort = $_GET['sort'];
}
$PHP_SELF = $_SERVER['PHP_SELF'];
$allPages = ceil($lastReview/$NumReviews);

$stop = $start + $NumReviews;
if($stop > $lastReview) {
	$stop = $lastReview; 
}

 $nextPage = "";
 for($j=1;$j<=$allPages;$j++) {
	if($pg == $j) { $nextPage .= " | $j ";}
	else {  $nextPage .= " | <a href=\"$PHP_SELF?pg=$j&amp;username=$username&amp;". htmlspecialchars(SID) ."\">$j</a> ";  }

 } //for

$p=1;
if(isset($_GET['pg']) && $_GET['pg']!="") $p=$_GET['pg'];

$nextPage=cPaging($allPages,$p,$NumReviews,$NumPages);

if(isset($_GET['pg_orig']) && $_GET['pg_orig']>=1)$nextPage.=" | <a href='$PHP_SELF?username=" .$username ."&amp;pg=" .$_GET['pg_orig'] ."'>Back To Paged View</a>";

function cPaging($count,$start,$pageSize,$displayPages){
	global  $PHP_SELF,$item_id,$sort,$order,$PHPSESSID,$username;
	$tempStr="";
	$flag=1;
	$center=$start+($displayPages>>1);
	if($center < 0) $center=0;
	for($i=1;$i<=$count;$i++){
		
		if($i<=($displayPages>>1) && $i>=$start-($displayPages>>1) ){
				if($i==$start){
					$tempStr .= " | $i";		
				}else{
					$tempStr .= " | <a href='$PHP_SELF?pg=" . $i ."&username=$username'>$i</a> ";		
				}
				$flag++;		
			
		}elseif($flag<=$displayPages && ($i>=$start-($displayPages>>1) && $i<=$start+($displayPages>>1))){
				if($i==$start){
					$tempStr .= " | $i";		
				}else{
					$tempStr .= " | <a href='$PHP_SELF?pg=" . $i ."&username=$username'>$i</a> ";		
				}
				$flag++;		
		}elseif($flag<=$displayPages && $i>=$start-($displayPages>>1)){
				if($i==$start){
					$tempStr .= " | $i";		
				}else{
					$tempStr .= " | <a href='$PHP_SELF?pg=" . $i ."&username=$username'>$i</a> ";		
				}
				$flag++;		
		
		}elseif($i==1){
			if($i==$start){
				$tempStr.="  $i";			
			}else{
				$tempStr .= "  <a href='$PHP_SELF?pg=" . $i ."&username=$username'>[$i ....]</a>&nbsp;&nbsp;";			
			}
			
		}	elseif($i==$count){
			if($i==$start){
				$tempStr.=" | $i";			
			}else{
				$tempStr .= " | &nbsp;&nbsp;<a href='$PHP_SELF?pg=" . $i ."&username=$username'>[$i ....]</a> ";			
			}
		}		
	}			
	//calculate previous next
	if($start < $count){
				$tempStr .= " &nbsp;&nbsp;&nbsp;&nbsp;<a href='$PHP_SELF?pg=" . ($start+1) ."&username=$username'><b>Next</b></a> ";
	}
	
	if($start > 1){
				$tempStr .= " &nbsp;&nbsp;&nbsp;&nbsp;<a href='$PHP_SELF?pg=" . ($start-1) ."&username=$username'><b>Prev</b></a> ";	}
	return $tempStr;
}



if ($lastReview != "0") { $nextPage .= " |"; }

////////////////////////////////////////////////////////////////////
//set variable names.
for ($i = 0; $i < 1; $i++) {
	@list($item_id2, $item_name, $item_desc, $item_type, $id, $rating, $summary, $review, $source, $location, $review_item_id, $visitorIP, $date_added, $useful, $notuseful, $approve) = split("\|",$AllReviews[$i]);
}

//BodyHeader("$name's Reviews");
	?>
    <br />
    <hr noshade="noshade" size="1" width="100%" />
    <?php //display page navigation on top  
$parsed = parse_url($_SERVER['PHP_SELF']);
$lastpage = $parsed['path'];

//don't display the pagination if there are no reviews
if ($lastReview != "0") { 
?>
    <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <th scope="col" nowrap="nowrap"><div align="left"><font color="#0033CC">Showing
            <?php $begin = $start+1; echo "$begin"; ?>
            to
            <?php if (isset($_GET['view']) && $_GET['view'] == "all") { echo $lastReview; } else {echo "$stop";  }?>
            of <?php echo "$lastReview"; ?> reviews </font></div></th>
        <th width="100%" scope="col" align="center"><?php echo "$nextPage"; ?>
          <?php //only show view all if there is more than one page
if($lastReview > $NumReviews) { 
?>
          - <a href="<?php echo "$PHP_SELF"; ?>?username=<?php echo "$username"; ?>&amp;view=all&amp;pg_orig=<?php echo @$_GET['pg']; ?>">View All</a>
          <?php } //end viewall check ?>
        </th>
      </tr>
    </table>
    <hr noshade="noshade" size="1" width="100%" />
    <br />
    <?php } //end pagination check 

$item_id = @clean($_GET["item_id"]);

//set variable names.
?>  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
<?php
for ($ii = $start; $ii < $stop; $ii++) {
	list($item_id, $item_name, $item_desc, $item_type, $id, $rating, $summary, $review, $source, $location, $review_item_id, $visitorIP, $date_added, $useful, $notuseful, $approve) = split("\|",$AllReviews[$ii]);
	?>
    <tr>
      <td><font face="verdana,arial,helvetica" size="-1"><a href="<?php echo "$directory"; ?>/review-item/<?php echo "$item_id"; ?>.php?<?php echo htmlspecialchars(SID); ?>"><?php echo stripslashes($item_name); ?></a><br />
        <?php 
		echo "$useful of "; 
		$total_useful = ($useful + $notuseful);
		echo "$total_useful";
		?>
        people found the following review helpful:<br />
        <br />
        </font></td>
    </tr>
    <tr>
      <td><font face="verdana,arial,helvetica" size="-1">
        <?php 
			$flag=0;
			$queryR="SELECT * FROM rating_details WHERE review_id=$id";
			$resultR=mysql_query($queryR) or die(mysql_error());
			$total=0;
			$count=0;
			while($rowR=mysql_fetch_array($resultR)){
				$flag=1;
				if($rowR["ratingValue"] >0){
					$total=$total+ $rowR["ratingValue"];
					$count++;
				}
			}
			$st="";
			if($total>0){
				
			}else{
				$st="";
			}
				
			if($flag==1){
				if($total > 0){
				
				//$rat = number_format($total/$count, 2, '.', '');
			$display = ($total/5)*100;
				$str="<table><tr><td colspan=2><b>Rating</td><td colspan=2> <div class=\"rating_bar\">
        <div style=\"width:$display%\"></div>
      </div></td><td colspan=2><b> ($total)</b></td></tr></table>";
			
				
				
					//$str="<table><tr><td class=style2><strong>Rating</strong></td><td class=style2>&nbsp;&nbsp;$st</td></tr></table>";
				}else{
					$str="<table><tr><td class=style2><strong>Rating</strong></td><td class=style2><strong>&nbsp;&nbsp;N/A</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td></tr></table>";
				}
				echo $str ."<br />";
			}else{
				 
		//show stars if there is no review criteria
			 $display = ($rating/5)*100; ?>
        <div class="rating_bar">
          <div style="width:<?php echo "$display"; ?>%"></div>
        </div>
        <?php
 			}
			echo stripslashes($summary); ?>
        <?php

			$tempdate = explode("-", "$date_added"); 
			$date_added = mktime(0,0,0,$tempdate[1],$tempdate[2],$tempdate[0]); 
			$newdate = date("M d, Y", "$date_added"); 
		 	echo "$newdate";

		 	?>
        <br />
        </font></td>
    </tr>
    <tr>
      <td><font face="verdana,arial,helvetica" size="-1">reviewer: <b> <a href="<?php echo "$directory"; ?>/reviewer/<?php echo "$username"; ?>.php?<?php echo htmlspecialchars(SID); ?>"><?php echo ucfirst(stripslashes($name)); ?></a></b> from <?php echo stripslashes($location); ?><br />
        </font></td>
    </tr>
    <tr>
      <?
		if ($use_bbcode == "yes") {
			include("bbcode.php");
  			$sig = str_replace($bbcode, $htmlcode, $sig);
  			$sig = nl2br($sig);//second pass
 			$review = str_replace($bbcode, $htmlcode, $review);
			$review = nl2br($review);//second pass
  			$aboutme = str_replace($bbcode, $htmlcode, $aboutme);
  			$aboutme = nl2br($aboutme);//second pass
		}

		?>
      <td><?php echo stripslashes($review); ?></td>
    </tr>
    <tr>
      <td><form action="<?php echo "$directory"; ?>/useful.php?item_id=<?php echo "$item_id"; ?>" method="post">
          <input name="id" type="hidden" value="<?php echo "$id"; ?>" />
          <input name="item_id" type="hidden" value="<?php echo "$item_id"; ?>" />
          <input name="item_name" type="hidden" value="<?php echo "$item_name2"; ?>" />
          <br />
          <span class="small">Was this review helpful to you?&nbsp;&nbsp;</span>
          <input type="image" value="1" name="useful" src="<?php echo "$directory"; ?>/images/thumbs_up.gif" border="0" width="16" height="16" align="absmiddle" />
          <input type="image" value="1" name="notuseful" src="<?php echo "$directory"; ?>/images/thumbs_down.gif" border="0" width="16" height="16" align="absmiddle" />
          (<a href="<?php echo "$directory"; ?>/report.php?id=<?php echo "$id"; ?>&amp;item_id=<?php echo "$item_id"; ?>" class="style1">Report this</a>) <br />
          <br />
          <br />
        </form></td>
    </tr>
    <?php
		  }

?>
  </table>
  <?php

 
//$item_id = @$_GET["item_id"];
//display page navigation on bottom  
//$parsed = parse_url($_SERVER['PHP_SELF']);
//$lastpage = $parsed['path'];

//don't display the pagination if there are no reviews
if ($lastReview != "0") { 
?>
  <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <th  scope="col" nowrap="nowrap"><div align="left"><font color="#0033CC">Showing
          <?php $begin = $start+1; echo "$begin"; ?>
          to
          <?php if (isset($_GET['view']) && $_GET['view'] == "all") { echo $lastReview; } else {echo "$stop";  }?>
          of <?php echo "$lastReview"; ?> reviews </font></div></th>
      <th width="100%" scope="col"><?php 

//echo "$stop is stop and $lastReview is lastreview";
echo "$nextPage";   ?>
        <?php //only show view all if there is more than one page
if($lastReview > $NumReviews) { 
?>
        - <a href="<?php echo "$PHP_SELF"; ?>?username=<?php echo "$username"; ?>&amp;view=all&amp;pg_orig=<?php echo @$_GET['pg']; ?>">View All</a>
        <?php } //end viewall check ?>
      </th>
    </tr>
  </table>
  <hr noshade="noshade" size="1" width="100%" />
  <br />
  <?php } //end pagination check 
?>
</div>
<?php
//if no reviews are present close up the open table.  If reviews are present the table is closed in the code above.
if ($sRows < 1) {
?>
<?php }
BodyFooter();
exit;
?>
