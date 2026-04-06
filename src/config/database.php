<?php

$host = getenv('DB_HOST') ?: 'db';
$database = getenv('DB_NAME') ?: 'app_db';
$username = getenv('DB_USER') ?: 'app_user';
$password = getenv('DB_PASSWORD') ?: 'app_password';

try {
    $dsn = "mysql:host={$host};dbname={$database};charset=utf8mb4";
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
} catch (PDOException $exception) {
    die('Database connection failed: ' . $exception->getMessage());
}
