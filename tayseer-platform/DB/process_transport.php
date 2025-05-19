<?php
require '..\admin\auth_check.php';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
  $title = $_POST['title'];
  $description = $_POST['description'];
  $type = $_POST['type'];
  $map_link = $_POST['map_link'];
  
  // معالجة الصورة
  $target_dir = "uploads/";
  $target_file = $target_dir . basename($_FILES["image"]["name"]);
  move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
  
  $stmt = $conn->prepare("INSERT INTO transportation (title, description, image_url, map_link, type) VALUES (?, ?, ?, ?, ?)");
  $stmt->bind_param("sssss", $title, $description, $target_file, $map_link, $type);
  $stmt->execute();
  
  header("Location: ..\admin\admin_dashboard.php");
}