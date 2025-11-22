<?php
session_start();
require_once '../../functions/db_connection.php';
require_once '../../functions/auth.php';
require_once '../../functions/utilities.php';

if (isLoggedIn()) {
    header('Location: /BTL/index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verify CSRF
    $csrf = $_POST['csrf_token'] ?? '';
    if (!verifyCsrfToken($csrf)) {
        $error = 'Yêu cầu không hợp lệ (CSRF). Vui lòng thử lại.';
    } else {
        // Sanitize and validate inputs (preserve values on error)
        $username = sanitizeInput($_POST['username'] ?? '');
        $password_raw = $_POST['password'] ?? '';
        $email = strtolower(sanitizeInput($_POST['email'] ?? ''));
        $full_name = sanitizeInput($_POST['full_name'] ?? '');
        $cmnd_cccd = sanitizeInput($_POST['cmnd_cccd'] ?? '');
        $phone = sanitizeInput($_POST['phone'] ?? '');
        $address = sanitizeInput($_POST['address'] ?? '');
        $date_of_birth = $_POST['date_of_birth'] ?? null;
        $gender = sanitizeInput($_POST['gender'] ?? '');
        $high_school = sanitizeInput($_POST['high_school'] ?? '');
        $graduation_year = isset($_POST['graduation_year']) ? (int)$_POST['graduation_year'] : null;

        // password policy: min 8 chars, at least one letter and one number
        $password_ok = preg_match('/(?=.{8,})(?=.*[A-Za-z])(?=.*\d)/', $password_raw);

        if (empty($username) || empty($password_raw) || empty($email) || empty($full_name) || empty($cmnd_cccd)) {
            $error = 'Vui lòng điền đầy đủ các trường bắt buộc.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Email không hợp lệ.';
        } elseif (!$password_ok) {
            $error = 'Mật khẩu phải có ít nhất 8 ký tự, gồm chữ và số.';
        } else {
            try {
                // Check duplicates
                $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ? LIMIT 1");
                $stmt->execute([$username, $email]);
                $exists = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($exists) {
                    $error = 'Username hoặc email đã được sử dụng.';
                } else {
                    $password = password_hash($password_raw, PASSWORD_DEFAULT);
                    $pdo->beginTransaction();

                    $stmt = $pdo->prepare("INSERT INTO users (username, password, email, full_name, user_type, last_login, created_at) VALUES (?, ?, ?, ?, 'candidate', NULL, NOW())");
                    $stmt->execute([$username, $password, $email, $full_name]);
                    $user_id = $pdo->lastInsertId();

                    $stmt = $pdo->prepare("INSERT INTO candidates (user_id, cmnd_cccd, phone, address, date_of_birth, gender, high_school, graduation_year) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                    $stmt->execute([$user_id, $cmnd_cccd, $phone, $address, $date_of_birth, $gender, $high_school, $graduation_year]);

                    $pdo->commit();
                    $_SESSION['success'] = "Đăng ký tài khoản thành công! Vui lòng đăng nhập.";
                    header('Location: /BTL/views/auth/login.php');
                    exit();
                }
            } catch (PDOException $e) {
                // If the error is caused by 'Data too long' for the password column, attempt a safe migration and retry once
                $pdo->rollBack();
                $msg = $e->getMessage();
                $sqlState = $e->getCode();
                $handled = false;

                if (stripos($msg, 'Data too long') !== false || stripos($msg, 'right truncated') !== false || $sqlState === '22001') {
                    try {
                        // Attempt to increase password column length to 255
                        $pdo->exec("ALTER TABLE users MODIFY password VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL");
                        // Retry insertion once
                        $pdo->beginTransaction();
                        $password = password_hash($password_raw, PASSWORD_DEFAULT);
                        $stmt = $pdo->prepare("INSERT INTO users (username, password, email, full_name, user_type, last_login, created_at) VALUES (?, ?, ?, ?, 'candidate', NULL, NOW())");
                        $stmt->execute([$username, $password, $email, $full_name]);
                        $user_id = $pdo->lastInsertId();

                        $stmt = $pdo->prepare("INSERT INTO candidates (user_id, cmnd_cccd, phone, address, date_of_birth, gender, high_school, graduation_year) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                        $stmt->execute([$user_id, $cmnd_cccd, $phone, $address, $date_of_birth, $gender, $high_school, $graduation_year]);

                        $pdo->commit();
                        $_SESSION['success'] = "Đăng ký tài khoản thành công! (cột password đã được mở rộng)";
                        header('Location: /BTL/views/auth/login.php');
                        exit();
                    } catch (PDOException $e2) {
                        if ($pdo->inTransaction()) $pdo->rollBack();
                        $error = 'Lỗi đăng ký sau khi thử sửa cột mật khẩu: ' . $e2->getMessage();
                        $handled = true;
                    }
                }

                if (!$handled) {
                    $error = "Lỗi đăng ký: " . $msg;
                }
            }
        }
    }
}
?>

<?php include '../partials/header.php'; ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white text-center">
                    <h4>ĐĂNG KÝ THÍ SINH</h4>
                    <p class="mb-0">Đại học Hà Nội - Tuyển sinh 2024</p>
                </div>
                <div class="card-body p-4">
                    <?php if(isset($error)): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>

                    <form method="POST">
                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(generateCsrfToken()); ?>">
                        <h5 class="mb-3">Thông tin tài khoản</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tên đăng nhập *</label>
                                <input type="text" name="username" class="form-control" required value="<?php echo htmlspecialchars($username ?? ''); ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Mật khẩu *</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email *</label>
                                <input type="email" name="email" class="form-control" required value="<?php echo htmlspecialchars($email ?? ''); ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Họ và tên *</label>
                                <input type="text" name="full_name" class="form-control" required value="<?php echo htmlspecialchars($full_name ?? ''); ?>">
                            </div>
                        </div>

                        <h5 class="mb-3 mt-4">Thông tin cá nhân</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">CMND/CCCD *</label>
                                <input type="text" name="cmnd_cccd" class="form-control" required value="<?php echo htmlspecialchars($cmnd_cccd ?? ''); ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Số điện thoại</label>
                                <input type="tel" name="phone" class="form-control" value="<?php echo htmlspecialchars($phone ?? ''); ?>">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Ngày sinh</label>
                                <input type="date" name="date_of_birth" class="form-control" value="<?php echo htmlspecialchars($date_of_birth ?? ''); ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Giới tính</label>
                                <select name="gender" class="form-select">
                                    <option value="Nam" <?php if(($gender ?? '') === 'Nam') echo 'selected'; ?>>Nam</option>
                                    <option value="Nữ" <?php if(($gender ?? '') === 'Nữ') echo 'selected'; ?>>Nữ</option>
                                    <option value="Khác" <?php if(($gender ?? '') === 'Khác') echo 'selected'; ?>>Khác</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Địa chỉ</label>
                                <input type="text" name="address" class="form-control" value="<?php echo htmlspecialchars($address ?? ''); ?>">
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Trường THPT</label>
                                <input type="text" name="high_school" class="form-control" value="<?php echo htmlspecialchars($high_school ?? ''); ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Năm tốt nghiệp</label>
                                <input type="number" name="graduation_year" class="form-control" min="2000" max="2024" value="<?php echo htmlspecialchars($graduation_year ?? ''); ?>">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2">Đăng ký tài khoản</button>
                    </form>
                    
                    <div class="text-center mt-3">
                        <p>Đã có tài khoản? <a href="/BTL/views/auth/login.php">Đăng nhập ngay</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../partials/footer.php'; ?>