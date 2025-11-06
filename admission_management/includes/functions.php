<?php
// Hàm chuyển hướng cải tiến
function redirect($url) {
    $base_url = getBaseUrl();
    $full_url = $base_url . '/' . ltrim($url, '/');
    header("Location: " . $full_url);
    exit();
}

// Hàm lấy base URL
function getBaseUrl() {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
    $host = $_SERVER['HTTP_HOST'];
    $script_path = dirname($_SERVER['SCRIPT_NAME']);
    
    // Xác định thư mục gốc của ứng dụng
    $base_path = str_replace('/index.php', '', $script_path);
    
    return $protocol . "://" . $host . $base_path;
}

// Hàm hiển thị thông báo
function flashMessage($message, $type = 'success') {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    $_SESSION['flash_message'] = [
        'message' => $message,
        'type' => $type
    ];
}

// Hàm tạo mã thí sinh tự động
function generateCandidateCode() {
    return 'TS' . date('Y') . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
}

// Hàm upload file
function uploadFile($file, $uploadDir, $allowedTypes = []) {
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'message' => 'Lỗi upload file'];
    }
    
    // Tạo thư mục nếu chưa tồn tại
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    
    $fileName = time() . '_' . basename($file['name']);
    $targetPath = $uploadDir . $fileName;
    
    if (!empty($allowedTypes) && !in_array($file['type'], $allowedTypes)) {
        return ['success' => false, 'message' => 'Loại file không được hỗ trợ'];
    }
    
    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        return ['success' => true, 'file_name' => $fileName];
    } else {
        return ['success' => false, 'message' => 'Không thể upload file'];
    }
}

// Hàm validate email
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Hàm validate số điện thoại
function validatePhone($phone) {
    return preg_match('/^[0-9]{10,11}$/', $phone);
}

// Hàm hiển thị flash message
function displayFlashMessage() {
    if (isset($_SESSION['flash_message'])) {
        $message = $_SESSION['flash_message']['message'];
        $type = $_SESSION['flash_message']['type'];
        
        $alertClass = '';
        switch ($type) {
            case 'success':
                $alertClass = 'alert-success';
                break;
            case 'error':
                $alertClass = 'alert-danger';
                break;
            case 'warning':
                $alertClass = 'alert-warning';
                break;
            default:
                $alertClass = 'alert-info';
        }
        
        echo "<div class='alert $alertClass' style='padding: 1rem; margin-bottom: 1rem; border-radius: 5px;'>
                $message
                <button type='button' class='close' onclick='this.parentElement.remove()' style='float: right; background: none; border: none; font-size: 1.2rem;'>&times;</button>
              </div>";
        
        unset($_SESSION['flash_message']);
    }
}
?>