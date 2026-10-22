<?php
session_start();
require 'db.php';

if (isset($_SESSION['user_id'])) {
    // Clear token from DB
    $stmt = $pdo->prepare("UPDATE students SET remember_token = NULL, token_expiry = NULL WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
}

// Clear cookie
setcookie('remember_me', '', time() - 3600, '/');

// Clear session
session_unset();
session_destroy();

header("Location: login.html");
exit;
?>