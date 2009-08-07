<?php
include ("../functions.php");
include ("../f_secure.php");
include ("../config.php");

$limit = 50;


$query = "select * from review_items WHERE item_name != '' order by item_id limit $limit";
$result = mysql_query($query);

while ($line = mysql_fetch_assoc($result))
        {
            $return[] = $line;
        }

$now = date("D, d M Y H:i:s T");

$output = "<?xml version=\"1.0\"?>
            <rss version=\"2.0\">
                <channel>
                    <title>$sitename</title>
                    <link>$url$directory/rss/rss.php</link>
                    <description>RSS feed for reviews at $sitename</description>
                    <language>en-us</language>
                    <pubDate>$now</pubDate>
                    <lastBuildDate>$now</lastBuildDate>
                    <managingEditor>$admin</managingEditor>
                    <webMaster>$admin</webMaster>
            ";
            
foreach ($return as $line)
{
    $output .= "<item><title>".htmlentities($line['item_name'])."</title>
                    <link>".$url."".$directory."/review-item/".htmlentities($line['item_id']). ".php"."</link>
                    
<description>".htmlentities(strip_tags($line['item_desc']))."</description>
                </item>";
}
$output .= "</channel></rss>";
header("Content-Type: application/rss+xml");
echo $output;
?>
