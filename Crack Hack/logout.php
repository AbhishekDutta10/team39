<?php
session_start();
// Remove session variable
unset($_SESSION["logged_in"]);
unset($_SESSION["name"]);
unset($_SESSION["user_id"]);
// Destroy the session
session_destroy();
// Redirect to login page
header("Location: login.php");
exit();
?>
