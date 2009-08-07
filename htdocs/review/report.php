<?php

$id = "";
$item_id = "";

include ('body.php');
include ('functions.php');
include ('f_secure.php');
include ('config.php');


$id = clean(makeStringSafe($_GET['id']));
$item_id = clean(makeStringSafe($_GET['item_id']));

$id = preg_replace("'<script[^>]*?>.*?</script>'si", "$errjava", $id);
$item_id = preg_replace("'<script[^>]*?>.*?</script>'si", "$errjava", $item_id);

//check user input and remove any reference to javascript.
$errjava = "<font color=red><BR><BR><B>No Javascript is allowed!  Please click edit and remove the offending code.<BR><BR></B></font>";


if (!is_numeric($id)) {
echo "stop id is $id";
exit;
}

if (!is_numeric($item_id)) {
echo "stop item_id";
exit;
}


BodyHeader("Report a Review - $sitename","","");
?>
<link href="review.css" rel="stylesheet" type="text/css" />

<table width="80%" border="0" align="center" cellpadding="0" cellspacing="0"><tr>
    <td><p>&nbsp;</p>
      <p align="center">If you feel this review contains inappropriate content type the following <br />
      verification numbers into the box below and then click Report:</p>
      <p align="center"><strong><?php echo date(dm); ?></strong></p>
      <form id="form1" name="form1" method="post" action="report2.php">
        <p align="center">
          <input name="verify" type="text" id="verify" maxlength="6" />
		  <input name="id" type="hidden" id="id" value="<?php echo "$id"; ?>" />
		    <input name="item_id" type="hidden" id="item_id" value="<?php echo "$item_id"; ?>" />
        </p>
        <p align="center">
          <input name="Report" type="submit" id="Report" value="Report" />
        </p>
      </form>
      <p>&nbsp;</p></td>
  </tr></table>



<?php
BodyFooter();
exit;
?>
