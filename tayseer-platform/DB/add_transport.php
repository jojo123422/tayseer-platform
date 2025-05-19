<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>النقل - تيسير</title>
  <!-- Font Awesome وخط تاجوال -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;700&display=swap" rel="stylesheet">
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
    /* شبكة البطاقات الخاصة بالنقل */
    .schools-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
      gap: 1rem; /* تقليل المسافة بين البطاقات */
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
      /* إزالة التقييد لجعل البطاقة تأخذ كامل مساحة العمود */
      margin: 0;
    }
    .school-card:hover {
      transform: translateY(-8px);
      box-shadow: 0 12px 40px rgba(0,0,0,0.15);
    }
    .school-image img {
      width: 100%;
      height: 240px;  /* جعل الصورة أكبر */
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
  <div class="container">
    <h2>إضافة وسيلة نقل جديدة</h2>
    <form action="process_transport.php" method="POST" enctype="multipart/form-data">
      <input type="text" name="title" placeholder="العنوان" required>
      <textarea name="description" placeholder="الوصف"></textarea>
      <select name="type" required>
        <option value="school">نقل مدرسي</option>
        <option value="taxi">نقل تكسي</option>
      </select>
      <input type="file" name="image" accept="image/*" required>
      <input type="text" name="map_link" placeholder="رابط الخريطة">
      <button type="submit" name="action" value="add">💾 حفظ</button>
    </form>
  </div>
</body>
</html>