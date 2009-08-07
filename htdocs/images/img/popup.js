function getWindowWidth() {
	winW = (ie) ? screen.width : window.outerWidth;
	return winW;
}

function getWindowHeight() {
	winH = (ie) ? screen.Availheight : window.outerHeight;
	return winH;
}

function popIt(theFile,windowW,windowH){
	bWidth = getWindowWidth();
	bHeight = getWindowHeight();
	bTop = (ie) ? 0 : this.window.screenY;
	bLeft = (ie) ? 0 : this.window.screenX;
	popLeft = ((bWidth - windowW)/2) + bLeft;
	popTop = ((bHeight - windowH)/2) + bTop;
	var popWindow = window.open(theFile,'pop','width='+windowW+',height='+windowH+',top='+popTop+',left='+popLeft+',scrollbars=no,resizable=no');
	popWindow.focus();
}

function popen(theFile,theWindow,theAttributes){
	var popWindow = window.open(theFile,theWindow,theAttributes);
	popWindow.focus();
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