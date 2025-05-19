<?php
session_start();

// التحقق من صلاحيات المشرف
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: ../../log/login.php");
    exit();
}

require __DIR__ . '/../../db/db_connect.php';

// جلب بيانات الوسيلة الحالية
$transport = [];
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT * FROM transportation WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $transport = $result->fetch_assoc();
    $stmt->close();
}

// معالجة تحديث البيانات
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = intval($_POST['id']);
    $type = htmlspecialchars($_POST['type']);
    $name = htmlspecialchars($_POST['name']);
    $description = htmlspecialchars($_POST['description']);
    $map_link = htmlspecialchars($_POST['map_link']);
    
    // معالجة الصورة الجديدة إذا تم رفعها
    $imageData = $transport['image_url'];
    if (!empty($_FILES['image_url']['tmp_name'])) {
        $imageData = file_get_contents($_FILES['image_url']['tmp_name']);
    }

    $stmt = $conn->prepare("UPDATE transportation SET
        type = ?,
        name = ?,
        description = ?,
        image_url = ?,
        map_link = ?
        WHERE id = ?");
        
    $stmt->bind_param("sssssi", 
        $type,
        $name,
        $description,
        $imageData,
        $map_link,
        $id
    );

    if ($stmt->execute()) {
        $_SESSION['success'] = "تم تحديث البيانات بنجاح";
        header("Location: transport_admin.php");
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
    <title>تعديل وسيلة النقل</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        /* نفس أنماط الصفحات الأخرى */
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

        .preview-image {
            max-width: 200px;
            margin: 1rem 0;
            border-radius: 8px;
            border: 2px solid #3182ce;
        }

        .alert {
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 8px;
        }

        .success {
            background: #c6f6d5;
            color: #2f855a;
        }

        .error {
            background: #fed7d7;
            color: #c53030;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>✏️ تعديل وسيلة النقل</h2>

        <?php if(isset($_SESSION['error'])): ?>
            <div class="alert error"><?= $_SESSION['error'] ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= $transport['id'] ?>">

            <div class="form-group">
                <label>نوع الوسيلة:</label>
                <select name="type" required>
                    <option value="school" <?= $transport['type'] == 'school' ? 'selected' : '' ?>>نقل مدرسي</option>
                    <option value="taxi" <?= $transport['type'] == 'taxi' ? 'selected' : '' ?>>نقل تكسي</option>
                </select>
            </div>

            <div class="form-group">
                <label>اسم الوسيلة:</label>
                <input type="text" name="name" value="<?= htmlspecialchars($transport['name']) ?>" required>
            </div>

            <div class="form-group">
                <label>الوصف:</label>
                <textarea name="description" rows="3"><?= htmlspecialchars($transport['description']) ?></textarea>
            </div>

            <div class="form-group">
                <label>الصورة الحالية:</label>
                <img src="data:image/jpeg;base64,<?= base64_encode($transport['image_url']) ?>" class="preview-image">
            </div>

            <div class="form-group">
                <label>تغيير الصورة (اختياري):</label>
                <input type="file" name="image_url" accept="image/*">
            </div>

            <div class="form-group">
                <label>رابط الخريطة:</label>
                <input type="url" name="map_link" value="<?= htmlspecialchars($transport['map_link']) ?>" required>
            </div>

            <button type="submit">حفظ التغييرات</button>
        </form>
    </div>
</body>
</html>
</html>