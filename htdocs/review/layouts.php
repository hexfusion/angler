<?php
session_start();
include ('body.php');
include ('functions.php');
include ('f_secure.php');
include ('config.php');

BodyHeader("Layout Options - $sitename","Five Star Review Script Alternate Layouts","Review Script Alternate Layouts");
?>
<p>There are several layouts you can choose from:</p>
<p>1. <a href="demo.php?<?php echo htmlspecialchars(SID); ?>">Standard</a></p>
<p>2. <a href="review_categories.php?<?php echo htmlspecialchars(SID); ?>">Linear</a></p>
<p>3. <a href="review_categories_yahoo.php?<?php echo htmlspecialchars(SID); ?>">Yahoo Style </a></p>
<p>4. <a href="review_categories_yahoo_cats.php?<?php echo htmlspecialchars(SID); ?>">Yahoo Style 2 </a></p>
<p align="center">Back to <a href="./admin/admin_menu.php?<?php echo htmlspecialchars(SID); ?>">Menu</a></p>
<?php
BodyFooter();
exit;
?>

