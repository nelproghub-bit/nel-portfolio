<?php
// Migration script to update certifications table
require_once __DIR__ . '/config/db.php';

echo "<h2>Migrating Certifications Table...</h2>";

try {
    // Check if certificate_file column exists
    $stmt = $pdo->query("SHOW COLUMNS FROM certifications LIKE 'certificate_file'");
    $columnExists = $stmt->rowCount() > 0;
    
    if (!$columnExists) {
        echo "<p>Adding 'certificate_file' column...</p>";
        
        // Add certificate_file column
        $pdo->exec("ALTER TABLE certifications ADD COLUMN certificate_file VARCHAR(255) DEFAULT NULL AFTER issue_date");
        echo "<p style='color: green;'>✓ Added 'certificate_file' column</p>";
        
        // Check if credential_url column exists
        $stmt = $pdo->query("SHOW COLUMNS FROM certifications LIKE 'credential_url'");
        $oldColumnExists = $stmt->rowCount() > 0;
        
        if ($oldColumnExists) {
            echo "<p>Migrating data from 'credential_url' to 'certificate_file'...</p>";
            
            // Copy data from credential_url to certificate_file
            $pdo->exec("UPDATE certifications SET certificate_file = credential_url WHERE credential_url IS NOT NULL AND credential_url != ''");
            echo "<p style='color: green;'>✓ Migrated existing URL data</p>";
            
            // Drop the old column
            $pdo->exec("ALTER TABLE certifications DROP COLUMN credential_url");
            echo "<p style='color: green;'>✓ Removed old 'credential_url' column</p>";
        }
    } else {
        echo "<p style='color: blue;'>Column 'certificate_file' already exists. No migration needed.</p>";
    }
    
    echo "<h3 style='color: green;'>✓ Migration completed successfully!</h3>";
    echo "<p><a href='/nel-portfolio/admin/certifications.php'>Go to Certifications</a></p>";
    
} catch (PDOException $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}
?>
