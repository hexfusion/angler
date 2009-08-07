<?
session_start();
include ('functions.php');
include ('f_secure.php');
include ('body.php');
include ('config.php');

BodyHeader("Amazon Style Review Script - $sitename", "This php script allows users to add reviews of your products and services to your website", "review, amazon review, review script");
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
          <td width="165" valign="top"><? include("reviews_latest2.php"); ?>
            <br />
            <? include("review_categories_block.php"); ?>
		    <br />
	
<? include("category_block.php"); ?>
<br />
<? include("reviews_top_rating.php"); ?>
<br />
<br /></td>
          <td valign="top"><table width="90%" align="center">
              <tr>
                <td class="index-table"><div align="center" class="category">
                          <p align="left">
                            <?php if (isset($_SESSION['username_logged']) && $_SESSION['username_logged']) { echo "<B>Welcome " . ucfirst($_SESSION['username_logged']) . "!</B><BR><BR>"; } ?>
                            If you like this review script software, you can purchase it <a href="http://www.review-script.com/purchase/browse_products.php">here</a>.</p>
                          <p align="left">Here is the NEW <a href="/review_beta">Five Star Review Version 5</a>. It will be released for purchase before the end of January '08. Here is a list of <a href="/review_beta/features.php">features</a>.</p>
                    <p align="left">This is a demonstration page to show off some of the features of the Five Star Review Script. You can add the different plug-ins to any page! Here are samples of different <a href="layouts.php?<?=SID?>">layout</a> options.</p>
                          <p align="left">This is an example site designed for movie and dvd reviews - <a href="http://www.movie-critique.com">Movie-Critique.com</a>. </p>
                          <p align="left">Take a look at some <a href="http://www.review-script.com/inaction.php">sites</a> that have added the Five Star Review Script to their site. </p>
                          <p align="left">The Google ads you see on this site are controlled by a setting in the configuration file. Read more about the Google AdSense Revenue Sharing feature <a href="http://www.review-script.com/adsense_sharing.php">here</a>. </p>
                          <p align="left">If you'd like to test out the <a href="admin/index.php?<?=SID?>">admin</a> area, use the following access info:</p>
                          <p align="left">username: webmaster<br />
                            pass: 4tugboat
</p>
                          </div></td>
              </tr>
                  </table>
            <br>
            <br>
            <table width="90%"  border="0" align="center" cellpadding="0" cellspacing="0">
              <tr>
                <td class="index-table"><div align="center">
                          <? include("review_categories_insert.php"); ?>
                        </div></td>
              </tr>
            </table>
            <br>
            <br>
            <table width="90%"  border="0" align="center" cellpadding="0" cellspacing="0">
              <tr>
                <td class="index-table"><div align="center" class="category">
                          <p class="index2-Orange">Latest News...</p>
                          <p align="left">This version is available for <a href="http://www.review-script.com/purchase/browse_products.php">download</a>! <br>
                            <br>
                          </p>
                        </div></td>
              </tr>
            </table>
            <br>
            <? 
				include("items_top_rating.php");
			?>
            <p>&nbsp;</p>
            <p>&nbsp;</p></td>
          <td width="130" valign="top">
		  <table width="161" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td class="index-table"><script type="text/javascript"><!--
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

		  
		  
          <br />
          <table width="181" border="0" align="center" cellpadding="0" cellspacing="0" bordercolor="0">
            <tr>
              <td class="index-table"><p align="center" class="index2-Orange">Most Useful
                  </p>
                  <? include("search_most_useful.php"); ?>
                  <br />
                  <br />
              </td>
            </tr>
          </table>
          <br />
          <table width="181" border="0" align="center" cellpadding="0" cellspacing="0">
            <tr>
              <td class="index-table"><p align="center" class="index2-Orange">Most Active
                  </p>
                  <? include("search_most_reviews.php"); ?>
                <br />
                  <br />
              </td>
            </tr>
          </table>
          <br />
          <table width="181" border="0" align="center" cellpadding="0" cellspacing="0">
            <tr>
              <td class="index-table"><p align="center" class="index2-Orange">Most Active This Month</p>
                  <? include("search_most_reviews_by_curr_month.php"); ?>
                  <br />
                  <br />
              </td>
            </tr>
          </table></td>
        </tr>
      </table></td>
  </tr>
</table>
<div align="center"><span class="small"><a href="archive_mod_rewrite.php">Archives</a></span></div>
  <? BodyFooter(); ?>

