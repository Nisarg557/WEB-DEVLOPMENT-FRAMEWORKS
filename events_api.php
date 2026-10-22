<?php
require 'db.php';
header('Content-Type: application/json');

$action = $_REQUEST['action'] ?? '';

try {
    if ($action === 'read') {
        $search = $_GET['search'] ?? '';
        $searchTerm = "%$search%";
        
        $sql = "SELECT * FROM events WHERE title LIKE ? OR description LIKE ? ORDER BY event_date ASC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$searchTerm, $searchTerm]);
        
        echo json_encode(["status" => "success", "data" => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
    } 
    elseif ($action === 'create') {
        $data = json_decode(file_get_contents("php://input"));
        $sql = "INSERT INTO events (title, description, event_date) VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$data->title, $data->description, $data->event_date]);
        
        echo json_encode(["status" => "success", "message" => "Event created successfully."]);
    } 
    elseif ($action === 'update') {
        $data = json_decode(file_get_contents("php://input"));
        $sql = "UPDATE events SET title = ?, description = ?, event_date = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$data->title, $data->description, $data->event_date, $data->id]);
        
        echo json_encode(["status" => "success", "message" => "Event updated successfully."]);
    } 
    elseif ($action === 'delete') {
        $data = json_decode(file_get_contents("php://input"));
        $sql = "DELETE FROM events WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$data->id]);
        
        echo json_encode(["status" => "success", "message" => "Event deleted successfully."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Invalid action."]);
    }
} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => "Database error: " . $e->getMessage()]);
}
?>