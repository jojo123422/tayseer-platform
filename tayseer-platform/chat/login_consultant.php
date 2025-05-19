<?php
session_start();
require __DIR__ . '/../db/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password FROM consultants WHERE email = ? AND is_approved = 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 1) {
        $stmt->bind_result($id, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION['consultant_id'] = $id;
            header("Location: consultant_dashboard.php");
            exit();
        } else {
            $error = "كلمة المرور غير صحيحة";
        }
    } else {
        $error = "البريد الإلكتروني غير موجود أو لم يتم اعتماده بعد";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>تسجيل دخول الاستشاري</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    /* نسخ تنسيقك السابق تمامًا */
    body { font-family: 'Tajawal', sans-serif; background: linear-gradient(to right, #2C5364, #203A43, #0F2027); color: white; padding: 2rem; }
    .container { max-width: 600px; margin: auto; background: #1a202c; padding: 2rem; border-radius: 16px; box-shadow: 0 0 20px rgba(0,0,0,0.3); }
    h2 { text-align: center; color: #f7fafc; margin-bottom: 2rem; }
    .form-group { margin-bottom: 1.5rem; }
    label { display: block; margin-bottom: 0.5rem; color: #90cdf4; }
    input { width: 100%; padding: 0.75rem; border: none; border-radius: 8px; background-color: #2D3748; color: white; }
    button { width: 100%; padding: 0.75rem; background-color: #3182ce; border: none; border-radius: 8px; color: white; font-weight: bold; cursor: pointer; }
    button:hover { background-color: #2b6cb0; }
    .error { color: #f56565; text-align: center; margin-bottom: 1rem; }
  </style>
</head>
<body>
  <div class="container">
    <h2>تسجيل دخول الاستشاري</h2>
    <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
	<?php if (isset($_GET['registered']) && $_GET['registered'] == 1): ?>
  <p style="color: lightgreen; text-align: center;">تم التسجيل بنجاح، يمكنك الآن تسجيل الدخول</p>
<?php endif; ?>
    <form method="POST">
      <div class="form-group">
        <label>البريد الإلكتروني:</label>
        <input type="email" name="email" required>
      </div>
      <div class="form-group">
        <label>كلمة المرور:</label>
        <input type="password" name="password" required>
      </div>
      <button type="submit">تسجيل الدخول</button>
	  <div style="text-align: center; margin-top: 1.5rem;">
  <a href="register_consultant.php" style="color: #90cdf4; text-decoration: none;">ليس لديك حساب؟ أنشئ حساب جديد</a>
</div>
    </form>
  </div>
</body>
</html>
