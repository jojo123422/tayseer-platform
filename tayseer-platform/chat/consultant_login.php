<?php
define('INCLUDE_CHECK', true);

session_start();
require '../db/db_connect.php';

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM consultants WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    
    $consultant = $stmt->get_result()->fetch_assoc();

    if($consultant && password_verify($password, $consultant['password'])) {
        $_SESSION['consultant_id'] = $consultant['id'];
        header("Location: consultant_dashboard.php");
    } else {
        $error = "بيانات الدخول غير صحيحة";
    }
}
?>

<!-- نموذج تسجيل الدخول -->