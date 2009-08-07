// preload images
var cart_icon_0 = new Image();
var cart_icon_1 = new Image();
cart_icon_0.src = "/en/common/media/images/icons/icon_cart_off.gif";
cart_icon_1.src = "/en/common/media/images/icons/icon_cart_on.gif";

function getCartCookie() {
	var value = '';
	var allcookie = document.cookie;
	var pos = allcookie.indexOf('cart_items=');
	if (pos != -1) {
		var start = pos + 11;
		var end = allcookie.indexOf(';', start);
		if (end == -1) {
			end = allcookie.length;
		}
		value = allcookie.substring(start, end);
	}
	return unescape(value);
}

function setCartCookie(cookieString) {
	var today = new Date();
	var nextyear = new Date();
	nextyear.setFullYear(today.getFullYear() + 1);
	document.cookie = 'cart_items='+escape(cookieString)+'; path=/; domain=hilton.com; expires='+nextyear.toGMTString();
}

function getTodayDate() {
	var now = new Date();
	var theDay = now.getDate();
	var theMonth = now.getMonth() + 1;
	var theYear = now.getFullYear();
	if (theDay < 10) {
		theDay = '0'+theDay;
	}
	if (theMonth < 10) {
		theMonth = '0'+theMonth;
	}
	return theMonth+'/'+theDay+'/'+theYear;
}

function removeItem(cart, ctyhocn) {
	var returnValue = '';
	var items = cart.split('|');
	for(i = 0; i < items.length; i++) {
		if (items[i].indexOf(ctyhocn) == -1) {
			returnValue = returnValue + '|' + items[i];
		}
	}
	return returnValue.substring(1);
}

function removeAllItem() {
	setCartCookie('');
}

function disableAddToCart(ctyhocn) {
	var iconLink = document.getElementById('add_to_cart_icon_link_'+ctyhocn);
	var iconImg = document.getElementById('add_to_cart_icon_img_'+ctyhocn);
	var textLink = document.getElementById('add_to_cart_text_link_'+ctyhocn);
	var textTxt = document.getElementById('add_to_cart_text_txt_'+ctyhocn);
	if ( iconLink != null && iconImg != null && textLink != null && textTxt != null ) {
		iconLink.removeAttribute('href');
		iconImg.src = '/en/common/media/images/icons/icon_cart_off.gif';
		iconImg.alt = 'Added to My Favorite Hotels';
		textLink.removeAttribute('href');
		textTxt.innerHTML = 'Added to Favorite Hotels';
	}
}

function writeAddToCart(ctyhocn) {
	var cart = getCartCookie();
	document.write('<table width="166" height="30" border="0" cellspacing="0" cellpadding="0"><tr><td height="5" colspan="2"><img src="`brandImage("spacer.gif")`" width="1" height="1"></td></tr><tr>');
	if (cart.indexOf(ctyhocn) == -1) {
		document.write('<td width="26" align="left"><a id="add_to_cart_icon_link_'+ctyhocn+'" href="javascript:addToCart(\''+ctyhocn+'\',false)" class="addToCartLink"><img id="add_to_cart_icon_img_'+ctyhocn+'" src="/en/common/media/images/icons/icon_cart_on.gif" border="0" alt="Add to My Favorite Hotels"></a></td>');
		document.write('<td width="140" align="left" ><a id="add_to_cart_text_link_'+ctyhocn+'" href="javascript:addToCart(\''+ctyhocn+'\',false)" class="addToCartLink"><span id="add_to_cart_text_txt_'+ctyhocn+'" class="addToCartLink">Add to Favorite Hotels</span></a></td>');
	} else {
		document.write('<td width="26" align="left"><a id="add_to_cart_icon_link_'+ctyhocn+'" class="addToCartLink"><img name="add_to_cart_icon_img_'+ctyhocn+'" src="/en/common/media/images/icons/icon_cart_off.gif" border="0" alt="Added to My Favorite Hotels"></a></td>');
		document.write('<td width="140" align="left" title="Added to My Favorite Hotels"><a id="add_to_cart_text_link_'+ctyhocn+'" class="addToCartLink"><span id="add_to_cart_text_txt_'+ctyhocn+'" class="addToCartLink">Added to Favorite Hotels</span></a></td>');
	}
	document.write('</tr><tr><td height="5" colspan="2"><img src="`brandImage("spacer.gif")`" width="1" height="1"></td></tr></table>');
}

function writeHotelHomeAddToCart(ctyhocn) {
	var cart = getCartCookie();
	document.write('<table width="178" height="20" border="0" cellspacing="0" cellpadding="0"><tr>');
	if (cart.indexOf(ctyhocn) == -1) {
		document.write('<td width="26" align="center"><a id="add_to_cart_icon_link_'+ctyhocn+'" href="javascript:addToCart(\''+ctyhocn+'\',false)" class="hotelTopNav"><img id="add_to_cart_icon_img_'+ctyhocn+'" src="/en/common/media/images/icons/icon_cart_on.gif" border="0" alt="Add to My Favorite Hotels"></a></td>');
		document.write('<td width="150" align="center" ><a id="add_to_cart_text_link_'+ctyhocn+'" href="javascript:addToCart(\''+ctyhocn+'\',false)" class="hotelTopNav"><span id="add_to_cart_text_txt_'+ctyhocn+'" class="hotelTopNav">Add to Favorite Hotels</span></a></td>');
	} else {
		document.write('<td width="26" align="center"><a id="add_to_cart_icon_link_'+ctyhocn+'" class="hotelTopNav"><img id="add_to_cart_icon_img_'+ctyhocn+'" src="/en/common/media/images/icons/icon_cart_off.gif" border="0" alt="Added to My Favorite Hotels"></a></td>');
		document.write('<td width="150" align="center" title="Added to My Favorite Hotels"><a id="add_to_cart_text_link_'+ctyhocn+'" class="hotelTopNav"><span id="add_to_cart_text_txt_'+ctyhocn+'" class="hotelTopNav">Added to Favorite Hotels</span></a></td>');
	}
	document.write('<td width="2" class="hotelTopNavBG"><img src="/en/common/media/images/shim.gif"></td></tr><tr><td colspan="3" height="2" class="hotelTopNavBG"><img src="/en/common/media/images/shim.gif"></td></tr></table>');
}

function checkCookies(ctyhocn, isPop) {
	var cart = getCartCookie();
	if (cart.indexOf(ctyhocn) == -1) {
		alert('Please enable your cookies to use this feature.');
	} else {
		if (isPop == true) {
			alert('Hotel has been added to your list of Favorites.  You can click on "My Favorite Hotels" at any time to view your selections.');
		} else {
			disableAddToCart(ctyhocn);
		}
	}
}

function addToCookie(ctyhocn) {
	var oldCart = getCartCookie();
	var newCart = removeItem(oldCart, ctyhocn);
	if ( newCart.length > 0 ) {
		setCartCookie(newCart+'|'+ctyhocn+':'+getTodayDate());
	} else {
		setCartCookie(ctyhocn+':'+getTodayDate());
	}
}

function removeFromCookie(ctyhocn) {
	var oldCart = getCartCookie();
	var newCart = removeItem(oldCart, ctyhocn);
	setCartCookie(newCart);
}

//add to cart and display js popup message or replace the add to cart button
function addToCart(ctyhocn, ispop) {
	addToCookie(ctyhocn);
	checkCookies(ctyhocn, ispop);
}

//add to cart for Plansoft pages
function addToCartPop(url, ctyhocn) {
	var addToCartWin = window.open(url+'?ctyhocn='+ctyhocn,'pop','width=1,height=1,resizable=no,scrollbars=no,toolbar=no,left=2000,top=2000');
	alert('Hotel has been added to your list of Favorites. You can click on "My Favorite Hotels" at any time to view your selections.');
}

