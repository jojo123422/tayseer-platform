<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../log/login.php");
    exit();
}

require __DIR__ . '/../../db/db_connect.php';

try {
    $result = $conn->query("SELECT 
        id,
        type,
        name,
        description,
        image_url,
        map_link,
        created_at 
    FROM transportation 
    ORDER BY created_at DESC");
    
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($row['name']) . '</td>';
            echo '<td>' . htmlspecialchars($row['type']) . '</td>';
            echo '<td>' . htmlspecialchars(substr($row['description'], 0, 50)) . '...</td>';
            echo '<td><img src="data:image/jpeg;base64,' . base64_encode($row['image_url']) . '" class="transport-image"></td>';
            echo '<td>';
            if ($row['map_link']) {
                echo '<a href="' . htmlspecialchars($row['map_link']) . '" target="_blank" class="map-link">';
                echo '<i class="fas fa-map-marker-alt"></i>';
                echo '</a>';
            } else {
                echo '--';
            }
            echo '</td>';
            echo '<td>';
            echo '<div class="action-buttons">';
            // التصحيح هنا
            echo '<a href="edit_transport.php?id=' . $row['id'] . '" class="btn-edit">';
            echo '<i class="fas fa-pencil-alt"></i>';
            echo '</a>';
            // التصحيح هنا
            echo '<a href="delete_transport.php?id=' . $row['id'] . '" class="btn-delete" onclick="return confirm(\'هل أنت متأكد من الحذف؟\')">';
            echo '<i class="fas fa-trash"></i>';
            echo '</a>';
            echo '</div>';
            echo '</td>';
            echo '</tr>';
        }
    } else {
        echo '<tr><td colspan="6">لا توجد خدمات نقل مسجلة</td></tr>';
    }
    
} catch (Exception $e) {
    echo '<tr><td colspan="6">خطأ في جلب البيانات: ' . $e->getMessage() . '</td></tr>';
} finally {
    $conn->close();
}
?>