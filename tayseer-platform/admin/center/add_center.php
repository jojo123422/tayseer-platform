<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../log/login.php");
    exit();
}

require __DIR__ . '/../../db/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $specialization = $_POST['specialization'];
    $gender = $_POST['gender'];
    $map_link = $_POST['map_link'];
    $image = file_get_contents($_FILES['image']['tmp_name']);
    
    $stmt = $conn->prepare("INSERT INTO centers 
        (name, specialization, gender, map_link, image) 
        VALUES (?, ?, ?, ?, ?)");
        
    $stmt->bind_param("sssss", 
        $name,
        $specialization,
        $gender,
        $map_link,
        $image
    );

    if ($stmt->execute()) {
        header("Location: centers_admin.php");
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
  <title>إضافة مركز جديد</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    /* نفس أنماط إضافة وسيلة النقل */
    body {
      font-family: 'Tajawal', sans-serif;
      background: linear-gradient(to right, #2C5364, #203A43, #0F2027);
      color: white;
      margin: 0;
      padding: 2rem;
    }

    .container {
      max-width: 800px;
      margin: 2rem auto;
      background: #1a202c;
      padding: 2rem;
      border-radius: 16px;
      box-shadow: 0 0 20px rgba(0,0,0,0.3);
    }

    h2 {
      text-align: center;
      color: #f7fafc;
      margin-bottom: 2rem;
    }

    .form-group {
      margin-bottom: 1.5rem;
    }

    label {
      display: block;
      margin-bottom: 0.5rem;
      color: #90cdf4;
    }

    input, select, textarea {
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
      background-color: #38a169;
      border: none;
      border-radius: 8px;
      color: white;
      font-weight: bold;
      cursor: pointer;
      transition: background 0.3s ease;
    }

    button:hover {
      background-color: #2f855a;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>إضافة مركز جديد</h2>
    
    <form method="POST" enctype="multipart/form-data">
      <div class="form-group">
        <label>اسم المركز:</label>
        <input type="text" name="name" required>
      </div>
      
      <div class="form-group">
        <label>الفئة المستهدفة:</label>
        <select name="specialization" required>
          <option value="المكفوفين">المكفوفين</option>
          <option value="صعوبات التعلم">صعوبات التعلم</option>
          <option value="البكم">البكم</option>
          <option value="اعاقة سمعية">اعاقة سمعية</option>
          <option value="توحد">توحد</option>
          <option value="جميع الاحتياجات">جميع الاحتياجات</option>
        </select>
      </div>
      
      <div class="form-group">
        <label>الجنس:</label>
        <select name="gender" required>
          <option value="الكل">الكل</option>
          <option value="بنات">بنات</option>
          <option value="بنين">بنين</option>
        </select>
      </div>
      
      <div class="form-group">
        <label>رابط الخريطة:</label>
    <input type="url" name="map_link" placeholder="https://maps.google.com/..." required>
    <small class="text-muted">يمكنك الحصول على الرابط من <a href="https://www.google.com/maps" target="_blank">خرائط جوجل</a></small>
      </div>
      
      <div class="form-group">
        <label>صورة المركز:</label>
        <input type="file" name="image" accept="image/*" required>
      </div>
      
      <button type="submit">حفظ المركز</button>
    </form>
  </div>
</body>
</html>