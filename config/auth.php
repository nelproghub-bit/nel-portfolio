<?php
// config/auth.php
session_start();

// Get the base path - on Vercel this might be different
$base_path = $_ENV['BASE_PATH'] ?? '/nel-portfolio';

function check_auth() {
    global $base_path;
    if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
        header('Location: ' . $base_path . '/admin/login.php');
        exit;
    }
}

function check_auth_htmx() {
    global $base_path;
    if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
        header('HX-Redirect: ' . $base_path . '/admin/login.php');
        http_response_code(403);
        exit;
    }
}
?>
