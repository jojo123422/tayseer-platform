<?php
define('INCLUDE_CHECK', true);

session_start();
require '../db/db_connect.php';

$consultant_id = $_GET['consultant_id'];
$user_id = $_SESSION['user_id'];

// جلب تاريخ المحادثة
$messages = $conn->prepare("SELECT * FROM chats 
                          WHERE (user_id = ? AND consultant_id = ?)
                          ORDER BY created_at ASC");
$messages->bind_param("ii", $user_id, $consultant_id);
$messages->execute();
$result = $messages->get_result();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>محادثة مع الاستشاري</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../include/styles.css">
    <style>
        :root {
            --primary-blue: #1a365d;
            --accent-orange: #e67e22;
            --light-cream: #f9f6f0;
        }

        body {
            background: #f5f5f5;
            font-family: 'Tajawal', sans-serif;
        }

        .chat-container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 2rem;
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .chat-header {
            text-align: center;
            padding-bottom: 1.5rem;
            border-bottom: 2px solid #eee;
            margin-bottom: 2rem;
        }

        .message {
            margin: 1rem 0;
            padding: 1.5rem;
            border-radius: 15px;
            max-width: 70%;
            position: relative;
        }

        .user-message {
            background: var(--light-cream);
            margin-left: auto;
            border-bottom-right-radius: 5px;
        }

        .consultant-message {
            background: var(--primary-blue);
            color: white;
            margin-right: auto;
            border-bottom-left-radius: 5px;
        }

        .message-time {
            font-size: 0.8rem;
            color: #666;
            margin-top: 0.5rem;
            display: block;
            text-align: left;
        }

        #message-form {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 2px solid #eee;
        }

        #message-input {
            flex-grow: 1;
            padding: 0.8rem 1.2rem;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }

        #message-input:focus {
            border-color: var(--primary-blue);
            outline: none;
        }

        .btn-primary {
            background: var(--primary-blue);
            color: white;
            padding: 0.8rem 1.5rem;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 1rem;
        }

        .btn-primary:hover {
            background: var(--accent-orange);
            transform: translateY(-2px);
        }

        @media (max-width: 768px) {
            .chat-container {
                margin: 1rem;
                padding: 1rem;
            }
            
            .message {
                max-width: 85%;
                padding: 1rem;
            }
        }
    </style>
</head>
<body>
    <?php require '../include/header.php'; ?>

    <div class="chat-container">
        <div class="chat-header">
            <h1>محادثة مع الاستشاري</h1>
        </div>

        <?php while($msg = $result->fetch_assoc()): ?>
            <div class="message <?= $msg['sender'] === 'user' ? 'user-message' : 'consultant-message' ?>">
                <p><?= htmlspecialchars($msg['message']) ?></p>
                <span class="message-time">
                    <?= date('H:i | Y/m/d', strtotime($msg['created_at'])) ?>
                </span>
            </div>
        <?php endwhile; ?>

        <form id="message-form" onsubmit="sendMessage(event)">
            <input type="hidden" id="consultant_id" value="<?= $consultant_id ?>">
            <input type="text" 
                   id="message-input" 
                   placeholder="اكتب رسالتك..."
                   autocomplete="off">
            <button type="submit" class="btn-primary">إرسال</button>
        </form>
    </div>

    <script>
    function sendMessage(e) {
        e.preventDefault();
        const messageInput = document.getElementById('message-input');
        const message = messageInput.value.trim();
        const consultantId = document.getElementById('consultant_id').value;

        if(!message) return;

        fetch('send_message.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                message: message,
                consultant_id: consultantId
            })
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                messageInput.value = '';
                location.reload();
            }
        });
    }
    </script>
</body>
</html>