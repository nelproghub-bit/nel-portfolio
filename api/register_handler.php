<?php
// api/register_handler.php
require_once __DIR__ . '/../config/db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (empty($username) || empty($password) || empty($confirm_password)) {
        echo "<p class='text-red-500 text-sm mt-2 bg-red-500/10 p-3 rounded-lg border border-red-500/20'>All fields are required.</p>";
        exit;
    }

    if ($password !== $confirm_password) {
        echo "<p class='text-red-500 text-sm mt-2 bg-red-500/10 p-3 rounded-lg border border-red-500/20'>Passwords do not match.</p>";
        exit;
    }

    // Check if username already exists
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
    $stmt->execute([$username]);
    if ($stmt->fetchColumn() > 0) {
        echo "<p class='text-red-500 text-sm mt-2 bg-red-500/10 p-3 rounded-lg border border-red-500/20'>Username already exists. Please choose another.</p>";
        exit;
    }

    // Securely hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    try {
        $stmt = $pdo->prepare("INSERT INTO users (username, password_hash) VALUES (?, ?)");
        $stmt->execute([$username, $hashed_password]);
        
        // Output success and redirect to login
        echo "<p class='text-emerald-500 text-sm mt-2 bg-emerald-500/10 p-3 rounded-lg border border-emerald-500/20'>Registration successful! Redirecting to login...</p>";
        echo "<script>setTimeout(() => window.location.href = '/nel-portfolio/admin/login.php', 2000);</script>";
        exit;
    } catch (Exception $e) {
        echo "<p class='text-red-500 text-sm mt-2 bg-red-500/10 p-3 rounded-lg border border-red-500/20'>An error occurred. Please try again.</p>";
        exit;
    }
}
?>
