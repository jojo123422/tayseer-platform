<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['type'] !== 'user') {
  header("Location: index.html");
  exit();
}
?>

<h1>أهلًا بك أيها المستخدم، <?php echo $_SESSION['fullname']; ?>!</h1>
<a href="logout.php">تسجيل الخروج</a>
