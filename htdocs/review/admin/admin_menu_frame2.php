<?
session_start();
$validU="";

if (isset($_SESSION['valid_user']) && $_SESSION['valid_user']!="")$validU=$_SESSION['valid_user']; 

if ($validU=="")
	{
	// User not logged in, redirect to login page
	Header("Location: index.php");
	}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title><? echo "$title"; ?></title>
<style type="text/css">
<!--
.style2 {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	font-weight: bold;
}
.style4 {font-size: 12px}
.style5 {font-family: Arial, Helvetica, sans-serif}
.style6 {font-family: Arial, Helvetica, sans-serif; font-size: 14px; }
-->
</style>
</head>

<body>
<blockquote>
  <p><span class="style2">Reviews</span></p>
</blockquote>
<ul class="style4"><li class="style5"><a href="admin_approve1.php?<?=SID?>" target="mainFrame">Approve a Review</a>
  <li class="style5"><a href="admin_del1.php?<?=SID?>" target="mainFrame">Delete a Review</a>
  <li class="style5"><a href="admin_edit1.php?<?=SID?>" target="mainFrame">Edit a Review </a>
  <li class="style5"><a href="admin_sort.php?<?=SID?>" target="mainFrame">Select Display Order within Categories</a> <br>
  <li class="style5"><a href="admin_del_unapp1.php?<?=SID?>" target="mainFrame">Delete all Unapproved Reviews<br>
        <br>
    </a><strong>Items</strong>
  <li class="style5"><a href="admin_add1.php?<?=SID?>" target="mainFrame">Add Item for Review</a>
  <li class="style5"><a href="admin_del_item1.php?<?=SID?>" target="mainFrame">Delete Item for Review</a>
  <li class="style5"><a href="admin_edit_item1.php?<?=SID?>" target="mainFrame">Edit Item for Review</a>
  <li class="style5"><a href="admin_approve_item_user.php?<?=SID?>" target="mainFrame">Approve User Submitted Item</a>
  <li class="style5"><a href="admin_delete_item_user.php?<?=SID?>" target="mainFrame">Delete User Submitted Item</a> <br>
      <br>
      <strong>Categories</strong>
  <li class="style5"> <a href="admin_add_cat.php?<?=SID?>" target="mainFrame">Add a Category </a>
  <li class="style5"><a href="admin_del_cat.php?<?=SID?>" target="mainFrame">Delete a Category</a>
  <li class="style5"><a href="admin_edit_cat1.php?<?=SID?>" target="mainFrame">Edit a Category </a>
  <li class="style5"><a href="admin_sort_cat.php?<?=SID?>" target="mainFrame">Set Display Order for Categories </a><br>
      <BR>
      <strong>Miscellaneous</strong>
  <li class="style5"><a href="../demo.php?<?=SID?>" target="mainFrame">View Reviews</a>
  <li class="style5"><a href="admin_change_p.php?<?=SID?>" target="mainFrame">Change Password </a>
  <li class="style5"><a href="../layouts.php?<?=SID?>" target="mainFrame">Display Optional Layouts</a><br>
      <br>
  <li><span class="style5"><a href="logout.php?<?=SID?>" target="_parent">Logout</a></span>
  </ul>
<p align="center"><a href="http://www.review-script.com" target="_blank" class="style6">Review-Script.com</a></p>
</body>
</html>
