<?php //include("../config.php"); ?>
<div align="center">| <a href="<?php echo "$directory"; ?>/usercp/profile_edit1.php?<?php echo htmlspecialchars(SID); ?>">Edit About You</a> | <a href="<?php echo "$directory"; ?>/usercp/upload/upload.php?<?php echo htmlspecialchars(SID); ?>">Edit Photo</a> | <a href="<?php echo "$directory"; ?>/usercp/index.php?<?php echo htmlspecialchars(SID); ?>">User CP</a>  | <a href="<?php echo "$directory"; ?>/reviewer_about.php?username=<?php echo $_SESSION['username_logged']; ?>&amp;<?php echo htmlspecialchars(SID); ?>">View myProfile</a> |
<a href="<?php echo "$directory"; ?>/usercp/notes.php">Manage Notes</a>
</div>
