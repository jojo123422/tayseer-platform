<?php
define('INCLUDE_CHECK', true);

require __DIR__ . '/../db/db_connect.php';

// استعلامات الإحصائيات
$stats = [
    'schools' => $conn->query("SELECT COUNT(*) as count FROM schools")->fetch_assoc()['count'],
    'health_centers' => $conn->query("SELECT COUNT(*) as count FROM health_centers")->fetch_assoc()['count'],
    'transport' => $conn->query("SELECT COUNT(*) as count FROM transportation")->fetch_assoc()['count'],
    'users' => $conn->query("SELECT COUNT(*) as count FROM users")->fetch_assoc()['count'],
    
    'schools_by_stage' => $conn->query("SELECT stage, COUNT(*) as count FROM schools GROUP BY stage")->fetch_all(MYSQLI_ASSOC),
    'health_by_disability' => $conn->query("SELECT disability_type, COUNT(*) as count FROM health_centers GROUP BY disability_type")->fetch_all(MYSQLI_ASSOC),
    'transport_by_type' => $conn->query("SELECT type, COUNT(*) as count FROM transportation GROUP BY type")->fetch_all(MYSQLI_ASSOC),
];
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>منصة تيسير التعليمية</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
        }

     


        .header-icons {
            display: flex;
            gap: 1.2rem;
            color: #244069;
            margin-right: auto;
            

            order: 2; /* إضافة هذه الخاصية */
        }

        .header-icons i {
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 1.2rem;
        }

        .header-icons i:hover {
            transform: translateY(-2px);
            opacity: 0.9;
            color:#244069;
        }

        .hero-header {
            padding: 130px 2rem 5rem;
            background: linear-gradient(rgba(43,106,120,0.9), rgba(26,54,93,0.9));
            color: white;
            text-align: center;
            clip-path: polygon(0 0, 100% 0, 100% 90%, 0 100%);
            margin-top: 70px;
        }

        .hero-header h1 {
            font-size: 2.8rem;
            margin-bottom: 1.5rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }

        .services-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); /* الأعمدة تتكيف مع الحجم */
    gap: 1.5rem; /* المسافة بين البطاقات */
    padding: 4rem 2rem; /* المسافة حول الشبكة */
    max-width: 1200px;
    margin: 0 auto; /* تمركز الشبكة في الصفحة */
}

.service-card {
    background: white;
    padding: 1.8rem;
    border-radius: 15px;
    box-shadow: 0 8px 30px rgba(0,0,0,0.1);
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    position: relative;
    overflow: hidden;
    border: 1px solid rgba(43,106,120,0.1);
}

.service-card::after {
    content: '';
    position: absolute;
    bottom: -40px;
    right: -40px;
    width: 80px;
    height: 80px;
    background: var(--primary-blue);
    opacity: 0.1;
    border-radius: 50%;
    transition: all 0.3s ease;
}

.service-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 40px rgba(0,0,0,0.15);
}

.service-icon {
    color: var(--primary-blue);
    margin-bottom: 1rem;
    font-size: 2rem !important;
}

.service-card h3 {
    font-size: 1.2rem;
    margin: 1rem 0;
}

.service-card p {
    font-size: 0.95rem;
    line-height: 1.6;
}

/* لضمان أن الشبكة متجاورة بشكل جيد */
@media (max-width: 768px) {
    .services-grid {
        grid-template-columns: 1fr; /* عمود واحد في الشاشات الصغيرة */
    }
}


        .about-image img {
            width: 100%;
            max-width: 500px;
            border-radius: 15px;
            box-shadow: 20px 20px 0 var( --secondary-teal);
            transition: transform 0.3s ease;
            margin-bottom: 70px;
        }

        .about-image:hover img {
            transform: translate(-10px, -10px);
        }

        .features-list {
            list-style: none;
            padding: 0;
        }

        .features-list li {
            position: relative;
            padding-right: 35px;
            margin-bottom: 1.5rem;
            font-size: 1.1rem;
        }

        .features-list i {
            position: absolute;
            right: -25px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--accent-orange);
            font-size: 1.2rem;
        }

        footer {
            background: var(--secondary-teal);
            color: white;
            /* padding: 4rem 1rem 2rem; */
            margin-top: -3rem;
            position: relative;
           
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            gap: 2rem;
        }

        .footer-columns {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 2rem;
            text-align: center;
            position: relative;
            padding-top: 2rem;
        }

        .footer-columns::before {
            content: '';
            position: absolute;
            top: 0;
            right: 50%;
            transform: translateX(50%);
            width: 200px;
            height: 3px;
            background:#2B6A78;;
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
            transition: transform 0.3s ease;
            padding: 0.5rem;
        }

        .social-links a:hover {
            transform: translateY(-3px);
            color: var(--accent-orange);
        }

        .copyright {
            text-align: center;
            padding-top: 2rem;
            border-top: 1px solid rgba(255,255,255,0.1);
            margin-top: 2rem;
            font-size: 0.9rem;
        }

        @media (max-width: 768px) {
            .nav-links {
                display: none;
            }
            
            .header-icons {
                display: none;
            }

            .hero-header {
                padding: 120px 1rem 4rem;
                clip-path: polygon(0 0, 100% 0, 100% 95%, 0 100%);
            }

            .hero-header h1 {
                font-size: 2rem;
            }

            .services-grid {
                grid-template-columns: 1fr;
                padding: 3rem 1rem;
            }

            .about-section {
                grid-template-columns: 1fr;
                text-align: center;
                padding: 2rem 1rem;
                margin: 2rem auto;
            }

            .about-image img {
                box-shadow: 10px 10px 0 var(--primary-blue);
            }

            .footer-columns {
                grid-template-columns: 1fr;
            }
        }

        @media (min-width: 1200px) {
            .services-grid {
                grid-template-columns: repeat(4, 1fr);
            }
        }

 
    
        /* أنماط الإحصائيات الجديدة */
        .stats-section {
            padding: 4rem 2rem;
            background: #f8f9fa;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .stat-card {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .stat-number {
            font-size: 2.5rem;
            color: var(--primary-blue);
            font-weight: 700;
            margin: 1rem 0;
        }

        .chart-container {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            margin: 2rem 0;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <?php require __DIR__ . '/../include/header.php'; ?>

  <header class="hero-header">
        <div class="hero-content">
            <h1>مرحبًا بك في تيسير – دليلك الشامل للخدمات! 🌟</h1>
            <p>وفرنا لك دليلًا متكاملًا لأفضل المراكز الصحية، أماكن التعليم، والخدمات المختلفة، مما يسهل عليك الوصول إلى الخيارات الأنسب لك بسرعة وسهولة. </p>
        </div>
    </header>

   <section id="services">
        <div class="services-grid">
            <a href="School.php" "class="service-card">
                <div class="service-icon">
                    <i class="fas fa-book-open"></i>
                </div>
                <h3>تعليم</h3>
                <p>نوفر لك جميع المدارس والمؤسسات التعليمية التي تناسب احتياجاتك.</p>
            </a>
    
            <a href="http://localhost/pnu2/user/center.php"" class="service-card">
                <div class="service-icon">
                    <i class="fas fa-university"></i>
                </div>
                <h3>مراكز</h3>
                <p>مجموعة متنوعة من المراكز التي تلبي احتياجاتك، سواء كانت ترفيهية أو تدريبية.</p>
            </a>
    
            <a href="http://localhost/pnu2/user/health.php" class="service-card">
                <div class="service-icon">
                    <i class="fas fa-heartbeat"></i>
                </div>
                <h3>مراكز صحية</h3>
                <p>نوفر لك أفضل المراكز الصحية التي تقدم خدمات طبية متكاملة.</p>
            </a>
    
            <a href="car.php" class="service-card">
                <div class="service-icon">
                    <i class="fas fa-bus"></i>
                </div>
                <h3>نقل</h3>
                <p>خدمات نقل آمنة وسريعة تناسب احتياجات الأفراد والشركات.</p>
            </a>
        </div>
    </section>
    
    
    <section id="about" class="about-section">
        <div class="about-image">
            <img src="t.png" alt="واجهة المنصة">
        </div>
        
        <div class="about-content">
            <h2>لماذا تختار منصتنا؟🤔✨</h2>
            <ul class="features-list">
                <li><i class="fas fa-check-circle"></i>  توفير الوقت والجهد – بدلاً من البحث المطول، وفر وقتك مع "مَعًا".</li>
                <li><i class="fas fa-check-circle"></i> دقة وتحديث مستمر – نحرص على تقديم بيانات محدثة وموثوقة.</li>
                <li><i class="fas fa-check-circle"></i> تجربة مستخدم سلسة – تصميم بسيط وسهل الاستخدام.</li>
                <li><i class="fas fa-check-circle"></i> اختيار أوسع – قارن بين الخيارات المتاحة واختر الأفضل لك.</li>
                <!-- <p>اختر "مَعًا" وتمتع بتجربة ذكية وسلسة للوصول إلى الخدمات التي تهمك! </p> -->
            </ul>
        </div>
    </section>

    <!-- قسم الإحصائيات الجديد -->
    <section class="stats-section" id="statistics">
        <h2 style="text-align: center; margin-bottom: 2rem;">الإحصائيات العامة</h2>
        
        <div class="stats-grid">
            <div class="stat-card">
                <i class="fas fa-school" style="font-size: 2rem; color: var(--secondary-teal);"></i>
                <div class="stat-number"><?= $stats['schools'] ?></div>
                <p>عدد المدارس المسجلة</p>
            </div>

            <div class="stat-card">
                <i class="fas fa-hospital" style="font-size: 2rem; color: var(--accent-orange);"></i>
                <div class="stat-number"><?= $stats['health_centers'] ?></div>
                <p>عدد المراكز الصحية</p>
            </div>

            <div class="stat-card">
                <i class="fas fa-bus" style="font-size: 2rem; color: var(--primary-blue);"></i>
                <div class="stat-number"><?= $stats['transport'] ?></div>
                <p>خدمات النقل المتاحة</p>
            </div>

            <div class="stat-card">
                <i class="fas fa-users" style="font-size: 2rem; color: var(--secondary-teal);"></i>
                <div class="stat-number"><?= $stats['users'] ?></div>
                <p>عدد المستخدمين</p>
            </div>
        </div>

        <!-- الرسوم البيانية -->
        <div class="stats-grid">
            <div class="chart-container">
                <canvas id="schoolsChart"></canvas>
            </div>

            <div class="chart-container">
                <canvas id="healthChart"></canvas>
            </div>

            <div class="chart-container">
                <canvas id="transportChart"></canvas>
            </div>
        </div>
    </section>

    <script>
        // تكوين الرسوم البيانية
        const chartOptions = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {position: 'top', rtl: true},
                tooltip: {rtl: true}
            }
        };

        // رسم بياني للمدارس حسب المرحلة
        new Chart(document.getElementById('schoolsChart'), {
            type: 'bar',
            data: {
                labels: <?= json_encode(array_column($stats['schools_by_stage'], 'stage')) ?>,
                datasets: [{
                    label: 'عدد المدارس',
                    data: <?= json_encode(array_column($stats['schools_by_stage'], 'count')) ?>,
                    backgroundColor: '#2B6A78'
                }]
            },
            options: chartOptions
        });

        // رسم بياني للمراكز الصحية
        new Chart(document.getElementById('healthChart'), {
            type: 'pie',
            data: {
                labels: <?= json_encode(array_column($stats['health_by_disability'], 'disability_type')) ?>,
                datasets: [{
                    label: 'التوزيع',
                    data: <?= json_encode(array_column($stats['health_by_disability'], 'count')) ?>,
                    backgroundColor: ['#FF7F50', '#2B6A78', '#244069']
                }]
            },
            options: chartOptions
        });

        // رسم بياني للنقل
        new Chart(document.getElementById('transportChart'), {
            type: 'doughnut',
            data: {
                labels: <?= json_encode(array_column($stats['transport_by_type'], 'type')) ?>,
                datasets: [{
                    label: 'أنواع النقل',
                    data: <?= json_encode(array_column($stats['transport_by_type'], 'count')) ?>,
                    backgroundColor: ['#FF7F50', '#2B6A78', '#244069']
                }]
            },
            options: chartOptions
        });
    </script>

</body>
</html>