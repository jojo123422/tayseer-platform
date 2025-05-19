<?php
// اتصال بقاعدة البيانات
$conn = new mysqli("localhost:3307", "root", "", "taisir_platform");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// الحصول على البيانات من النموذج
$fullname = $_POST['fullname'];
$phone = $_POST['phone'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT); // تشفير كلمة المرور
$type = $_POST['type'];

// استعلام إدخال البيانات
$sql = "INSERT INTO users (fullname, phone, password, type) VALUES ('$fullname', '$phone', '$password', '$type')";

// تنفيذ الاستعلام
if ($conn->query($sql) === TRUE) {
    echo "تم إنشاء الحساب بنجاح";
} else {
    echo "خطأ: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
