<?php
session_start();

$sort = "";
$item_id = "";
$order = "";
$build = "";

include ("../body.php");
include ("../functions.php");
include ("../f_secure.php");
include ("../config.php");

$back = $_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING'];

$review_id = htmlspecialchars($_GET[review_id], ENT_QUOTES);
$review_id = makeStringSafe($_GET['review_id']);

$item_id = htmlspecialchars($_GET[item_id], ENT_QUOTES);
$item_id = makeStringSafe($_GET['item_id']);

if(!is_numeric($item_id) || !is_numeric($review_id)) {
BodyHeader("Invalid item","","");
echo "The item you are attempting to view is not valid.";
BodyFooter();
exit;
}

$sel_com = "select *
from review_items
left join review
on (review.review_item_id = review_items.item_id)
where
review.review_item_id='" . makeStringSafe($item_id) . "' AND review.id='$review_id' AND approve = 'y'

";

$AllReviews = array();
$sResult = mysql_query("$sel_com");
$sRows = mysql_numrows( $sResult );

while($row = mysql_fetch_array($sResult)) {
	array_push($AllReviews,"$row[item_id]|$row[item_name]|$row[item_desc]|$row[item_type]|$row[id]|$row[rating]|$row[summary]|$row[review]|$row[source]|$row[location]|$row[review_item_id]|$row[visitorIP]|$row[date_added]|$row[useful]|$row[notuseful]|$row[approve]|$row[sig_show]|$row[username]|$row[user_image]|");
} //while

//$row[item_aff_url]|$row[item_aff_txt]|$row[item_aff_code]|
//select the item_id to be displayed in case there are no reviews that have been written.
$sel_com2 = "select item_name, item_desc, item_type, item_image, item_aff_url, item_aff_txt, item_aff_code from review_items where item_id = '$item_id'";

$sResult2 = mysql_query("$sel_com2") or die(sprintf("Couldn't execute sql_count, %s: %s", db_errno(), db_error()));

while($row2 = mysql_fetch_array($sResult2)) {
	$item_name2 = stripslashes($row2['item_name']);
	$item_type2 = stripslashes($row2['item_type']);
	$item_desc2 = stripslashes($row2['item_desc']);
	$item_image = stripslashes($row2['item_image']);
	$item_aff_url = stripslashes($row2['item_aff_url']);
	$item_aff_txt = stripslashes($row2['item_aff_txt']);
	$item_aff_code = stripslashes($row2['item_aff_code']);
} //while

//Set the number of reviews to display per page in the /info/functions.php file
$lastReview = sizeof($AllReviews);
if(isset($_GET['view']) && $_GET['view']=="all")$NumReviews=$lastReview;
$pg = @$_REQUEST['pg'];

if($pg > 1) {
	$start = ($pg-1)*$NumReviews;
	$npg = $pg + 1;
}else{
	$start = 0;
	$pg = 1; $npg = 2;
}

$PHP_SELF = $_SERVER['PHP_SELF'];
if(!$exit1) {exit;}
$allPages = 1;
$stop = 1;


$p=1;

////////////////////////////////////////////////////////////////////
//set variable names.
for ($i = 0; $i < 1; $i++) {
	@list($item_id2, $item_name, $item_desc, $item_type, $id, $rating, $summary, $review, $source, $location, $review_item_id, $visitorIP, $date_added, $useful, $notuseful, $approve, $sig_show, $username, $user_image) = split("\|",$AllReviews[$i]);
}

//query to count number of people that have rated.
$sql_count = "SELECT COUNT(*) as total FROM review WHERE rating != '' and review.review_item_id = '$item_id' AND approve = 'y'";
$sql_result_count = mysql_query($sql_count)	or die(sprintf("Couldn't execute sql_count, %s: %s", db_errno(), db_error()));

$rows = mysql_fetch_array($sql_result_count);
$total = $rows["total"];

//find average of reviews
$sql_avg = "select avg(rating) as average from review WHERE  rating !='' AND review.review_item_id = '$item_id' AND approve = 'y'";
$sql_result_avg = mysql_query($sql_avg)	or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));

while ( $row_avg = mysql_fetch_array($sql_result_avg) ) {
	$average = $row_avg["average"];
}

//if there is not a review must assign a value
if ($item_name == "") { $item_name = $item_name2; }
if ($item_desc == "") { $item_desc = $item_desc2; }


$item_desc_meta = strip_tags($item_desc);
$item_name_meta = ucfirst(stripslashes($item_name));

BodyHeader("Comments for " . ucfirst($source) ."'s $item_name_meta Review", "Reviews for $item_name_meta - $item_desc_meta", "$item_name_meta");
?>
<!-- enable this to make editing this page easier <link href="review.css" rel="stylesheet" type="text/css" /> -->

<a href="<?php echo "$url$directory/index2.php?item_id=$item_id"; ?>">Back</a> to <?php echo stripslashes($item_name); ?><?php
//comments below 

$review_id = clean($_GET['review_id']);

?>
<br /><br />

<script LANGUAGE="JavaScript" src="<?php echo "$directory/comments"; ?>/comments.js"></script>
<script LANGUAGE="JavaScript" src="<?php echo "$directory/comments"; ?>/XHConn.js"></script>

<body onLoad="prepare_entries();">



<link href="<?php echo "$directory/comments"; ?>/comments.css" rel="stylesheet" type="text/css">
  
<div id="borderbox">
<table width="700" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td bgcolor="#FFFFFF">
	<div style="padding:10px;"> 
        <table width="600" height="20" border="0" cellpadding="0" cellspacing="0" style="border: 1px solid #C1C1C1;">
          <tr valign="top"> 
            <td width="35" height="25">
            </td>
            <td> 
             <div class="comments_header"> 
                <table width="100%" border="0" cellspacing="0" cellpadding="3">
                  <tr>
    <td width="22%"> <?php 
	if (($registered == "yes") && ($_SESSION['signedin'] != "y")) {
	?>
    <div align="left"><span class="comments_header_text"><a href=<?php echo "$directory"; ?>//login/signin.php?back=<?php echo "$back"; ?>>Login to Post</a></span> 
              </div><?php
 } else { ?>
                <div align="left"><span class="comments_header_text"> <a href="#" onClick="showPost(this, 1);" id="postLink">Post 
                  Comment</a>  </span> 
              </div>
              <?php } ?>
              
              </td>
    <td width="78%"><span class="comments_header_text"> <span id="pageInfo"></span></span></td>
  </tr>
</table>
</div>
			</td>
          </tr>
        </table>
       
<div id="postcomment">
        <form action="" method="get" enctype="multipart/form-data" style="margin:0px;">
        <input name="review_id" type="hidden" id="review_id" value="<?php echo "$review_id"; ?>" />
            <table width="600" border="0" cellspacing="5" cellpadding="0" class="forms_table">
              <tr> 
        <td width="103" valign="top"><div align="right">Comment:</div></td>
        <td width="503"><textarea name="comment" cols="50" rows="4" id="comment" class="forms_textfield"></textarea></td>
      </tr>
      <tr> 
        <td valign="top"><div align="right">Name:</div></td>
        <td><input name="author" type="text" id="author" class="forms_textfield">
        </td>
      </tr>
      <tr> 
        <td valign="top"><div align="right"></div></td>
        <td><input type="button" name="Button" value="Post" onClick="verify_new();" class="forms_button">
          <input name="page" type="hidden" id="page" value="<?php if ($_GET['page'] == '') echo 1; else echo clean($_GET['page']); ?>"></td>
      </tr>
    </table>

    </form>
</div>
<div id="currentEntries"></div>
</div>
	</td>
  </tr>
</table>
</div>














<br /><link href="../default.css" rel="stylesheet" type="text/css" />
<br /><div id="borderbox">
<table width="100%"  border="0" align="center" cellpadding="6" cellspacing="0" style="borderbox">
  <tr>
    <link href="<?php echo "$directory"; ?>/javascript/style_notes.css" rel="stylesheet" type="text/css" />
    <td valign="top"><div id="reviewnote" style="left:250px;top:180px">
        <table width="250" cellpadding="0" cellspacing="0" border="0">
          <tr>
            <td colspan="2"><img src="<?php echo "$directory"; ?>/images/spacer.gif" width="250" height="5" /></td>
          </tr>
          <tr>
            <td align="right" nowrap="nowrap"><a href="#" onClick="javascript: window.open('<?php echo "$directory"; ?>/help_notes.php', 'notehelp', 'height=300,width=600,toolbar=no,scrollbars=no,resizable=yes,status=no'); return false;"><img
src="<?php echo "$directory"; ?>/images/sticky_questionmark.gif" alt="Review Sticky Notes Help"
border="0" /></a><a onClick="javascript: close_note();"><img src="<?php echo "$directory"; ?>/images/sticky_close.gif" alt="Close" width="17" height="15" border="0" /></a><a onClick="javascript: save_note('<?php echo "$directory"; ?>/save_notes.php');"><img src="<?php echo "$directory"; ?>/images/save.bmp" alt="Save Review" width="18" height="16" border="0" /></a> &nbsp; </td>
          </tr>
          <tr>
            <td colspan="2"><img src="<?php echo "$directory"; ?>/images/spacer.gif" alt="Spacer" width="250" height="15" /> </td>
          </tr>
          <tr>
            <td align="center"><form action="" method="post" name="note" id="note">
                <input type="hidden" name="action" value="savenote" />
                <input type="hidden" name="item_id" value="<?php echo $_GET['item_id']; ?>" />
                <?php if (isset($_SESSION["username_logged"]) && $_SESSION["username_logged"]!=""){?>
                <input type="hidden" id="user_name" name="user_name" value="<?php echo $_SESSION['username_logged'] ?>" />
                <?php }else{?>
                <input type="hidden" id="user_name" name="user_name" value="" />
                <?php }?>
                <?php
	$prevNotes="";
	if (isset($_SESSION["username_logged"]) && $_SESSION["username_logged"]!="" && isset($_GET['item_id']) && $_GET['item_id']!=""){
		$queryNotes="SELECT note_notes,note_date FROM review_items_note WHERE note_user_name='".$_SESSION["username_logged"] ."' AND note_item_id=$item_id";
		$resultNotes=mysql_query($queryNotes) or die(mysql_error());
		if(mysql_num_rows($resultNotes) > 0){
			$prevNotesRow=mysql_fetch_array($resultNotes);
			$prevNotes=stripslashes($prevNotesRow['note_notes']);
		}
	}
?>
                <textarea onChange="javascript: has_changed=true;" id="notearea" name="notearea" class="sticky" cols="25" rows="7">
<?php echo $prevNotes; ?>







<?php if (isset($_SESSION["username_logged"]) && $_SESSION["username_logged"]!=""){?>
<?php }else{?>
Please login to post notes about this Listing!
<?php }?>
</textarea>
              </form></td>
          </tr>
        </table>
      </div>
      <script language="JavaScript" src="<?php echo "$directory"; ?>/javascript/review_httpxml.js" type="text/javascript"></script>
      <script language="JavaScript" src="<?php echo "$directory"; ?>/javascript/review_note.js" type="text/javascript"></script>
      <script language="JavaScript1.2" src="<?php echo "$directory"; ?>/javascript/review_move.js" type="text/javascript"></script>
      

      <table>
        <tr>
          <td><b class="index2-Orange"><?php echo "$item_name2"; ?> : User Reviews </b></td>
          <td>&nbsp; &nbsp;<a href="javascript: show_review_note();"><img src="<?php echo "$directory"; ?>/images/sticky-index.png" title="Sticky Review Notes for <?php echo "$item_name2"; ?>" alt="Sticky Review Notes for <?php echo "$item_name2"; ?>" width="16" height="16" border="0" /></a></td>
          <td><b>Notes</b></td>
        </tr>
      </table>
      <?php
			$flag=0;
		$str="";
		$totalOutter=0;
		$countOutter=0;
		$queryR="SELECT a.criteriaId,a.criteriaName FROM rating_criteria a,item_rating_criteria b WHERE a.criteriaId=b.criteriaId AND item_id=" . makeStringSafe($_GET["item_id"]);
		$resultR=mysql_query($queryR) or die(mysql_error());
		while($rowR=mysql_fetch_array($resultR)){
			//we have criteria id, now get review
			$flag=1;
			$cId=$rowR["criteriaId"];
			$cName=$rowR["criteriaName"];

			//now get ratings where criteriaid is $cId
			$queryInner="SELECT a.ratingValue FROM rating_details a,review b WHERE a.review_id=b.id AND b.review_item_id=" .makeStringSafe($_GET["item_id"]) ." AND a.criteriaId=" .$cId;
			$resultInner=mysql_query($queryInner) or die(mysql_error());
			$totalInner=0;
			$countInner=0;
			while($rowInner=mysql_fetch_array($resultInner)){
				$totalInner+=$rowInner["ratingValue"];
				$countInner++;
				$totalOutter+=$rowInner["ratingValue"];
				$countOutter++;

			}
			
			if($totalInner > 0){
			$rat = number_format($totalOutter/$countOutter, 2, '.', ''); 
			 $display = ($rat/5)*100;
					$str.="<tr><td class=index2-small-bold><strong>$cName</strong></td><td class=index2-small-bold>					
					
					      <div class=\"rating_bar\">
        <div style=\"width:".$display."%\"></div>
      </div></td></tr>";
			}else{
					$str.="<tr><td class=index2-small-bold><strong>$cName</strong></td><td class=index2-small-bold><strong>&nbsp;&nbsp;N/A</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td></tr>";
			}

		}

	if($flag==1){
				?>
        <br />
        <table cellspacing="0" cellpadding="0" border="1" bordercolor="orange">
          <tr>
            <td><table  cellspacing="2" cellpadding="2">
                <?php echo "$str"; ?>
                <tr height="1">
                  <td colspan="2" bgcolor="black"></td>
                </tr>
                <?php //echo "$str1"; ?>
                <tr>
                  <td width="125" class="index2-small-bold"><strong>Average Rating:<?php echo "[".number_format($totalOutter/$countOutter, 2, '.', '')."]"; ?></strong></td>
                  <td width="17" class="index2-small-bold"><?php	//show stars if there is no review criteria
			$rat = number_format($totalOutter/$countOutter, 2, '.', ''); 
			 $display = ($rat/5)*100; ?>
                    <div class="rating_bar">
                      <div style="width:<?php echo "$display"; ?>%"></div>
                    </div></td>
                </tr>
              </table></td>
          </tr>
        </table>
        <?php
	}else{
		if ($average >= "1.0") { ?>
        <span class="index2-small-bold">Avg. Customer Review
        <?php } //if average
		$average_for = sprintf ("%01.1f", $average);
		if ($average <= "5.0") { echo "($average_for Stars):";  } ?>
        </span>
        <?php
		//show stars if there is no review criteria
			 $display = ($rating/5)*100; ?>
        <div class="rating_bar">
          <div style="width:<?php echo "$display"; ?>%"></div>
        </div>
        <?php
	}


	$item_id = makeStringSafe($_GET["item_id"]);

	if(!is_numeric($item_id)) {
		BodyHeader("Invalid item");
		echo "The item you are trying to view is not valid.";
		BodyFooter();
		exit;
	}

$back = $_SERVER['PHP_SELF'];
?>
      <br />
      <span class="index2-small-bold">Number of Reviews: <?php echo "$total"; ?> </span><br />
      <br />
      <span class="index2-Orange">Product Description</span><br />
      <br />
      <?php echo stripslashes($item_desc2); ?><br />
      <br /></td>
    <td width="200" valign="top">




<span class="index2-small">

<div align="left"> <img src="<?php echo "$directory"; ?>/images/write2.gif" title="Submit a <?php echo "$item_name2"; ?> review"  width="16" height="16" border="0" /><a href="<?php echo "$directory"; ?>/review_form.php?item_id=<?php
echo "$item_id"; ?>&amp;$back=<?php echo $_SERVER['REQUEST_URI']; ?>&amp;<?php echo htmlspecialchars(SID); ?>">Submit</a> a <?php echo "$item_name2"; ?> review</div>
<div align="left"><img src="<?php echo "$directory"; ?>/images/mail.gif" title="Email a Friend about this <?php echo "$item_type2"; ?>" width="16" height="16" border="0" /> <a href="<?php echo "$directory"; ?>/recommend.php?item_id=<?php echo "$item_id"; ?>&amp;<?php echo htmlspecialchars(SID); ?>">Recomend</a> to a friend</div>
<div align="left"><img src="<?php echo "$directory"; ?>/images/print.gif" title="Printer friendly format" width="16" height="16" border="0" /> <a href="<?php echo "$directory"; ?>/printpage.php?item_id=<?php echo "$item_id"; ?>">Printer</a> friendly format</div>
<div align="left"><img src="<?php echo "$directory"; ?>/images/save2.gif" title="Printer friendly format" width="16" height="16" border="0" /> <a href="<?php echo "$directory"; ?>/favorites/save.php?item_id=<?php echo "$item_id"; ?>&amp;<?php echo htmlspecialchars(SID); ?>">Save</a> to User Control Panel</div>
<div align="left"> <img src="<?php echo "$directory"; ?>/images/subscribe.gif" title="Subscribe to <?php echo "$item_name2"; ?> reviews" width="16" height="16" /> <a href="../subscriptions/save.php?item_id=<?php echo "$item_id"; ?>&amp;<?php echo htmlspecialchars(SID); ?>">Subscribe</a> to <?php echo stripslashes($item_name); ?> reviews</div>

<?php if ($item_aff_url != "") { ?>

<div align="left"><img src="<?php echo "$directory"; ?>/images/link.gif" title="More info on <?php echo "$item_name2"; ?>" width="16" height="16" /> <a href="<?php echo "../$item_aff_url"; ?>" target="_blank"><?php echo "$item_aff_txt"; ?></a></div>

<?php } elseif ($item_aff_code != "") { ?>

<div align="left"><img src="<?php echo "$directory"; ?>/images/link.gif" title="More info on <?php echo "$item_name2"; ?>" width="16" height="16" /> <?php echo "$item_aff_code"; ?></div>

</span>




	 <?php } ?>

      <br />
        <?php
	//if there is a music file, show the music icon.

	//if there is an image, resize it and then display it.
	$filename = "images/items/$item_image";

	$ext = strrchr($filename, ".");
	if ($ext == ".mp3") {
		echo "<a href=$directory/$filename target=_blank ><img src=\"$directory/images/music.gif\" width=\"48\" height=\"48\" border=\"0\" title=\"Click here to listen to $item_name2\"><span class=small>Listen to $item_name2</span></a>";
	} elseif ($item_image != "") { ?>
		 <a class="thumbnail" href="#thumb"><img src="<?php echo "$directory/resize_item_image.php?filename=$filename"; ?>" border="0" /><span><img src="<?php echo "$directory/$filename"; ?>" /> <?php echo "$item_name2"; ?></span></a>
        <?php	} ?>
    </td>
  </tr>
</table></div>      <br />

<span class="index2-Orange">User Review</span><?php //display page navigation on top
//$parsed = parse_url($_SERVER['PHP_SELF']);
//$lastpage = $parsed['path'];
?><br />

<br>

<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <?php
//end top page navigation

	$item_id = makeStringSafe($_GET["item_id"]);
	
	if(!is_numeric($item_id)) {
		BodyHeader("Invalid item");
		echo "The item you are trying to view is not valid.";
		BodyFooter();
		exit;
	}


//set variable names.
$tempCount=0;
for ($i = $start; $i < $stop; $i++) {
	list($item_id, $item_name, $item_desc, $item_type, $id, $rating, $summary, $review, $source, $location, $review_item_id, $visitorIP, $date_added, $useful, $notuseful, $approve, $sig_show, $username, $user_image) = split("\|",$AllReviews[$i]);
?>
  <tr>
    <td valign="top"><span class="index2-summary"><?php echo stripslashes($summary) . ', ';?></span>
      <?php
	$tempdate = explode("-", "$date_added");
	$date_added = mktime(0,0,0,$tempdate[1],$tempdate[2],$tempdate[0]);
	$newdate = date("F d, Y", "$date_added"); ?>
      <span class="index2-small"><?php echo "$newdate"; ?></span> <br />
      <span class="index2-small">Reviewer: <a href="<?php echo "$directory"; ?>/reviewer/<?php echo "$username"; ?>.php"><?php echo ucfirst(stripslashes($source)); ?></a></b> from <?php echo stripslashes($location); ?> - <a href="<?php echo "$directory"; ?>/reviewer/<?php echo stripslashes($username); ?>.php">See all reviews by <?php echo ucfirst(stripslashes($source)); ?></a></span><br />
      <br />
      <?
	//print_r($_SESSION);
	//show users signature?
	if($sig_show == "y") {
		$sql = "SELECT sig FROM
			review_users
			WHERE
			username = '" . makeStringSafe($username) . "'";

		$sql_result = mysql_query($sql)	or die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));

		while ($row = mysql_fetch_array($sql_result)) {
			$sig = stripslashes($row["sig"]);
		}
	//$_SESSION['sig'] = "$sig";
	}

	if ($use_bbcode == "yes") {
		include("../bbcode.php");
  		$sig = @str_replace($bbcode, $htmlcode, $sig);
  		$sig = nl2br($sig);//second pass
  		$review = str_replace($bbcode, $htmlcode, $review);
		$review = nl2br($review);//second pass
	}
	?>
      <table width="70%" border="0" cellspacing="8" cellpadding="8">
        <tr>
          <td width="100" valign="top" nowrap="nowrap"><?php
		$flag1=0;
		$queryReview1="SELECT a.criteriaId,a.criteriaName,b.ratingValue FROM rating_criteria a,rating_details b,item_rating_criteria c,review d WHERE a.criteriaId=b.criteriaId AND c.item_id=d.review_item_id AND b.review_id=d.id AND a.criteriaId=c.criteriaId AND b.review_id=" . $id;
		$resultReview1=mysql_query($queryReview1) or die(mysql_error());
		if(mysql_num_rows($resultReview1)>0){
		?>
              <table cellpadding="0" cellspacing="0" class="index2-criteria-table">
                <tr>
                  <td><table  cellspacing="2" cellpadding="2">
                      <?php
					$flag1=1;
					while($rowReview1=mysql_fetch_array($resultReview1)){
						$val=$rowReview1["ratingValue"];
						$sName=$rowReview1["criteriaName"];


if($val > 0){
							?>
                      <tr>
                        <td class="index2-small-bold"><?php echo "$sName"; ?>&nbsp;&nbsp;&nbsp;</td>
                        <?php
 $display = ($val/5)*100;  ?>
                        <td><div class="rating_bar">
                            <div style="width:<?php echo "$display"; ?>%"></div>
                          </div></td>
                      </tr>
                      <?php 
	  
						}else{
							 echo "<tr><td class=index2-small-bold>$sName</td><td class=index2-small-bold>&nbsp;&nbsp;N/A&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td></tr>";
						}
 } //end while ?>
                    </table></td>
                </tr>
              </table>
              <?php	}

	   		if($flag1==0){
				//show stars if there is no review criteria
			 $display = ($rating/5)*100; ?>
              <div class="rating_bar">
                <div style="width:<?php echo "$display"; ?>%"></div>
              </div>
              <?php
 		}
	 ?> </td>
          <td align="left" width="100%" valign="top"><div align="left"><span class="index2-small-bold"><?php echo "$useful of ";
	$total_useful = ($useful + $notuseful); echo "$total_useful";
	?> people found this review helpful</span><br />
              <br />
              <?php
			  //show review
			  echo stripslashes($review);

			  //show signature
			  if($sig_show == "y") { echo "<br /><br />__________________<br />$sig"; } ?> </div>
            <form action="<?php echo "$directory"; ?>/useful.php?item_id=<?php echo "$item_id"; ?>" method="post">
              <input name="id" type="hidden" value="<?php echo "$id"; ?>" />
              <input name="item_id" type="hidden" value="<?php echo "$item_id"; ?>" />
              <input name="item_name" type="hidden" value="<?php echo "$item_name2"; ?>" />
              <br />
              <span class="index2-small-bold">Was this review helpful to you?&nbsp;&nbsp;</span>
              <input type="image" value="1" name="useful" src="<?php echo "$directory"; ?>/images/yes.gif" border="0" width="34" height="16" align="middle" />
              <input type="image" value="1" name="notuseful" src="<?php echo "$directory"; ?>/images/no.gif" border="0" width="34" height="16" align="middle" />
              <br />
              <span class="index2-small">(<a href="<?php echo "$directory"; ?>/report.php?id=<?php echo "$id"; ?>&amp;item_id=<?php echo "$item_id"; ?>">Report this</a>) </span><br />
              <div class="index2-underline"></div>
              <br />
              <br />
            </form>
          </div></td>
          <td align="left" width="100%" valign="top"><div align="center"><?php
		  if ($user_upload == "y") {

	//if there is an image uploaded by reviewer, resize it and then display it.
	$filename2 = "images/user_upload/$user_image";

if ($user_image != "") {
		$html="<a href=$directory/$filename2 target=_blank ><img src=\"$directory/resize_item_image.php?filename=$filename2\" border=\"0\" title=\"Click here to see a larger image of $item_name2\"></a>";
		print $html;  } }//end if user upload 
		?>
          </div></td>
        </tr>
      </table></td>
  </tr>
  <tr>
    <td><?php
	if($tempCount==0){
		//code goes here for displaying ad sense ads
		$queryAd="SELECT * FROM review_adsense";
		$resultAd=mysql_query($queryAd) or die(mysql_error());
		if(mysql_num_rows($resultAd)> 0){
			$rowAd=mysql_fetch_array($resultAd);
			$adShare=$rowAd['ad_share'];
			$adPercent=$rowAd['ad_percent'];
			$adActive=$rowAd["ad_active"];
			$adClientId=$rowAd["ad_clientid"];
			$adChannelId=$rowAd["ad_channel"];

			if(strtolower($adShare)=="y" && $adPercent!="100"){
				//addshare is set to yes
				$cArray=array();
				$iAd=0;
				for($iAd=0;$iAd<$adPercent;$iAd++){
					$cArray[$iAd]="0";
				} //end if strtolower
				$queryAd="SELECT a.username FROM review a,review_users b WHERE a.username=b.username AND review_item_id=$item_id AND  b.adsense_clientid<>'' AND  b.adsense_channelid<>''";
				$resultShow=mysql_query($queryAd) or die(mysql_error());
				$countAd=mysql_num_rows($resultShow);
				if(! $countAd >0){
					//show admin ad as there is no user ads to  be shown
					for($iAd=$iAd;$iAd<100;$iAd++){
						$cArray[$iAd]="0";
					} //end if countAd
				}else{
					$pendingSeats=100-$iAd;
					$share=round($pendingSeats/$countAd);
					while($rowShow=mysql_fetch_array($resultShow)){
						for($sAds=$iAd;$sAds<$countAd*$share+$iAd;$sAds++ ){
							if($sAds<100){
								$cArray[$sAds]=$rowShow["username"];
							}else{
								break;
							} //end if sAds
						}  //end for sAds
					}  //end else

					//see if some slots are free, which may happen due to ronding off
					$checkCount=count($cArray);
					if($checkCount <99){
						for($inc=$checkCount;$inc<100;$inc++){
							$cArray[$inc]="0";
						}
					}
				} //end if

				//now we have the array, choose one index randomly
				$showAd="";//we will save -1[ for admin] or username[for user] in this variable
				$randomIndex=rand(0,99);
				if($cArray[$randomIndex]=="0"){
					//show admin ad








					?>
      <script type="text/javascript"><!--
						google_ad_client = "<?php echo "$adClientId"; ?>";
						google_ad_width = 728;
						google_ad_height = 90;
						google_ad_format = "728x90_as";
						google_ad_type = "text"; google_ad_channel ="<?php echo "$adChannelId"; ?>";
						google_color_border = "FFFFFF"; google_color_bg = "FFFFFF";
						google_color_link = "333333"; google_color_url = "0066CC";
						google_color_text = "333333";
					//--></script>
      <script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>
      <br />
      <br />
      <?php
				}else{
					//show user ad
						$query="SELECT adsense_clientid,adsense_channelid FROM review_users WHERE username='" .$cArray[$randomIndex] ."'";
						$resultUser=mysql_query($query) or die(mysql_error());
						if(mysql_num_rows($resultUser) >0) {
						$rowUser=mysql_fetch_array($resultUser);
						$adClientId=makeStringSafe($rowUser['adsense_clientid']);
						$adChannelId=makeStringSafe($rowUser['adsense_channelid']);
					?>
      <script type="text/javascript"><!--
								google_ad_client = "<?php echo "$adClientId"; ?>";
								google_ad_width = 728;
								google_ad_height = 90;
								google_ad_format = "728x90_as";
								google_ad_type = "text"; google_ad_channel ="<?php echo "$adChannelId"; ?>";
								google_color_border = "FFFFFF"; google_color_bg = "FFFFFF";
						google_color_link = "333333"; google_color_url = "0066CC";
						google_color_text = "333333";
							//--></script>
      <script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>
      <br />
      <br />
      <?php
					}
				}
			}else{
				//adshare is set to no, see if ad_active is yes

				if(strtolower($adActive)=="y"){
					//show admin ad 100% time
				?>
      <script type="text/javascript"><!--
						google_ad_client = "<?php echo "$adClientId"; ?>";
						google_ad_width = 728;
						google_ad_height = 90;
						google_ad_format = "728x90_as";
						google_ad_type = "text"; google_ad_channel ="<?php echo "$adChannelId"; ?>";
						google_color_border = "FFFFFF"; google_color_bg = "FFFFFF";
						google_color_link = "333333"; google_color_url = "0066CC";
						google_color_text = "333333";
					//--></script>
      <script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>
      <br />
      <br />
      <?php
				}
			}
		}
	}
	$tempCount++;
	}
?></td>
  </tr>
</table>

<br />
<br />
<?php  
BodyFooter();
exit;
?>