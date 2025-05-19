<?php
if (!defined('INCLUDE_CHECK')) {
    die('غير مصرح بالوصول المباشر');
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id']) && basename($_SERVER['PHP_SELF']) != 'login.php') {
    header("Location: /login.php");
    exit();
}

$isAdmin = isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'admin';
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-blue: #2B6A78;
            --secondary-teal: #244069;
            --accent-orange: #FF7F50;
            --light-cream: #F7F4EF;
            --dark-text: #2D3748;
        }

        .top-nav {
            background: #ede6db;
            padding: 1rem 2rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 60px;
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
        }

        .nav-links {
            display: flex;
            gap: 2rem;
            align-items: center;
        }

        .nav-links a {
            color: var(--secondary-teal);
            text-decoration: none;
            padding: 0.5rem 1rem;
            border-radius: 25px;
            transition: all 0.3s ease;
        }

        .nav-links a:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .admin-links {
            background: var(--primary-blue);
        }

        .admin-links a {
            color: white !important;
        }

        .admin-links a:hover {
            background: rgba(255, 255, 255, 0.1) !important;
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

        .logo img {
            height: 130px;
            transition: transform 0.3s ease;
            width: auto;
        }

        .user-nav .nav-links {
            margin-left: auto;
        }

        @media (max-width: 768px) {
            .nav-links {
                display: none;
            }
            .logo img {
                height: 100px;
            }
        }
    </style>
</head>
<body>
 <?php if ($isAdmin): ?>
        <!-- هيدر المدير -->
        <nav class="top-nav">
            <div class="nav-container">
                <div class="logo">
                    <img src="logo.png" alt="شعار تيسير">
                </div>
                <div class="nav-links">
                    <a href="../admin_dashpord.php">لوحة التحكم</a>
                    <a href="../health/health_dashboard.php">المراكز الصحية</a>
                    <a href="../school/schools_dashboard.php">إدارة المدارس</a>
                    <a href="../center/centers_admin.php">المراكز المتخصصة</a>
                    <a href="../transport/transport_admin.php">وسائل النقل</a>
                </div>
                <div class="header-icons">
                    <i class="fas fa-cog" title="الإعدادات"></i>
                    <i class="fas fa-sign-out-alt" title="تسجيل الخروج" onclick="window.location.href='http://localhost/PNU2/log/logout.php'"></i>
                </div>
            </div>
        </nav>
    <?php else: ?>
        <!-- هيدر المستخدم العادي -->
        <nav class="top-nav">
            <div class="nav-container">
                <div class="logo">
                    <img src="logo.png" alt="شعار تيسير">
                </div>
                <div class="nav-links">
                    <a href="//localhost/PNU2/user/main.php">الرئيسية</a>
                    <a href="//localhost/PNU2/user/school.php">التعليم</a>
                    <a href="//localhost/PNU2/user/health.php">المراكز الصحية</a>
                    <a href="//localhost/PNU2/user/center.php">المراكز</a>
                    <a href="car.php">النقل</a>
                </div>
                <div class="header-icons">
                    <i class="fas fa-user" title="الحساب" onclick="window.location.href='/profile.php'"></i>
                    <i class="fas fa-bell" title="الإشعارات"></i>
                    <i class="fas fa-sign-out-alt" title="تسجيل الخروج" onclick="window.location.href='http://localhost/PNU2/log/logout.php'"></i>
                </div>
            </div>
        </nav>
        <div class="chat-float" onclick="window.location.href='../chat/chat.php'">
            <i class="fas fa-comments"></i>
        </div>
    <?php endif; ?>
	
</body>
</html>