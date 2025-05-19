<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../log/login.php");
    exit();
}

require __DIR__ . '/../../db/db_connect.php';

// جلب بيانات المركز الحالي
$center = [];
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT * FROM centers WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $center = $result->fetch_assoc();
    $stmt->close();
}

// معالجة تحديث البيانات
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = intval($_POST['id']);
    $name = htmlspecialchars($_POST['name']);
    $specialization = htmlspecialchars($_POST['specialization']);
    $gender = htmlspecialchars($_POST['gender']);
    $map_link = htmlspecialchars($_POST['map_link']);
    
    // معالجة الصورة الجديدة إذا تم رفعها
    $imageData = $center['image'];
    if (!empty($_FILES['image']['tmp_name'])) {
        $imageData = file_get_contents($_FILES['image']['tmp_name']);
    }

    $stmt = $conn->prepare("UPDATE centers SET
        name = ?,
        specialization = ?,
        gender = ?,
        map_link = ?,
        image = ?
        WHERE id = ?");
        
    $stmt->bind_param("sssssi", 
        $name,
        $specialization,
        $gender,
        $map_link,
        $imageData,
        $id
    );

    if ($stmt->execute()) {
        $_SESSION['success'] = "تم تحديث بيانات المركز بنجاح";
        header("Location: centers_admin.php");
    } else {
        $_SESSION['error'] = "حدث خطأ أثناء التحديث";
    }
    
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تعديل مركز</title>
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
        <h2>✏️ تعديل مركز</h2>

        <?php if(isset($_SESSION['error'])): ?>
            <div class="alert error"><?= $_SESSION['error'] ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= $center['id'] ?>">

            <div class="form-group">
                <label>اسم المركز:</label>
                <input type="text" name="name" value="<?= htmlspecialchars($center['name']) ?>" required>
            </div>

            <div class="form-group">
                <label>الفئة المستهدفة:</label>
                <select name="specialization" required>
                    <option value="المكفوفين" <?= $center['specialization'] == 'المكفوفين' ? 'selected' : '' ?>>المكفوفين</option>
                    <option value="صعوبات التعلم" <?= $center['specialization'] == 'صعوبات التعلم' ? 'selected' : '' ?>>صعوبات التعلم</option>
                    <option value="البكم" <?= $center['specialization'] == 'البكم' ? 'selected' : '' ?>>البكم</option>
                    <option value="اعاقة سمعية" <?= $center['specialization'] == 'اعاقة سمعية' ? 'selected' : '' ?>>اعاقة سمعية</option>
                    <option value="توحد" <?= $center['specialization'] == 'توحد' ? 'selected' : '' ?>>توحد</option>
                    <option value="جميع الاحتياجات" <?= $center['specialization'] == 'جميع الاحتياجات' ? 'selected' : '' ?>>جميع الاحتياجات</option>
                </select>
            </div>

            <div class="form-group">
                <label>الجنس:</label>
                <select name="gender" required>
                    <option value="الكل" <?= $center['gender'] == 'الكل' ? 'selected' : '' ?>>الكل</option>
                    <option value="بنات" <?= $center['gender'] == 'بنات' ? 'selected' : '' ?>>بنات</option>
                    <option value="بنين" <?= $center['gender'] == 'بنين' ? 'selected' : '' ?>>بنين</option>
                </select>
            </div>

            <div class="form-group">
                <label>رابط الخريطة:</label>
                <input type="url" name="map_link" value="<?= htmlspecialchars($center['map_link']) ?>" required>
            </div>

            <div class="form-group">
                <label>الصورة الحالية:</label>
                <img src="data:image/jpeg;base64,<?= base64_encode($center['image']) ?>" class="preview-image">
            </div>

            <div class="form-group">
                <label>تغيير الصورة (اختياري):</label>
                <input type="file" name="image" accept="image/*">
            </div>

            <button type="submit">حفظ التغييرات</button>
        </form>
    </div>
</body>
</html>