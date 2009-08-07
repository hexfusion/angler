<?php
//bbcode
$bbcode = array(//"<", ">",
                "[list]", "[*]", "[/list]", 
                "[img]", "[/img]", 
                "[b]", "[/b]", 
                "[u]", "[/u]", 
                "[i]", "[/i]",
                '[color="', "[/color]",
                "[size=\"", "[/size]",
                '[url="', "[/url]",
                "[mail=\"", "[/mail]",
                "[code]", "[/code]",
                "[quote]", "[/quote]",
                '"]');
$htmlcode = array(//"&lt;", "&gt;",
                "<ul>", "<li>", "</ul>", 
                "<img src=\"", "\">", 
                "<b>", "</b>", 
                "<u>", "</u>", 
                "<i>", "</i>",
                "<span style=\"color:", "</span>",
                "<span style=\"font-size:", "</span>",
                '<a target=_blank href="', "</a>",
                "<a href=\"mailto:", "</a>",
                "<code>", "</code>",
                "<table width=100% bgcolor=lightgray><tr><td bgcolor=white>Quote:  ", "</td></tr></table>",
                '">');

//  $review = str_replace($bbcode, $htmlcode, $review);


// $review = nl2br($review);//second pass
//$review = str_replace("<br />","", $review);

//this replaces the html format with a break.
$review = @str_replace("&lt;br /&gt;","<br />", $review);

?>