<?php
// api/auth_handler.php
require_once __DIR__ . '/../config/db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        echo "<p class='text-red-500 text-sm mt-2'>Username and password are required.</p>";
        exit;
    }

    $stmt = $pdo->prepare("SELECT id, password_hash FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_id'] = $user['id'];
        // Tell HTMX to redirect
        header('HX-Redirect: /nel-portfolio/admin/index.php');
        echo "Success"; // Fallback text
        exit;
    } else {
        echo "<p class='text-red-500 text-sm mt-2 bg-red-500/10 p-3 rounded-lg border border-red-500/20'>Invalid username or password.</p>";
        exit;
    }
}
?>
