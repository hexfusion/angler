<?php
session_start();
session_unset();
session_destroy();
setcookie ("admin_username", "", time() - 3600);
setcookie ("admin_passtext", "", time() - 3600);
Header("Location: index.php");
?>