<?php
require_once '../functions/db_connection.php';
require_once '../functions/utilities.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitizeInput($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $email = sanitizeInput($_POST['email']);
    $full_name = sanitizeInput($_POST['full_name']);
    $cmnd_cccd = sanitizeInput($_POST['cmnd_cccd']);
    $phone = sanitizeInput($_POST['phone']);
    $address = sanitizeInput($_POST['address']);
    $date_of_birth = $_POST['date_of_birth'];
    $gender = $_POST['gender'];
    $high_school = sanitizeInput($_POST['high_school']);
    $graduation_year = $_POST['graduation_year'];

    try {
        $pdo->beginTransaction();

        // Thêm user
        $stmt = $pdo->prepare("INSERT INTO users (username, password, email, full_name, user_type) VALUES (?, ?, ?, ?, 'candidate')");
        $stmt->execute([$username, $password, $email, $full_name]);
        $user_id = $pdo->lastInsertId();

        // Thêm thông tin thí sinh
        $stmt = $pdo->prepare("INSERT INTO candidates (user_id, cmnd_cccd, phone, address, date_of_birth, gender, high_school, graduation_year) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$user_id, $cmnd_cccd, $phone, $address, $date_of_birth, $gender, $high_school, $graduation_year]);

        $pdo->commit();
        echo json_encode(['success' => true, 'message' => 'Đăng ký tài khoản thành công!']);
    } catch (PDOException $e) {
        $pdo->rollBack();
        echo json_encode(['success' => false, 'message' => 'Lỗi đăng ký: ' . $e->getMessage()]);
    }
}
?>