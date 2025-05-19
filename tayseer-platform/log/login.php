<?php
session_start(); // يجب أن تكون في الأعلى قبل أي مخرجات

$conn = new mysqli("localhost:3307", "root", "", "taisir_platform");
if ($conn->connect_error) {
    die("فشل الاتصال بقاعدة البيانات: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $phone = $conn->real_escape_string($_POST['phone']);
    $password = $_POST['password'];

    // استخدام prepared statements لمنع الثغرات الأمنية
    $stmt = $conn->prepare("SELECT id, password, type FROM users WHERE phone = ?");
    $stmt->bind_param("s", $phone);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_type'] = $user['type'];
            
            // استخدام المسارات الصحيحة وتجنب الرموز الخاصة
            if ($user['type'] == 'admin') {
                header("Location: ..\admin\admin_dashpord.php");
            } else {
                header("Location: ..\user\main.php");
            }
            exit(); // إيقاف التنفيذ بعد التوجيه
        } else {
            echo "رقم الجوال أو كلمة المرور غير صحيحة";
        }
    } else {
        echo "رقم الجوال غير مسجل!";
    }
    
    $stmt->close();
}

$conn->close();
?>