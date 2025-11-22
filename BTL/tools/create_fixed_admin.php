<?php
/**
 * Create or update a fixed admin account for local development.
 * Usage (browser): http://localhost/BTL/tools/create_fixed_admin.php
 * Usage (CLI): & 'C:\xampp\php\php.exe' 'C:\xampp\htdocs\BTl\tools\create_fixed_admin.php'
 */
require_once __DIR__ . '/../functions/db_connection.php';

// Configuration: change defaults here if you want fixed credentials
$DEFAULT_USERNAME = 'admin';
$DEFAULT_PASSWORD = 'Admin@123';
$DEFAULT_EMAIL = 'admin@localhost';
$DEFAULT_FULLNAME = 'Site Administrator';

// Allow overrides via env/GET for convenience, but keep defaults
$username = $_GET['username'] ?? $DEFAULT_USERNAME;
$password = $_GET['password'] ?? $DEFAULT_PASSWORD;
$email = $_GET['email'] ?? $DEFAULT_EMAIL;
$full_name = $_GET['full_name'] ?? $DEFAULT_FULLNAME;

header('Content-Type: text/html; charset=utf-8');

try {
    // Ensure users table exists and password column is wide enough
    $stmt = $pdo->query("SHOW COLUMNS FROM users LIKE 'password'");
    $col = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$col) {
        throw new Exception('Bảng `users` hoặc cột `password` không tồn tại. Hãy chạy scripts tạo bảng trước.');
    }

    // Check existing user by username
    $check = $pdo->prepare("SELECT id, user_type FROM users WHERE username = ? LIMIT 1");
    $check->execute([$username]);
    $existing = $check->fetch(PDO::FETCH_ASSOC);

    $hashed = password_hash($password, PASSWORD_DEFAULT);

    if ($existing) {
        // Update password/email/full_name and set user_type to admin
        $update = $pdo->prepare("UPDATE users SET password = ?, email = ?, full_name = ?, user_type = 'admin' WHERE id = ?");
        $update->execute([$hashed, $email, $full_name, $existing['id']]);
        $message = "Tài khoản admin đã tồn tại; mật khẩu và thông tin đã được cập nhật.";
        $created = false;
    } else {
        $insert = $pdo->prepare("INSERT INTO users (username, password, email, full_name, user_type, last_login, created_at) VALUES (?, ?, ?, ?, 'admin', NULL, NOW())");
        $insert->execute([$username, $hashed, $email, $full_name]);
        $message = "Tài khoản admin đã được tạo.";
        $created = true;
    }

    // Output HTML with credentials (for local dev only)
    echo "<h2>Admin account ready</h2>";
    echo "<p><strong>Username:</strong> " . htmlspecialchars($username) . "</p>";
    echo "<p><strong>Password:</strong> " . htmlspecialchars($password) . "</p>";
    echo "<p>Ghi chú: Đây là tài khoản dành cho phát triển cục bộ. Hãy đổi mật khẩu trước khi đưa lên production.</p>";
    echo "<p>Status: " . htmlspecialchars($message) . "</p>";
    echo "<p><a href=\"/BTL/views/auth/login.php\">Đi tới trang đăng nhập</a></p>";

} catch (Exception $e) {
    http_response_code(500);
    echo "<h2>Lỗi</h2><pre>" . htmlspecialchars($e->getMessage()) . "</pre>";
}

?>
