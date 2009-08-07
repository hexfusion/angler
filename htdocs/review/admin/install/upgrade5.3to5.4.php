<?php
//upgrade from 5.0 to 5.1 review-script.com
include ("../../functions.php");
include ("../../f_secure.php");
include ("../../config.php");
include ("../../body.php");
error_reporting(E_ALL ^ E_NOTICE);
BodyHeader("Five Star Review Script Upgrade 5.0 to 5.1","","");
//make sure review script is there.
if (!$db_name) {
echo "Your functions.php file from the Five Star Review Script is not setup or available.  Please correct that and then return to this installation script.";
BodyFooter();
exit;
}
?>
<style type="text/css">
<!--
.style1 {
	font-size: 16px;
	font-family: Arial, Helvetica, sans-serif;
	font-weight: bold;
}
.style2 {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 14px;
}
.style4 {font-size: 16px; font-family: Arial, Helvetica, sans-serif; font-weight: bold; color: #FF0000; }
.style5 {color: #FF0000}
-->
</style><?php

if (@$_POST['step'] == "") { ?>

<p align="center" class="style1">Welcome to Five Star Review Script Upgrade 5.3 to 5.4</p>
<hr>
<p class="style2"> This installation script should make installing your script as painless as possible.</p>
<p class="style2"><strong>Step 1:</strong> Make sure you have uploaded all of the files contained in the downloaded zip file.</p>
<p class="style2">&nbsp;</p>
<p class="style2">Once Steps 1 has  been completed, please proceed to the next step.</p>
<form name="form1" method="post" action="upgrade5.3to5.4.php?step=2">
  <div align="right">
    <input name="step" type="hidden" id="step" value="2">
    <input type="submit" name="Submit" value="Next">
  </div>
</form>
<p>&nbsp;</p>
<?php 
BodyFooter();
exit;
} //end step = "" 

if (@$_POST['step'] == "2") { 

function run_query_batch($handle, $filename="")
{
  // --------------
  // Open SQL file.
  // --------------
  if (! ($fd = fopen($filename, "r")) ) {
    die("Failed to open $filename: " . mysql_error() . "<br />");
  }

  // --------------------------------------
  // Iterate through each line in the file.
  // --------------------------------------
  while (!feof($fd)) {

    // -------------------------
    // Read next line from file.
    // -------------------------
    $line = fgets($fd, 32768);
    $stmt = "$stmt$line";

    // -------------------------------------------------------------------
    // Semicolon indicates end of statement, keep adding to the statement.
    // until one is reached.
    // -------------------------------------------------------------------
    if (!preg_match("/;/", $stmt)) {
      continue;
    }

    // ----------------------------------------------
    // Remove semicolon and execute entire statement.
    // ----------------------------------------------
    $stmt = preg_replace("/;/", "", $stmt);

//echo "$stmt";
    // ----------------------
    // Execute the statement.
    // ----------------------
  //  mysql_query($stmt, $handle) ||
    //  die("Query failed: " . mysql_error() . "<br />");
	
	
	   @$sql_result3 = @mysql_query($stmt, $handle);
	  
	  
			
if(!sql_result3) 
{ 
  if(mysql_errno() == 1062) 
  { 
    $errors[] = 'Duplicate entry, proceeding to next record...<br />'; 
  } 
  if(mysql_errno() == 1060) 
  { 
    $errors[] = 'Duplicate column entry, proceeding to next record...<br />'; 
  } 
  if(mysql_errno() == 1064) 
  { 
    $errors[] = 'Table already exists, no problem, proceeding to next operation...<br />'; 
  } 
  else { 
	die(sprintf("Couldn't execute query, %s: %s", db_errno(), db_error()));

  } 
} 



    $stmt = "";
  }

  // ---------------
  // Close SQL file.
  // ---------------
  fclose($fd);
}

run_query_batch($connection, "upgrade_to_5.4.sql");

echo "Finished creating mysql database tables.<br /><br />";


$sql = "UPDATE review_admin SET 
	version = '5.4'";
	
        $sql_result = mysql_query($sql) 
            or die( "Couldn't execute update query."); 

	if (!$sql_result) {  
   
		echo "<P>Couldn't update version number in database!";

	}

//NOW show the form to fill out for the config.php variables.

?> </p>
	<span class="style4">REMOVE THIS FILE FROM YOUR SERVER FOR SECURITY PURPOSES.    </span>
    <p class="style2">
    
    <p class="style2 style5">Your script and database are now successfully upgraded.    
    <p class="style2 style5">Enjoy!! :). 

<p class="style2">&nbsp;</p>
<p align="center"><a href="http://www.review-script.com" target="_blank"><img src="http://www.review-script.com/images/banner.gif" border="0"></a></p>	
<?php	

BodyFooter();
} 
?>