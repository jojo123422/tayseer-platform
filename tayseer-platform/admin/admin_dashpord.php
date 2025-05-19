<?php
require __DIR__ . '/auth_check.php';
require __DIR__ . '/../db/db_connect.php';
$conn = new mysqli("localhost:3307", "root", "", "taisir_platform");

$chartsData = [
    // بيانات الرسم الدائري لأنواع المراكز
    'centers' => [
        'تعليمية' => $conn->query("SELECT COUNT(*) FROM schools")->fetch_row()[0],
        'صحية' => $conn->query("SELECT COUNT(*) FROM health_centers")->fetch_row()[0],
        'نقل' => $conn->query("SELECT COUNT(*) FROM transportation")->fetch_row()[0]
    ],
    
    // بيانات الرسم الشريطي لمراحل المدارس
    'school_stages' => $conn->query("
        SELECT 
            stage,
            COUNT(*) as count 
        FROM schools 
        GROUP BY stage
        ORDER BY FIELD(stage, 'ابتدائي', 'متوسط', 'ثانوي', 'جميع المراحل')
    ")->fetch_all(MYSQLI_ASSOC)
];
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>لوحة التحكم - تيسير</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;700&display=swap" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  
  <style>
    :root {
      --primary-color: #2B6A78;
      --secondary-color: #244069;
      --accent-color: #FF7F50;
      --light-bg: #F7F4EF;
      --dark-text: #2D3748;
    }

    body {
      font-family: 'Tajawal', sans-serif;
      background: var(--light-bg);
      margin: 0;
    }

    .admin-container {
      display: grid;
      grid-template-columns: 250px 1fr;
      gap: 2rem;
      padding: 2rem;
    }

    /* القائمة الجانبية */
    .sidebar {
      background: white;
      border-radius: 15px;
      padding: 1.5rem;
      box-shadow: 0 2px 15px rgba(0,0,0,0.1);
    }

    .sidebar-nav a {
      display: flex;
      align-items: center;
      gap: 1rem;
      padding: 1rem;
      margin: 0.5rem 0;
      border-radius: 8px;
      color: var(--dark-text);
      text-decoration: none;
      transition: all 0.3s;
    }

    .sidebar-nav a:hover {
      background: var(--primary-color);
      color: white;
    }

    /* محتوى اللوحة */
    .dashboard-content {
      background: white;
      border-radius: 15px;
      padding: 2rem;
      box-shadow: 0 2px 15px rgba(0,0,0,0.1);
    }

    /* بطاقات الإحصائيات */
    .stats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 1.5rem;
      margin-bottom: 3rem;
    }

    .stat-card {
      background: var(--light-bg);
      padding: 1.5rem;
      border-radius: 10px;
      text-align: center;
      transition: transform 0.3s;
    }

    .stat-card:hover {
      transform: translateY(-5px);
    }

    .stat-value {
      font-size: 2.5rem;
      font-weight: 700;
      color: var(--primary-color);
    }

    /* الرسوم البيانية */
    .charts-container {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 2rem;
      margin: 2rem 0;
    }

    .chart-box {
      padding: 1.5rem;
      background: white;
      border-radius: 15px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    /* الجداول الحديثة */
    .recent-activity {
      margin: 2rem 0;
    }

    .recent-table {
      width: 100%;
      border-collapse: collapse;
      margin: 1rem 0;
    }

    .recent-table th, 
    .recent-table td {
      padding: 1rem;
      border-bottom: 1px solid #eee;
      text-align: right;
    }

    .recent-table th {
      background: var(--primary-color);
      color: white;
    }

    .table-responsive {
      overflow-x: auto;
    }
  </style>
</head>
<body>

<div class="admin-container">
  <!-- القائمة الجانبية -->
  <aside class="sidebar">
    <h2 style="text-align: center; margin-bottom: 2rem;">القائمة</h2>
    <nav class="sidebar-nav">
      <a href="center\centers_admin.php">
        <i class="fas fa-building"></i>
        المراكز
      </a>
      <a href="health\health_dashboard.php">
        <i class="fas fa-hospital"></i>
        المراكز الصحية
      </a>
      <a href="school\schools_dashboard.php">
        <i class="fas fa-school"></i>
        المدارس
      </a>
      <a href="transport\transport_admin.php">
        <i class="fas fa-bus"></i>
        النقل
      </a>
      <a href="users\users_admin.php">
        <i class="fas fa-users"></i>
        المستخدمين
      </a>
    </nav>
  </aside>

  <!-- المحتوى الرئيسي -->
  <main class="dashboard-content">
    <h1>مرحبا بك في لوحة التحكم</h1>

    <!-- إحصائيات سريعة -->
    <div class="stats-grid">
      <?php
      $stats = [
        'users' => $conn->query("SELECT COUNT(*) FROM users")->fetch_row()[0],
        'schools' => $conn->query("SELECT COUNT(*) FROM schools")->fetch_row()[0],
        'health' => $conn->query("SELECT COUNT(*) FROM health_centers")->fetch_row()[0],
        'transport' => $conn->query("SELECT COUNT(*) FROM transportation")->fetch_row()[0]
      ];
      ?>

      <div class="stat-card">
        <div class="stat-value"><?= $stats['users'] ?></div>
        <p>المستخدمين</p>
        <i class="fas fa-users fa-2x"></i>
      </div>

      <div class="stat-card">
        <div class="stat-value"><?= $stats['schools'] ?></div>
        <p>المدارس</p>
        <i class="fas fa-school fa-2x"></i>
      </div>

      <div class="stat-card">
        <div class="stat-value"><?= $stats['health'] ?></div>
        <p>المراكز الصحية</p>
        <i class="fas fa-hospital fa-2x"></i>
      </div>

      <div class="stat-card">
        <div class="stat-value"><?= $stats['transport'] ?></div>
        <p>وسائل النقل</p>
        <i class="fas fa-bus fa-2x"></i>
      </div>
    </div>

    <!-- الرسوم البيانية -->
    <div class="charts-container">
      <div class="chart-box">
        <canvas id="centersChart"></canvas>
      </div>
      <div class="chart-box">
        <canvas id="schoolsChart"></canvas>
      </div>
    </div>

    <!-- آخر الإضافات -->
    <div class="recent-activity">
      <h2>آخر الإضافات</h2>
      <div class="table-responsive">
        <table class="recent-table">
          <thead>
            <tr>
              <th>النوع</th>
              <th>الاسم</th>
              <th>تاريخ الإضافة</th>
              <th>الإجراءات</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $recent = $conn->query("
              (SELECT 'مدرسة' as type, name, created_at FROM schools ORDER BY created_at DESC LIMIT 2)
              UNION
              (SELECT 'مركز صحي' as type, name, created_at FROM health_centers ORDER BY created_at DESC LIMIT 2)
              ORDER BY created_at DESC LIMIT 4
            ");
            
            while($row = $recent->fetch_assoc()):
            ?>
            <tr>
              <td><?= $row['type'] ?></td>
              <td><?= $row['name'] ?></td>
              <td><?= date('Y-m-d', strtotime($row['created_at'])) ?></td>
              <td>
                <a href="#" class="edit-btn"><i class="fas fa-edit"></i></a>
                <a href="#" class="delete-btn"><i class="fas fa-trash"></i></a>
              </td>
            </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    </div>

  </main>
</div>

<script>
// رسم بياني الدائري لأنواع المراكز
const centersData = {
    labels: <?= json_encode(array_keys($chartsData['centers'])) ?>,
    datasets: [{
        data: <?= json_encode(array_values($chartsData['centers'])) ?>,
        backgroundColor: ['#2B6A78', '#244069', '#FF7F50'],
    }]
};

new Chart(document.getElementById('centersChart'), {
    type: 'pie',
    data: centersData
});

// رسم بياني شريطي لمراحل المدارس
const schoolsData = {
    labels: <?= json_encode(array_column($chartsData['school_stages'], 'stage')) ?>,
    datasets: [{
        label: 'عدد المدارس',
        data: <?= json_encode(array_column($chartsData['school_stages'], 'count')) ?>,
        backgroundColor: '#2B6A78',
    }]
};

new Chart(document.getElementById('schoolsChart'), {
    type: 'bar',
    data: schoolsData,
    options: {
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            }
        }
    }
});
</script>

</body>
</html>