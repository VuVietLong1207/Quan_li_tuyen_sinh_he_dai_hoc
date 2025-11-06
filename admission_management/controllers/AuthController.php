<?php
// Khởi động session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Sử dụng đường dẫn tuyệt đối
$root_dir = dirname(dirname(__FILE__));
include_once $root_dir . '/config/database.php';
include_once $root_dir . '/includes/functions.php';

class AuthController {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    // Đăng ký tài khoản
    public function register() {
        if ($_POST) {
            try {
                $full_name = $_POST['full_name'];
                $email = $_POST['email'];
                $phone = $_POST['phone'];
                $username = $_POST['username'];
                $password = $_POST['password'];
                $confirm_password = $_POST['confirm_password'];
                $role = $_POST['role'];

                // Kiểm tra mật khẩu
                if ($password !== $confirm_password) {
                    flashMessage('Mật khẩu xác nhận không khớp!', 'error');
                    redirect('views/auth/register.php');
                }

                // Kiểm tra email hợp lệ
                if (!validateEmail($email)) {
                    flashMessage('Email không hợp lệ!', 'error');
                    redirect('views/auth/register.php');
                }

                // Kiểm tra username đã tồn tại
                $query = "SELECT id FROM users WHERE username = ? OR email = ?";
                $stmt = $this->db->prepare($query);
                $stmt->bindParam(1, $username);
                $stmt->bindParam(2, $email);
                $stmt->execute();

                if ($stmt->rowCount() > 0) {
                    flashMessage('Tên đăng nhập hoặc email đã tồn tại!', 'error');
                    redirect('views/auth/register.php');
                }

                // Mã hóa mật khẩu
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                // Tạo tài khoản
                $query = "INSERT INTO users (full_name, email, phone, username, password, role, created_at) 
                         VALUES (?, ?, ?, ?, ?, ?, NOW())";
                $stmt = $this->db->prepare($query);
                $stmt->bindParam(1, $full_name);
                $stmt->bindParam(2, $email);
                $stmt->bindParam(3, $phone);
                $stmt->bindParam(4, $username);
                $stmt->bindParam(5, $hashed_password);
                $stmt->bindParam(6, $role);

                if ($stmt->execute()) {
                    flashMessage('Đăng ký tài khoản thành công! Vui lòng đăng nhập.', 'success');
                    redirect('views/auth/login.php');
                } else {
                    flashMessage('Có lỗi xảy ra khi đăng ký tài khoản!', 'error');
                }
            } catch (Exception $e) {
                flashMessage('Lỗi: ' . $e->getMessage(), 'error');
            }
        }
    }

    // Đăng nhập
    public function login() {
        if ($_POST) {
            try {
                $username = $_POST['username'];
                $password = $_POST['password'];

                $query = "SELECT * FROM users WHERE username = ? OR email = ?";
                $stmt = $this->db->prepare($query);
                $stmt->bindParam(1, $username);
                $stmt->bindParam(2, $username);
                $stmt->execute();

                if ($stmt->rowCount() == 1) {
                    $user = $stmt->fetch(PDO::FETCH_ASSOC);
                    
                    if (password_verify($password, $user['password'])) {
                        // Đăng nhập thành công
                        $_SESSION['user_id'] = $user['id'];
                        $_SESSION['username'] = $user['username'];
                        $_SESSION['full_name'] = $user['full_name'];
                        $_SESSION['role'] = $user['role'];
                        
                        flashMessage('Đăng nhập thành công!', 'success');
                        redirect('../index.php');
                    } else {
                        flashMessage('Mật khẩu không chính xác!', 'error');
                    }
                } else {
                    flashMessage('Tên đăng nhập không tồn tại!', 'error');
                }
            } catch (Exception $e) {
                flashMessage('Lỗi: ' . $e->getMessage(), 'error');
            }
        }
    }

    // Đăng xuất
    public function logout() {
        session_destroy();
        flashMessage('Đã đăng xuất thành công!', 'success');
        redirect('views/auth/login.php');
    }
}

// Xử lý các action
if (isset($_GET['action'])) {
    $controller = new AuthController();
    
    switch ($_GET['action']) {
        case 'register':
            $controller->register();
            break;
        case 'login':
            $controller->login();
            break;
        case 'logout':
            $controller->logout();
            break;
    }
}
?>