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
    $result = $conn->query("SELECT * FROM health_centers ORDER BY created_at DESC");
    
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($row['name']) . '</td>';
            echo '<td>' . htmlspecialchars($row['specialization']) . '</td>';
            echo '<td>' . htmlspecialchars($row['disability_type']) . '</td>';
            echo '<td><img src="data:image/jpeg;base64,' . base64_encode($row['image']) . '" class="health-image"></td>';
            echo '<td>';
						echo '<div class="action-buttons">';
            echo '<a href="edit_health.php?id=' . $row['id'] . '" class="btn-edit">';
            echo '<i class="fas fa-pencil-alt"></i>';
            echo '</a>';
            echo '<a href="delete_health.php?id=' . $row['id'] . '" class="btn-delete" onclick="return confirm(\'?? ??? ????? ?? ??????\')">';
            echo '<i class="fas fa-trash"></i>';
            echo '</a>';
            echo '</div>';
			echo '</td>';
            echo '</tr>';
        }
    } else {
        echo '<tr><td colspan="5">لا توجد مراكز مسجلة</td></tr>';
    }
    
} catch (Exception $e) {
    echo '<tr><td colspan="5">خطأ في جلب البيانات: ' . $e->getMessage() . '</td></tr>';
} finally {
    $conn->close();
}
?>