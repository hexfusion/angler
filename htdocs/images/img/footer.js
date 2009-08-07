var to = false;
var flag = false;
function layerVis(id,dir) {
    if (document.layers) {
        v = (dir == "on") ? "show" : "hide";
        //document.layers[id].visibility = v;
    }
    else if (document.all) {
        v = (dir == "on") ? 'visible'	: 'hidden';
        document.all[id].style.visibility = v;
    }
    else if (document.getElementById) {
        v = (dir == "on") ? 'visible' : 'hidden';
        document.getElementById(id).style.visibility = v;
    }
	
	flag = (dir == "on") ? true : false;
	
	if(to) {clearTimeout(to)}
}

function onSubNav() {
	if(to) {clearTimeout(to)}
}

function offSubNav() {
	to = setTimeout("layerVis('aboutNav', 'off')", 2500)
}

function regSubNav(x) {
	if (flag) {
		if (x == "over"){onSubNav();}
		else if (x == "out"){offSubNav();}
	}
}