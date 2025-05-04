<?php
require_once __DIR__ . '/config.php';

try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    if (ENABLE_LOGGING) {
        error_log(date('[Y-m-d H:i:s] ') . "DB Connection Error: " . $e->getMessage() . PHP_EOL, 3, LOG_FILE);
    }
    die("Database connection failed.");
}
