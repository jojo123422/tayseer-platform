<?php
session_start();
require '../db/db_connect.php';

$data = json_decode(file_get_contents('php://input'), true);

// تحديد المرسل بناءً على نوع المستخدم
$sender = isset($_SESSION['user_id']) ? 'user' : 'consultant';

$stmt = $conn->prepare("INSERT INTO chats 
                      (user_id, consultant_id, message, sender) 
                      VALUES (?, ?, ?, ?)");

if($sender === 'user') {
    $stmt->bind_param("iiss", 
                    $_SESSION['user_id'], 
                    $data['consultant_id'], 
                    $data['message'],
                    $sender);
} else {
    $stmt->bind_param("iiss", 
                    $data['user_id'],
                    $_SESSION['consultant_id'], 
                    $data['message'],
                    $sender);
}

echo json_encode(['success' => $stmt->execute()]);