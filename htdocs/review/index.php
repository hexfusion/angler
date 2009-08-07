<?php
session_start();
include ('functions.php');
include ('f_secure.php');
include ('body.php');
include ('config.php');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>Amazon Style Review Script -<?php echo "$sitename"; ?></title>
<meta name="keywords" content="review, amazon review, review script" />
<meta name="description" content="This php script allows users to add reviews of your products and services to your website" />
<link href="default.css" rel="stylesheet" type="text/css" media="screen" />
<link rel="stylesheet" type="text/css" href="ajaxtabs/ajaxtabs.css" />

<script type="text/javascript" src="ajaxtabs/ajaxtabs.js">

/***********************************************
* Ajax Tabs Content script v2.2- © Dynamic Drive DHTML code library (www.dynamicdrive.com)
* This notice MUST stay intact for legal use
* Visit Dynamic Drive at http://www.dynamicdrive.com/ for full source code
***********************************************/

</script>
</head>
<body>
<!-- start wrapper -->
<div id="wrapper">
  <!-- start menu -->
  <div id="menu">
    <ul>
      <li class="current_page_item"><a href="<?php echo "$directory"; ?>/index.php?<?php echo htmlspecialchars(SID); ?>">Home</a></li>
      <?php if (@$_SESSION['signedin'] != "y") { ?>
      <li><a href="<?php echo "$directory"; ?>/login/register.php?<?php echo htmlspecialchars(SID); ?>">Register</a></li>
      <li> <a href="<?php echo "$directory"; ?>/login/signin.php?<?php echo htmlspecialchars(SID); ?>">Login</a></li>
      <?php } else { 
@$back = $_SERVER['PHP_SELF'];
?>
      <li> <a href="<?php echo "$directory"; ?>/logout.php?<?php echo htmlspecialchars(SID); ?>">Logout</a></li>
      <?php } ?>
      <li><a href="<?php echo "$directory"; ?>/usercp/index.php?<?php echo htmlspecialchars(SID); ?>">UserCP</a></li>
      <li><a href="<?php echo "$directory"; ?>/rss/rss.xml?<?php echo htmlspecialchars(SID); ?>">RSS</a></li>
      <li><a href="<?php echo "$directory"; ?>/search/index.php?<?php echo htmlspecialchars(SID); ?>">Search</a></li>
    </ul>
  </div>
  <!-- end menu -->
  <!-- start header -->
  <div id="header">
    <div id="logo">
      <h1>Five Star</h1>
      <p>Review Script</p>
    </div>
    <!--    <div id="search">
    
	  
	  
	  <form method="get" action="">
        <fieldset>
        <legend>Quick Search</legend>
        <input id="s" type="text" name="s" value="" />
        <input id="x" type="submit" value="Search" />
        </fieldset>
      </form> 
    </div>-->
  </div>
  <!-- end header -->
  <!-- start page -->
  <div id="page">
    <div id="bgtop"></div>
    <div id="bgbottom">
      <!-- start content -->
      <div id="content">
        <div class="post">
          <div>
            <div id="borderboxrt">
              <p>The Five Star Review Script is a PHP/MYSQL/AJAX based script that 
                allows users to read and write reviews for your products or services. This powerful script can power your entire website or can be adapted for use in your current site. Adding reviews to your website increases traffic and sales. Get the extra edge, add reviews to your site <a href="http://www.review-script.com/purchase/">today</a>!</p>
            </div>
            <div>
              <div align="left"><img src="images/star_big.png" alt="Five Star" width="250" height="250" /></div>
            </div>
          </div>
          <h1 align="left" class="title">About Five Star Review</h1>
          <p class="byline">&nbsp;</p>
          <div class="entry">
            <p><strong>
              <?php if (isset($_SESSION['username_logged']) && $_SESSION['username_logged']) { echo "<B>Welcome " . ucfirst($_SESSION['username_logged']) . "!</B><BR><BR>"; } ?>
              If you like this review script software, you can purchase it <a href="http://www.review-script.com/purchase/browse_products.php">here</a>. </p>
            <p align="left">This is a demonstration page to show off some of the features of the Five Star Review Script. You can add the different plug-ins to any page! Here are samples of different <a href="layouts.php?<?php echo htmlspecialchars(SID); ?>">layout</a> options.</p>
            <p align="left">This is an example site designed for movie and dvd reviews - <a href="http://www.movie-critique.com">Movie-Critique.com</a>. </p>
            <p align="left">Take a look at some <a href="http://www.review-script.com/inaction.php">sites</a> that have added the Five Star Review Script to their site. </p>
            <p align="left">The Google ads you see on this site are controlled by a setting in the configuration file. Read more about the Google AdSense Revenue Sharing feature <a href="http://www.review-script.com/adsense_sharing.php">here</a>. </p>
            <p align="left">If you'd like to test out the <a href="admin/index.php?<?php echo htmlspecialchars(SID); ?>">admin</a> area, use the following access info:</p>
            <p align="left">username: webmaster<br />
              pass: 4tugboat </p>
            </p>
            <?php include('ajaxtabs.php'); ?>
            <br />
            <div id="borderbox">
              <?php include('review_categories_insert.php'); ?>
            </div>
            <br />
            <div id="borderbox">
              <h2>Most Popular Categories</h2>
              <?php include('cloud2.php'); ?>
            </div>
            <br />
            <div id="borderbox">
              <h2>Top Rated Items</h2>
              <?php include('items_top_rating.php'); ?>
            </div>
          </div>
        </div>
      </div>
      <!-- end content -->
      <!-- start sidebar two -->
      <div id="sidebar2" class="sidebar">
        <div id="borderbox">
          <?php include('login_block.php'); ?>
        </div>
        <br />
        <div id="borderbox">
          <h2>Review Categories</h2>
          <?php include('review_categories_block.php'); ?>
        </div>
        <br />
        <?php include('category_block.php'); ?>
        <br />
        <div id="borderbox">
          <h2>Top Rated Reviews</h2>
          <?php include('reviews_top_rating.php'); ?>
        </div>
        <br />
        <div id="borderbox">
          <h2>Most Useful</h2>
          <?php include('search_most_useful.php'); ?>
        </div>
        <br />
        <div id="borderbox">
          <h2>Most Active</h2>
          <?php include('search_most_reviews.php'); ?>
        </div>
        <br />
        <div id="borderbox">
          <h2>Most Active This Month</h2>
          <?php include('search_most_reviews_by_curr_month.php'); ?>
        </div>
        <br />
        <div id="borderbox">
          <h2>Latest Reviews</h2>
          <?php include('reviews_latest2.php'); ?>
        </div>
        <br />
      </div>
      <!-- end sidebar two -->
      <div id="content_wide"><br />
        <br />
        this goes all the way acrooss if u need it too this goes all the way acrooss if u need it too this goes all the way acrooss if u need it too this goes all the way acrooss if u need it too this goes all the way acrooss if u need it too this goes all the way acrooss if u need it too this goes all the way acrooss if u need it too this goes all the way acrooss if u need it too </div>
      <div style="clear: both;">&nbsp;</div>
    </div>
  </div>
  <!-- end page -->
  <hr />
  <!-- start footer -->
  <div id="footer">
    <p><a href="<?php echo "$directory"; ?>/archive_mod_rewrite.php">Archives</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &copy;2007 All Rights Reserved</p>
  </div>
  <!-- end footer -->
</div>
<!-- end wrapper -->
<div align="center">
  <?php
    //Removing the PoweredBy link will disable your script as it is against the terms of your license.  Replace 101 with your own affiliate code.  Signup to become an affiliate here:  http://www.review-script.com/affiliates/index.php.  If you wish to remove the Powered By link, you can purchase that version of the script here...
    @PoweredBy("101");
?>
  <br />
</div>
</body>
</html>
