<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../log/login.php");
    exit();
}

require __DIR__ . '/../../db/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullname = $_POST['fullname'];
    $phone = $_POST['phone'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $type = $_POST['type'];
    
    $stmt = $conn->prepare("INSERT INTO users 
        (fullname, phone, password, type) 
        VALUES (?, ?, ?, ?)");
        
    $stmt->bind_param("ssss", $fullname, $phone, $password, $type);

    if ($stmt->execute()) {
        header("Location: users_admin.php");
    } else {
        echo "حدث خطأ: " . $conn->error;
    }
    
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>إضافة مستخدم جديد</title>
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
    <h2>إضافة مستخدم جديد</h2>
    
    <form method="POST">
      <div class="form-group">
        <label>الاسم الكامل:</label>
        <input type="text" name="fullname" required>
      </div>
      
      <div class="form-group">
        <label>رقم الجوال:</label>
        <input type="tel" name="phone" " required>
      </div>
      
      <div class="form-group">
        <label>كلمة المرور:</label>
        <input type="password" name="password" minlength="6" required>
      </div>
      
      <div class="form-group">
        <label>نوع الحساب:</label>
        <select name="type" required>
          <option value="user">مستخدم عادي</option>
          <option value="admin">مدير</option>
        </select>
      </div>
      
      <button type="submit">حفظ المستخدم</button>
    </form>
  </div>
</body>
</html>