<?php
define('INCLUDE_CHECK', true);
require __DIR__ . '/../auth_check.php';
require __DIR__ . '/../../db/db_connect.php';
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة التحكم - إدارة خدمات النقل</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../include/styles.css">
    <style>
        :root {
            --primary-blue: #2B6A78;
            --secondary-teal: #3A6B7C;
            --accent-orange: #FF7F50;
            --light-cream: #F7F4EF;
            --dark-text: #2D3748;
            --hover-bg: #f0f4f7;
        }

        body {
            font-family: 'Tajawal', sans-serif;
            background: var(--light-cream);
            padding: 120px 20px 40px;
        }

        .management-panel {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 25px rgba(0,0,0,0.08);
            padding: 2rem;
        }

        .page-header {
            text-align: center;
            margin-bottom: 2rem;
            padding: 0 1rem;
        }

        .page-title {
            color: var(--primary-blue);
            font-size: 2.2rem;
            margin: 0 0 2rem;
            position: relative;
            display: inline-block;
        }

        .page-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 3px;
            background: var(--accent-orange);
        }

        .table-controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .add-btn {
            background: var(--primary-blue);
            padding: 0.8rem 1.5rem;
            border: none;
            border-radius: 25px;
            color: white;
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            transition: all 0.2s ease;
            text-decoration: none;
        }

        .add-btn:hover {
            background: var(--secondary-teal);
            transform: translateY(-2px);
            box-shadow: 0 3px 12px rgba(43,106,120,0.2);
        }

        .schools-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            margin-top: 1rem;
        }

        .schools-table thead {
            background: var(--primary-blue);
            color: white;
        }

        .schools-table th {
            padding: 1.2rem;
            font-weight: 500;
            border-bottom: 3px solid var(--accent-orange);
        }

        .schools-table td {
            padding: 1rem;
            border-bottom: 1px solid #eaeef1;
            transition: background 0.2s;
        }

        .schools-table tr:last-child td {
            border-bottom: none;
        }

        .schools-table tr:hover td {
            background: var(--hover-bg);
        }

        .action-buttons {
            display: flex;
            gap: 0.6rem;
            justify-content: center;
        }

        .btn-edit, .btn-delete {
            width: 36px;
            height: 36px;
            border: none;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .btn-edit {
            background: #e8f4fc;
            color: var(--primary-blue);
        }

        .btn-edit:hover {
            background: var(--primary-blue);
            color: white;
        }

        .btn-delete {
            background: #fbe9eb;
            color: #e74c3c;
        }

        .btn-delete:hover {
            background: #e74c3c;
            color: white;
        }

        .transport-image {
            width: 60px;
            height: 60px;
            border-radius: 8px;
            object-fit: cover;
            border: 2px solid #f0f0f0;
        }

        @media (max-width: 768px) {
            .management-panel {
                padding: 1.5rem;
                border-radius: 12px;
            }
            
            .page-title {
                font-size: 1.8rem;
            }
            
            .add-btn {
                padding: 0.7rem 1.2rem;
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
    <?php require __DIR__ . '/../../include/header.php'; ?>
    
    <div class="management-panel">
        <div class="page-header">
            <h1 class="page-title">إدارة خدمات النقل</h1>
        </div>

        <div class="table-controls">
            <a href="add_transport.php" class="add-btn">
                <i class="fas fa-plus"></i>
                إضافة خدمة جديدة
            </a>
        </div>

        <table class="schools-table">
            <thead>
                <tr>
                    <th>اسم الخدمة</th>
                    <th>النوع</th>
                    <th>الوصف</th>
                    <th>الصورة</th>
                    <th>الخريطة</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                <?php include 'fetch_transport.php'; ?>
            </tbody>
        </table>
    </div>

    <?php require __DIR__ . '/../../include/footer.php'; ?>
</body>
</html>