<?php
define('INCLUDE_CHECK', true);
require __DIR__ . '/../db/db_connect.php';

// جلب البيانات من قاعدة البيانات
$stmt = $conn->prepare("SELECT * FROM schools");
$stmt->execute();
$schools = $stmt->get_result();

// جلب القيم الفريدة للفلتر
$specializations = [];
$genders = [];
$stages = [];

while ($row = $schools->fetch_assoc()) {
    // معالجة التخصصات
    $specs = explode(',', $row['specialization']);
    foreach ($specs as $spec) {
        $specializations[trim($spec)] = true;
    }
    
    // جمع الجنسين والمراحل
    $genders[$row['gender']] = true;
    $stages[$row['stage']] = true;
}

// إعادة تعيين مؤشر النتائج
$schools->data_seek(0);
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <!-- نفس الجزء العلوي من الهيدر -->
  <style>
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

    /* قسم الفلتر */
    .filter-container {
      margin: 2rem auto;
      max-width: 800px;
      display: flex;
      gap: 1rem;
      flex-wrap: wrap;
      justify-content: center;
    }
    .filter-container select {
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
    .filter-container select:hover {
      background: rgba(255,255,255,0.2);
    }
    .filter-container select:focus {
      outline: none;
      box-shadow: 0 0 0 3px rgba(43,106,120,0.3);
    }
    .filter-container select option {
      background: var(--primary-blue);
      color: white;
      padding: 10px;
    }

    /* شبكة البطاقات الخاصة بالمدارس */
    .schools-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 2rem;
      padding: 4rem 2rem;
      max-width: 1200px;
      margin: 0 auto;
    }
    .school-card {
      background: white;
      border-radius: 15px;
      box-shadow: 0 8px 30px rgba(0,0,0,0.1);
      overflow: hidden;
      transition: all 0.4s ease;
      position: relative;
      border: 1px solid rgba(43,106,120,0.1);
      opacity: 1;
      cursor: pointer;
      /* تعديل حجم البطاقة عند ظهور عدد قليل منها */
      max-width: 350px;
      margin: 0 auto;
    }
    .school-card:hover {
      transform: translateY(-8px);
      box-shadow: 0 12px 40px rgba(0,0,0,0.15);
    }
    .school-image img {
      width: 100%;
      height: 180px;  /* تم تقليل الارتفاع من 200px إلى 180px */
      object-fit: cover;
      border-bottom: 3px solid var(--primary-blue);
      transition: transform 0.3s ease;
    }
    .school-card:hover .school-image img {
      transform: scale(1.05);
    }
    .school-info {
      padding: 1.5rem;
      text-align: right;
    }
    .school-info h3 {
      color: var(--secondary-teal);
      margin: 0 0 1rem;
      font-size: 1.3rem;
      line-height: 1.4;
    }
    .school-info p {
      color: var(--dark-text);
      font-size: 0.95rem;
      line-height: 1.6;
      margin: 0.5rem 0;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }
    .school-info p::before {
      content: '';
      width: 6px;
      height: 6px;
      background: var(--accent-orange);
      border-radius: 50%;
      display: inline-block;
      margin-left: 5px;
    }
    .map-link {
      display: inline-block;
      margin-top: 10px;
      background-color: var(--primary-blue);
      color: white;
      padding: 8px 12px;
      border-radius: 20px;
      text-decoration: none;
      font-size: 0.9rem;
      transition: background-color 0.3s ease;
    }
    .map-link:hover {
      background-color: var(--accent-orange);
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

    /* التذييل */
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

    /* استجابة الشاشات */
    @media (max-width: 768px) {
      .nav-links { display: none; }
      .header-icons { display: none; }
      .healthcare-header {
        padding: 120px 1rem 4rem;
        clip-path: polygon(0 0, 100% 0, 100% 95%, 0 100%);
      }
      .healthcare-header h1 { font-size: 2rem; }
      .schools-grid {
        grid-template-columns: 1fr;
        padding: 3rem 1rem;
      }
      .logo img {
        height: 100px;
        margin-right: -100px;
      }
      .filter-container select {
        font-size: 0.9rem;
        padding: 10px 15px;
        background-position: 97% center;
      }
      .school-info h3 { font-size: 1.2rem; }
    }
    @media (max-width: 480px) {
      .top-nav { padding: 1rem; }
      .logo img {
        height: 80px;
        margin-right: -80px;
      }
      .healthcare-header { margin-top: 50px; }
      .school-info { padding: 1rem; }
    }
  </style>
</head>
<body>
<div>
		    <link rel="stylesheet" href="../../include/styles.css">
</div>
  <!-- الهيدر الرئيسي مع الفلترة -->
    <?php require __DIR__ . '/../include/header.php'; ?>

  <header class="healthcare-header">
    <div class="hero-content">
      <h1>المدارس المخصصة لذوي الاحتياجات الخاصة</h1>
      <p>اكتشف أفضل المؤسسات التعليمية المتخصصة في الرعاية والتدريس</p>
      <div class="filter-container">
        <!-- فلتر التخصصات -->
        <select id="categoryFilter" onchange="filterSchools()">
          <option value="all">كل الفئات</option>
          <?php foreach (array_keys($specializations) as $spec): ?>
            <option value="<?= htmlspecialchars($spec) ?>"><?= htmlspecialchars($spec) ?></option>
          <?php endforeach; ?>
        </select>

        <!-- فلتر الجنس -->
        <select id="genderFilter" onchange="filterSchools()">
          <option value="all">كل الجنسين</option>
          <?php foreach (array_keys($genders) as $gender): ?>
            <option value="<?= htmlspecialchars($gender) ?>"><?= htmlspecialchars($gender) ?></option>
          <?php endforeach; ?>
        </select>

        <!-- فلتر المرحلة -->
        <select id="stageFilter" onchange="filterSchools()">
          <option value="all">جميع المراحل</option>
          <?php foreach (array_keys($stages) as $stage): ?>
            <option value="<?= htmlspecialchars($stage) ?>"><?= htmlspecialchars($stage) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>
  </header>

  <!-- المحتوى الرئيسي: بطاقات المدارس -->
  <main class="schools-grid">
    <div class="no-results" id="noResults">
      🏫 لا توجد مدارس متاحة للتصنيف المحدد
    </div>

    <?php while ($school = $schools->fetch_assoc()): ?>
      <div class="school-card" 
           data-gender="<?= htmlspecialchars($school['gender']) ?>"
           data-category="<?= htmlspecialchars(str_replace(',', '', $school['specialization'])) ?>"
           data-stage="<?= htmlspecialchars($school['stage']) ?>">
        
        <div class="school-image">
          <img src="data:image/jpeg;base64,<?= base64_encode($school['image']) ?>" 
               alt="<?= htmlspecialchars($school['name']) ?>">
        </div>
        
        <div class="school-info">
          <h3><?= htmlspecialchars($school['name']) ?></h3>
          <p>📚 التخصص: <?= htmlspecialchars($school['specialization']) ?></p>
          <p>🏫 المرحلة: <?= htmlspecialchars($school['stage']) ?></p>
          <a href="<?= htmlspecialchars($school['location']) ?>" 
             class="map-link" 
             target="_blank">الموقع على الخريطة</a>
        </div>
      </div>
    <?php endwhile; ?>

  </main>

         <?php require __DIR__ . '/../include/footer.php'; ?>


  <!-- نفس سكريبت الفلترة -->
  <script>
    function filterSchools() {
      const selectedCategory = document.getElementById('categoryFilter').value;
      const selectedGender = document.getElementById('genderFilter').value;
      const selectedStage = document.getElementById('stageFilter').value;
      const schools = document.querySelectorAll('.school-card');
      const noResults = document.getElementById('noResults');
      let visibleCount = 0;

      schools.forEach(school => {
        const cardCategory = school.dataset.category;
        const cardGender = school.dataset.gender;
        const cardStage = school.dataset.stage;

        const matchCategory = selectedCategory === 'all' || 
                            cardCategory.includes(selectedCategory);
        const matchGender = selectedGender === 'all' || 
                           cardGender === selectedGender;
        const matchStage = selectedStage === 'all' || 
                          cardStage === selectedStage;

        if(matchCategory && matchGender && matchStage) {
          school.style.display = 'block';
          visibleCount++;
        } else {
          school.style.display = 'none';
        }
      });

      noResults.style.display = visibleCount === 0 ? 'block' : 'none';
    }
  </script>
</body>
</html>