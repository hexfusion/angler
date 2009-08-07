<?php
$type = $_POST['type'];

/* DATABASE LOGIN */
include ("../body.php");
include ("../functions.php");
include ("../f_secure.php");
include ("../config.php");

/* how many comments per page */
$entriesperpage	= 5;


if ($type == 1) {
	$page = $_POST['page'];
	
	// FIND TOTAL COUNT
	$queryStr = "SELECT * FROM review_comment ORDER BY id DESC";
	$result = mysql_query($queryStr) or die(mysql_error());
	$totalCount = mysql_num_rows($result);
	
	$numberToAdvance = ($page-1) * $entriesperpage;
	while ($numberToAdvance > 0) {
		$numberToAdvance--;
		$row = mysql_fetch_array($result);
	}
	
	$returnString = "$totalCount|";
	
	// PAGE BUILDER
	$pageHTML = buildPageLinks($page, $entriesperpage, $totalCount);
	$returnString .= "$pageHTML|";
	
	$tempPerPage = $entriesperpage;
	while ($tempPerPage > 0) {
		$tempPerPage--;
		$row = mysql_fetch_array($result);
		$returnString .= $row['author'].",".$row['time'].",".$row['comment']."|";
	}
	echo $returnString;
}
else if ($type == 2) {
	$comment = $_POST['comment'];
    $comment = cut_words( "$comment" , 35, 2 ); 
	$author = $_POST['author'];
	$review_id = $_POST['review_id'];
	$queryStr = "INSERT INTO review_comment (comment,review_id,author,time) VALUES ('" . mysql_real_escape_string($comment) . "','" . mysql_real_escape_string($review_id) . "','" . mysql_real_escape_string($author) . "', NOW())";
	mysql_query($queryStr) or die(mysql_error());
	echo "1";
}

/* FUNCTIONS */
function buildPageLinks($pageNumber, $perPage, $totalCount) {
	if ($totalCount > 0)
	$htmlText = "Page ";
	// GET TOTAL PAGES
	$totalPages = intval($totalCount / $perPage);
	if ($totalCount % $perPage != 0)
		$totalPages++;
	//PREVIOUS
	if ($pageNumber > 1)
	$htmlText .= " ".buildAHREF("#","Previous Page", "process_page(".($pageNumber-1).")")." ";

	for ($i = 1; $i <= $totalPages; $i++) {
		if ($i == $pageNumber)
		$htmlText .= " $i ";
		else
		$htmlText .= " ".buildAHREF("#",$i, "process_page(".$i.")")." ";
	}
	//NEXT
	if ($pageNumber < $totalPages)
	$htmlText .= " ".buildAHREF("#","Next Page", "process_page(".($pageNumber+1).")")." ";
	return $htmlText;
}
function buildAHREF($href, $content, $click = '', $id = '') {
	return "<a href=\"$href\" id=\"$id\" onClick=\"$click\">$content</a>";
}


//if some idiot enters a word longer than 75 characters, break it up to fit on screen.
function cut_words( $txt , $limit, $html_nl = 0 )
{
   $str_nl = $html_nl == 1 ? "<br />" : ( $html_nl == 2 ? "<br />" : "\n" );
   $pseudo_words = explode( ' ',$txt );
   $txt = '';
   foreach( $pseudo_words as $v )
   {
       if( ( $tmp_len = strlen( $v ) ) > $limit )
       {
           $final_nl = is_int( $tmp_len / $limit );
           $txt .= chunk_split( $v, $limit, $str_nl );
           if( !$final_nl )
               $txt = substr( $txt, 0, - strlen( $str_nl ) );
           $txt .= ' ';
       }
       else
           $txt .= $v . ' ';
   }
   return substr( $txt, 0 , -1 );
}
?>