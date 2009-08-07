<?php
session_start();

//make sure user has been logged in.
$validU="";

if (isset($_SESSION['valid_user']) && $_SESSION['valid_user']!="")$validU=$_SESSION['valid_user']; 

if ($validU==""){
	// User not logged in, redirect to login page
	Header("Location: index.php");
}

include ("../body.php");
include ("../functions.php");
include ("../f_secure.php");
include ("../config.php");

if(isset($_GET['id']) && $_GET['id']!="" && isset($_GET['status']) &&($_GET['status']=="T" || $_GET['status']=="F")){
	$strId=0;
	$strId=addslashes(stripslashes($_GET['id']));
	$strStatus=$_GET['status'];
	if($strStatus=="F"){
		$strStatus="T";
	}else{
		$strStatus="F";
	}
	
	$query="UPDATE rating_criteria SET isActive='" . $strStatus ."' WHERE criteriaId=" .$strId;
	mysql_query($query) or die(mysql_error());
	$_SESSION['msg']="Record Updated";
	header("Location: admin_view_review_criteria.php");
	exit;		
}else{
	$_SESSION['msg']="Invalid Data";
	header("Location: admin_view_review_criteria.php");
	exit;			
}
?>
