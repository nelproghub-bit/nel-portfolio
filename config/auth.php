<?php
// config/auth.php
session_start();

function check_auth() {
    if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
        header('Location: /nel-portfolio/admin/login.php');
        exit;
    }
}

function check_auth_htmx() {
    if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
        header('HX-Redirect: /nel-portfolio/admin/login.php');
        http_response_code(403);
        exit;
    }
}
?>
