<?php
session_start();
require 'db.php';

$timeout_duration = 1800; 

// Check for Remember Me cookie if session is dead
if (!isset($_SESSION['user_id']) && isset($_COOKIE['remember_me'])) {
    $token = $_COOKIE['remember_me'];
    $hashedToken = hash('sha256', $token);

    $stmt = $pdo->prepare("SELECT * FROM students WHERE remember_token = ? AND token_expiry > NOW()");
    $stmt->execute([$hashedToken]);
    $user = $stmt->fetch();

    if ($user) {
        // Log them back in
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['last_activity'] = time();
    }
}

// Session Verification & Timeout Check
if (isset($_SESSION['user_id'])) {
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout_duration) {
        session_unset();
        session_destroy();
        header("Location: login.html?expired=1");
        exit;
    }
    $_SESSION['last_activity'] = time(); // Update activity timestamp
} else {
    header("Location: login.html");
    exit;
}

// Optional: Role-based access wrapper function
function requireAdmin() {
    if ($_SESSION['role'] !== 'admin') {
        header("HTTP/1.1 403 Forbidden");
        die("You do not have permission to view this page.");
    }
}
?>