<?php
require 'db.php';
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

try {
    switch ($method) {
        case 'GET':
            $stmt = $pdo->query("SELECT * FROM counselling_sessions ORDER BY session_date ASC");
            echo json_encode(["status" => "success", "data" => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
            break;

        case 'POST':
            $stmt = $pdo->prepare("INSERT INTO counselling_sessions (student_name, topic, session_date) VALUES (?, ?, ?)");
            $stmt->execute([$input['student_name'], $input['topic'], $input['session_date']]);
            echo json_encode(["status" => "success", "message" => "Session booked successfully."]);
            break;

        case 'PUT':
            $stmt = $pdo->prepare("UPDATE counselling_sessions SET student_name = ?, topic = ?, session_date = ? WHERE id = ?");
            $stmt->execute([$input['student_name'], $input['topic'], $input['session_date'], $input['id']]);
            echo json_encode(["status" => "success", "message" => "Session updated successfully."]);
            break;

        case 'DELETE':
            $stmt = $pdo->prepare("DELETE FROM counselling_sessions WHERE id = ?");
            $stmt->execute([$input['id']]);
            echo json_encode(["status" => "success", "message" => "Session deleted successfully."]);
            break;

        default:
            echo json_encode(["status" => "error", "message" => "Invalid Request Method"]);
            break;
    }
} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => "Database Error: " . $e->getMessage()]);
}
?>