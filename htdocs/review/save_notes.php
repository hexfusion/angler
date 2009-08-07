<?php
//session_start();
//$userName=@$_SESSION['username_logged'];
$userName=@$_GET['user_name'];
$notes=@$_GET['notearea'];
$itemId=@$_GET['item_id'];


if($userName!="" && $notes!="" && $itemId!=""){
	include ("functions.php");
	include ("f_secure.php");
	include ("config.php");

	$query="SELECT note_id FROM review_items_note WHERE note_user_name='$userName' AND note_item_id=$itemId";
	$result=mysql_query($query) or die(mysql_error());
	$notes=trim(addslashes($notes));			
	if(mysql_num_rows($result) > 0){
		//update
		$query=" UPDATE review_items_note SET note_notes='$notes',note_date=now() WHERE note_user_name='$userName' AND note_item_id=$itemId";
	}else{
		//insert
		$query=" INSERT INTO review_items_note(note_item_id,note_user_name,note_notes,note_date) VALUES($itemId,'$userName','$notes',now())";
	}	
	mysql_query($query) or die(mysql_error());
}
?>