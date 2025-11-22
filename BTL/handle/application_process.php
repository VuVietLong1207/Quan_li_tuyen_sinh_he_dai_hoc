<?php
require_once '../functions/db_connection.php';
require_once '../functions/utilities.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    switch($action) {
        case 'update_status':
            $application_id = $_POST['application_id'];
            $status = $_POST['status'];
            
            try {
                $stmt = $pdo->prepare("UPDATE applications SET status = ? WHERE id = ?");
                $stmt->execute([$status, $application_id]);
                echo json_encode(['success' => true, 'message' => 'Cập nhật trạng thái thành công!']);
            } catch (PDOException $e) {
                echo json_encode(['success' => false, 'message' => 'Lỗi cập nhật: ' . $e->getMessage()]);
            }
            break;
            
        case 'add_major':
            $major_code = sanitizeInput($_POST['major_code']);
            $major_name = sanitizeInput($_POST['major_name']);
            $description = sanitizeInput($_POST['description']);
            $quota = $_POST['quota'];
            $duration = $_POST['duration'];
            
            try {
                $stmt = $pdo->prepare("INSERT INTO majors (major_code, major_name, description, quota, duration) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$major_code, $major_name, $description, $quota, $duration]);
                echo json_encode(['success' => true, 'message' => 'Thêm ngành học thành công!']);
            } catch (PDOException $e) {
                echo json_encode(['success' => false, 'message' => 'Lỗi thêm ngành: ' . $e->getMessage()]);
            }
            break;
            
        case 'delete_major':
            $major_id = $_POST['major_id'];
            
            try {
                $stmt = $pdo->prepare("DELETE FROM majors WHERE id = ?");
                $stmt->execute([$major_id]);
                echo json_encode(['success' => true, 'message' => 'Xóa ngành học thành công!']);
            } catch (PDOException $e) {
                echo json_encode(['success' => false, 'message' => 'Lỗi xóa ngành: ' . $e->getMessage()]);
            }
            break;
            
        case 'reset_password':
            $user_id = $_POST['user_id'];
            $new_password = password_hash('123456', PASSWORD_DEFAULT);
            
            try {
                $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
                $stmt->execute([$new_password, $user_id]);
                echo json_encode(['success' => true, 'message' => 'Reset mật khẩu thành công! Mật khẩu mới: 123456']);
            } catch (PDOException $e) {
                echo json_encode(['success' => false, 'message' => 'Lỗi reset mật khẩu: ' . $e->getMessage()]);
            }
            break;
            
        case 'delete_user':
            $user_id = $_POST['user_id'];
            
            try {
                $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
                $stmt->execute([$user_id]);
                echo json_encode(['success' => true, 'message' => 'Xóa người dùng thành công!']);
            } catch (PDOException $e) {
                echo json_encode(['success' => false, 'message' => 'Lỗi xóa người dùng: ' . $e->getMessage()]);
            }
            break;
            
        default:
            echo json_encode(['success' => false, 'message' => 'Hành động không hợp lệ!']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Phương thức không hợp lệ!']);
}
?>