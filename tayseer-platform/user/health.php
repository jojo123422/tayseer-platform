<?php
define('INCLUDE_CHECK', true);
require __DIR__ . '/../db/db_connect.php';

// جلب بيانات المراكز الصحية
$stmt = $conn->prepare("SELECT * FROM health_centers");
$stmt->execute();
$health_centers = $stmt->get_result();

// جمع أنواع الإعاقات للفلتر
$disability_types = [];

while ($row = $health_centers->fetch_assoc()) {
    $disability_types[$row['disability_type']] = true;
}

// إعادة تعيين مؤشر النتائج
$health_centers->data_seek(0);
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>المراكز الصحية - تيسير</title>
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
            color: #244069;
            margin-right: auto;
            order: 2;
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

        .healthcare-header h1 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }

        .healthcare-header p {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        .filter-container {
            margin: 2rem auto;
            max-width: 500px;
            position: relative;
        }

        #disabilityFilter {
            width: 100%;
            padding: 12px 20px;
            border-radius: 30px;
            border: 2px solid rgba(255,255,255,0.3);
            background: rgba(255,255,255,0.1);
            color: white;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            appearance: none;
            -webkit-appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%23fff' viewBox='0 0 16 16'%3E%3Cpath d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: 95% center;
            background-size: 12px;
        }

        #disabilityFilter:hover {
            background: rgba(255,255,255,0.2);
        }

        #disabilityFilter:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(43,106,120,0.3);
        }

        #disabilityFilter option {
            background: var(--primary-blue);
            color: white;
            padding: 10px;
        }

        .hospitals-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            padding: 4rem 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .hospital-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.1);
            overflow: hidden;
            transition: all 0.4s ease;
            position: relative;
            border: 1px solid rgba(43,106,120,0.1);
            opacity: 1;
            cursor: pointer;
        }

        .hospital-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 40px rgba(0,0,0,0.15);
        }

        .hospital-image {
            position: relative;
        }

        .hospital-image img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-bottom: 3px solid var(--primary-blue);
            transition: transform 0.3s ease;
        }

        .hospital-card:hover .hospital-image img {
            transform: scale(1.05);
        }

        .rating {
            position: absolute;
            bottom: 10px;
            left: 10px;
            background: rgba(255, 255, 255, 0.9);
            color: var(--accent-orange);
            padding: 6px 12px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            gap: 5px;
            font-weight: 700;
            backdrop-filter: blur(5px);
        }

        .hospital-info {
            padding: 1.5rem;
            text-align: right;
        }

        .hospital-info h3 {
            color: var(--secondary-teal);
            margin: 0 0 1rem;
            font-size: 1.3rem;
            line-height: 1.4;
        }

        .hospital-features {
            display: flex;
            flex-wrap: wrap;
            gap: 0.8rem;
            margin-bottom: 1.2rem;
        }

        .feature-badge {
            background: rgba(43, 106, 120, 0.1);
            padding: 8px 12px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.9rem;
        }

        .hospital-actions {
            display: flex;
            gap: 1rem;
            margin-top: 1.5rem;
        }

        .action-btn {
            flex: 1;
            padding: 12px;
            border-radius: 12px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-family: 'Tajawal', sans-serif;
            font-weight: 500;
        }

        .action-btn.primary {
            background: var(--primary-blue);
            color: white;
        }

        .action-btn.secondary {
            background: var(--accent-orange);
            color: white;
        }

        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        footer {
            background: var(--secondary-teal);
            color: white;
            margin-top: 4rem;
            position: relative;
            padding-top: 2rem;
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            gap: 2rem;
            padding: 2rem;
        }

        .social-links {
            display: flex;
            gap: 1.5rem;
            justify-content: center;
            margin-top: 1rem;
        }

        .social-links a {
            color: white;
            font-size: 1.5rem;
            transition: all 0.3s ease;
            padding: 0.5rem;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .social-links a:hover {
            transform: translateY(-3px);
            color: var(--accent-orange);
            background: rgba(255,255,255,0.1);
        }

        .copyright {
            text-align: center;
            padding-top: 2rem;
            border-top: 1px solid rgba(255,255,255,0.1);
            margin-top: 2rem;
            font-size: 0.9rem;
            opacity: 0.8;
        }

        .no-results {
            text-align: center;
            color: var(--primary-blue);
            font-size: 1.2rem;
            grid-column: 1 / -1;
            display: none;
            padding: 2rem;
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 768px) {
            .nav-links { display: none; }
            .header-icons { display: none; }
            .healthcare-header {
                padding: 120px 1rem 4rem;
                clip-path: polygon(0 0, 100% 0, 100% 95%, 0 100%);
            }
            .healthcare-header h1 { font-size: 2rem; }
            .hospitals-grid {
                grid-template-columns: 1fr;
                padding: 3rem 1rem;
            }
            .logo img {
                height: 100px;
                margin-right: -100px;
            }
            #disabilityFilter {
                font-size: 0.9rem;
                padding: 10px 15px;
                background-position: 97% center;
            }
            .hospital-info h3 { font-size: 1.2rem; }
            .hospital-actions {
                flex-direction: column;
            }
            .action-btn {
                width: 100%;
            }
        }

        @media (max-width: 480px) {
            .top-nav { padding: 1rem; }
            .logo img {
                height: 80px;
                margin-right: -80px;
            }
            .healthcare-header { margin-top: 50px; }
            .hospital-info { padding: 1rem; }
        }
    </style>
</head>
<body>
  <?php require __DIR__ . '/../include/header.php'; ?>

    <header class="healthcare-header">
        <div class="hero-content">
            <h1>المراكز الصحية المعتمدة</h1>
            <p>اكتشف أفضل المراكز الصحية الموثوقة في منطقتك</p>
            <div class="filter-container">
                <select id="disabilityFilter" onchange="filterHospitals()">
                    <option value="all">جميع الأنواع</option>
                    <?php foreach (array_keys($disability_types) as $type): ?>
                        <option value="<?= htmlspecialchars($type) ?>">
                            <?= htmlspecialchars($type) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </header>

    <main class="hospitals-grid">
        <div class="no-results" id="noResults">
            🏥 لا توجد مراكز متاحة لنوع الإعاقة المحددة
        </div>

        <?php while ($center = $health_centers->fetch_assoc()): ?>
            <div class="hospital-card" data-disability="<?= htmlspecialchars($center['disability_type']) ?>">
                <div class="hospital-image">
                    <img src="data:image/jpeg;base64,<?= base64_encode($center['image']) ?>" 
                         alt="<?= htmlspecialchars($center['name']) ?>">
                    <div class="rating">
                        <i class="fas fa-star"></i>
                        <span><?= number_format($center['rating'], 1) ?></span>
                    </div>
                </div>
                
                <div class="hospital-info">
                    <h3><?= htmlspecialchars($center['name']) ?></h3>
                    
                    <div class="hospital-features">
                        <div class="feature-badge">
                            <i class="fas fa-map-marker-alt"></i>
                            <?= htmlspecialchars($center['location']) ?>
                        </div>
                        <div class="feature-badge">
                            <i class="fas fa-stethoscope"></i>
                            <?= htmlspecialchars($center['specialization']) ?>
                        </div>
                    </div>

                    <div class="hospital-actions">
                        <button class="action-btn primary" 
                                onclick="window.location='center_details.php?id=<?= $center['id'] ?>'">
                            <i class="fas fa-info-circle"></i>
                            التفاصيل
                        </button>
                        <button class="action-btn secondary" 
                                onclick="window.open('tel:<?= $center['phone'] ?>')">
                            <i class="fas fa-phone"></i>
                            اتصل بنا
                        </button>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </main>

    <footer id="contact">
        <div class="footer-content">
            <div class="social-links">
                <a href="#"><i class="fab fa-twitter"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
                <a href="#"><i class="fab fa-linkedin"></i></a>
            </div>
            <div class="copyright">
                <p>© 2024 منصة تيسير. جميع الحقوق محفوظة</p>
                <p>موقعنا الإلكتروني: <a href="https://www.example.com">www.example.com</a></p>
            </div>
        </div>
    </footer>

    <script>
        function filterHospitals() {
            const selectedType = document.getElementById('disabilityFilter').value;
            const hospitals = document.querySelectorAll('.hospital-card');
            const noResults = document.getElementById('noResults');
            let visibleCount = 0;

            hospitals.forEach(hospital => {
                const hospitalType = hospital.dataset.disability;
                const shouldShow = selectedType === 'all' || hospitalType === selectedType;

                hospital