<?php
session_start();

include ('body.php');
include ('functions.php');
include ('f_secure.php');
include ('config.php');

BodyHeader("Review Guidelines - $sitename","$sitename Review Guidelines","review guidelines");
?>
<table width="80%" border="0" align="center" cellpadding="0" cellspacing="0"><tr>
    <td><p>&nbsp;</p>
      <p>Place your review guidelines here.</p>
      <p>&nbsp;</p></td>
  </tr></table>



<?php
BodyFooter();
exit;
?>
