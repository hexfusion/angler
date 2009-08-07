/* -------------------------------------------------------------
THE AJAX comments
Written by Sean Fannan

If you would like to use this comments, please
leave these comments here.  If you need to contact me
for any reason, my email address is sfannan@gmail.com.  Enjoy
---------------------------------------------------------------*/

/* CONSTANTS */
var phpFileName = 'functions_comments.php';

// Key function to send Ajax packet to php file.
function sendPacket(fileName, strQuery, functionName) {
		var myConn = new XHConn();
		if (!myConn) 
			alert("XMLHTTP not available. Try a newer/better browser.");
		myConn.connect(fileName, "POST", strQuery, functionName);
}
// toggles the comment form from visible to hidden
function showPost(linkElement, swap) {
	var formElement = document.getElementById('postcomment');
	if (swap) {
		linkElement.innerHTML = 'Hide Comment';
		linkElement.onclick = function () { showPost(this, 0); };
		formElement.style.display = 'block';
	}
	else {
		linkElement.innerHTML = 'Post Comment';
		linkElement.onclick = function () { showPost(this, 1); };
		formElement.style.display = 'none';
	}
}
/* Verifies the form has no missing fields */
function verify_new() {
var comment = document.getElementById('comment');
var author = document.getElementById('author');
var review_id = document.getElementById('review_id');
if (comment.value.length == 0 || author.value.length == 0)
	alert("There are empty fields");
else
	prepare_new(comment, author, review_id);
}
// Clears the form
function clear_new() {
var comment = document.getElementById('comment');
var author = document.getElementById('author');
comment.value = '';
author.value = '';
}

/* PREPARE FUNCTIONS */
// sends the request for all the entries on a given page
function prepare_entries() {
	document.getElementById('currentEntries').innerHTML = "loading . . .";
	var page = document.getElementById('page');
	strQuery = 'type=1&page='+page.value;
	var functionName = function(oXML) { process_entries(oXML); };
	sendPacket(phpFileName, strQuery, functionName);
}
// sends a request for inserting an entry into the database
function prepare_new(comment, author, review_id) {
	strQuery = 'type=2&comment='+comment.value+"&author="+author.value+"&review_id="+review_id.value;
	var functionName = function(oXML) { process_new(oXML); };
	sendPacket(phpFileName, strQuery, functionName);
	
	showPost(document.getElementById('postLink'), 0);
	clear_new();
}
/* PROCESS FUNCTIONS */
// processes the incoming entries from a given page
function process_entries(oXML) {
	var response = oXML.responseText.split("|");
	var entries = new Array();
	var count = response[0];
	var pageHTML = response[1];
	var strPageInfo = pageHTML;
	if (count > 0)
	strPageInfo += ' | Total Comments: ' + count;
	document.getElementById('pageInfo').innerHTML = strPageInfo;
	var htmlText = '<br>';//'<br>Total comments: '+count+'<br><br>';
	var currentEntries = document.getElementById('currentEntries');
	for (i = 2; i < response.length-1; i++) {
		entries = response[i].split(",");
		if (entries[0] != '') {
			htmlText += '<div class=\"comments_entry_comment\">'+entries[0]+'</div>';
			htmlText += '<div class=\"comments_entry_name\">'+entries[1]+'</div>';
			htmlText += '<div class=\"comments_entry_time\">'+entries[2]+'</div>';
		}
		htmlText += '<br>';
	}
	currentEntries.innerHTML = htmlText;
}
// processes a new entry, and eventually calls prepare_entries()
function process_new(oXML) {
	var response = oXML.responseText;
	if (response=='1')
		prepare_entries();
}
// retrieve entries for a new page
function process_page(page) {
	document.getElementById('page').value = page;
	prepare_entries();
}