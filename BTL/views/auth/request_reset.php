<?php
session_start();
require_once '../../functions/db_connection.php';
require_once '../../functions/utilities.php';

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrf = $_POST['csrf_token'] ?? '';
    if (!verifyCsrfToken($csrf)) {
        $error = 'Yêu cầu không hợp lệ (CSRF).';
    } else {
        $email = strtolower(sanitizeInput($_POST['email'] ?? ''));
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Email không hợp lệ.';
        } else {
            try {
                $stmt = $pdo->prepare("SELECT id, username FROM users WHERE email = ? LIMIT 1");
                $stmt->execute([$email]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                if (!$user) {
                    // don't leak whether email exists
                    $message = 'Nếu email tồn tại trong hệ thống, bạn sẽ nhận được hướng dẫn đặt lại mật khẩu.';
                } else {
                    // ensure password_resets table exists; if not, show helpful error
                    $token = bin2hex(random_bytes(32));
                    $expires = date('Y-m-d H:i:s', time() + 3600);
                    $stmt = $pdo->prepare("INSERT INTO password_resets (user_id, token, expires_at, created_at) VALUES (?, ?, ?, NOW())");
                    $stmt->execute([$user['id'], $token, $expires]);

                    $resetLink = sprintf('%s://%s/BTL/views/auth/reset_password.php?token=%s',
                        (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http',
                        $_SERVER['HTTP_HOST'], $token);

                    // In production you would email $resetLink. For local dev we show it.
                    $message = 'Đã tạo yêu cầu đặt lại mật khẩu. Dùng link sau để đặt lại (chỉ hiển thị cục bộ): <a href="' . htmlspecialchars($resetLink) . '">' . htmlspecialchars($resetLink) . '</a>';
                }
            } catch (PDOException $e) {
                $error = 'Lỗi hệ thống: ' . $e->getMessage();
            }
        }
    }
}

include '../partials/header.php';
?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-primary text-white text-center">Yêu cầu đặt lại mật khẩu</div>
                <div class="card-body">
                    <?php if(!empty($error)): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>
                    <?php if(!empty($message)): ?>
                        <div class="alert alert-success"><?php echo $message; ?></div>
                    <?php endif; ?>

                    <form method="POST">
                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(generateCsrfToken()); ?>">
                        <div class="mb-3">
                            <label>Email đã đăng ký</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <button class="btn btn-primary w-100">Gửi yêu cầu</button>
                    </form>

                    <div class="mt-3 text-center">
                        <a href="/BTL/views/auth/login.php">Quay lại đăng nhập</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../partials/footer.php'; ?>
