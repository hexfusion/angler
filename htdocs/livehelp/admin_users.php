<?php
//===========================================================================
//* --    ~~                Crafty Syntax Live Help                ~~    -- *
//===========================================================================
//           URL:   http://www.craftysyntax.com/    EMAIL: livehelp@craftysyntax.com
//         Copyright (C) 2003-2007 Eric Gerdes   (http://www.craftysyntax.com )
// ----------------------------------------------------------------------------
// Please check http://www.craftysyntax.com/ or REGISTER your program for updates
// --------------------------------------------------------------------------
// NOTICE: Do NOT remove the copyright and/or license information any files. 
//         doing so will automatically terminate your rights to use program.
//         If you change the program you MUST clause your changes and note
//         that the original program is Crafty Syntax Live help or you will 
//         also be terminating your rights to use program and any segment 
//         of it.        
// --------------------------------------------------------------------------
// LICENSE:
//     This program is free software; you can redistribute it and/or
//     modify it under the terms of the GNU General Public License
//     as published by the Free Software Foundation; 
//     This program is distributed in the hope that it will be useful,
//     but WITHOUT ANY WARRANTY; without even the implied warranty of
//     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//     GNU General Public License for more details.
//
//     You should have received a copy of the GNU General Public License
//     along with this program in a file named LICENSE.txt .
//===========================================================================
require_once("admin_common.php");
validate_session($identity);

// not working yet:
?>
<SCRIPT type="text/javascript">
function goingthere(){
 window.location.replace("admin_users_refresh.php");
}
setTimeout("goingthere();",1200);
</SCRIPT>
Loading...<br>
<br>
<a href="admin_users_refresh.php">click here</a> 
<?php
exit;

// the list of active users can be in either XML HTTP or refresh: but can NOT 
// be in flush() mode.. so unless the user has set not to use XML HTTP
// check XML HTTP and if fail use refresh:
if(!(eregi("xmlhttp",$CSLH_Config['chatmode'])))
  Header("Location: admin_users_refresh.php");

$success = "admin_users_xmlhttp.php";
$fail = "admin_users_refresh.php";
if(!($serversession))
  $mydatabase->close_connect();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Strict//EN"> 
<html> 
<head> 
<title>Detect XMLHTTP</title> 
<script src="javascript/xmlhttp.js" type="text/javascript"></script> 
<SCRIPT type="text/javascript">
function checkXMLHTTP(){
  if(XMLHTTP_supported){
    window.location.replace("<?php print $success; ?>");
  } else {   
    window.location.replace("<?php print $fail; ?>");
  }
}
setTimeout('loadXMLHTTP()', 500);
setTimeout('checkXMLHTTP()',4000);
</SCRIPT>
</HEAD>
<body> 
Checking XML HTTP support...       
</body> 