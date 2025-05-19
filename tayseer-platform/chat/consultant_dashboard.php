<?php
session_start();
require '../db/db_connect.php';

// التحقق من صلاحية الاستشاري
if(!isset($_SESSION['consultant_id'])) {
    header("Location: consultant_login.php");
    exit();
}

// جلب قائمة المستخدمين
$users = $conn->query("
    SELECT DISTINCT u.id, u.fullname 
    FROM users u
    JOIN chats c ON u.id = c.user_id
    WHERE c.consultant_id = {$_SESSION['consultant_id']}
")->fetch_all(MYSQLI_ASSOC);

// معالجة البيانات عند اختيار مستخدم
$current_user_id = isset($_GET['user_id']) ? $_GET['user_id'] : null;
$current_user = null;

if($current_user_id) {
    // جلب محادثات المستخدم المحدد
    $messages = $conn->prepare("SELECT * FROM chats 
                              WHERE user_id = ? AND consultant_id = ?
                              ORDER BY created_at ASC");
    $messages->bind_param("ii", $current_user_id, $_SESSION['consultant_id']);
    $messages->execute();
    $chat_result = $messages->get_result();
    
    // جلب بيانات المستخدم الحالي
    $user_stmt = $conn->prepare("SELECT fullname FROM users WHERE id = ?");
    $user_stmt->bind_param("i", $current_user_id);
    $user_stmt->execute();
    $current_user = $user_stmt->get_result()->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة الاستشاري</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --primary-blue: #1a365d;
            --accent-orange: #e67e22;
            --light-cream: #f9f6f0;
        }

        body {
            margin: 0;
            font-family: 'Tahoma', sans-serif;
        }

        .container {
            display: flex;
            height: 100vh;
        }

        /* الجانب الأيمن - قائمة المستخدمين */
        .user-sidebar {
            width: 300px;
            background: #f8f9fa;
            border-left: 1px solid #ddd;
            overflow-y: auto;
        }

        .user-list {
            padding: 1rem;
        }

        .user-card {
            padding: 1rem;
            margin: 0.5rem 0;
            background: white;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .user-card:hover {
            background: var(--light-cream);
            transform: translateX(-5px);
        }

        .user-card.active {
            border-right: 4px solid var(--primary-blue);
        }

        /* منطقة الدردشة الرئيسية */
        .chat-main {
            flex: 1;
            background: white;
            display: flex;
            flex-direction: column;
        }

        .chat-header {
            padding: 1rem;
            border-bottom: 1px solid #eee;
            background: var(--primary-blue);
            color: white;
        }

        .chat-messages {
            flex: 1;
            overflow-y: auto;
            padding: 1rem;
            background: #f0f2f5;
        }

        .message {
            max-width: 70%;
            margin: 1rem;
            padding: 1rem;
            border-radius: 15px;
            position: relative;
        }

        .user-message {
            background: white;
            margin-right: auto;
        }

        .consultant-message {
            background: var(--primary-blue);
            color: white;
            margin-left: auto;
        }

        .message-time {
            font-size: 0.75rem;
            color: #666;
            margin-top: 0.5rem;
        }

        .chat-input {
            padding: 1rem;
            border-top: 1px solid #eee;
            background: white;
        }

        #message-form {
            display: flex;
            gap: 1rem;
        }

        #message-input {
            flex: 1;
            padding: 0.8rem;
            border: 1px solid #ddd;
            border-radius: 8px;
        }

        .btn-send {
            background: var(--primary-blue);
            color: white;
            border: none;
            padding: 0.8rem 1.5rem;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .btn-send:hover {
            background: var(--accent-orange);
        }

        .no-chat {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #666;
            font-size: 1.2rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- قائمة المستخدمين -->
        <div class="user-sidebar">
            <div class="user-list">
                <h2 style="margin-top: 0;">المستخدمون</h2>
                <?php foreach($users as $user): ?>
                    <div class="user-card <?= $current_user_id == $user['id'] ? 'active' : '' ?>" 
                         onclick="window.location.href='?user_id=<?= $user['id'] ?>'">
                        <h3><?= htmlspecialchars($user['fullname']) ?></h3>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- منطقة الدردشة -->
        <div class="chat-main">
            <?php if($current_user && $current_user_id): ?>
                <div class="chat-header">
                    <h2><?= htmlspecialchars($current_user['fullname']) ?></h2>
                </div>

                <div class="chat-messages">
                    <?php while($msg = $chat_result->fetch_assoc()): ?>
                        <div class="message <?= $msg['sender'] === 'user' ? 'user-message' : 'consultant-message' ?>">
                            <p><?= htmlspecialchars($msg['message']) ?></p>
                            <div class="message-time">
                                <?= date('H:i | Y/m/d', strtotime($msg['created_at'])) ?>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>

                <div class="chat-input">
                    <form method="POST" id="message-form">
                        <input type="hidden" name="user_id" value="<?= $current_user_id ?>">
                        <input type="text" 
                               id="message-input" 
                               name="message" 
                               placeholder="اكتب رسالتك..."
                               required>
                        <button type="submit" class="btn-send">إرسال</button>
                    </form>
                </div>
            <?php else: ?>
                <div class="no-chat">
                    <p>اختر مستخدمًا لبدء المحادثة</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php
    // معالجة إرسال الرسالة
    if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'])) {
        $message = htmlspecialchars($_POST['message']);
        $user_id = $_POST['user_id'];
        
        $stmt = $conn->prepare("INSERT INTO chats 
                              (user_id, consultant_id, message, sender) 
                              VALUES (?, ?, ?, 'consultant')");
        $stmt->bind_param("iis", $user_id, $_SESSION['consultant_id'], $message);
        $stmt->execute();
        
        header("Location: ?user_id=$user_id");
        exit();
    }
    ?>
</body>
</html>