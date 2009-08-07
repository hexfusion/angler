<?php 
function BodyHeader($title, $description, $keywords)
{
include ("config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
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
</head>
<body>
<table width="100%" align="center">
  <tr>
    <td width="100%"><?php include("body_menu.php"); ?>
      <br />
      <?php
}
function BodyFooter()
{
?>
    </td>
  </tr>
</table>
<br /><br />
<p> 
</p>
<?php 
//Removing the PoweredBy link will disable your script as it is against the terms of your license.  Replace 101 with your own affiliate code.  Signup to become an affiliate here:  http://www.review-script.com/affiliates/index.php.  If you wish to remove the Powered By link, you can purchase that version of the script here...
@PoweredBy("101"); 
?>

</body>
</html>
<?php
}
?>
