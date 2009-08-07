<?
//if a session does not yet exist for this user, start one
session_start();

//make sure user has been logged in.
$validU="";

if (isset($_SESSION['valid_user']) && $_SESSION['valid_user']!="")$validU=$_SESSION['valid_user']; 

if ($validU=="")
	{
	// User not logged in, redirect to login page
	Header("Location: index.php");
	}

include ("../body.php");
include ("../functions.php");
include ("../config.php");
BodyHeader("Administration");
?>

<frameset rows="*" cols="235,*" frameborder="NO" border="0" framespacing="0">
  <frame src="admin_menu_frame2.php?<?=SID?>" name="leftFrame" frameborder="no" scrolling="auto" noresize>
  <frameset rows="*,80" frameborder="NO" border="0" framespacing="0">
    <frame src="admin_menu_frame1.php?<?=SID?>" name="mainFrame">
    <frame src="admin_menu_frame3.php?<?=SID?>" name="bottomFrame" scrolling="NO" noresize>
  </frameset>
</frameset>
<noframes><body>
</body></noframes>
<?
BodyFooter();
?>
