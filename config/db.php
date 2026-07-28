<?php
// config/db.php
// Support both local development and production (Vercel) environments

// Get configuration from environment variables or use defaults for local development
$host = $_ENV['DB_HOST'] ?? $_SERVER['DB_HOST'] ?? '127.0.0.1';
$db   = $_ENV['DB_NAME'] ?? $_SERVER['DB_NAME'] ?? 'nel_portfolio';
$user = $_ENV['DB_USER'] ?? $_SERVER['DB_USER'] ?? 'root';
$pass = $_ENV['DB_PASS'] ?? $_SERVER['DB_PASS'] ?? '';
$port = $_ENV['DB_PORT'] ?? $_SERVER['DB_PORT'] ?? '3306';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    // In production, log this instead of echoing
    if (getenv('ENVIRONMENT') === 'production') {
        error_log("Database connection failed: " . $e->getMessage());
        http_response_code(500);
        die(json_encode(['error' => 'Database connection failed']));
    }
    die("Database connection failed: " . $e->getMessage());
}
?>
