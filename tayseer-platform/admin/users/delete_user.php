<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../log/login.php");
    exit();
}

require __DIR__ . '/../../db/db_connect.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    try {
        $id = intval($_GET['id']);
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    } finally {
        $stmt->close();
        $conn->close();
    }
}

header("Location: users_admin.php");
?>