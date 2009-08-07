/* function used for showing/hiding layers for tabs or dropdown menus - jkenney */

/*example

<style>
#div_hotel {position:relative; top: 0px; right: 0px; visibility:hidden; z-index:1}
</style>

<html>
<a href="#" onClick="toggleDiv('div_hotel', 1);">show hidden layer</a>
<div id="div_hotel">stuff</div>
</html>

*/

function toggleDiv(divID, divState) // 1 visible, 0 hidden
{
	//alert(divID + ":" + divState);
	var obj = document.getElementById(divID);
	
	if (obj) {
		obj.style.display = divState ? "" : "none";
	} else {
		//alert("unable to find element with id: " + divID);
	}
}