<?php
session_start();
include ('functions.php');
include ('f_secure.php');
include ('body.php');
include ('config.php');

//Get file name for navigation
$currentFile = $_SERVER["SCRIPT_NAME"]; 
$parts = explode('/', $currentFile); 
$currentFile = $parts[count($parts) - 1];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title><?php echo "$title"; ?></title>
<meta name="MSSmartTagsPreventParsing" content="TRUE" />
<link rel="stylesheet" type="text/css" href="<?php echo "$directory"; ?>/review.css" />
<?php
 if (isset($description)) {
//put $description = "text here"; on each page you want to have a custom description.
echo "<meta name=\"description\" content=\"$description\" />";
} else {
?>
<meta name="Description" content="This Amazon-style review script allows users to rank a product or item on a scale of 1-5 stars and make comments related to the product for other users to read.  Email notification will allow the administrator to approve comments before being added to the website.  Admin panel allows the following:  Approve a Review before it is listed, Delete a Review, Delete all Unapproved Reviews and Add Item for Review.  Can be used to rate books, videos, music, websites....the sky's the limit!  Fast mysql backend.  One template file controls the entire site/script layout." />
<?php } ?>
<meta name="Keywords" content="<?php if ($keywords) { echo "$keywords,"; } ?> review script, php review script, five star review, product review script, amazon review script, rating, php, amazon" />
<meta name="ROBOTS" content="ALL" />
<meta name="robots" content="follow" />
<meta name="resource-type" content="document" />
<meta name="revisit-after" content="30 days" />
<meta name="distribution" content="Global" />
<meta name="language" content="en-us" />
<meta http-equiv="pragma" content="no-cache" />
<link rel="alternate" type="application/rss+xml" title="Five Star Review Script" href="<?php echo "$url$directory"; ?>/rss/rss.xml" />
<link href="/review/default.css" rel="stylesheet" type="text/css" media="screen" />
</head>
<body>
<!-- start wrapper -->
<div id="wrapper">
  <!-- start menu -->
  <div id="menu">
    <ul>
      <li class="current_page_item"><a href="index.php">Home</a></li>
      <li><a href="#">Register</a></li>
      <li><a href="#">Login</a></li>
      <li><a href="#">UserCP</a></li>
      <li><a href="#">Contact</a></li>
            <li><a href="#">RSS</a></li>

    </ul>
  </div>
  <!-- end menu -->
  <!-- start header -->
  <div id="header">
    <div id="logo">
      <h1>Five Star</h1>
      <p>Review Script</p>
    </div>
    <div id="search">
      <form method="get" action="">
        <fieldset>
        <legend>Quick Search</legend>
        <input id="s" type="text" name="s" value="" />
        <input id="x" type="submit" value="Search" />
        </fieldset>
      </form>
    </div>
  </div>
  <!-- end header -->
  <!-- start page -->
  <div id="page">
    <div id="bgtop"></div>
    <div id="bgbottom">
      <!-- start content -->
      <div id="content">
        <div class="post">
          <h1 align="left" class="title"><?php echo "$title"; ?></h1>
          <p class="byline">&nbsp;</p>
          <div class="entry">
            <p>Header above footer below</p>
            </p>
            <br />
          </div>
        </div>
      </div>
      <!-- end content -->
      <!-- start sidebar two -->
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


<?php 
//Removing the PoweredBy link will disable your script as it is against the terms of your license.  Replace 101 with your own affiliate code.  Signup to become an affiliate here:  http://www.review-script.com/affiliates/index.php.  If you wish to remove the Powered By link, you can purchase that version of the script here...
@PoweredBy("101"); 
?>

</body>
</html>
