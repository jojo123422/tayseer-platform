<?php
// بدء الجلسة بطريقة آمنة
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// التحقق من أن المستخدم مسجل دخوله وهو إداري
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: ../log/login.php");
    exit();
}


// الاتصال بقاعدة البيانات
require __DIR__ . '/../../db/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
 
 
    $type = $_POST['type'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $imageData = file_get_contents($_FILES['image_url']['tmp_name']);
    $map_link = $_POST['map_link'];

    $stmt = $conn->prepare("INSERT INTO transportation 
        (type, name, description, image_url, map_link) 
        VALUES (?, ?, ?, ?, ?)");
        
    $stmt->bind_param("sssss", 
        $type,
        $name,
        $description,
        $imageData,
        $map_link
    );

    if ($stmt->execute()) {
        header("Location: transport_admin.php");
    } else {
        echo "حدث خطأ: " . $conn->error;
    }
    
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>إضافة وسيلة نقل جديدة</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    /* نفس أنماط صفحة التسجيل مع تعديلات بسيطة */
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: linear-gradient(to right, #2C5364, #203A43, #0F2027);
      color: white;
      margin: 0;
      padding: 0;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
    }

    .container {
      background-color: #1a202c;
      padding: 2rem;
      border-radius: 16px;
      box-shadow: 0 0 20px rgba(0,0,0,0.3);
      max-width: 600px;
      width: 90%;
      margin: 2rem auto;
    }

    h2 {
      text-align: center;
      margin-bottom: 1.5rem;
      color: #f7fafc;
    }

    .form-group {
      margin-bottom: 1.5rem;
    }

    label {
      display: block;
      margin-bottom: 0.5rem;
      color: #90cdf4;
    }

    input, select {
      width: 100%;
      padding: 0.75rem;
      border: none;
      border-radius: 8px;
      background-color: #2D3748;
      color: white;
    }

    button {
      width: 100%;
      padding: 0.75rem;
      background-color: #3182ce;
      border: none;
      border-radius: 8px;
      color: white;
      font-weight: bold;
      cursor: pointer;
      transition: background 0.3s ease;
    }

    button:hover {
      background-color: #2b6cb0;
    }

    .back-link {
      display: block;
      text-align: center;
      margin-top: 1rem;
      color: #90cdf4;
      text-decoration: none;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>إضافة وسيلة نقل جديدة</h2>
    <form action="add_transport.php" method="POST" enctype="multipart/form-data">
      <div class="form-group">
        <label for="type">نوع الوسيلة:</label>
        <select name="type" id="type" required>
          <option value="school">نقل مدرسي</option>
          <option value="taxi">نقل تكسي</option>
        </select>
      </div>

      <div class="form-group">
        <label for="name">اسم الوسيلة:</label>
        <input type="text" name="name" id="name" required>
      </div>

      <div class="form-group">
        <label for="description">الوصف:</label>
        <input type="text" name="description" id="description">
      </div>

      <div class="form-group">
        <label for="image_url">رابط الصورة:</label>
        <input type="file" name="image_url" id="image_url" accept="image/*" required>
      </div>

      <div class="form-group">
        <label for="map_link">الموقع:</label>
      
    <input type="url" name="map_link" placeholder="https://maps.google.com/..." required>
    <small class="text-muted">يمكنك الحصول على الرابط من <a href="https://www.google.com/maps" target="_blank">خرائط جوجل</a></small>
      </div>

      <button type="submit">إضافة وسيلة</button>
    </form>

    <a href="admin_dashboard.php" class="back-link">العودة إلى لوحة التحكم</a>
  </div>
</body>
</html>