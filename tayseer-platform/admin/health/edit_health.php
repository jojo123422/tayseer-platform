<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../log/login.php");
    exit();
}

require __DIR__ . '/../../db/db_connect.php';

// جلب بيانات المركز الحالي
$center = [];
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT * FROM health_centers WHERE id = ?");
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
    $disability_type = htmlspecialchars($_POST['disability_type']);
    $location = htmlspecialchars($_POST['location']);
    $services = htmlspecialchars($_POST['services']);
    
    // معالجة الصورة الجديدة إذا تم رفعها
    $imageData = $center['image'];
    if (!empty($_FILES['image']['tmp_name'])) {
        $imageData = file_get_contents($_FILES['image']['tmp_name']);
    }

    $stmt = $conn->prepare("UPDATE health_centers SET
        name = ?,
        specialization = ?,
        disability_type = ?,
        location = ?,
        services = ?,
        image = ?
        WHERE id = ?");
        
    $stmt->bind_param("ssssssi", 
        $name,
        $specialization,
        $disability_type,
        $location,
        $services,
        $imageData,
        $id
    );

    if ($stmt->execute()) {
        $_SESSION['success'] = "تم تحديث بيانات المركز بنجاح";
        header("Location: health_dashboard.php");
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
    <title>تعديل مركز صحي</title>
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
        <h2>✏️ تعديل مركز صحي</h2>

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
                <label>التخصص:</label>
                <input type="text" name="specialization" value="<?= htmlspecialchars($center['specialization']) ?>" required>
            </div>

            <div class="form-group">
                <label>نوع الإعاقة:</label>
                <select name="disability_type" required>
                    <option value="visual" <?= $center['disability_type'] == 'visual' ? 'selected' : '' ?>>الإعاقة البصرية</option>
                    <option value="physical" <?= $center['disability_type'] == 'physical' ? 'selected' : '' ?>>الإعاقة الحركية</option>
                    <option value="hearing" <?= $center['disability_type'] == 'hearing' ? 'selected' : '' ?>>الإعاقة السمعية</option>
                    <option value="intellectual" <?= $center['disability_type'] == 'intellectual' ? 'selected' : '' ?>>الإعاقة الذهنية</option>
                </select>
            </div>

            <div class="form-group">
                <label>الموقع:</label>
                <input type="text" name="location" value="<?= htmlspecialchars($center['location']) ?>" required>
            </div>

            <div class="form-group">
                <label>الخدمات:</label>
                <textarea name="services" rows="3" required><?= htmlspecialchars($center['services']) ?></textarea>
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