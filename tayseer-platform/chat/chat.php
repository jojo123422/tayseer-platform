<?php
define('INCLUDE_CHECK', true);

session_start();
require '../db/db_connect.php';

// جلب قائمة الاستشاريين
$consultants = $conn->query("SELECT * FROM consultants")->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;700&display=swap" rel="stylesheet">
  	    <link rel="stylesheet" href="../../include/styles.css">
    <!-- نفس أنماط الهيدر -->
    <style>
        .consultant-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            padding: 4rem 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .consultant-card {
            background: white;
            padding: 1.5rem;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            text-align: center;
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        .consultant-card:hover {
            transform: translateY(-5px);
        }
    </style>
</head>
<body>
    <?php require '../include/header.php'; ?>

    <main class="consultant-grid">
        <?php foreach($consultants as $consultant): ?>
            <div class="consultant-card" 
                 onclick="window.location.href='chat_window.php?consultant_id=<?= $consultant['id'] ?>'">
                <h3><?= htmlspecialchars($consultant['name']) ?></h3>
                <p>متخصص في: <?= htmlspecialchars($consultant['specialization']) ?></p>
            </div>
        <?php endforeach; ?>
    </main>
</body>
</html>