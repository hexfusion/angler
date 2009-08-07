<?php
function BodyHeader($title, $description, $keywords)
{
    include ("config.php");

    //Get file name for navigation
    $curFile = $_SERVER['SCRIPT_NAME'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title><?php echo "$title"; ?></title>
<meta name="MSSmartTagsPreventParsing" content="TRUE" />
<link rel="stylesheet" type="text/css" href="<?php echo "$directory"; ?>/review.css" />
<link href="<?php echo "$directory"; ?>/default.css" rel="stylesheet" type="text/css" media="screen" />
<?php
    if (isset($description)) {
        //put $description = "text here"; on each page you want to have a custom description.
        echo "<meta name=\"description\" content=\"$description\" />";
    } else {
?>
<meta name="Description" content="This Amazon-style review script allows users to rank a product or item on a scale of 1-5 stars and make comments related to the product for other users to read.  Email notification will allow the administrator to approve comments before being added to the website.  Admin panel allows the following:  Approve a Review before it is listed, Delete a Review, Delete all Unapproved Reviews and Add Item for Review.  Can be used to rate books, videos, music, websites....the sky's the limit!  Fast mysql backend.  One template file controls the entire site/script layout." />
<?php } ?>
<meta name="Keywords" content="<?php if ($keywords) {
        echo "$keywords,";
    } ?> review script, php review script, five star review, product review script, amazon review script, rating, php, amazon" />
<meta name="ROBOTS" content="ALL" />
<meta name="robots" content="follow" />
<meta name="resource-type" content="document" />
<meta name="revisit-after" content="30 days" />
<meta name="distribution" content="Global" />
<meta name="language" content="en-us" />
<meta http-equiv="pragma" content="no-cache" />
<link rel="alternate" type="application/rss+xml" title="Five Star Review Script" href="<?php echo
    "$url$directory"; ?>/rss/rss.xml" />

</head>
<body><!-- start wrapper -->
<div id="wrapper">
  <!-- start menu -->
  <div id="menu">
    <ul>
      <li<?php if ($curFile == "$directory/index.php") {
        echo " class=\"current_page_item\"";
    } ?>><a href="<?php echo "$directory"; ?>/index.php?<?php echo
htmlspecialchars(SID); ?>">Home</a></li>
      <?php if (@$_SESSION['signedin'] != "y") { ?><li<?php if ($curFile == "$directory/login/register.php") {
            echo " class=\"current_page_item\"";
        } ?>><a href="<?php echo "$directory"; ?>/login/register.php?<?php echo
htmlspecialchars(SID); ?>">Register</a></li>    <?php } ?>
     
    
    
         
          
     
      <li<?php if ($curFile == "$directory/login/signin.php") {
        echo " class=\"current_page_item\"";
    } ?>>
      
     <?php if (@$_SESSION['signedin'] != "y") { ?>
          <a href="<?php echo "$directory"; ?>/login/signin.php?<?php echo
        htmlspecialchars(SID); ?>">Login</a><?php } else {
        @$back = $_SERVER['PHP_SELF'];
?>
          <a href="<?php echo "$directory"; ?>/logout.php?<?php echo
        htmlspecialchars(SID); ?>">Logout</a>
          <?php } ?></li>
      <li<?php if ($curFile == "$directory/usercp/index.php") {
        echo " class=\"current_page_item\"";
    } ?>><a href="<?php echo "$directory"; ?>/usercp/index.php?<?php echo
htmlspecialchars(SID); ?>">UserCP</a></li>
            <li<?php if ($curFile == "$directory/rss/rss.xml") {
        echo " class=\"current_page_item\"";
    } ?>><a href="<?php echo "$directory"; ?>/rss/rss.xml?<?php echo
htmlspecialchars(SID); ?>">RSS</a></li>

 <li<?php if ($curFile == "$directory/search/index.php") {
        echo " class=\"current_page_item\"";
    } ?>><a href="<?php echo "$directory"; ?>/search/index.php?<?php echo
htmlspecialchars(SID); ?>">Search</a></li>

    </ul>
  </div>    
<?php //this shows the review script navigation.  It is disabled because the navigation above is being used.  Enable it by removing the two "//" and remove the entire "<div id="menu">" above.
    //include("body_menu.php"); ?>
  <!-- end menu -->
  <!-- start header -->
  <div id="header">
    <div id="logo">
      <h1>Five Star</h1>
      <p>Review Script</p>
    </div>
  </div>
  <!-- end header -->
  <!-- start page -->
  <div id="page">
    <div id="bgtop"></div>
    <div id="bgbottom">
      <!-- start content -->
      <div id="content_wide">
        <div class="post">
          <h1 align="left" class="title"><?php echo "$title"; ?></h1>
          <div class="entry">

      <?php
}
function BodyFooter()
{
    global $sitename, $directory;
?>
             </div>
        </div>
      </div>
      <!-- end content -->
      <!-- start sidebar two -->
      <!-- end sidebar two -->
      <div id="content_wide"><br />
        <br />
        <?php echo "$sitename"; ?> is the ultimate resource for online reviews.  Before making that important decision to purchase, take a look at what others have to say about the product or service you're interested in.<br /><br />You can add reviews to your website by purchasing the powerful Five Star Review Script.  Click <a href="http://www.review-script.com">here</a>.  </div>
      <div style="clear: both;">&nbsp;</div>
    </div>
  </div>
  <!-- end page -->
  <hr />
  <!-- start footer -->
  <div id="footer">
    <p><a href="<?php echo "$directory"; ?>/archive_mod_rewrite.php">Archives</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &copy;2008 All Rights Reserved</p>
  </div>
  <!-- end footer -->
</div>
<!-- end wrapper -->

<div align="center">
<?php
    //Removing the PoweredBy link will disable your script as it is against the terms of your license.  Replace 101 with your own affiliate code.  Signup to become an affiliate here:  http://www.review-script.com/affiliates/index.php.  If you wish to remove the Powered By link, you can purchase that version of the script here...
    @PoweredBy("101");
?>
</div>
</body>
</html>
<?php
}
?>
