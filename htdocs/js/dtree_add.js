//**************************************************
//  Trial Version
//
//
//  Deluxe Tree (c) 2006 - 2008, by Deluxe-Tree.com
//  version 3.2.5
//  http://deluxe-tree.com
//  E-mail:  support@deluxe-menu.com
//
//  ------
//  Obfuscated by Javascript Obfuscator
//  http://javascript-source.com

//
//**************************************************


function _tsc(){return[(isIEComp?docElement.scrollLeft:pageXOffset),(isIEComp?docElement.scrollTop:pageYOffset)]};function _tgc(iter,c,n){var dx=(n-c)/iter;with(Math)if(abs(dx)<1)dx=abs(dx)/dx;return c+((c!=n)?dx:0)+'px';};function _tsw(){if(tmoveRec.isMoving)return;for(var j=0;j<dtree_menu.ln();j++)with(dtree_menu[j]){if(floating&&absPos){var mObj=_toi(id+'div'),mXY=_tsx(mObj),pageOff=_tsc(),x=pageOff[0]+parseInt(left),y=pageOff[1]+parseInt(top);if(floatingX&&mXY[0]!=x)mObj.style.left=_tgc(tfloatIterations,mXY[0],x);if(floatingY&&mXY[1]!=y)mObj.style.top=_tgc(tfloatIterations,mXY[1],y);};};};var tuserMoveEvent=null;function _teOI(){return(tmoveRec.isMoving?false:true)};function _tee(e){if(isMAC&&isIE)e=window.event;with(e)return[(isIE||isOP)?clientX:pageX,(isIE||isOP)?clientY:pageY];};function _tm(){if(dtdo.attachEvent){dtdo.attachEvent('onmousemove',_tm2);if(isIE)dtdo.attachEvent("onselectstart",_teOI);}else{tuserMoveEvent=dtdo.onmousemove;dtdo.onmousemove=function(e){_tm2(e);if(tuserMoveEvent)tuserMoveEvent();return true;};};};function _tl1s(evnt,mInd){with(tmoveRec){if(!tisLoaded||isMoving)return;_tmv=dtree_menu[mInd];mObj=_toi(_tmv.id+'div');var mouseXY=_tee(evnt),mXY=_tsx(mObj),pOff=isIEComp?_tsc():[0,0];cX=mouseXY[0]-mXY[0]+pOff[0];cY=mouseXY[1]-mXY[1]+pOff[1];isMoving=1;};};function _tm2(event){with(tmoveRec)if(isMoving&&tisLoaded){var mouseXY=_tee(event),pOff=isIEComp?_tsc():[0,0],nX=mouseXY[0]-cX+pOff[0],nY=mouseXY[1]-cY+pOff[1];with(mObj.style){left=((nX>=0)?nX:0)+'px';top=((nY>=0)?nY:0)+'px';};};return true;};function _tl11(){with(tmoveRec){if(!tisLoaded||!isMoving)return;var pOff=_tsc(),mXY=_tsx(mObj);_tmv.left=mXY[0]-pOff[0];_tmv.top=mXY[1]-pOff[1];isMoving=0;};};
