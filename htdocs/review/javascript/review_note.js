var ns4=document.layers
var ie4=document.all
var ns6=document.getElementById &&!document.all

if (ns4)
crossobj=document.layers.reviewnote
else if (ie4||ns6)
crossobj=ns6? document.getElementById("reviewnote") : document.all.reviewnote


function close_review_note(){
if (ie4||ns6)
crossobj.style.visibility="hidden"
else if (ns4)
crossobj.visibility="hide"
}

function show_review_note(){
if (ie4||ns6)
crossobj.style.visibility="visible"
else if (ns4)
crossobj.visibility="show"
document.note.notearea.focus();
}

var original_length = document.note.elements['notearea'].value.length;
var has_changed = false;

function save_note(listing_url) {
  frm=document.note;
  new_length = frm.elements['notearea'].value.length;
  if(new_length == original_length && !has_changed) {
    close_review_note();
  }

  url=listing_url+"?action=savenote&user_name="+ frm.elements['user_name'].value +"&item_id=" + frm.elements['item_id'].value+ "&notearea="+escape(frm.elements['notearea'].value);
  if(xmlhttp) {
    xmlhttp.open("GET",url,true);
    xmlhttp.send(null)
    close_review_note();
    return false;
  }
  else {
    document.note.submit();
  }
}
function close_note() {
    close_review_note();
}
