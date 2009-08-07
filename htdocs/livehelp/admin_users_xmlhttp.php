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

$timeof = date("YmdHis");

//
// Start initial var setup
//
if(empty($UNTRUSTED['action'])){ $UNTRUSTED['action'] = ""; }
if(empty($UNTRUSTED['clearchannel'])){ $UNTRUSTED['clearchannel'] = "N"; }
// comma deliminated list of people to ignore chat requests for..
if(empty($UNTRUSTED['ignorelist'])){ $UNTRUSTED['ignorelist'] = "0"; }
// comma deliminated list of operators that we are talking to.
if(empty($UNTRUSTED['operators'])){ $UNTRUSTED['operators'] = "0"; }
// setting for refresh rate 
if(empty($UNTRUSTED['autoinvite'])){ $UNTRUSTED['autoinvite'] = ""; }
if(empty($UNTRUSTED['togglerefresh'])){ $UNTRUSTED['togglerefresh'] = ""; }
if(empty($UNTRUSTED['hidevisitors'])){ $UNTRUSTED['hidevisitors']=""; }
if(empty($UNTRUSTED['needaction'])){ $UNTRUSTED['needaction']=0; }
$stuffatbottom = "";
$DIVS = "";
 
if(!(empty($UNTRUSTED['togglerefresh']))){
  switch($CSLH_Config['admin_refresh']){
    case "auto":
        $CSLH_Config['admin_refresh'] = "10";
        break;
    case "10":
        $CSLH_Config['admin_refresh'] = "20";
        break;
    case "20":
        $CSLH_Config['admin_refresh'] = "30";
        break;
    case "30":
        $CSLH_Config['admin_refresh'] = "auto";
        break;        
    default;    
  	$CSLH_Config['admin_refresh'] = "auto";
  }	
  $query = "UPDATE livehelp_config set admin_refresh ='".$CSLH_Config['admin_refresh']."'";
  $mydatabase->query($query);
}
 
// peoplestring is a sting of the current users online it is used 
// to compair with the live people online to determine if we should 
// reload the page or not basicly a bandwith saver.
  $peoplestring = "users";
  if(empty($UNTRUSTED['hidevisitors']))
    $query = "SELECT * FROM livehelp_users ORDER by user_id DESC";
  else
    $query = "SELECT * FROM livehelp_users WHERE status='chat' ORDER by user_id DESC";      
  
  $visitors = $mydatabase->query($query);
  while( $visitor = $visitors->fetchRow(DB_FETCHMODE_ASSOC)){
    $visitor_string = $visitor['sessionid'] . $visitor['status'];
    $user = "_" . ereg_Replace("([^a-zA-Z0-9])*", "", $visitor_string);
    $peoplestring .= str_replace(" ","",$user);
  }  
  
  $query = "SELECT * FROM livehelp_operator_channels ORDER by user_id DESC";        
  $visitors = $mydatabase->query($query);
  while( $visitor = $visitors->fetchRow(DB_FETCHMODE_ASSOC)){
    $visitor_string = $visitor['user_id'];
    $peoplestring .= str_replace(" ","",$user);
  } 
 
// List of people we are ignoring:
$ignorelist_array = array();
$ignorelist = $UNTRUSTED['ignorelist'];
if(!(empty($UNTRUSTED['ignorelist']))){
  $ignorelist_array = split(",",$UNTRUSTED['ignorelist']);  
}


// List of operators that I know I am talking to:
$operators_array = array();
if(!(empty($UNTRUSTED['operators']))){
  $operators_array = split(",",$UNTRUSTED['operators']);  
} 



if($UNTRUSTED['autoinvite']=="ON"){
  $query = "UPDATE livehelp_users set auto_invite='Y' WHERE sessionid='".$identity['SESSIONID']."'";
  $mydatabase->query($query);
}

if($UNTRUSTED['autoinvite']=="OFF"){
  $query = "UPDATE livehelp_users set auto_invite='N' WHERE sessionid='".$identity['SESSIONID']."'";
  $mydatabase->query($query);
}
 


  $query = "SELECT * FROM livehelp_users WHERE sessionid='".$identity['SESSIONID']."'";	
  $people = $mydatabase->query($query);
  $people = $people->fetchRow(DB_FETCHMODE_ASSOC);
  $myid = $people['user_id'];
  $channel = $people['onchannel'];
  $show_arrival = $people['show_arrival']; 
  $user_alert = $people['user_alert'];
  $auto_invite = $people['auto_invite'];
  $greeting = $people['greeting'];
  $photo = $people['photo'];
  if(!(empty($photo)))
    $greeting ="<table><tr><td><img src=$photo></td><td>$greeting</td></tr></table>";


    
?>
<SCRIPT type="text/javascript">
function seepages(id){
  url = 'details.php?id=' + id;
  window.open(url, 'chat54050872', 'width=555,height=320,menubar=no,scrollbars=1,resizable=1');
}
<?php

 // see if any new operators are talking to me:  
 // select out all of the operators that are online and create an array of operators talking to me
 $query = "SELECT * FROM livehelp_users WHERE isoperator='Y' AND isonline='Y'";
 $operators_online = $mydatabase->query($query);
 $operators_talking = array();
 while($operator = $operators_online->fetchRow(DB_FETCHMODE_ASSOC)){
      $commonchannel = 0;
      $query = "SELECT * FROM livehelp_operator_channels WHERE user_id='".$operator['user_id']."'";
      $mychannels = $mydatabase->query($query);
      while($rowof = $mychannels->fetchRow(DB_FETCHMODE_ASSOC)){
         $query = "SELECT * FROM livehelp_operator_channels WHERE user_id=".intval($myid). " And channel='".$rowof['channel']."'";
         $counting = $mydatabase->query($query);        
         if($counting->numrows() != 0){ 
             array_push($operators_talking,$operator['user_id']);
         }
      }  
 } 
 
 // see if I do not know about one of them:
 for($k=0;$k< count($operators_talking); $k++){
     if(!(in_array($operators_talking[$k],$operators_array))){
      //  print " window.parent.bottomof.location.replace(\"admin_chat_bot.php\"); \n";   
      //  print " window.parent.connection.location.replace(\"admin_connect.php\"); \n";                
     }
 }

 // if they do not know about me
 for($k=0;$k< count($operators_array); $k++){
     if(!(in_array($operators_array[$k],$operators_talking))){
       // print " window.parent.bottomof.location.replace(\"admin_chat_bot.php\"); \n";   
       // print " window.parent.connection.location.replace(\"admin_connect.php\"); \n";                
     }
 }

// re-buld list:
$operators ="";
 for($k=0;$k< count($operators_talking); $k++){
   if($k==0)
     $operators = $operators_talking[$k];
   else
    $operators .= "," . $operators_talking[$k];
 }



 
 // re-buld list:
 $operators2 = "";
 for($k=0;$k< count($operators_array); $k++){
   if($k==0)
     $operators2 = $operators_array[$k];
   else
    $operators2 .= "," . $operators_array[$k];
 }
 
?>
</SCRIPT>
<?php
// activiate chatting with the user.. 
if($UNTRUSTED['action'] == "activiate"){

  // see if anyone is chatting with this person. 
  $query = "SELECT * FROM livehelp_operator_channels WHERE userid=" . intval($UNTRUSTED['who']);
  $counting = $mydatabase->query($query);
  // if someone is chatting with them ask if we would like to join...
  if( ($counting->numrows() != 0) && ($UNTRUSTED['needaction'] ==1)){
   print "another operator has already answered this request. ";   
  } else {
  
  // see if anyone is chatting with this person. 
  $query = "SELECT * FROM livehelp_operator_channels WHERE userid=" . intval($UNTRUSTED['who']);
  $counting = $mydatabase->query($query);
  if($counting->numrows() == 0){       
   // get session data and post it as a message if not one
   $query = "SELECT * FROM livehelp_users WHERE user_id=" . intval($UNTRUSTED['who']);
   $userdata = $mydatabase->query($query);
   $user_row = $userdata->fetchRow(DB_FETCHMODE_ASSOC);
   $sessiondata = $user_row['sessiondata'];
   $datapairs = explode("&",$sessiondata);
   $datamessage="";
   for($l=0;$l<count($datapairs);$l++){
  	  $dataset = explode("=",$datapairs[$l]);
  	  if(!(empty($dataset[1]))){
  	  	$fieldid = str_replace("field_","",$dataset[0]);
  	  	$query = "SELECT * FROM livehelp_questions WHERE id=".intval($fieldid);
  	  	$questiondata = $mydatabase->query($query);
        $question_row = $questiondata->fetchRow(DB_FETCHMODE_ASSOC);    	  
    	  $datamessage.= $question_row['headertext'] . "<br><font color=000000><b>" . urldecode($dataset[1]) . "</font></b><br>";
      }
   }
   if($datamessage!=""){
  	 $timeof++;
  	 $query = "INSERT INTO livehelp_messages (saidto,saidfrom,message,channel,timeof) VALUES (".intval($myid).",".intval($UNTRUSTED['who']).",'<br>".filter_sql($datamessage)."',".intval($UNTRUSTED['whatchannel']).",'$timeof')";	
     $mydatabase->query($query);
   }
  }

  if( (empty($whatchannel)) || ($whatchannel == 0) ){
    $whatchannel = createchannel($UNTRUSTED['who']);
  }

   // generate random Hex..
    $txtcolor = "";
    $lowletters = array("0","2","4","6");
    for ($index = 1; $index <= 6; $index++) {
       $randomindex = rand(0,3); 
       $txtcolor .= $lowletters[$randomindex];
    }	 
           
  $query = "DELETE FROM livehelp_operator_channels WHERE user_id=".intval($myid)." AND userid=" . intval($UNTRUSTED['who']);	
  $mydatabase->query($query);  
  $channelcolor = get_next_channelcolor();  
  $query = "INSERT INTO livehelp_operator_channels (user_id,channel,userid,txtcolor,channelcolor) VALUES (".intval($myid).",".intval($whatchannel).",".intval($UNTRUSTED['who']).",'$txtcolor','$channelcolor')";	
  $mydatabase->query($query);
  // add to history:
   $query = "INSERT INTO livehelp_operator_history (opid,action,dateof,channel,totaltime) VALUES ($myid,'startchat','".date("YmdHis")."',".intval($whatchannel).",0)";
   $mydatabase->query($query);
  if (!(empty($UNTRUSTED['conferencein']))){
    $channelcolor = get_next_channelcolor();  
    $query = "INSERT INTO livehelp_operator_channels (user_id,channel,userid,txtcolor,channelcolor) VALUES (".intval($UNTRUSTED['who']).",".intval($whatchannel).",".intval($myid).",'$txtcolor','$channelcolor')";	
    $mydatabase->query($query);    
  }
  $timeof = date("YmdHis");
  $query = "INSERT INTO livehelp_messages (saidto,saidfrom,message,channel,timeof) VALUES (".intval($UNTRUSTED['who']).",".intval($myid).",'".filter_sql($greeting)."',".intval($whatchannel).",'$timeof')";	
  $mydatabase->query($query);
  

  $channelsplit = $whatchannel . "__" . $UNTRUSTED['who'];

  $query = "SELECT * FROM livehelp_users WHERE isoperator='Y' AND isonline='Y'";
  $operators_online = $mydatabase->query($query);
  $operators_talking = array();
  while($operator = $operators_online->fetchRow(DB_FETCHMODE_ASSOC)){
      $commonchannel = 0;
      $query = "SELECT * 
                FROM livehelp_operator_channels 
                WHERE user_id=".intval($operator['user_id']);
      $mychannels = $mydatabase->query($query);
      while($rowof = $mychannels->fetchRow(DB_FETCHMODE_ASSOC)){
         $query = "SELECT * 
                   FROM livehelp_operator_channels 
                   WHERE user_id=".intval($myid)." And channel=".$rowof['channel'];
         $counting = $mydatabase->query($query);        
         if($counting->numrows() != 0){ 
             array_push($operators_talking,$operator['user_id']);
         }
      }  
   } 
  
  // re-buld list:
 for($k=0;$k< count($operators_talking); $k++){
   if($k==0)
     $operators = $operators_talking[$k];
   else
    $operators .= "," . $operators_talking[$k];
  }
 }
}
?>
<HEAD>
<META http-equiv="pragma" CONTENT="no-cache"> 
<?php
if($CSLH_Config['admin_refresh'] != "auto"){
?>
<META HTTP-EQUIV="REFRESH" content="<?php echo $CSLH_Config['admin_refresh']; ?>;URL=admin_users_refresh.php?ignorelist=<?php echo $ignorelist; ?>&operators=<?php echo $operators; ?>">
<?php } ?>
<META HTTP-EQUIV="EXPIRES" CONTENT="Sat, 01 Jan 2001 00:00:00 GMT">
<link title="new" rel="stylesheet" href="style.css" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $lang['charset']; ?>" />
</HEAD>

<?php
$html_head = "
<SCRIPT type="text/javascript">

var ismac = navigator.platform.indexOf('Mac');
//var ismac =1;

//-----------------------------------------------------------------
// Update the control image. This is the image that the operators 
// use to communitate with the visitor. 
function csgetimage()
{	 
	imageloaded = 1;
	 // set a number to identify this page .
	 csID=Math.round(Math.random()*9999);
	 randu=Math.round(Math.random()*9999);
   cscontrol = new Image;
      	 
	 var u = 'admin_image.php?randu=' + randu + '&hidevisitors=".$UNTRUSTED['hidevisitors']."&what=usercheck' + '&peoplestring_test=' + '$peoplestring'
	 cscontrol.src = u;
	 
	 	if (ismac > -1){
       document.getElementById(\"imageformac\").src= u;
       document.getElementById(\"imageformac\").onload = lookatimage;
    }   
    else {
       cscontrol.src= u; 
       cscontrol.onload = lookatimage;
    }   
   setTimeout('lookatimage()', 2700);
      
}

function lookatimage(){
	
	if(typeof(cscontrol) == 'undefined' ){
		setTimeout('refreshit()',9000); 
    return; 
 }   
			 
	 if (ismac > -1)
      w = document.getElementById(\"imageformac\").width;
   else
      w = cscontrol.width; 
        	 
      	 if(w == 55){
           delete cscontrol;
           imageloaded = 0; 	
  	       refreshit();
	        } 	        	            
        delete cscontrol;            
        imageloaded = 0;      
 
} 
	
csTimeout = 299; 
imageloaded = 0;
cscontrol = new Image;

";

if($CSLH_Config['admin_refresh'] == "auto"){  
$html_head .= " setInterval('csgetimage()', 4000); \n";
$stuffatbottom .= "
<SCRIPT type="text/javascript">
if (ismac > -1) {
	randu=Math.round(Math.random()*9999);
  document.write('<img id=\"imageformac\" name=\"imageformac\" src=\"'  +
  'admin_image.php?randu=' + randu + 
  '&hidevisitors=".$UNTRUSTED['hidevisitors']."&what=usercheck' + 
  '&peoplestring_test=' +  
  '$peoplestring' +
  '\" border=\"0\">');
 }
</SCRIPT>
";
}

$html_head .= "

function updatepeople(refreshtime){
	document.admin_actions.submit();
	if(refreshtime ==1)
    setTimeout(\"refreshit();\",7920);
  else
    setTimeout(\"refreshit();\",2920);  
}

function refreshit(){
 window.location.replace(\"admin_users_refresh.php?ignorelist=".$UNTRUSTED['ignorelist']."&operators=".$UNTRUSTED['operators']."&hidevisitors=".$UNTRUSTED['hidevisitors']."\");
}	
  
  setTimeout(\"refreshit();\",99920);

 
";

if($UNTRUSTED['action'] == "stop"){
  $sqlquery = "SELECT * FROM livehelp_users WHERE user_id=".intval($UNTRUSTED['who']);	
  $rs = $mydatabase->query($sqlquery);  
  $person = $rs->fetchRow(DB_FETCHMODE_ASSOC);
  $onchannel = $person['onchannel'];
  $sessionid = $person['sessionid'];
  stopchat($sessionid);
  $UNTRUSTED['action'] = "leave";
}

// leave the channel or user..
if($UNTRUSTED['action'] == "leave"){
  if($clearchannel=="Y"){
  $query = "DELETE FROM livehelp_operator_channels WHERE channel=". intval($whatchannel);	
  $mydatabase->query($query);  
  }
  $query = "DELETE FROM livehelp_operator_channels WHERE user_id=".intval($myid)." AND channel=".intval($whatchannel)." AND userid=".intval($UNTRUSTED['who']);	
  $mydatabase->query($query);    
  $html_head .= " window.parent.bottomof.location.replace(\"admin_chat_bot.php\");";
}

if($UNTRUSTED['action'] == "activiate"){
	if(empty($channelsplit)) $channelsplit = "";
  $html_head .= " window.parent.bottomof.location.replace(\"admin_chat_bot.php?channelsplit=$channelsplit\");";
  $html_head .= " window.parent.connection.location.replace(\"admin_connect.php?sleep=2\");";

}

$html_head .= "

function tellme(){
 if(window.parent.bottomof.loaded){
  window.parent.bottomof.shouldifocus();
 }
";

if($user_alert == "Y"){ 
$html_head .= "  alert(\"".$lang['txt139']."\"); ";
} 

$html_head .= " }

function doorbell(){   
";
if($show_arrival != "N"){
  $html_head .= " if(window.parent.bottomof.loaded) { window.parent.bottomof.shouldifocus(); }";
  if($user_alert == "Y"){ 
     $html_head .= "  alert(\"".$lang['txt140']."\"); ";	
  }
}
$html_head .= "
}

function seepages(id){
  url = 'details.php?id=' + id;
  window.open(url, 'chat54050872', 'width=590,height=350,menubar=no,scrollbars=1,resizable=1');
}
</SCRIPT>
";

print $html_head;
?>
<script type="text/javascript">
 
ns4 = (document.layers)? 1 : 0;
ie4 = (document.all)? 1 : 0;

bluecount = 0
redcount = 0

 
ns4 = (document.layers)? true:false;
ie4 = (document.all)? true:false;
 
 
ready = true;

NS4 = (document.layers) ? 1 : 0;
IE4 = (document.all) ? 1 : 0;
W3C = (document.getElementById) ? 1 : 0;	
// W3C stands for the W3C standard, implemented in Mozilla (and Netscape 6) and IE5

// Function show(evt, name)
//	evt is a pointer to the Event object passed when the event occurs
//	name is the ID attribute of the element to show
function show ( evt, name ) {
  if (IE4) {
   evt = window.event;  //is it necessary?
  }

  var currentX,		//mouse position on X axis
      currentY,		//mouse position on X axis
      x,		//layer target position on X axis
      y,		//layer target position on Y axis
      docWidth,		//width of current frame
      docHeight,	//height of current frame
      layerWidth,	//width of popup layer
      layerHeight,	//height of popup layer
      ele;		//points to the popup element

  // First let's initialize our variables
  if ( W3C ) {
    ele = document.getElementById(name);
    currentX = evt.clientX,
    currentY = evt.clientY;
    docWidth = document.width;
    docHeight = document.height;
    layerWidth = ele.style.width;
    layerHeight = ele.style.height;

  } else if ( NS4 ) {
    ele = document.layers[name];
    currentX = evt.pageX,
    currentY = evt.pageY;
    docWidth = document.width;
    docHeight = document.height;
    layerWidth = ele.clip.width;
    layerHeight = ele.clip.height;

  } else {	// meant for IE4
    ele = document.all[name];
    currentX = evt.clientX,
    currentY = evt.clientY;
    docHeight = document.body.offsetHeight;
    docWidth = document.body.offsetWidth;
    //var layerWidth = document.all[name].offsetWidth;
    // for some reason, this doesn't seem to work... so set it to 200
    layerWidth = 200;
    layerHeight = ele.offsetHeight;
  }

  // Then we calculate the popup element's new position
  if ( ( currentX + layerWidth ) > docWidth ) {
    x = ( currentX - layerWidth );
  }
  else {
    x = currentX;
  }
  if ( ( currentY + layerHeight ) >= docHeight ) {
     y = ( currentY - layerHeight - 20 );
  }
  else {
    y = currentY + 20;
  }
  if ( IE4 ) {
    x += document.body.scrollLeft;
    y += document.body.scrollTop;
  } else if ( NS4)  {
  } else {
    x += window.pageXOffset;
    y += window.pageYOffset;
  }
// (for debugging purpose) alert("docWidth " + docWidth + ", docHeight " + docHeight + "\nlayerWidth " + layerWidth + ", layerHeight " + layerHeight + "\ncurrentX " + currentX + ", currentY " + currentY + "\nx " + x + ", y " + y);
  x = 5;
  // Finally, we set its position and visibility
  if ( NS4 ) {
    //ele.xpos = parseInt ( x );
    ele.left = parseInt ( x );
    //ele.ypos = parseInt ( y );
    ele.top = parseInt ( y );
    ele.visibility = "show";
  } else {  // IE4 & W3C
    ele.style.left = parseInt ( x );
    ele.style.top = parseInt ( y );
    ele.style.visibility = "visible";
  }
}

function showit ( name ) {
  if (W3C) {
    document.getElementById(name).style.visibility = "visible";
  } else if (NS4) {
    document.layers[name].visibility = "show";
  } else {
    document.all[name].style.visibility = "visible";
  }
}

function hide ( name ) {
  if (W3C) {
    document.getElementById(name).style.visibility = "hidden";
  } else if (NS4) {
    document.layers[name].visibility = "hide";
  } else {
    document.all[name].style.visibility = "hidden";
  }
}

function unhide ( name ) {
  if (W3C) {
    document.getElementById(name).style.visibility = "visible";
  } else if (NS4) {
    document.layers[name].visibility = "show";
  } else {
    document.all[name].style.visibility = "visible";
  }
}
function ExecRes(textstring){

	//var chatelement = document.getElementById('currentchat'); 
  
  //element.innerHTML = textstring;
}

/**
  * loads a XMLHTTP response into parseresponse
  *
  *@param string url to request
  *@see parseresponse()
  */ 
function update_xmlhttp() { 
     // account for cache..
	   randu=Math.round(Math.random()*9999);
     sURL = 'xmlhttp.php';
     sPostData = 'op=yes&whattodo=userslist&rand='+ randu;
     PostForm(sURL, sPostData)     
} 
setInterval('update_xmlhttp()',3000);
 
</SCRIPT>
<script src="javascript/xmlhttp.js" type="text/javascript"></script> 
</HEAD>

<body bgcolor=<?php echo $color_background;?>>
<center>
<table bgcolor=<?php echo $color_background;?> cellpadding=0 cellspacing=0 border=0 width=280>
<tr>
<td bgcolor=000000><img src=images/blank.gif width=1 height=1></td>
<td bgcolor=000000><img src=images/blank.gif width=278 height=1></td>
<td bgcolor=000000><img src=images/blank.gif width=1 height=1></td>
</tr>
<tr>
<td bgcolor=000000><img src=images/blank.gif width=1 height=1></td>
<td>

<table bgcolor=EEEEEE width=100%><tr><td  bgcolor=EEEEEE align=center>
<?php
$query = "SELECT * FROM livehelp_config";
$data = $mydatabase->query($query);
$data = $data->fetchRow(DB_FETCHMODE_ASSOC);
$offset = $data['offset'];
if($offset == ""){ $offset = 0; }
$when = mktime ( date("H")+$offset, date("i"), date("s"), date("m") , date("d"), date("Y") );
?>
<?php echo date("F j, Y, g:i a",$when); ?>
</td></tr></table>
</td>
<td bgcolor=000000><img src=images/blank.gif width=1 height=1></td>
</tr>
<tr>
<td bgcolor=000000><img src=images/blank.gif width=1 height=1></td>
<td bgcolor=000000><img src=images/blank.gif width=278 height=1></td>
<td bgcolor=000000><img src=images/blank.gif width=1 height=1></td>
</tr>
<tr>
<td bgcolor=000000><img src=images/blank.gif width=1 height=1></td>
<td>
<FORM method=post action=admin_actions.php Target=_Blank name=admin_actions>
<table width=100% bgcolor=D4DCF2><tr><td>&nbsp;</td><td><b> Chatting Users </b></td></tr></table>
</td>
<td bgcolor=000000><img src=images/blank.gif width=1 height=1></td></tr>
<tr>
  <td bgcolor=000000><img src=images/blank.gif width=1 height=1></td>
  <td>
     <span id="currentchatters"> </span>  
  </td>
<td bgcolor=000000><img src=images/blank.gif width=1 height=1></td>
</tr>

<tr>
<td bgcolor=000000><img src=images/blank.gif width=1 height=1></td>
<td><DIV ID="chattersdiv" STYLE="z-index: 2;"><table><tr><td><img src=images/arrow_ltr.png width=38 height=22><select name=whattodochat onchange=updatepeople(1)><option value="">with selected:</option><option value=stop>Stop Chat</option><option value=transfer>transfer Chat</option></select> <a href=javascript:updatepeople(1)><img src=images/go.gif border=0 width=20 height=20></a></td></tr></table></DIV></td>
<td bgcolor=000000><img src=images/blank.gif width=1 height=1></td>
</tr>

<tr>
<td bgcolor=000000><img src=images/blank.gif width=1 height=1></td>
<td bgcolor=000000><img src=images/blank.gif width=278 height=1></td>
<td bgcolor=000000><img src=images/blank.gif width=1 height=1></td>
</tr>
<tr>
<td bgcolor=000000><img src=images/blank.gif width=1 height=1></td>
<td>
     <span id="currentvisitors"> </span>
</td>
<td bgcolor=000000><img src=images/blank.gif width=1 height=1></td>

<tr>
<td bgcolor=000000><img src=images/blank.gif width=1 height=1></td>
<td><DIV ID="visitorsdiv" STYLE="z-index: 2;"><table><tr><td><img src=images/arrow_ltr.png width=38 height=22><select name=whattodo onchange=updatepeople(2)><option value="">with selected:</option><option value=DHTML>Invite: Layer</option><option value=pop>Invite: Pop-up</option></select> <a href=javascript:updatepeople(2)><img src=images/go.gif border=0 width=20 height=20></a></td></tr></table></DIV></td>
<td bgcolor=000000><img src=images/blank.gif width=1 height=1></td>
</tr>     

<tr>
<td bgcolor=000000><img src=images/blank.gif width=1 height=1></td>
<td bgcolor=000000><img src=images/blank.gif width=278 height=1></td>
<td bgcolor=000000><img src=images/blank.gif width=1 height=1></td>
</tr>

</table>

</FORM>
</td>
<td bgcolor=000000><img src=images/blank.gif width=1 height=1></td>
</tr>
<tr>
<td bgcolor=000000><img src=images/blank.gif width=1 height=1></td>
<td bgcolor=000000><img src=images/blank.gif width=278 height=1></td>
<td bgcolor=000000><img src=images/blank.gif width=1 height=1></td>
</tr>
</table>
<SCRIPT type="text/javascript">
function open_url(url){
  window.open(url, 'chat545087', 'width=590,height=400,menubar=no,scrollbars=1,resizable=1'); 
}
</SCRIPT><br>
<!-- auto invite and refresh rate: -->
<table bgcolor=<?php echo $color_background;?> cellpadding=0 cellspacing=0 border=0 width=280>
<tr>
<td bgcolor=000000><img src=images/blank.gif width=1 height=1></td>
<td bgcolor=000000><img src=images/blank.gif width=278 height=1></td>
<td bgcolor=000000><img src=images/blank.gif width=1 height=1></td>
</tr>
<tr>
<td bgcolor=000000><img src=images/blank.gif width=1 height=1></td>
<td bgcolor=FEFEFE Align=center>
<?php echo $lang['txt138']; ?>: <font color=007777><b><?php
if($CSLH_Config['admin_refresh'] == "30"){ print "30 " . $lang['Seconds']; }
if($CSLH_Config['admin_refresh'] == "20"){ print "20 ". $lang['Seconds']; }
if($CSLH_Config['admin_refresh'] == "10"){ print "10 ". $lang['Seconds']; }
if($CSLH_Config['admin_refresh'] == "auto"){ print $lang['Automatic']; }
?>
</b></font><br> (<a href=admin_users_refresh.php?ignorelist=<?php echo $ignorelist; ?>&operators=<?php echo $operators; ?>&togglerefresh=1&hidevisitors=<?php print $UNTRUSTED['hidevisitors']; ?>><?php echo $lang['Change']; ?></a> | <a href=admin_users_refresh.php?ignorelist=<?php echo $ignorelist; ?>&operators=<?php echo $operators; ?>&hidevisitors=<?php print $UNTRUSTED['hidevisitors']; ?>><?php echo $lang['txt131']; ?></a>)<br> 
<?php
if($auto_invite =="Y"){
  print $lang['txt132'] . ": <font color=007700><b>".$lang['ON']."</b></font><br> (<a href=admin_users_refresh.php?ignorelist=$ignorelist&operators=$operators&autoinvite=OFF&hidevisitors=".$UNTRUSTED['hidevisitors']." onclick=\"javascript:open_url('autoinvite.php');\">".$lang['MakeOFF']."</a> <b>|</b> <a href=autoinvite.php target=_blank>".$lang['txt133']."</a>)";
} else {
  print $lang['txt132'] . ": <font color=000077><b>".$lang['OFF']."</b></font><br> (<a href=admin_users_refresh.php?ignorelist=$ignorelist&operators=$operators&autoinvite=ON&hidevisitors=".$UNTRUSTED['hidevisitors']." onclick=\"javascript:open_url('autoinvite.php');\">".$lang['MakeON']."</a> <b>|</b> <a href=autoinvite.php target=_blank>".$lang['txt133']."</a>)";
}

?>  
</td>
<td bgcolor=000000><img src=images/blank.gif width=1 height=1></td>
</tr>
<tr>
<td bgcolor=000000><img src=images/blank.gif width=1 height=1></td>
<td bgcolor=000000><img src=images/blank.gif width=278 height=1></td>
<td bgcolor=000000><img src=images/blank.gif width=1 height=1></td>
</tr>
</table>

</center>

<SCRIPT type="text/javascript">
 <?php echo $onload; ?>
</SCRIPT>
 
<?php
if(!($serversession))
  $mydatabase->close_connect();
?>