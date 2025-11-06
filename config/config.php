<?php
// Cấu hình ứng dụng
define('APP_NAME', 'Hệ Thống Quản Lý Tuyển Sinh');
define('APP_VERSION', '1.0.0');
define('BASE_URL', getBaseUrl());

// Cấu hình database
define('DB_HOST', 'localhost');
define('DB_NAME', 'admission_management');
define('DB_USER', 'root');
define('DB_PASS', '');

// Cấu hình upload
define('UPLOAD_MAX_SIZE', 5242880); // 5MB
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/png', 'image/jpg']);
define('ALLOWED_DOCUMENT_TYPES', ['application/pdf']);

// Hàm lấy base URL
function getBaseUrl() {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
    $host = $_SERVER['HTTP_HOST'];
    $script_path = dirname($_SERVER['SCRIPT_NAME']);
    
    // Xác định thư mục gốc của ứng dụng
    $base_path = str_replace('/index.php', '', $script_path);
    
    return $protocol . "://" . $host . $base_path;
}
?>