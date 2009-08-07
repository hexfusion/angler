<?
session_start();
include ("functions.php");
include ("f_secure.php");
include ("body.php");
include ("config.php");

//how many records to show
$NumReviews=1;
//how many page links to show
$NumPages=3;

BodyHeader("Amazon Style Review Script - $sitename","","");
?>
<link href="review.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.style5 {font-size: 14px}
.style6 {font-size: 18px}
-->
</style>
<table width="99%"  border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#CCCCCC">
  <tr>
    <td><table width="99%"  border="0" align="center" cellpadding="8" cellspacing="0">
        <tr>
          <td width="165" valign="top"><table width="160" border="1" align="center" cellpadding="3" cellspacing="0" bordercolor="#000000">
              <tr>
                <td><div align="center"><span class="style1"><strong>Search Reviews </strong></span>
                    <form method="get" action="<?php echo "$directory"; ?>/search/index.php">
                      <input type="hidden" name="cmd" value="search" />
                      <input name="words" type="text" value="" size="15" maxlength="255" />
                      <input name="mode" type="hidden" value="normal">
                      <input type="submit" value="Search" />
                    </form>
                  </div></td>
              </tr>
              <tr>
                <td class="style1"><p align="center"><strong>Most Useful </strong></p>
                  <p>
                    <? include("search_most_useful.php"); ?>
                  </p></td>
              </tr>
              <tr>
                <td class="style1"><p align="center"><strong>Most Active Reviewers </strong></p>
                  <p>
                    <? include("search_most_reviews.php"); ?>
                  </p></td>
              </tr>
            </table>
            <BR>
            <? include("reviews_latest2.php"); ?>
            <BR>
            <? include("review_categories_block.php"); ?>
		<br />
<? include("category_block.php"); ?>
            <BR>
            <? include("reviews_top_rating.php"); ?>
          </td>
          <td valign="top" align=center>
				<table width="100%"  border="1" align="center" cellpadding="6" cellspacing="0" bordercolor="black">
                    <tr>
                      <td>
							<h2>Review Items</h2>
							<div align="left">
							<?
							// get a count of how many links are in the database
							$category=@$_GET["category"];

							if($category!=""){
								$category=addslashes($category);
								$link_count = "SELECT count(*) as count FROM review_items WHERE category = '$category'";
								$result = mysql_query($link_count);
								$row=mysql_fetch_array($result);
								$num_links=$row['count'];
								if ($num_links <1){
									echo "There are no results for your search.";
								}else{
									
									$pg=1;
									if(isset($_GET['pg']) && $_GET['pg']!=""){
										$pg=$_GET['pg'];
									}
									if($pg > 1) {
										$start = ($pg-1)*$NumReviews;
									}else{
										$start = 0;
										$pg = 1;
									}
									
									$lastReview=$num_links;
									$PHP_SELF = $_SERVER['PHP_SELF'];
									$allPages = ceil($lastReview/$NumReviews);
									$stop = $NumReviews;
									if($stop >= $lastReview) { $stop = $lastReview; }
									if(isset($_GET['view']) && $_GET['view']=="all"){
										$start=0;
										$allPages=1;
										$stop=$lastReview;
									}
									$nextPage = "";
									function cPaging($count,$start,$pageSize,$displayPages){
										global  $PHP_SELF,$category,$PHPSESSID;
										$category=stripslashes($category);
										$tempStr="";
										$flag=1;
										$center=$start+($displayPages>>1);
										if($center < 0) $center=0;
										for($i=1;$i<=$count;$i++){
										if($i<=($displayPages>>1) && $i>=$start-($displayPages>>1) ){
											if($i==$start){
												$tempStr .= " | $i";		
											}else{
												$tempStr .= " | <a href='$PHP_SELF?pg=" . $i ."&category=" . stripslashes($category) ."&PHPSESSID=$PHPSESSID'>$i</a> ";		
											}
											$flag++;		
										}elseif($flag<=$displayPages && ($i>=$start-($displayPages>>1) && $i<=$start+($displayPages>>1))){
											if($i==$start){
												$tempStr .= " | $i";		
											}else{
												$tempStr .= " | <a href='$PHP_SELF?pg=" . $i ."&category=" . stripslashes($category) ."&PHPSESSID=$PHPSESSID'>$i</a> ";		
											}
											$flag++;		
										}elseif($flag<=$displayPages && $i>=$start-($displayPages>>1)){
											if($i==$start){
												$tempStr .= " | $i";		
											}else{
												$tempStr .= " | <a href='$PHP_SELF?pg=" . $i ."&category=" . stripslashes($category) ."&PHPSESSID=$PHPSESSID'>$i</a> ";		
											}
											$flag++;		
										}elseif($i==1){
											if($i==$start){
												$tempStr.="  $i";			
											}else{
												$tempStr .= "  <a href='$PHP_SELF?pg=" . $i ."&category=" . stripslashes($category) ."&PHPSESSID=$PHPSESSID'>[$i ....]</a>&nbsp;&nbsp;";			
											}
										}elseif($i==$count){
											if($i==$start){
												$tempStr.=" | $i";			
											}else{
												$tempStr .= " | &nbsp;&nbsp;<a href='$PHP_SELF?pg=" . $i ."&category=" . stripslashes($category) ."&PHPSESSID=$PHPSESSID'>[$i ....]</a> ";			
											}
										}		
									}			
									//calculate previous next
									
									if($start < $count){
										$tempStr .= " &nbsp;&nbsp;&nbsp;&nbsp;[ <a href='$PHP_SELF?pg=" . ($start+1) ."&category=" . stripslashes($category) ."&PHPSESSID=$PHPSESSID'><b>Next</b></a> ]";
									}
									
									if($start > 1){
										$tempStr .= " &nbsp;&nbsp;&nbsp;&nbsp;[ <a href='$PHP_SELF?pg=" . ($start-1) ."&category=" . stripslashes($category) ."&PHPSESSID=$PHPSESSID'><b>Prev</b></a> ]";
									}
									return $tempStr;
								}

								$nextPage=cPaging($allPages,$pg,$NumReviews,$NumPages);

								if ($lastReview != "0") {
									$nextPage .= " |";
								}
									$query2 = "SELECT * FROM review_items 	WHERE category='$category' ORDER BY category ASC LIMIT $start,$stop";
									$result2 = mysql_query($query2); 
									
									while ( $row = mysql_fetch_array($result2)){
										$item_name = stripslashes($row["item_name"]);
										$item_id = $row["item_id"];
										$item_desc = stripslashes($row["item_desc"]);
										$cat=stripslashes($row["category"]);
										// now print the links in this category
									?>
  										<span class="maintext"><a href=index2.php?item_id=<?php echo "$item_id"; ?>&<?=SID?>><?php echo ucfirst($item_name); ?></a> - <?php echo "$item_desc"; ?></span><BR>
									<?
									}
									echo "<br>" . $nextPage;
										if(isset($_GET['pg_orig']) && $_GET['pg_orig']>=1){
											echo (" [ <a href='$PHP_SELF?pg=" .$_GET['pg_orig'] ."&category=" . stripslashes($category) ."&PHPSESSID=$PHPSESSID'><b>Paged View</b></a> ]");
										}else{
											if($lastReview > $NumReviews) { 
											?>
												[ <a href="<? echo "$PHP_SELF"; ?>?view=all&category=<?php echo stripslashes($category); ?>&pg_orig=<? echo "$pg"; ?>&PHPSESSID=<?php echo "$PHPSESSID"; ?>"><b>View All</b></a> ]
											<?
											}
										}
									}
								?>
								</div>
								<div align="center"><span class="style1"><br>
  								<span class="maintext">Didn't see the Item you would like to review? <a href="user_add_item.php?<?=SID?>">Suggest</a> one!</span></span> </div>
								<?php
								}else{
									echo "There are no results for your search.";
								}
							?>
					  </td>
                    </tr>
                  </table>			
			</td>
          <td width="130" valign="top">
		  <table width="161" border="1" cellpadding="0" cellspacing="0" bordercolor="#000000">
  <tr>
    <td><script type="text/javascript"><!--
google_ad_client = "pub-4166677013602529";
google_ad_width = 160;
google_ad_height = 600;
google_ad_format = "160x600_as";
google_ad_type = "text";
google_ad_channel ="0389955383";
google_color_border = "ffffff";
google_color_bg = "FFFFFF";
google_color_link = "0000ff";
google_color_url = "333333";
google_color_text = "000000";
//--></script>
<script type="text/javascript"
  src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script></td>
  </tr>
</table>

		  
		  
          </td>
        </tr>
      </table></td>
  </tr>
</table>
<div align="center"><span class="small"><a href="archive_mod_rewrite.php">Archives</a></span>
  <? BodyFooter(); ?>
</div>
