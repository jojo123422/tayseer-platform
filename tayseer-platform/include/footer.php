<?php
// التحقق من أن الملف لا يتم استدعاؤه مباشرةً
if (!defined('INCLUDE_CHECK')) {
    die('غير مصرح بالوصول المباشر');
}

// التحقق من حالة تسجيل الدخول
if (!isset($_SESSION['user_id']) && basename($_SERVER['PHP_SELF']) != 'login.php') {
    header("Location: /login.php");
    exit();
}
?>
  <!-- التذييل -->
  <footer id="contact">
    <div class="footer-content">
      <div class="social-links">
        <a href="#"><i class="fab fa-twitter"></i></a>
        <a href="#"><i class="fab fa-instagram"></i></a>
        <a href="#"><i class="fab fa-facebook"></i></a>
      </div>
      <div class="copyright">
        <p>جميع الحقوق محفوظة © 2025</p>
      </div>
    </div>
  </footer>
</body>
</html>