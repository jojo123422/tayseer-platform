<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../log/login.php");
    exit();
}

require __DIR__ . '/../../db/db_connect.php';

// جلب بيانات المدرسة الحالية
$school = [];
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT * FROM schools WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $school = $result->fetch_assoc();
    $stmt->close();
}

// معالجة تحديث البيانات
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = intval($_POST['id']);
    $name = htmlspecialchars($_POST['name']);
    $specialization = htmlspecialchars($_POST['specialization']);
    $gender = htmlspecialchars($_POST['gender']);
    $stage = htmlspecialchars($_POST['stage']);
    $location = htmlspecialchars($_POST['location']);
    
    // معالجة الصورة الجديدة إذا تم رفعها
    $imageData = $school['image'];
    if (!empty($_FILES['image']['tmp_name'])) {
        $imageData = file_get_contents($_FILES['image']['tmp_name']);
    }

    $stmt = $conn->prepare("UPDATE schools SET
        name = ?,
        specialization = ?,
        gender = ?,
        stage = ?,
        location = ?,
        image = ?
        WHERE id = ?");
        
    $stmt->bind_param("ssssssi", 
        $name,
        $specialization,
        $gender,
        $stage,
        $location,
        $imageData,
        $id
    );

    if ($stmt->execute()) {
        $_SESSION['success'] = "تم تحديث بيانات المدرسة بنجاح";
        header("Location: schools_dashboard.php");
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
    <title>تعديل مدرسة</title>
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

        .preview-image {
            max-width: 200px;
            margin: 1rem 0;
            border-radius: 8px;
            border: 2px solid #3182ce;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>✏️ تعديل مدرسة</h2>

        <?php if(isset($_SESSION['error'])): ?>
            <div class="alert error"><?= $_SESSION['error'] ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= $school['id'] ?>">

            <div class="form-group">
                <label>اسم المدرسة:</label>
                <input type="text" name="name" value="<?= htmlspecialchars($school['name']) ?>" required>
            </div>

            <div class="form-group">
                <label>التخصص:</label>
                <select name="specialization" required>
                    <option value="مكفوفين" <?= $school['specialization'] == 'مكفوفين' ? 'selected' : '' ?>>مكفوفين</option>
                    <option value="صعوبات تعلم" <?= $school['specialization'] == 'صعوبات تعلم' ? 'selected' : '' ?>>صعوبات تعلم</option>
                    <option value="بكم" <?= $school['specialization'] == 'بكم' ? 'selected' : '' ?>>بكم</option>
                    <option value="اعاقه سمعيه" <?= $school['specialization'] == 'اعاقه سمعيه' ? 'selected' : '' ?>>اعاقه سمعيه</option>
                    <option value="توحد" <?= $school['specialization'] == 'توحد' ? 'selected' : '' ?>>توحد</option>
                </select>
            </div>

            <div class="form-group">
                <label>الجنس:</label>
                <select name="gender" required>
                    <option value="بنات" <?= $school['gender'] == 'بنات' ? 'selected' : '' ?>>بنات</option>
                    <option value="بنين" <?= $school['gender'] == 'بنين' ? 'selected' : '' ?>>بنين</option>
                    <option value="مختلط" <?= $school['gender'] == 'مختلط' ? 'selected' : '' ?>>مختلط</option>
                </select>
            </div>

            <div class="form-group">
                <label>المرحلة:</label>
                <select name="stage" required>
                    <option value="ابتدائي" <?= $school['stage'] == 'ابتدائي' ? 'selected' : '' ?>>ابتدائي</option>
                    <option value="متوسط" <?= $school['stage'] == 'متوسط' ? 'selected' : '' ?>>متوسط</option>
                    <option value="ثانوي" <?= $school['stage'] == 'ثانوي' ? 'selected' : '' ?>>ثانوي</option>
                    <option value="جميع المراحل" <?= $school['stage'] == 'جميع المراحل' ? 'selected' : '' ?>>جميع المراحل</option>
                </select>
            </div>

            <div class="form-group">
                <label>الموقع:</label>
                <input type="text" name="location" value="<?= htmlspecialchars($school['location']) ?>" required>
            </div>

            <div class="form-group">
                <label>الصورة الحالية:</label>
                <img src="data:image/jpeg;base64,<?= base64_encode($school['image']) ?>" class="preview-image">
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