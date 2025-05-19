<?php
session_start();

// التحقق من صلاحيات المشرف
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: ../log/login.php");
    exit();
}

require __DIR__ . '/../../db/db_connect.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    try {
        $id = intval($_GET['id']);
        
        // حذف البيانات
        $stmt = $conn->prepare("DELETE FROM transportation WHERE id = ?");
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = "تم الحذف بنجاح";
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

header("Location: transport_admin.php");