<?php
//Set the name of the Table, Database, Username and Password for Mysql.
$db_name = "test_site2";

//mysql database username
$mysql_username = "root";

//mysql database password
$mysql_password = "s1a2mayfly";

//mysql hostname - usually "localhost"
$mysql_host = "localhost";

//mysql port - usually "3306"
$mysql_port = "3306";


//Clean up data before displaying or inserting it.  DO NOT TOUCH THIS.
function clean($v) { 
  $v = stripslashes(trim("$v")); 
  $v = nl2br("$v"); 
  $v = htmlentities("$v"); 
  return $v; 
} 

function makeStringSafe($str){
   if (get_magic_quotes_gpc()) {
       $str = stripslashes("$str");
   }

   if (function_exists("mysql_real_escape_string") ) {
       	$str = "" . mysql_real_escape_string("$str") . "";
   }else{
   		$str=addslashes("$str");
   }
   return $str;

}


?>