<?php
session_start();
include ('../functions.php');
include ('../f_secure.php');
include ('../body.php');
include ('../config.php');
$username = @$_SESSION['username_logged'];

if (!$username) {
	BodyHeader("User Not Found!","","");
?>
	<link href="../review.css" rel="stylesheet" type="text/css">
	<P align="center">
	<P align="center"><span class="box1">Click to <a href=../login/register.php?item_id=$item_id&back=$back>Register</a> or if you've already registered, <a href=../login/signin.php?item_id=$item_id&back=$back>Login In</a>. </span>
	<P align="center"><br />
  <?
   BodyFooter();  
	exit;  
}else{
	$note_id="";
	if(isset($_GET['note_id'])&& $_GET['note_id']!=""){
		$note_id=$_GET['note_id'];
	}
	if($note_id!="" && is_numeric($note_id)){
		$query="SELECT note_id FROM review_items_note WHERE note_id=$note_id AND note_user_name='$username'";
		$result=mysql_query($query) or die(mysql_error());
		if(mysql_num_rows($result) > 0){
			$query=" DELETE FROM review_items_note WHERE note_id=$note_id";
			mysql_query($query) or die(mysql_error());
			$pg=1;
			if(isset($_GET['pg']) && $_GET['pg']!=""){
				$pg=$_GET['pg'];
			}
			header("Location: notes.php?pg=" . $pg);
		}
	}
	?>
<?
}
?>