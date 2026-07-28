<?php
/**
 * ONE-TIME SETUP SCRIPT
 * Run this script once to securely create your admin user.
 * After creating the user, DELETE THIS FILE for security.
 */
require_once 'config/db.php';

// Set your desired admin credentials here
$username = 'admin'; // Change this to your preferred username
$password = 'SecretPassword123!'; // Change this to a strong password

// Securely hash the password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

try {
    // Check if user already exists
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
    $stmt->execute([$username]);
    if ($stmt->fetchColumn() > 0) {
        die("Error: User '$username' already exists in the database. Please delete this file.");
    }

    // Insert the secure, hashed password into the database
    $stmt = $pdo->prepare("INSERT INTO users (username, password_hash) VALUES (?, ?)");
    $stmt->execute([$username, $hashed_password]);

    echo "<div style='font-family: sans-serif; max-width: 500px; margin: 40px auto; padding: 20px; border-radius: 8px; background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46;'>";
    echo "<h2 style='margin-top: 0;'>✅ Success!</h2>";
    echo "<p>The admin user <strong>'$username'</strong> was created securely.</p>";
    echo "<p>The password was securely hashed using bcrypt.</p>";
    echo "<hr style='border: none; border-top: 1px solid #a7f3d0; margin: 20px 0;'>";
    echo "<p><strong>⚠️ CRITICAL SECURITY STEP:</strong> You must now delete this <code>setup_admin.php</code> file so nobody else can create users.</p>";
    echo "<a href='/nel-portfolio/admin/login.php' style='display: inline-block; padding: 10px 20px; background: #059669; color: white; text-decoration: none; border-radius: 5px; font-weight: bold;'>Go to Login</a>";
    echo "</div>";

} catch (Exception $e) {
    echo "Error creating user: " . $e->getMessage();
}
?>
