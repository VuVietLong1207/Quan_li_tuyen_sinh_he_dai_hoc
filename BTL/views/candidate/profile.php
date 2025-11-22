<?php
require_once '../../functions/auth.php';
requireLogin();
require_once '../../functions/db_connection.php';

if ($_SESSION['user_type'] !== 'candidate') {
    header('Location: /BTL/index.php');
    exit();
}

$candidate_stmt = $pdo->prepare("
    SELECT u.*, c.* 
    FROM users u 
    LEFT JOIN candidates c ON u.id = c.user_id 
    WHERE u.id = ?
");
$candidate_stmt->execute([$_SESSION['user_id']]);
$candidate = $candidate_stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $date_of_birth = $_POST['date_of_birth'];
    $gender = $_POST['gender'];
    $high_school = $_POST['high_school'];
    $graduation_year = $_POST['graduation_year'];

    try {
        $pdo->beginTransaction();

        // Update user
        $stmt = $pdo->prepare("UPDATE users SET full_name = ?, email = ? WHERE id = ?");
        $stmt->execute([$full_name, $email, $_SESSION['user_id']]);

        // Update candidate
        $stmt = $pdo->prepare("UPDATE candidates SET phone = ?, address = ?, date_of_birth = ?, gender = ?, high_school = ?, graduation_year = ? WHERE user_id = ?");
        $stmt->execute([$phone, $address, $date_of_birth, $gender, $high_school, $graduation_year, $_SESSION['user_id']]);

        $pdo->commit();
        $_SESSION['success'] = "Cập nhật thông tin thành công!";
        header('Location: /BTL/views/candidate/profile.php');
        exit();
    } catch (PDOException $e) {
        $pdo->rollBack();
        $error = "Lỗi cập nhật: " . $e->getMessage();
    }
}
?>

<?php include '../partials/header.php'; ?>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">TÀI KHOẢN THÍ SINH</h5>
                </div>
                <div class="list-group list-group-flush">
                    <a href="/BTL/views/candidate/dashboard.php" class="list-group-item list-group-item-action">
                        <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                    </a>
                    <a href="/BTL/views/candidate/profile.php" class="list-group-item list-group-item-action active">
                        <i class="fas fa-user me-2"></i>Thông tin cá nhân
                    </a>
                    <a href="/BTL/views/candidate/application.php" class="list-group-item list-group-item-action">
                        <i class="fas fa-file-alt me-2"></i>Đăng ký xét tuyển
                    </a>
                    <a href="/BTL/views/candidate/results.php" class="list-group-item list-group-item-action">
                        <i class="fas fa-chart-bar me-2"></i>Kết quả xét tuyển
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Thông tin cá nhân</h4>
                </div>
                <div class="card-body">
                    <?php if(isset($_SESSION['success'])): ?>
                        <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
                    <?php endif; ?>

                    <?php if(isset($error)): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>

                    <form method="POST">
                        <h5 class="mb-3">Thông tin tài khoản</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tên đăng nhập</label>
                                <input type="text" class="form-control" value="<?php echo $candidate['username']; ?>" readonly>
                                <small class="text-muted">Không thể thay đổi tên đăng nhập</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">CMND/CCCD</label>
                                <input type="text" class="form-control" value="<?php echo $candidate['cmnd_cccd']; ?>" readonly>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Họ và tên *</label>
                                <input type="text" name="full_name" class="form-control" value="<?php echo $candidate['full_name']; ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email *</label>
                                <input type="email" name="email" class="form-control" value="<?php echo $candidate['email']; ?>" required>
                            </div>
                        </div>

                        <h5 class="mb-3 mt-4">Thông tin cá nhân</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Số điện thoại</label>
                                <input type="tel" name="phone" class="form-control" value="<?php echo $candidate['phone'] ?? ''; ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Ngày sinh</label>
                                <input type="date" name="date_of_birth" class="form-control" value="<?php echo $candidate['date_of_birth'] ?? ''; ?>">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Giới tính</label>
                                <select name="gender" class="form-select">
                                    <option value="Nam" <?php echo ($candidate['gender'] ?? '') == 'Nam' ? 'selected' : ''; ?>>Nam</option>
                                    <option value="Nữ" <?php echo ($candidate['gender'] ?? '') == 'Nữ' ? 'selected' : ''; ?>>Nữ</option>
                                    <option value="Khác" <?php echo ($candidate['gender'] ?? '') == 'Khác' ? 'selected' : ''; ?>>Khác</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Năm tốt nghiệp</label>
                                <input type="number" name="graduation_year" class="form-control" value="<?php echo $candidate['graduation_year'] ?? ''; ?>" min="2000" max="2024">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Địa chỉ</label>
                            <input type="text" name="address" class="form-control" value="<?php echo $candidate['address'] ?? ''; ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Trường THPT</label>
                            <input type="text" name="high_school" class="form-control" value="<?php echo $candidate['high_school'] ?? ''; ?>">
                        </div>

                        <button type="submit" class="btn btn-primary">Cập nhật thông tin</button>
                        <a href="/BTL/views/candidate/dashboard.php" class="btn btn-secondary">Quay lại</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../partials/footer.php'; ?>