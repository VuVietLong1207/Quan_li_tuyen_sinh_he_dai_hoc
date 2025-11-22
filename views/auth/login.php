<?php
session_start();
require_once '../../functions/db_connection.php';
require_once '../../functions/auth.php';
require_once '../../functions/utilities.php';

if (isLoggedIn()) {
    redirectBasedOnUserType();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF check
    $csrf = $_POST['csrf_token'] ?? '';
    if (!verifyCsrfToken($csrf)) {
        $error = 'Yêu cầu không hợp lệ (CSRF). Vui lòng thử lại.';
    } else {
        $username = sanitizeInput($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        $user_type = sanitizeInput($_POST['user_type'] ?? 'candidate');

        // throttle check
        if (loginAttemptsExceeded()) {
            $error = 'Bạn đã thử đăng nhập quá nhiều lần. Vui lòng thử lại sau.';
        } else {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? AND user_type = ?");
            $stmt->execute([$username, $user_type]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                // successful login: clear attempts
                if (session_status() === PHP_SESSION_NONE) session_start();
                unset($_SESSION['login_attempts']);
                loginUser($user);
                redirectBasedOnUserType();
            } else {
                // record failed attempt
                recordLoginAttempt();
                $error = "Tên đăng nhập hoặc mật khẩu không đúng!";
            }
        }

            // compute remaining attempts for UI
            $remaining_attempts = getLoginAttemptsRemaining();
    }
}
?>

<?php include '../partials/header.php'; ?>

<div class="container">
    <div class="row justify-content-center min-vh-100 align-items-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-primary text-white text-center">
                    <h4>ĐĂNG NHẬP HỆ THỐNG</h4>
                    <p class="mb-0">Đại học Hà Nội</p>
                </div>
                <div class="card-body p-4">
                    <?php if(isset($_SESSION['success'])): ?>
                        <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
                    <?php endif; ?>

                    <?php if(isset($_GET['timeout']) && $_GET['timeout'] == 1): ?>
                        <div class="alert alert-warning">Phiên làm việc đã hết hạn. Vui lòng đăng nhập lại.</div>
                    <?php endif; ?>

                    <?php if(isset($error)): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>

                    <?php if(isset($remaining_attempts) && $remaining_attempts > 0): ?>
                        <div class="alert alert-info">Còn <strong><?php echo $remaining_attempts; ?></strong> lần thử đăng nhập trong thời gian giới hạn.</div>
                    <?php elseif(isset($remaining_attempts) && $remaining_attempts === 0): ?>
                        <div class="alert alert-warning">Bạn đã hết lượt thử đăng nhập. Vui lòng chờ hoặc đặt lại mật khẩu.</div>
                    <?php endif; ?>

                    <form method="POST">
                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(generateCsrfToken()); ?>">
                        <div class="mb-3">
                            <label class="form-label">Loại tài khoản</label>
                            <select name="user_type" class="form-select" required>
                                <option value="candidate">Thí sinh</option>
                                <option value="admin">Cán bộ tuyển sinh</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tên đăng nhập</label>
                            <input type="text" name="username" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Mật khẩu</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Đăng nhập</button>
                    </form>
                    <div class="mt-3 text-center">
                        <a href="/BTL/views/auth/request_reset.php">Quên mật khẩu?</a>
                    </div>
                    
                    <div class="text-center mt-3">
                        <p>Chưa có tài khoản? <a href="/BTL/views/auth/register.php">Đăng ký ngay</a></p>
                    </div>
                    <?php
                    // Dev-only helper links: show when running locally and tools exist
                    $is_local = (isset($_SERVER['HTTP_HOST']) && (stripos($_SERVER['HTTP_HOST'], 'localhost') !== false || $_SERVER['HTTP_HOST'] === '127.0.0.1')) || (in_array($_SERVER['REMOTE_ADDR'] ?? '', ['127.0.0.1', '::1']));
                    $tools_json = __DIR__ . '/../../tools/create_admin.php';
                    $tools_html = __DIR__ . '/../../tools/create_fixed_admin.php';
                    if ($is_local && (is_file($tools_json) || is_file($tools_html))): ?>
                        <div class="text-center mt-2 small">
                            <?php if (is_file($tools_html)): ?>
                                <a href="/BTL/tools/create_fixed_admin.php" target="_blank">Tạo tài khoản admin (dạng HTML)</a>
                            <?php endif; ?>
                            <?php if (is_file($tools_json)): ?>
                                <?php if (is_file($tools_html)) echo ' | '; ?>
                                <a href="/BTL/tools/create_admin.php?username=admin&password=Admin%40123&email=admin%40local" target="_blank">Tạo tài khoản admin (API JSON)</a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../partials/footer.php'; ?>