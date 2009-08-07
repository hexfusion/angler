<?php
session_start();
session_destroy();
setcookie("signedin", "", time() + 31536000, "/");
setcookie("registered", "", time() + 31536000, "/");
setcookie("username", "", time() + 31536000, "/");

Header("Location: index.php");
?>