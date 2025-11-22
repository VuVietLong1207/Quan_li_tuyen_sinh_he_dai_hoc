<?php
require_once __DIR__ . '/../functions/db_connection.php';

header('Content-Type: application/json; charset=utf-8');

$username = $_GET['username'] ?? null;
$new_password = $_GET['password'] ?? null;

if (!$username || !$new_password) {
    echo json_encode(['success' => false, 'message' => 'Thiếu tham số. Sử dụng ?username=...&password=...']);
    exit;
}

try {
    $hashed = password_hash($new_password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE username = ? LIMIT 1");
    $stmt->execute([$hashed, $username]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => true, 'message' => 'Mật khẩu đã được đặt lại.', 'username' => $username]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Không tìm thấy username hoặc không có thay đổi.']);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Lỗi DB: ' . $e->getMessage()]);
}

?>
