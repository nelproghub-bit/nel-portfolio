<?php
session_start();
session_destroy();
header('Location: /nel-portfolio/admin/login.php');
exit;
?>
