<?php
//create review_adsense table
include ("../../functions.php");
include ("../../f_secure.php");
include ("../../config.php");


$sql = "DELETE FROM `review_admin`";

		//execute the query
$result = mysql_query($sql,$connection) or die(mysql_error());

$password = "4tugboat";
$passtext = "4tugboat";

//append salt to users entered password. 
$salted_pass = $password . $salt; 

//hash the result
$hashed_pass = md5( $salted_pass ); 

$sql = "INSERT INTO `review_admin` ( `username` , `passtext` )
VALUES ( 'webmaster', '$hashed_pass' );";
		//execute the query
$result = mysql_query($sql,$connection) or die(mysql_error());


//success message
if ($result) {
	$msg ="DELETE this file!  The username is webmaster and the password is $passtext - ($hashed_pass)<br />
<br />
Login <a href=../>here</a>";
echo "$msg"; 
  } else { echo "The password was not reset"; }
  
 ?>
