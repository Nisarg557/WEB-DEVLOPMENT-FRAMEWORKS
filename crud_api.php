<?php
require 'db.php';
header('Content-Type: application/json');

$action = $_REQUEST['action'] ?? '';

try {
    if ($action === 'read') {
        $search = $_GET['search'] ?? '';
        $filterRole = $_GET['role'] ?? '';
        
        // Setup search parameters with wildcards
        $searchTerm = "%$search%";
        $roleTerm = $filterRole ? $filterRole : "%";

        $sql = "SELECT id, first_name, last_name, username, role FROM students 
                WHERE (first_name LIKE ? OR username LIKE ?) AND role LIKE ? 
                ORDER BY id DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$searchTerm, $searchTerm, $roleTerm]);
        
        echo json_encode(["status" => "success", "data" => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
    } 
    
    elseif ($action === 'create') {
        $data = json_decode(file_get_contents("php://input"));
        $hash = password_hash("DefaultPass123!", PASSWORD_DEFAULT); // Default password for admin-created users
        
        $sql = "INSERT INTO students (first_name, last_name, username, password, role) VALUES (?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$data->first_name, $data->last_name, $data->username, $hash, $data->role]);
        
        echo json_encode(["status" => "success", "message" => "Student added successfully."]);
    } 
    
    elseif ($action === 'update') {
        $data = json_decode(file_get_contents("php://input"));
        
        $sql = "UPDATE students SET first_name = ?, last_name = ?, role = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$data->first_name, $data->last_name, $data->role, $data->id]);
        
        echo json_encode(["status" => "success", "message" => "Student updated successfully."]);
    } 
    
    elseif ($action === 'delete') {
        $data = json_decode(file_get_contents("php://input"));
        
        $sql = "DELETE FROM students WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$data->id]);
        
        echo json_encode(["status" => "success", "message" => "Student deleted successfully."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Invalid action."]);
    }
} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => "Database error: " . $e->getMessage()]);
}
?>