
//drag drop function for ie4+ and NS6////
/////////////////////////////////

function drag_drop(e){
if (ie4&&dragapproved){
crossobj.style.left=tempx+event.clientX-offsetx
crossobj.style.top=tempy+event.clientY-offsety
return false
}
else if (ns6&&dragapproved){
crossobj.style.left=tempx+e.clientX-offsetx
crossobj.style.top=tempy+e.clientY-offsety
return false
}
}

function initializedrag(e){
if (ie4&&event.srcElement.id=="reviewnote"||ns6&&e.target.id=="reviewnote"){
offsetx=ie4? event.clientX : e.clientX
offsety=ie4? event.clientY : e.clientY

tempx=parseInt(crossobj.style.left)
tempy=parseInt(crossobj.style.top)

dragapproved=true
document.onmousemove=drag_drop
}
}
document.onmousedown=initializedrag
document.onmouseup=new Function("dragapproved=false")

