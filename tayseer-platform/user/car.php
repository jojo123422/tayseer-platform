<?php
define('INCLUDE_CHECK', true);
require __DIR__ . '/../db/db_connect.php';

// استعلام لجلب بيانات النقل
$transportData = [];
$sql = "SELECT * FROM transportation";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $transportData[] = $row;
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>خدمات النقل - تيسير</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;700&display=swap" rel="stylesheet">
	    <link rel="stylesheet" href="../../include/styles.css">

    <style>
        :root {
            --primary-blue: #2B6A78;
            --secondary-teal: #244069;
            --accent-orange: #FF7F50;
            --light-cream: #F7F4EF;
            --dark-text: #2D3748;
        }

        body {
            font-family: 'Tajawal', sans-serif;
            background-color: var(--light-cream);
            color: var(--dark-text);
            line-height: 1.8;
            margin: 0;
            scroll-behavior: smooth;
        }

        

        .header-icons {
            display: flex;
            gap: 1.2rem;
            color: var(--secondary-teal);
        }

        .header-icons i {
            cursor: pointer;
            transition: transform 0.2s ease;
        }

        .header-icons i:hover {
            transform: scale(1.1);
            color: var(--primary-blue);
        }

        .healthcare-header {
            padding: 130px 2rem 5rem;
            background: linear-gradient(rgba(43,106,120,0.9), rgba(26,54,93,0.9));
            color: white;
            text-align: center;
            clip-path: polygon(0 0, 100% 0, 100% 90%, 0 100%);
            margin-top: 70px;
        }

        .transport-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            padding: 4rem 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .transport-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.1);
            overflow: hidden;
            transition: all 0.4s ease;
            position: relative;
            border: 1px solid rgba(43,106,120,0.1);
        }

        .transport-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 40px rgba(0,0,0,0.15);
        }

        .transport-image img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-bottom: 3px solid var(--primary-blue);
            transition: transform 0.3s ease;
        }

        .transport-card:hover .transport-image img {
            transform: scale(1.05);
        }

        .transport-info {
            padding: 1.5rem;
            text-align: right;
        }

        .transport-info h3 {
            color: var(--secondary-teal);
            margin: 0 0 1rem;
            font-size: 1.3rem;
        }

        .transport-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 1rem;
        }

        .transport-type {
            background: rgba(43,106,120,0.1);
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.9rem;
        }

        .map-link {
            background: var(--primary-blue);
            color: white;
            padding: 8px 12px;
            border-radius: 20px;
            text-decoration: none;
            transition: background 0.3s ease;
        }

        .map-link:hover {
            background: var(--accent-orange);
        }

        footer {
            background: var(--secondary-teal);
            color: white;
            padding: 4rem 2rem 2rem;
            margin-top: 4rem;
            text-align: center;
        }

        .social-links {
            display: flex;
            gap: 1.5rem;
            justify-content: center;
            margin-bottom: 2rem;
        }

        .social-links a {
            color: white;
            font-size: 1.5rem;
            transition: transform 0.3s ease;
        }

        .social-links a:hover {
            transform: translateY(-3px);
            color: var(--accent-orange);
        }

        @media (max-width: 768px) {
            .nav-links { display: none; }
            .header-icons { display: none; }
            .healthcare-header {
                padding: 120px 1rem 4rem;
                clip-path: polygon(0 0, 100% 0, 100% 95%, 0 100%);
            }
            .transport-grid {
                grid-template-columns: 1fr;
                padding: 3rem 1rem;
            }
            
        }
    </style>
</head>
<body>
    <?php require __DIR__ . '/../include/header.php'; ?>

    <header class="healthcare-header">
        <div class="hero-content">
            <h1>خدمات النقل المتاحة</h1>
            <p>اختر وسيلة النقل المناسبة لاحتياجاتك</p>
        </div>
    </header>

    <main class="transport-grid">
        <?php if (!empty($transportData)): ?>
            <?php foreach ($transportData as $service): ?>
                <div class="transport-card" onclick="window.open('<?= htmlspecialchars($service['map_link']) ?>', '_blank')">
                    <div class="transport-image">
                        <img src="data:image/jpeg;base64,<?= base64_encode($service['image_url']) ?>" 
                             alt="<?= htmlspecialchars($service['name']) ?>">
                    </div>
                    <div class="transport-info">
                        <h3><?= htmlspecialchars($service['name']) ?></h3>
                        <p><?= htmlspecialchars($service['description']) ?></p>
                        <div class="transport-meta">
                            <span class="transport-type">
                                <i class="fas fa-tag"></i>
                                <?= htmlspecialchars($service['type']) ?>
                            </span>
                            <a href="<?= htmlspecialchars($service['map_link']) ?>" 
                               class="map-link" 
                               target="_blank">
                               <i class="fas fa-map-marker-alt"></i>
                               الموقع
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="no-results">
                <i class="fas fa-exclamation-triangle"></i>
                لا توجد خدمات نقل متاحة حاليًا
            </div>
        <?php endif; ?>
    </main>

        <?php require __DIR__ . '/../include/footer.php'; ?>

</body>
</html>