<?php
define('INCLUDE_CHECK', true);
require __DIR__ . '/../db/db_connect.php';

// جلب بيانات المراكز من قاعدة البيانات
$stmt = $conn->prepare("SELECT * FROM centers");
$stmt->execute();
$result = $stmt->get_result();
$centers = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;700&display=swap" rel="stylesheet">
  	    <link rel="stylesheet" href="../../include/styles.css">

  <title>المراكز والجمعيات</title>
  <style>
       /* تعريف المتغيرات اللونية الأساسية */
      :root {
      --primary-blue: #2B6A78;
      --secondary-teal: #244069;
      --accent-orange: #FF7F50;
      --light-cream: #F7F4EF;
      --dark-text: #2D3748;
    }

    /* تنسيق عام للصفحة */
    body {
      font-family: 'Tajawal', sans-serif;
      background-color: var(--light-cream);
      color: var(--dark-text);
      line-height: 1.8;
      margin: 0;
      scroll-behavior: smooth;
    }

    /* قائمة التنقل العلوية */
    .top-nav {
      background: #ede6db;
      padding: 1rem 2rem;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
      position: fixed;
      width: 100%;
      top: 0;
      z-index: 1000;
      backdrop-filter: blur(10px);
      display: flex;
      justify-content: space-between;
      align-items: center;
      height: 50px;
    }
    .nav-container {
      max-width: 1200px;
      margin: 0 auto;
      display: flex;
      align-items: center;
      width: 100%;
      justify-content: flex-start;
    }
    .logo img {
      height: 130px;
      transition: transform 0.3s ease;
      width: auto;
      order: -1;
      margin-right: -140px;
      margin-top: 12px;
    }
    .nav-links {
      display: flex;
      gap: 1.5rem;
      align-items: center;
      order: 1;
      margin-left: 2rem;
    }
    .nav-links a {
      color: var(--secondary-teal);
      text-decoration: none;
      padding: 0.5rem 1rem;
      border-radius: 25px;
      transition: all 0.3s ease;
      font-weight: 400;
      font-size: 0.95rem;
    }
    .nav-links a:hover {
      background: rgba(255, 255, 255, 0.2);
    }
    .header-icons {
      display: flex;
      gap: 1.2rem;
      color: var(--secondary-teal);
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
    .card img {
      width: 100%;
      height: 180px; /* أو أي ارتفاع يناسب تصميمك */
      object-fit: cover;
      border-radius: 8px; /* اختيارية لتنعيم الزوايا */
    }
    /* تنسيق الهيدر الخاص بالصفحة */
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
      text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
    }
    .healthcare-header p {
      font-size: 1.1rem;
      opacity: 0.9;
    }

    /* تنسيق الفلاتر */
    .filters {
      display: flex;
      justify-content: center;
      gap: 1rem;
      margin: 2rem 0;
    }
    .filters select {
      padding: 0.5rem 1rem;
      border-radius: 8px;
      border: 1px solid var(--secondary-teal);
      background-color: white;
      font-size: 1rem;
    }

    /* تنسيق بطاقات المراكز */
    .center-card {
      background-color: white;
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
      margin: 1rem;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .center-card:hover {
      transform: translateY(-10px);
      box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
    }
    .center-card img {
      width: 100%;
      height: 180px;
      object-fit: cover;
    }
    .center-info {
      padding: 1rem;
    }
    .center-info h3 {
      font-size: 1.5rem;
      margin-bottom: 0.5rem;
    }
    .center-info p {
      margin: 0.5rem 0;
    }

    .map-link {
      display: inline-block;
      margin-top: 10px;
      padding: 8px 12px;
      background-color: #00695c;
      color: white;
      border-radius: 20px;
      text-decoration: none;
      font-size: 0.9rem;
      transition: background-color 0.3s ease;
    }
    .map-link:hover {
      background-color: #004d40;
      transform: scale(1.05);
    }

    /* تنسيق الفوتر */
    footer {
      background-color: #283d3b;
      color: white;
      width: 100%;
      padding: 1rem 0;
      text-align: center;
      position: fixed;
      bottom: 0;
      font-size: 0.85rem;
    }

    /* تنسيق عند عدم وجود نتائج */
    #noResults {
      display: none;
      color: red;
      text-align: center;
      margin-top: 2rem;
    }
    
  </style>
</head>
<body>
    <?php require __DIR__ . '/../include/header.php'; ?>

  <header>
    <h1>المراكز والجمعيات</h1>
  </header>

  <div class="filters">
    <select id="specializationFilter">
      <option value="">الفئة المستهدفة</option>
      <option value="المكفوفين">المكفوفين</option>
      <option value="صعوبات التعلم">صعوبات التعلم</option>
      <option value="البكم">البكم</option>
      <option value="اعاقة سمعية">إعاقة سمعية</option>
      <option value="توحد">توحد</option>
      <option value="جميع الاحتياجات">جميع الاحتياجات</option>
    </select>

    <select id="genderFilter">
      <option value="">الكل</option>
      <option value="بنات">بنات</option>
      <option value="بنين">بنين</option>
    </select>
  </div>

  <div id="noResults">
    <p>لا توجد مراكز تطابق معايير الفلترة المحددة.</p>
  </div>

  <?php foreach ($centers as $center): ?>
    <div class="center-card" 
         data-specialization="<?= htmlspecialchars($center['specialization']) ?>" 
         data-gender="<?= htmlspecialchars($center['gender']) ?>">
      <img src="data:image/jpeg;base64,<?= base64_encode($center['image']) ?>" 
           alt="<?= htmlspecialchars($center['name']) ?>">
      <div class="center-info">
        <h3><?= htmlspecialchars($center['name']) ?></h3>
        <p>الفئة المستهدفة: <?= htmlspecialchars($center['specialization']) ?></p>
        <a href="<?= htmlspecialchars($center['map_link']) ?>" 
           class="map-link" 
           target="_blank">
           الموقع على الخريطة
        </a>
      </div>
    </div>
  <?php endforeach; ?>

  <footer>
    <p>جميع الحقوق محفوظة &copy; <?= date('Y') ?></p>
  </footer>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const specializationFilter = document.getElementById('specializationFilter');
      const genderFilter = document.getElementById('genderFilter');
      const cards = document.querySelectorAll('.center-card');

      function filterCenters() {
        const specialization = specializationFilter.value.trim().toLowerCase();
        const gender = genderFilter.value.trim().toLowerCase();
        let resultsFound = false;

        cards.forEach(card => {
          const cardSpecialization = card.dataset.specialization.toLowerCase();
          const cardGender = card.dataset.gender.toLowerCase();

          const specMatch = specialization === '' || cardSpecialization.includes(specialization);
          const genderMatch = gender === '' || cardGender.includes(gender);

          if (specMatch && genderMatch) {
            card.style.display = 'block';
            resultsFound = true;
          } else {
            card.style.display = 'none';
          }
        });

        document.getElementById('noResults').style.display = 
          resultsFound ? 'none' : 'block';
      }

      specializationFilter.addEventListener('change', filterCenters);
      genderFilter.addEventListener('change', filterCenters);
    });
  </script>
</body>
</html>