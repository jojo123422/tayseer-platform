<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تيسير - بوابة الخدمات الشاملة</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-blue: #2B6A78;
            --secondary-teal: #244069;
            --accent-orange: #FF7F50;
            --light-cream: #F7F4EF;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Tajawal', sans-serif;
            background-color: #f5f5f5;
            overflow-x: hidden;
            height: 100vh;
            perspective: 1000px;
        }

        /* شاشة الترحيب */
        .welcome-screen {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, var(--primary-blue), var(--secondary-teal));
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            z-index: 1000;
            transition: all 1s ease;
            opacity: 1;
            color: white;
        }

        .welcome-screen.hidden {
            opacity: 0;
            pointer-events: none;
        }

        .logo {
            width: 200px;
            height: 200px;
            margin-bottom: 2rem;
            animation: float 3s ease-in-out infinite;
            filter: drop-shadow(0 10px 20px rgba(0,0,0,0.3));
        }

        .welcome-message {
            text-align: center;
            max-width: 600px;
            padding: 0 2rem;
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInUp 1s ease forwards 1s;
        }

        .welcome-message h1 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            text-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }

        .welcome-message p {
            font-size: 1.2rem;
            line-height: 1.6;
        }

        /* شاشة الاختيار */
        .selection-screen {
            display: none;
            position: relative;
            width: 100%;
            height: 100vh;
            overflow: hidden;
        }

        .selection-container {
            position: relative;
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            transform-style: preserve-3d;
        }

        .option-card {
            position: relative;
            width: 300px;
            height: 400px;
            margin: 0 2rem;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
            transform-style: preserve-3d;
            transition: all 0.8s cubic-bezier(0.25, 0.8, 0.25, 1);
            cursor: pointer;
            overflow: hidden;
        }

        .option-card:hover {
            transform: translateY(-10px) scale(1.03);
            box-shadow: 0 30px 60px rgba(0,0,0,0.3);
        }

        .option-card.user {
            background: linear-gradient(45deg, #f5f7fa, #c3cfe2);
        }

        .option-card.consultant {
            background: linear-gradient(45deg, #e0f7fa, #80deea);
        }

        .card-content {
            position: relative;
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 2rem;
            transform-style: preserve-3d;
            z-index: 2;
        }

        .card-icon {
            font-size: 5rem;
            margin-bottom: 2rem;
            color: var(--secondary-teal);
            transform: translateZ(50px);
        }

        .card-title {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: var(--secondary-teal);
            transform: translateZ(50px);
        }

        .card-desc {
            text-align: center;
            font-size: 1rem;
            color: #555;
            transform: translateZ(30px);
        }

        .card-bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-size: cover;
            background-position: center;
            opacity: 0.1;
            z-index: 1;
        }

        .user .card-bg {
            background-image: url('https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?ixlib=rb-1.2.1&auto=format&fit=crop&w=634&q=80');
        }

        .consultant .card-bg {
            background-image: url('https://images.unsplash.com/photo-1581093450022-7fdf5a432e1e?ixlib=rb-1.2.1&auto=format&fit=crop&w=634&q=80');
        }

        /* تأثيرات الحركة */
        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(2deg); }
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes cardEntrance {
            from { opacity: 0; transform: translateY(50px) rotateY(30deg); }
            to { opacity: 1; transform: translateY(0) rotateY(0); }
        }

        /* تصميم متجاوب */
        @media (max-width: 768px) {
            .selection-container {
                flex-direction: column;
            }

            .option-card {
                margin: 1rem 0;
                width: 280px;
                height: 350px;
            }

            .welcome-message h1 {
                font-size: 2rem;
            }

            .welcome-message p {
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>
    <!-- شاشة الترحيب -->
    <div class="welcome-screen" id="welcomeScreen">
        <img src="logo.png" alt="شعار تيسير" class="logo">
        <div class="welcome-message">
            <h1>مرحبًا بك في تيسير – دليلك الشامل للخدمات! 🌟</h1>
            <p>وفرنا لك دليلًا متكاملًا لأفضل المراكز الصحية، أماكن التعليم، والخدمات المختلفة، مما يسهل عليك الوصول إلى الخيارات الأنسب لك بسرعة وسهولة.</p>
        </div>
    </div>

    <!-- شاشة اختيار طريقة الدخول -->
    <div class="selection-screen" id="selectionScreen">
        <div class="selection-container">
            <div class="option-card user" onclick="redirectTo('user')">
                <div class="card-content">
                    <i class="fas fa-user card-icon"></i>
                    <h2 class="card-title">الدخول كمستخدم</h2>
                    <p class="card-desc">استكشف الخدمات المتاحة واحصل على التوصيات الشخصية بناءً على احتياجاتك</p>
                </div>
                <div class="card-bg"></div>
            </div>
            
            <div class="option-card consultant" onclick="redirectTo('consultant')">
                <div class="card-content">
                    <i class="fas fa-headset card-icon"></i>
                    <h2 class="card-title">الدخول كاستشاري</h2>
                    <p class="card-desc">ادارة الطلبات والرد على استفسارات المستخدمين وتقديم الدعم اللازم</p>
                </div>
                <div class="card-bg"></div>
            </div>
        </div>
    </div>

    <script>
        // إخفاء شاشة الترحيب بعد 6 ثواني
        setTimeout(() => {
            document.getElementById('welcomeScreen').classList.add('hidden');
            document.getElementById('selectionScreen').style.display = 'block';
            
            // إضافة تأثيرات للبطاقات
            const cards = document.querySelectorAll('.option-card');
            cards.forEach((card, index) => {
                card.style.animation = `cardEntrance 0.8s ease forwards ${index * 0.2 + 0.5}s`;
                card.style.opacity = '0';
            });
        }, 6000);

        // توجيه المستخدم حسب الاختيار
        function redirectTo(type) {
            const card = document.querySelector(`.option-card.${type}`);
            card.style.transform = 'translateY(-20px) scale(1.05)';
            card.style.boxShadow = '0 40px 80px rgba(0,0,0,0.4)';
            
            setTimeout(() => {
                if(type === 'user') {
                    window.location.href = 'log/log.html';
                } else {
                    window.location.href = 'chat/login_consultant.php';
                }
            }, 800);
        }

        // تأثيرات ثلاثية الأبعاد للبطاقات
        document.querySelectorAll('.option-card').forEach(card => {
            card.addEventListener('mousemove', (e) => {
                const xAxis = (window.innerWidth / 2 - e.pageX) / 25;
                const yAxis = (window.innerHeight / 2 - e.pageY) / 25;
                card.style.transform = `rotateY(${xAxis}deg) rotateX(${yAxis}deg) translateY(-10px)`;
            });

            card.addEventListener('mouseenter', () => {
                card.style.transition = 'none';
            });

            card.addEventListener('mouseleave', () => {
                card.style.transition = 'all 0.5s ease';
                card.style.transform = 'rotateY(0) rotateX(0) translateY(-10px)';
            });
        });
    </script>
</body>
</html>