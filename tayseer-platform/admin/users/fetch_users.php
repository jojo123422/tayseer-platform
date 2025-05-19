<?php
$stmt = $conn->prepare("SELECT id, fullname, phone, type, created_at FROM users ORDER BY created_at DESC");
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($row['fullname']) . '</td>';
        echo '<td>' . htmlspecialchars($row['phone']) . '</td>';
        echo '<td><span class="user-badge-' . $row['type'] . '">' . 
             ($row['type'] == 'admin' ? 'مدير' : 'مستخدم') . '</span></td>';
        echo '<td>' . date('Y/m/d', strtotime($row['created_at'])) . '</td>';
        echo '<td>';
        echo '<div class="action-buttons">';
        echo '<a href="edit_user.php?id=' . $row['id'] . '" class="btn-edit">';
        echo '<i class="fas fa-pencil-alt"></i></a>';
        echo '<a href="delete_user.php?id=' . $row['id'] . '" class="btn-delete" 
              onclick="return confirm(\'هل أنت متأكد من الحذف؟\')">';
        echo '<i class="fas fa-trash"></i></a>';
        echo '</div>';
        echo '</td>';
        echo '</tr>';
    }
} else {
    echo '<tr><td colspan="5">لا يوجد مستخدمين مسجلين</td></tr>';
}
?>