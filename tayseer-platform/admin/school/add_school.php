<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../log/login.php");
    exit();
}

require __DIR__ . '/../../db/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $specialization = $_POST['specialization'];
    $gender = $_POST['gender'];
    $stage = $_POST['stage'];
    $location = $_POST['location'];
    $image = file_get_contents($_FILES['image']['tmp_name']);
    
    $stmt = $conn->prepare("INSERT INTO schools 
        (name, specialization, gender, stage, location, image) 
        VALUES (?, ?, ?, ?, ?, ?)");
        
    $stmt->bind_param("ssssss", 
        $name,
        $specialization,
        $gender,
        $stage,
        $location,
        $image
    );

    if ($stmt->execute()) {
        header("Location: schools_dashboard.php");
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
  <title>إضافة مدرسة جديدة</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
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
    <h2>إضافة مدرسة جديدة</h2>
    
    <form method="POST" enctype="multipart/form-data">
      <div class="form-group">
        <label>اسم المدرسة:</label>
        <input type="text" name="name" required>
      </div>
      
      <div class="form-group">
        <label>التخصص:</label>
        <select name="specialization" required>
          <option value="مكفوفين">مكفوفين</option>
          <option value="صعوبات تعلم">صعوبات تعلم</option>
          <option value="بكم">بكم</option>
          <option value="اعاقه سمعيه">اعاقه سمعيه</option>
          <option value="توحد">توحد</option>
        </select>
      </div>
      
      <div class="form-group">
        <label>الجنس:</label>
        <select name="gender" required>
          <option value="بنات">بنات</option>
          <option value="بنين">بنين</option>
          <option value="مختلط">مختلط</option>
        </select>
      </div>
      
      <div class="form-group">
        <label>المرحلة:</label>
        <select name="stage" required>
          <option value="ابتدائي">ابتدائي</option>
          <option value="متوسط">متوسط</option>
          <option value="ثانوي">ثانوي</option>
          <option value="جميع المراحل">جميع المراحل</option>
        </select>
      </div>
      
      <div class="form-group">
        <label>الموقع:</label>
    <input type="url" name="map_link" placeholder="https://maps.google.com/..." required>
    <small class="text-muted">يمكنك الحصول على الرابط من <a href="https://www.google.com/maps" target="_blank">خرائط جوجل</a></small>
      </div>
      
      <div class="form-group">
        <label>صورة المدرسة:</label>
        <input type="file" name="image" accept="image/*" required>
      </div>
      
      <button type="submit">حفظ المدرسة</button>
    </form>
  </div>
</body>
</html>