<?php
session_start();
require_once '../../functions/db_connection.php';
require_once '../../functions/utilities.php';

$token = $_GET['token'] ?? ($_POST['token'] ?? '');
$error = '';
$message = '';

if (empty($token)) {
    $error = 'Token không hợp lệ.';
} else {
    try {
        $stmt = $pdo->prepare("SELECT pr.id, pr.user_id, pr.expires_at, u.email FROM password_resets pr JOIN users u ON u.id = pr.user_id WHERE pr.token = ? LIMIT 1");
        $stmt->execute([$token]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            $error = 'Token không tồn tại hoặc đã sử dụng.';
        } elseif (strtotime($row['expires_at']) < time()) {
            $error = 'Token đã hết hạn.';
        }
    } catch (PDOException $e) {
        $error = 'Lỗi hệ thống: ' . $e->getMessage();
    }
}

// Handle new password submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($error)) {
    $csrf = $_POST['csrf_token'] ?? '';
    if (!verifyCsrfToken($csrf)) {
        $error = 'Yêu cầu không hợp lệ (CSRF).';
    } else {
        $password_raw = $_POST['password'] ?? '';
        $confirm = $_POST['confirm_password'] ?? '';
        if ($password_raw !== $confirm) {
            $error = 'Mật khẩu xác nhận không khớp.';
        } elseif (!preg_match('/(?=.{8,})(?=.*[A-Za-z])(?=.*\d)/', $password_raw)) {
            $error = 'Mật khẩu phải có ít nhất 8 ký tự, gồm chữ và số.';
        } else {
            try {
                $password_hashed = password_hash($password_raw, PASSWORD_DEFAULT);
                $pdo->beginTransaction();
                $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
                $stmt->execute([$password_hashed, $row['user_id']]);
                $stmt = $pdo->prepare("DELETE FROM password_resets WHERE id = ?");
                $stmt->execute([$row['id']]);
                $pdo->commit();
                $_SESSION['success'] = 'Mật khẩu đã được đặt lại. Vui lòng đăng nhập bằng mật khẩu mới.';
                header('Location: /BTL/views/auth/login.php');
                exit();
            } catch (PDOException $e) {
                $pdo->rollBack();
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
                <div class="card-header bg-primary text-white text-center">Đặt lại mật khẩu</div>
                <div class="card-body">
                    <?php if(!empty($error)): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>

                    <?php if (empty($error)): ?>
                        <form method="POST">
                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(generateCsrfToken()); ?>">
                            <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                            <div class="mb-3">
                                <label>Mật khẩu mới</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label>Xác nhận mật khẩu</label>
                                <input type="password" name="confirm_password" class="form-control" required>
                            </div>
                            <button class="btn btn-primary w-100">Đặt lại mật khẩu</button>
                        </form>
                    <?php endif; ?>

                    <div class="mt-3 text-center">
                        <a href="/BTL/views/auth/login.php">Quay lại đăng nhập</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../partials/footer.php'; ?>
