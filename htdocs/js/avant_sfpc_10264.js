function delete_cookie ( cookie_name )
{
  var cookie_date = new Date ( );  // current date & time
  cookie_date.setTime ( cookie_date.getTime() - 1 );
  document.cookie = cookie_name += "=; expires=" + cookie_date.toGMTString();
}

var url = location.href;

if (url.indexOf("avad=") > 0) {
	var affdata;
	var merchant_id = 10264;
	var cookie_name = 'avant_' + merchant_id;
	var cookie_days = 60;
	var cookie_domain = '.westbranchangler.com';
	var aUrl = url.split("avad=");

	// Delete any existing cookie
	delete_cookie(cookie_name);

	// Parse out tracking data from the url
	if (aUrl[1].indexOf("&") > 0) {
		affdata = aUrl[1].substring(0, aUrl[1].indexOf("&"));
	}
	else {
		affdata = aUrl[1];
	}

	// Avant Direct Link Tracking
	if (url.indexOf("avdt=") > 0) {
		if (url.indexOf("ctc=") > 0) {
			var ctc = '';
			var aCtc = url.split("ctc=");
			var aAffData = affdata.split("_");
			var iCount = aAffData.length;

			if (iCount == 3) {
				affdata += '_0';
				iCount = 4;
			}

			if (iCount == 4) {
				if (aCtc[1].indexOf("&") > 0) { ctc = aCtc[1].substring(0, aCtc[1].indexOf("&")); }
				else { ctc = aCtc[1]; }

				if (ctc.length > 0) {
					if (affdata.lastIndexOf('_') == (affdata.length - 1)) { affdata += encodeURIComponent(ctc); }
					else { affdata += '_' + encodeURIComponent(ctc); }
				}
			}
		}

		var av_url = 'http://www.avantlink.com/click.php?mi=' + merchant_id + '&avad=' + affdata + '&refurl=' + encodeURIComponent(document.referrer);
		try { document.write('<s'+'cript language="javascript" src="'+av_url+'"></s'+'cript>'); }
		catch(e){}
	}

	var expdate = new Date();
	expdate.setTime(expdate.getTime() + cookie_days*24*60*60*1000);
	document.cookie = cookie_name + "=" + encodeURIComponent(affdata) + "; expires=" + expdate + "; path=/; domain=" + cookie_domain + ";";

}