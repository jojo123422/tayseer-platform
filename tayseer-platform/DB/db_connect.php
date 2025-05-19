<?php
$conn = new mysqli("localhost:3307", "root", "", "taisir_platform");
if ($conn->connect_error) {
  die("فشل الاتصال: " . $conn->connect_error);
}
?>