<?php
require 'db.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"));

if(isset($data->username) && isset($data->password)) {
    $hash = password_hash($data->password, PASSWORD_DEFAULT);
    $role = 'student'; 
    
    try {
        $stmtCounsellors = $pdo->query("SELECT id FROM counsellors");
        $counsellors = $stmtCounsellors->fetchAll(PDO::FETCH_COLUMN);
        
        $assigned_counsellor_id = 1; 
        
        if (!empty($counsellors)) {
            $assigned_counsellor_id = $counsellors[array_rand($counsellors)];
        }
        
        $stmt = $pdo->prepare("INSERT INTO students (first_name, middle_name, last_name, username, password, role, counsellor_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
        
        $stmt->execute([
            $data->firstName, 
            $data->middleName, 
            $data->lastName, 
            $data->username, 
            $hash, 
            $role, 
            $assigned_counsellor_id
        ]);
        
        echo json_encode(["status" => "success", "message" => "Registration successful! You have been assigned a counsellor."]);
        
    } catch(PDOException $e) {
        if ($e->getCode() == 23000) {
            echo json_encode(["status" => "error", "message" => "Username already exists! Please choose another."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Database error: " . $e->getMessage()]);
        }
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid data sent."]);
}
?>