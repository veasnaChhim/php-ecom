<?php
session_start();
session_unset();
session_destroy();
header("Location: shop.php"); // Redirect to the shop page
exit;
?>
