<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body>
<p>This
is a sample page.  By inserting special code (include &quot;review_insert.php&quot;;), I can make the reviews appear on my page below:</p>
<p>&nbsp;</p>

<?php
//if you're including this file in a directory other than the directory that review_insert.php is in, you must put the full path below or the script won't know where to find it.  Make sure you add ?item_id=1  (or whatever the item id is) to the url of the page that has the include statement in it.

//if the above line doesn't work, then put two slashes in front of it to disable it and then remove the two slashes from the beginning of the line below and insert your url.

//@include "http://www.rental-script.com/review/review_insert.php?item_id=5";


//CURL Method
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,'http://www.womens-self-defense.org/review/review_insert.php?item_id=1'.$_SERVER['QUERY_STRING']);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_exec($ch);
curl_close($ch);



?>

<p>&nbsp;</p>
<p>And this is the bottom of my original page. </p>
</body>
</html>
