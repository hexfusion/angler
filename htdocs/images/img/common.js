//PLEASE DO NOT USE THIS FILE ANY LONGER. LOOK IN /en/common/js/ FOR THIS FILE AND USE IT INSTEAD

// globals #######################################
ie = document.all ? true : false;
dom = document.getElementById ? true : false;

divleft = 0;
divtop = 0;
lastTab = 0;

// ################################################

function refreshIE(){
	if (lastTab && switchTab(lastTab)){ 
		switchTab(lastTab);
	}
	setMenus();
}

function isNS408(){  //NS4.08 reloads forever and ever... we don't want that
	if (navigator.appName.indexOf('Netscape') != -1){
		if ((navigator.appVersion.indexOf('4.08') == -1)){
		document.location.reload();
		}
	}
}
function rePos() {
	ie ? refreshIE() : isNS408();
}
window.onresize = rePos;

function isNumber(x) {
	var value = parseFloat(x);
	if (isNaN(value)) return false;
	return true;
}

function openTwoThirdsWindow(page) {
  var myWidth = 0, myHeight = 0;
  if( typeof( window.innerWidth ) == 'number' ) {
    //Non-IE
    myWidth = window.innerWidth;
    myHeight = window.innerHeight;
  } else {
    if( document.documentElement &&
        ( document.documentElement.clientWidth || document.documentElement.clientHeight ) ) {
      //IE 6+ in 'standards compliant mode'
      myWidth = document.documentElement.clientWidth;
      myHeight = document.documentElement.clientHeight;
    } else {
      if( document.body && ( document.body.clientWidth || document.body.clientHeight ) ) {
        //IE 4 compatible
        myWidth = document.body.clientWidth;
        myHeight = document.body.clientHeight;
      }
    }
  }
myWidth = (myWidth * 2) / 3;
myHeight = (myHeight * 2) / 3;
window.open( page, "siteUse", "width="+myWidth+",height="+myHeight+",scrollbars=yes,resizable=yes" );
}

function setMenus(){}
function setPosition(){}
