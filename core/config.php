<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

try {
    // Absolute path to the SQLite database
    $dbPath = __DIR__ . '/../databases/users.db';
    
    // Create PDO connection
    $db = new PDO("sqlite:" . $dbPath);
    
    // Set error mode to exceptions for better error reporting
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Optional: Force UTF-8 encoding for safety (not strictly required in SQLite)
    $db->exec("PRAGMA encoding = 'UTF-8';");

} catch (PDOException $e) {
    // Display and stop if connection fails
    die("Database connection failed: " . $e->getMessage());
}
?>
