<?php
session_start();
require 'db.php';
header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "User not logged in."]);
    exit;
}

$user_id = $_SESSION['user_id'];

try {
    // Fetch student info and JOIN with the counsellors table
    $sql = "SELECT 
                s.first_name, s.last_name, s.username,
                c.name AS counsellor_name, 
                c.email AS counsellor_email, 
                c.contact AS counsellor_contact
            FROM students s
            LEFT JOIN counsellors c ON s.counsellor_id = c.id
            WHERE s.id = ?";
            
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$user_id]);
    $info = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($info) {
        echo json_encode(["status" => "success", "data" => $info]);
    } else {
        echo json_encode(["status" => "error", "message" => "Information not found."]);
    }
} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => "Database Error: " . $e->getMessage()]);
}
?>