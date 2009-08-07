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
require_once("security.php");
// if this is an operator op is set.   
if(!(empty($UNTRUSTED['op']))){
  require_once("admin_common.php");
  validate_session($identity);
} else {
  require_once("visitor_common.php");
}
$whatheader = "Content-type: text/html; charset=". $lang['charset'];
header($whatheader);

  $sqlquery = "SELECT user_id,onchannel,isnamed,username,jsrn FROM livehelp_users WHERE sessionid='".$identity['SESSIONID']."'";	
  $people = $mydatabase->query($sqlquery);
  $people = $people->fetchRow(DB_FETCHMODE_ORDERED);
  $myid = $people[0];
  $channel = $people[1];
  $isnamed = $people[2];
  $username = $people[3];
  $jsrn = $people[4];
  $see = "";
  $hide = "";
  
if(empty($UNTRUSTED['whattodo']))
  $whattodo = "";
  
if($UNTRUSTED['whattodo'] == "ping"){	  
 echo 'OK';
 exit;
}

if(!(empty($UNTRUSTED['see'])))
  $see = $UNTRUSTED['see'];
  
if(!(empty($UNTRUSTED['externalchats'])))
  $hide = $UNTRUSTED['externalchats'];  
  
if(ereg("cslhVISITOR",$identity['IDENTITY']))
  $see = $channel;


if($UNTRUSTED['whattodo'] == "wantstochat"){

	 //update last action:
   $mytimeof = date("YmdHis");
   $sqlquery = "UPDATE livehelp_users set lastaction='$mytimeof' WHERE sessionid='".$identity['SESSIONID']."'";	
   $mydatabase->query($sqlquery);
  
   // if they have timed out:
   if($UNTRUSTED['waitTimeout'] < $mytimeof){
     print "TIMEOUT";
     exit;	
  }

   // see if someone is talking to this user on this channel if so send to 
   // chat:
   $sqlquery = "SELECT channel FROM livehelp_operator_channels WHERE channel=" . intval($channel);
   $counting = $mydatabase->query($sqlquery);
   if( $counting->numrows() != 0)
     print "CONNECTED";
   else
     print "LIGHTS-ARE-ON-BUT-NOBODY-IS-HOME";  
  
  exit;
  
}

if($UNTRUSTED['whattodo'] == "messages"){	 
	
	// if noone is talking to this user then send exit layer:
	if (empty($UNTRUSTED['op'])){
   $sqlquery = "SELECT user_id FROM livehelp_users WHERE user_id=".intval($myid)." AND status='chat'";
   $alive = $mydatabase->query($sqlquery);
   if($alive->numrows() == 0){ 
   	   $aftertime = date("YmdHis");
       $string = "messages[0] = new Array(); messages[0][0]=$aftertime; messages[0][1]=$jsrn; messages[0][2]=\"EXIT\"; messages[0][3]=\"\"; messages[0][4]=\"\";"; 
       print $string;
       exit;
   }
	}
	$omitself = true;
	if(!(empty($UNTRUSTED['includeself'])))
	  $omitself = false;
	print showmessages($myid,"",$UNTRUSTED['HTML'],$see,$hide,true,$omitself);
	print showmessages($myid,"writediv",$UNTRUSTED['LAYER'],$see,$hide,true,$omitself);		
}
 
?> 