<?php
require __DIR__ . '/../db/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $specialization = $_POST['specialization'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $stmt = $conn->prepare("INSERT INTO consultants (name, email, password, specialization) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $password, $specialization);

   if ($stmt->execute()) {
    header("Location: login_consultant.php?registered=1");
    exit();
} else {
    echo "<p style='color:red;text-align:center;'>حدث خطأ أثناء التسجيل: " . $conn->error . "</p>";
}

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>تسجيل استشاري جديد</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    /* نسخ التنسيق نفسه */
    body { font-family: 'Tajawal', sans-serif; background: linear-gradient(to right, #2C5364, #203A43, #0F2027); color: white; padding: 2rem; }
    .container { max-width: 600px; margin: auto; background: #1a202c; padding: 2rem; border-radius: 16px; box-shadow: 0 0 20px rgba(0,0,0,0.3); }
    h2 { text-align: center; color: #f7fafc; margin-bottom: 2rem; }
    .form-group { margin-bottom: 1.5rem; }
    label { display: block; margin-bottom: 0.5rem; color: #90cdf4; }
    input, select { width: 100%; padding: 0.75rem; border: none; border-radius: 8px; background-color: #2D3748; color: white; }
    button { width: 100%; padding: 0.75rem; background-color: #38a169; border: none; border-radius: 8px; color: white; font-weight: bold; cursor: pointer; }
    button:hover { background-color: #2f855a; }
  </style>
</head>
<body>
  <div class="container">
    <h2>تسجيل استشاري جديد</h2>
    <form method="POST">
      <div class="form-group">
        <label>الاسم الكامل:</label>
        <input type="text" name="name" required>
      </div>
      <div class="form-group">
        <label>البريد الإلكتروني:</label>
        <input type="email" name="email" required>
      </div>
      <div class="form-group">
        <label>كلمة المرور:</label>
        <input type="password" name="password" required>
      </div>
      <div class="form-group">
        <label>التخصص:</label>
        <select name="specialization" required>
          <option value="تعليم">تعليم</option>
          <option value="صحي">صحي</option>
          <option value="نقل">نقل</option>
        </select>
      </div>
      <button type="submit">تسجيل</button>
    </form>
  </div>
</body>
</html>
