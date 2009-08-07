// JavaScript Document #28/11/2005

// here you have some variables to custumize..

var url="http://www.pedrogilberto.net";                          // Favorits 'url'
var title="PedroGilberto.net";                                         // Favorits 'title'
// customization end


// check browsers
var op = /opera 5|opera\/5/i.test(navigator.userAgent);
var ie = !op && /msie/i.test(navigator.userAgent);var mz = !op && /mozilla\/5/i.test(navigator.userAgent);
if (ie && document.getElementById == null) {
	document.getElementById = function(sId) {
		return document.all[sId];
	};
}


if (ie && window.attachEvent) {
	window.attachEvent('onload', function () {
		var scrollBorderColor	='#888888';
		var scrollfaceColor	='#aaaaaa';
		with (document.body.style) {		
	scrollbarDarkShadowColor	=scrollBorderColor;
	scrollbar3dLightColor		=scrollBorderColor;
	scrollbarArrowColor		='#666666';
	scrollbarBaseColor		=scrollfaceColor;
	scrollbarfaceColor		=scrollfaceColor;
	scrollbarHighlightColor	=scrollfaceColor;
	scrollbarShadowColor		=scrollfaceColor;
	scrollbarTrackColor		='#aaaaaa';
		}
	});
}
  


function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}

function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}

function fav()
{
if ((navigator.appName == "Microsoft Internet Explorer") && (parseInt(navigator.appVersion) >= 4)) {


window.external.AddFavorite(url,title)
}
else {
var msg = "Don't forget to bookmark us!";
if(navigator.appName == "Netscape") msg += "  (CTRL-D)";
document.write(msg);
}
}


function MM_setTextOfLayer(objName,x,newText) { //v4.01
  if ((obj=MM_findObj(objName))!=null) with (obj)
    if (document.layers) {document.write(unescape(newText)); document.close();}
    else innerHTML = unescape(newText);
}

function MM_showHideLayers() { //v6.0
  var i,p,v,obj,args=MM_showHideLayers.arguments;
  for (i=0; i<(args.length-2); i+=3) if ((obj=MM_findObj(args[i]))!=null) { v=args[i+2];
    if (obj.style) { obj=obj.style; v=(v=='show')?'visible':(v=='hide')?'hidden':v; }
    obj.visibility=v; }
}


function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}

function MM_reloadPage(init) {  //reloads the window if Nav4 resized
  if (init==true) with (navigator) {if ((appName=="Netscape")&&(parseInt(appVersion)==4)) {
    document.MM_pgW=innerWidth; document.MM_pgH=innerHeight; onresize=MM_reloadPage; }}
  else if (innerWidth!=document.MM_pgW || innerHeight!=document.MM_pgH) location.reload();
}
MM_reloadPage(true);

function MM_callJS(jsStr) { //v2.0
  return eval(jsStr)
}


function Dollar (val) {  // force to valid dollar amount
var str,pos,rnd=0;
  if (val < .995) rnd = 1;  // for old Netscape browsers
  str = escape (val*1.0 + 0.005001 + rnd);  // float, round, escape
  pos = str.indexOf (".");
  if (pos > 0) str = str.substring (rnd, pos + 3);
  return str;
}

function ReadForm (obj1) { // process un-named selects
var i,amt,des,obj,pos,val,num="";
  amt = obj1.baseamt.value*1.0;       // base amount
  des = obj1.basedes.value;           // base description
  for (i=0; i<obj1.length; i++) {     // run entire form
    obj = obj1.elements[i];           // a form element
    if (obj.type == "select-one" &&   // just get selects
        obj.name == "") {             // must be un-named
      pos = obj.selectedIndex;        // which option selected
      val = obj.options[pos].value;   // selected value
      pos  = val.indexOf ("@");       // price set?
      if (pos >= 0) amt = val.substring (pos + 1)*1.0;
      pos  = val.indexOf ("+");       // price increment?
      if (pos >= 0) amt = amt + val.substring (pos + 1)*1.0;
      pos  = val.indexOf ("%");       // percent change?
      if (pos >= 0) amt = amt + (amt * val.substring (pos + 1)/100.0);
      pos  = val.indexOf ("#");       // item number?
      if (pos > 0) {                  //  yes
        num = val.substring (pos + 1);// get number, and rest of line
        val = val.substring (0, pos); // lop off some stuff
        pos = num.indexOf (" ");      // end it with space
        if (pos > 0) num = num.substring (0, pos);
      }
      if (des.length == 0) des = val;
      else des = des + ", " + val;    // accumulate value
    }
  }
  if (obj1.item_number && num.length > 0) obj1.item_number.value = num;
  obj1.item_name.value = des;
  obj1.amount.value = Dollar (amt);
  if (obj1.tot) obj1.tot.value = "$" + Dollar (amt);
}


// Fade In Script by Louis St-Amour (CSpotkill@CSpotkill.com) based on code from clagnut.com
// Released under the GPL as a part of Gallery 2 (http://gallery.menalto.com/)

if (document.getElementById) {
    document.write("<style type='text/css'>.giThumbImage img {visibility:hidden;} #gsSingleImageId img {visibility:hidden;} </style>");
}

function start() {
    if (SidebarFound = document.getElementById('gsSidebar')) {
	DivColl = SidebarFound.getElementsByTagName('div')
	for (var t = 0; t < DivColl.length; t++) {
	    var divClass = DivColl[t].className;
	    if (divClass == 'giThumbImage') {
		var anchorCollTemp = DivColl[t].getElementsByTagName('a');
		var imageCollTemp = anchorCollTemp[0].getElementsByTagName('img');
		var theimage = imageCollTemp[0];
		theimage.id = 'gbSidebarImage';
		CheckIfComplete('gbSidebarImage',1);
	    }
	}
    }
    if (SingleImageDiv = document.getElementById('gsSingleImageId')) {
	var imageCollTemp = SingleImageDiv.getElementsByTagName('img');
	var theimage = imageCollTemp[0];
	theimage.id = 'gTheSingleImage';
	CheckIfComplete('gTheSingleImage',1);
    }
    if (ThumbMatrix = document.getElementById('gbThumbMatrix')) {
	rowColl = ThumbMatrix.getElementsByTagName('tr');
	var ImageNumber = 0;
	for (var r = 0; r < rowColl.length; r++) {
	    var itemColl = rowColl[r].getElementsByTagName('td');
	    for (var i = 0; i < itemColl.length; i++) {
		var itemClass = itemColl[i].className; 
		if (itemClass == 'gbItemImage' || itemClass == 'gbItemAlbum') {
		    ImageNumber++;
		    var div = itemColl[i].getElementsByTagName('div');
		    var images = div[0].getElementsByTagName('img');
		    var theimage = images[0];
		    theimage.id = 'gbImageNo' + ImageNumber;
		    CheckIfComplete('gbImageNo' + ImageNumber, ImageNumber);
		}
	    }
	}
    }
}

function CheckIfComplete(ImageId,ImageNumber) {
    ImageObj = document.getElementById(ImageId);
    if (ImageObj.complete == false) {
	window.setTimeout("CheckIfComplete('"+ImageId+"')", 100);
    } else {
	startFade(ImageId,ImageNumber);
    }
}

function startFade(imageId,ImageNumber) {
    var ImageFromId = document.getElementById(imageId);
    setOpacity(ImageFromId, 0);
    ImageFromId.style.visibility = 'visible';
    window.setTimeout("fadeIn('" + imageId + "', 0)", (ImageNumber*600));
}

function fadeIn(objId,opacity) {
    if (document.getElementById) {
	obj = document.getElementById(objId);
	if (opacity <= 100) {
	    setOpacity(obj, opacity);
	    opacity += 5;
	    window.setTimeout("fadeIn('"+objId+"',"+opacity+")", 100);
	}
    }
}

function setOpacity(obj, opacity) {
    opacity = (opacity == 100)?99.999:opacity;
    
    // IE/Win
    obj.style.filter = "alpha(opacity:"+opacity+")";
    
    // Safari<1.2, Konqueror
    obj.style.KHTMLOpacity = opacity/100;
    
    // Older Mozilla and Firefox
    obj.style.MozOpacity = opacity/100;
    
    // Safari 1.2, newer Firefox and Mozilla, CSS3
    obj.style.opacity = opacity/100;
}


var type = "IE";    //Variable used to hold the browser name

BrowserSniffer();

//detects the capabilities of the browser
function BrowserSniffer() {
   if (navigator.userAgent.indexOf("Opera")!=-1 && document.getElementById) type="OP";    
    //Opera
   else if (document.all) type="IE";                                                      
 //Internet Explorer e.g. IE4 upwards
   else if (document.layers) type="NN";                                                   
//Netscape Communicator 4
   else if (!document.all && document.getElementById) type="MO";                          
 //Mozila e.g. Netscape 6 upwards
   else type = "IE";        //I assume it will not get here
}

//Displays the generic browser type
function whatBrows() {
   window.alert("Browser is : " + type);
}

//Puts the contents of str into the layer id
//id is the name of the layer
//str is the required content
//Works with all browsers except Opera
function ChangeContent(id, str) {
   if (type=="IE") {
       document.all[id].innerHTML = str;
   }
   if (type=="NN") {
       document.layers[id].document.open();
       document.layers[id].document.write(str);
       document.layers[id].document.close();
   }
   if (type=="MO" || type=="OP") {
       document.getElementById(id).innerHTML = str;
   }
}

//Show and hide a layer
//id is the name of the layer
//action is either hidden or visible
//Seems to work with all versions NN4 plus other browsers
function ShowLayer(id, action){
   if (type=="IE") eval("document.all." + id + ".style.visibility='" + action + "'");
   if (type=="NN") eval("document." + id + ".visibility='" + action + "'");
   if (type=="MO" || type=="OP") eval("document.getElementById('" + id + 
"').style.visibility='" + action + "'");
}

function expand (){
               window.moveTo(0,0);
               window.resizeTo(screen.availWidth, screen.availHeight);
}




function nrcIE(){
if (document.all){return false;}}

function nrcNS(e){
if(document.layers||(document.getElementById&&!document.all)){ 
if (e.which==2||e.which==3){

return false;}}}

function rightdisable() { 
if (document.layers){
document.captureEvents(Event.MOUSEDOWN);
document.onmousedown=nrcNS;
}else{document.onmouseup=nrcNS;document.oncontextmenu=nrcIE;}
document.oncontextmenu=new Function("return false");
}

function rightalert() { 
if (document.layers){
document.captureEvents(Event.MOUSEDOWN);
document.onmousedown=nrcNS;
}else{document.onmouseup=nrcNS;document.oncontextmenu=nrcIE;}
document.oncontextmenu=new Function("alert(msgmouse); return false");
}


function rightoptions() { 
if (document.layers){
document.captureEvents(Event.MOUSEDOWN);
document.onmousedown=nrcNS;
}else{document.onmouseup=nrcNS;document.oncontextmenu=nrcIE;}
document.oncontextmenu=new Function("a=ShowLayer('actions','visible'); return false");
}



// Layer Move Script based on the example of Brainjar from www.brainjar.com/dhtml/drag

// Determine browser and version.
function Browser() {
var ua, s, i;

this.isIE    = false;
this.isNS    = false;
this.version = null;

ua = navigator.userAgent;

s = "MSIE";
if ((i = ua.indexOf(s)) >= 0) {
this.isIE = true;
this.version = parseFloat(ua.substr(i + s.length));
return;
}
s = "Netscape6/";
if ((i = ua.indexOf(s)) >= 0) {
this.isNS = true;
this.version = parseFloat(ua.substr(i + s.length));
return;
}
// Treat any other "Gecko" browser as NS 6.1.
s = "Gecko";
if ((i = ua.indexOf(s)) >= 0) {
this.isNS = true;
this.version = 6.1;
return;
}
}
var browser = new Browser();

// Global object to hold drag information.
var dragObj = new Object();
dragObj.zIndex = 0;

function dragStart(event, id) {
var el;
var x, y;

// If an element id was given, find it. Otherwise use the element being
// clicked on.
if (id)
dragObj.elNode = document.getElementById(id);
else {
if (browser.isIE)
dragObj.elNode = window.event.srcElement;
if (browser.isNS)
dragObj.elNode = event.target;
// If this is a text node, use its parent element.
if (dragObj.elNode.nodeType == 3)
dragObj.elNode = dragObj.elNode.parentNode;
}

// Get cursor position with respect to the page.
if (browser.isIE) {
x = window.event.clientX + document.documentElement.scrollLeft
+ document.body.scrollLeft;
y = window.event.clientY + document.documentElement.scrollTop
+ document.body.scrollTop;
}
if (browser.isNS) {
x = event.clientX + window.scrollX;
y = event.clientY + window.scrollY;
}

// Save starting positions of cursor and element.
dragObj.cursorStartX = x;
dragObj.cursorStartY = y;
dragObj.elStartLeft  = parseInt(dragObj.elNode.style.left, 10);
dragObj.elStartTop   = parseInt(dragObj.elNode.style.top,  10);

if (isNaN(dragObj.elStartLeft)) dragObj.elStartLeft = 0;
if (isNaN(dragObj.elStartTop))  dragObj.elStartTop  = 0;

// Update element's z-index.
dragObj.elNode.style.zIndex = ++dragObj.zIndex;

// Capture mousemove and mouseup events on the page.
if (browser.isIE) {
document.attachEvent("onmousemove", dragGo);
document.attachEvent("onmouseup",   dragStop);
window.event.cancelBubble = true;
window.event.returnValue = false;
}
if (browser.isNS) {
document.addEventListener("mousemove", dragGo,   true);
document.addEventListener("mouseup",   dragStop, true);
event.preventDefault();
}
}

function dragGo(event) {
var x, y;

// Get cursor position with respect to the page.
if (browser.isIE) {
x = window.event.clientX + document.documentElement.scrollLeft
+ document.body.scrollLeft;
y = window.event.clientY + document.documentElement.scrollTop
+ document.body.scrollTop;
}
if (browser.isNS) {
x = event.clientX + window.scrollX;
y = event.clientY + window.scrollY;
}

// Move drag element by the same amount the cursor has moved.
dragObj.elNode.style.left = (dragObj.elStartLeft + x - dragObj.cursorStartX) + "px";
dragObj.elNode.style.top  = (dragObj.elStartTop  + y - dragObj.cursorStartY) + "px";

if (browser.isIE) {
window.event.cancelBubble = true;
window.event.returnValue = false;
}
if (browser.isNS)
event.preventDefault();
}

function dragStop(event) {
// Stop capturing mousemove and mouseup events.
if (browser.isIE) {
document.detachEvent("onmousemove", dragGo);
document.detachEvent("onmouseup",   dragStop);
}
if (browser.isNS) {
document.removeEventListener("mousemove", dragGo,   true);
document.removeEventListener("mouseup",   dragStop, true);
}
}

function hmenu() {
MM_showHideLayers('helpPG1','','hide','helpPG2','','hide','helpPG3','','hide','helpPG4','','hide','helpPG5','','hide','helpPG6','','hide','helpPG7','','hide')
}

function openHelp() { newWin=window.open("themes/PGtheme/templates/helpPG.html","newWin","width=785,height=600,status=no,toolbar=no,menubar=no,scrollbars=yes,resizable=yes"); 
if(!newWin.opener) newWin.opener=self;
}