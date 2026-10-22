<?php
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', isset($_SERVER['HTTPS']) ? 1 : 0);
session_start();

require 'db.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"));

if(isset($data->username) && isset($data->password)) {
    $stmt = $pdo->prepare("SELECT * FROM students WHERE username = ?");
    $stmt->execute([$data->username]);
    $user = $stmt->fetch();

    if ($user && password_verify($data->password, $user['password'])) {
        session_regenerate_id(true);

        $_SESSION['user_id'] = $user['id']; 
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['last_activity'] = time(); 

        if (isset($data->remember_me) && $data->remember_me === true) {
            $token = bin2hex(random_bytes(32)); 
            $hashedToken = hash('sha256', $token); 
            $expiry = date('Y-m-d H:i:s', time() + (86400 * 30)); 

            $updateStmt = $pdo->prepare("UPDATE students SET remember_token = ?, token_expiry = ? WHERE id = ?");
            $updateStmt->execute([$hashedToken, $expiry, $user['id']]);

            setcookie('remember_me', $token, time() + (86400 * 30), '/', '', isset($_SERVER['HTTPS']), true);
        }

        echo json_encode([
            "status" => "success", 
            "name" => $user['first_name'],
            "role" => $user['role']
        ]);
    } else {
        echo json_encode(["status" => "error", "message" => "Incorrect username or password!"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid data sent."]);
}
?>