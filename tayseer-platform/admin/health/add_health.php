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
    $disability_type = $_POST['disability_type'];
    $location = $_POST['location'];
    $services = $_POST['services'];
    $image = file_get_contents($_FILES['image']['tmp_name']);
    
    $stmt = $conn->prepare("INSERT INTO health_centers 
        (name, specialization, disability_type, location, services, image) 
        VALUES (?, ?, ?, ?, ?, ?)");
        
    $stmt->bind_param("ssssss", 
        $name,
        $specialization,
        $disability_type,
        $location,
        $services,
        $image
    );

    if ($stmt->execute()) {
        header("Location: health_dashboard.php");
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
  <title>إضافة مركز صحي جديد</title>
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
    <h2>إضافة مركز صحي جديد</h2>
    
    <form method="POST" enctype="multipart/form-data">
      <div class="form-group">
        <label>اسم المركز:</label>
        <input type="text" name="name" required>
      </div>
      
      <div class="form-group">
        <label>التخصص:</label>
        <input type="text" name="specialization" required>
      </div>
      
      <div class="form-group">
        <label>نوع الإعاقة:</label>
        <select name="disability_type" required>
          <option value="visual">الإعاقة البصرية</option>
          <option value="physical">الإعاقة الحركية</option>
          <option value="hearing">الإعاقة السمعية</option>
          <option value="intellectual">الإعاقة الذهنية</option>
        </select>
      </div>
      
      <div class="form-group">
        <label>الموقع:</label>
  
    <input type="url" name="map_link" placeholder="https://maps.google.com/..." required>
    <small class="text-muted">يمكنك الحصول على الرابط من <a href="https://www.google.com/maps" target="_blank">خرائط جوجل</a></small>
      </div>
      
      <div class="form-group">
        <label>الخدمات:</label>
        <textarea name="services" rows="3" required></textarea>
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