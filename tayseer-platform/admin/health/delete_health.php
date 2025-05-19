<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../log/login.php");
    exit();
}

require __DIR__ . '/../db/db_connect.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    try {
        $id = intval($_GET['id']);
        
        $stmt = $conn->prepare("DELETE FROM health_centers WHERE id = ?");
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = "تم حذف المركز الصحي بنجاح";
        } else {
            throw new Exception("خطأ في عملية الحذف");
        }
        
    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
    } finally {
        $stmt->close();
        $conn->close();
    }
}

header("Location: health_dashboard.php");