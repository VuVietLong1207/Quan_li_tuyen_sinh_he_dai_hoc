<?php
require_once '../functions/db_connection.php';
require_once '../functions/auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitizeInput($_POST['username']);
    $password = $_POST['password'];
    $user_type = sanitizeInput($_POST['user_type']);

    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? AND user_type = ?");
        $stmt->execute([$username, $user_type]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            loginUser($user);
            echo json_encode(['success' => true, 'message' => 'Đăng nhập thành công!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Tên đăng nhập hoặc mật khẩu không đúng!']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Lỗi hệ thống!']);
    }
}
?>