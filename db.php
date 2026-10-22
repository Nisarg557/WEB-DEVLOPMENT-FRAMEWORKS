<?php
$host = '127.0.0.1'; // Changed from localhost
$port = '3307';      // Added your custom XAMPP MySQL port
$dbname = 'student_hub';
$user = 'root';
$pass = ''; // Default XAMPP password is empty

try {
    // Added port=$port to the connection string
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die(json_encode(["status" => "error", "message" => "Database connection failed: " . $e->getMessage()]));
}
?>