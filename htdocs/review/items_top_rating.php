<?php
$show_num = 10;

//number of words to show of review
$show_words = 5;

//BodyHeader("Welcome to $sitename");
?>
<link href="<?php echo "$directory"; ?>/review.css" rel="stylesheet" type="text/css" />
<table width="85%" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td width="80" nowrap="nowrap"><div align="center"></div></td>
    <td nowrap="nowrap"><div align="left"></div></td>
    <td width="200" class="index2"><div align="center">Average Rating</div></td>
  </tr>
  <?php
		$query="select count(*) as re,sum(rating)/count(*) as rt,review_item_id,item_name, item_image, SUBSTRING_INDEX(item_desc, ' ', 10) as item_desc FROM review,review_items WHERE review.review_item_id=review_items.item_id group by review_item_id order by rt desc Limit $show_num";
		$result=mysql_query($query) or die(mysql_error);
		while($row_top=mysql_fetch_array($result)){
			?>
  <tr valign="middle">
    <td width="80" nowrap="nowrap"><div align="center">
        <?php
          //if there is an image, resize it and then display it.
$item_image = $row_top['item_image'];
	$filename = "images/items/$item_image";

	$ext = strrchr($filename, ".");
	if ($ext == ".mp3") {
		echo "<a href=$directory/$filename target=_blank ><img src=\"$directory/images/music.gif\" width=\"48\" height=\"48\" border=\"0\" title=\"Click here to listen to $item_name2\"><span class=small>Listen to $item_name2</span></a>";
	} elseif ($row_top['item_image'] != "") {
		?>
        <a class="thumbnail" href="#thumb"><img src="<?php echo "$directory/resize_item_image_rating.php?filename=$filename"; ?>" border="0" /><span><img src="<?php echo "$directory/$filename"; ?>" /> <?php echo "$item_name2"; ?></span></a> <br />
        <?php	} else { ?>
        <img src="<?php echo "$directory"; ?>/images/noimage80x80.gif" alt="No Image Available" height="80" width="80" border="0" /> <br />
        <?php } ?>
      </div></td>
    <td width="681" nowrap="nowrap"><div align="left"><a href="<?php echo "$directory"; ?>/review-item/<?php echo $row_top['review_item_id']; ?>.php?<?php echo htmlspecialchars(SID); ?>" class="style4"> <?php echo stripslashes($row_top['item_name']); ?> </a><span class="small">( <?php echo $row_top['re']; ?> Review<?php if($row_top['re'] >= 2) { echo "s"; } ?>
        ) <br /><?php echo stripslashes($row_top['item_desc']); ?>
</span></div></td>
    <td width="200">
        <?php 
					
					$totalTop=number_format($row_top['rt'],2,".",'');
					$display = ($totalTop/5)*100; ?>
                    
<div class="rating_bar">
  <div style="width:<?php echo "$display"; ?>%"></div>
</div>
  </td>
  </tr>
  <?php } ?>
</table>
