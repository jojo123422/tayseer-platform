<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../log/login.php");
    exit();
}

require __DIR__ . '/../../db/db_connect.php';

// Display error messages
if (isset($_SESSION['error'])) {
    echo '<div class="error" style="color: red; margin-bottom: 10px;">' . $_SESSION['error'] . '</div>';
    unset($_SESSION['error']);
}

$user = [];
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = intval($_POST['id']);
    $fullname = htmlspecialchars($_POST['fullname']);
    $phone = htmlspecialchars($_POST['phone']);
    $type = htmlspecialchars($_POST['type']);
    
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    $update_password = false;
    $hashed_password = '';

    // Validate password fields
    if (!empty($new_password) || !empty($confirm_password)) {
        if (empty($new_password) || empty($confirm_password)) {
            $_SESSION['error'] = 'يجب ملء كلتا حقلين كلمة المرور';
            header("Location: edit_user.php?id=$id");
            exit();
        } elseif ($new_password !== $confirm_password) {
            $_SESSION['error'] = 'كلمة المرور وتأكيدها غير متطابقين';
            header("Location: edit_user.php?id=$id");
            exit();
        } else {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $update_password = true;
        }
    }

    // Prepare SQL based on password update
    if ($update_password) {
        $sql = "UPDATE users SET 
                fullname = ?, 
                phone = ?, 
                type = ?, 
                password = ? 
                WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $fullname, $phone, $type, $hashed_password, $id);
    } else {
        $sql = "UPDATE users SET 
                fullname = ?, 
                phone = ?, 
                type = ? 
                WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $fullname, $phone, $type, $id);
    }

    if ($stmt->execute()) {
        header("Location: users_admin.php");
        exit();
    } else {
        $_SESSION['error'] = 'حدث خطأ أثناء تحديث المستخدم';
        header("Location: edit_user.php?id=$id");
        exit();
    }
    
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>تعديل مستخدم</title>
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
    <h2>تعديل مستخدم</h2>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="error" style="color: red; margin-bottom: 10px;">
            <?= $_SESSION['error'] ?>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <form method="POST">
      <input type="hidden" name="id" value="<?= $user['id'] ?>">
      
      <div class="form-group">
        <label>الاسم الكامل:</label>
        <input type="text" name="fullname" value="<?= $user['fullname'] ?>" required>
      </div>
      
      <div class="form-group">
        <label>رقم الجوال:</label>
        <input type="tel" name="phone" value="<?= $user['phone'] ?>" required>
      </div>
      
      <div class="form-group">
        <label>نوع الحساب:</label>
        <select name="type" required>
          <option value="user" <?= $user['type'] == 'user' ? 'selected' : '' ?>>مستخدم عادي</option>
          <option value="admin" <?= $user['type'] == 'admin' ? 'selected' : '' ?>>مدير</option>
        </select>
      </div>

      <div class="form-group">
        <label>كلمة المرور الجديدة:</label>
        <input type="password" name="new_password">
      </div>

      <div class="form-group">
        <label>تأكيد كلمة المرور الجديدة:</label>
        <input type="password" name="confirm_password">
      </div>
      
      <button type="submit">حفظ التعديلات</button>
    </form>
  </div>
</body>
</html>