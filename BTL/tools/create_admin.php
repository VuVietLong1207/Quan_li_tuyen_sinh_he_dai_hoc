<?php
require_once __DIR__ . '/../functions/db_connection.php';

header('Content-Type: application/json; charset=utf-8');

$username = $_GET['username'] ?? 'admin';
$password = $_GET['password'] ?? 'admin';
$email = $_GET['email'] ?? 'admin@example.com';
$full_name = $_GET['full_name'] ?? 'Administrator';
// Accept user_type param but restrict to allowed values for safety
$user_type = $_GET['user_type'] ?? 'admin';
if (!in_array($user_type, ['admin', 'candidate'])) {
    $user_type = 'admin';
}

try {
    // Check if username exists
    $check = $pdo->prepare("SELECT id, user_type FROM users WHERE username = ? LIMIT 1");
    $check->execute([$username]);
    $existing = $check->fetch(PDO::FETCH_ASSOC);

    if ($existing) {
        echo json_encode(['success' => false, 'message' => 'Username đã tồn tại. Vui lòng chọn username khác.']);
        exit;
    }

    $hashed = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (username, password, email, full_name, user_type, last_login, created_at) VALUES (?, ?, ?, ?, ?, NULL, NOW())");
    $stmt->execute([$username, $hashed, $email, $full_name, $user_type]);

    $displayType = $user_type === 'admin' ? 'Cán bộ tuyển sinh (admin)' : 'Thí sinh (candidate)';
    echo json_encode(['success' => true, 'message' => "Tài khoản $displayType đã được tạo.", 'username' => $username, 'password' => $password, 'user_type' => $user_type]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Lỗi DB: ' . $e->getMessage()]);
}

?>
