<?php
// الاتصال بقاعدة البيانات
$conn = new mysqli("localhost:3307", "root", "", "taisir_platform");

// التحقق من الاتصال
if ($conn->connect_error) {
    die("فشل الاتصال: " . $conn->connect_error);
}
// echo "تم الاتصال بنجاح"; // يمكنك إلغاء التعليق إذا أردت اختبار الاتصال
?>
