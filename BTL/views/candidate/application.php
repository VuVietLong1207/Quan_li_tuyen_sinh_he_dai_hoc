<?php
require_once '../../functions/auth.php';
requireLogin();
require_once '../../functions/db_connection.php';

if ($_SESSION['user_type'] !== 'candidate') {
    header('Location: /BTL/index.php');
    exit();
}

$majors = $pdo->query("SELECT * FROM majors WHERE status = 'active' ORDER BY major_name")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $major_id = $_POST['major_id'];
    $method = $_POST['method'];
    $subject_scores = json_encode([
        'toan' => $_POST['toan'] ?? 0,
        'van' => $_POST['van'] ?? 0,
        'anh' => $_POST['anh'] ?? 0,
        'ly' => $_POST['ly'] ?? 0,
        'hoa' => $_POST['hoa'] ?? 0,
        'sinh' => $_POST['sinh'] ?? 0,
        'su' => $_POST['su'] ?? 0,
        'dia' => $_POST['dia'] ?? 0
    ]);

    $application_code = 'APP' . date('YmdHis') . $_SESSION['user_id'];

    try {
        $stmt = $pdo->prepare("INSERT INTO applications (candidate_id, major_id, application_code, method, subject_scores) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$_SESSION['user_id'], $major_id, $application_code, $method, $subject_scores]);
        
        $_SESSION['success'] = "Đăng ký xét tuyển thành công! Mã hồ sơ: " . $application_code;
        header('Location: /BTL/views/candidate/application.php');
        exit();
    } catch (PDOException $e) {
        $error = "Lỗi đăng ký: " . $e->getMessage();
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
                    <a href="/BTL/views/candidate/profile.php" class="list-group-item list-group-item-action">
                        <i class="fas fa-user me-2"></i>Thông tin cá nhân
                    </a>
                    <a href="/BTL/views/candidate/application.php" class="list-group-item list-group-item-action active">
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
                    <h4 class="mb-0">Đăng ký xét tuyển</h4>
                </div>
                <div class="card-body">
                    <?php if(isset($_SESSION['success'])): ?>
                        <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
                    <?php endif; ?>

                    <?php if(isset($error)): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Chọn ngành *</label>
                                <select name="major_id" class="form-select" required>
                                    <option value="">-- Chọn ngành --</option>
                                    <?php foreach($majors as $major): ?>
                                    <option value="<?php echo $major['id']; ?>">
                                        <?php echo $major['major_name']; ?> (<?php echo $major['major_code']; ?>) - Chỉ tiêu: <?php echo $major['quota']; ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Phương thức xét tuyển *</label>
                                <select name="method" class="form-select" required>
                                    <option value="">-- Chọn phương thức --</option>
                                    <option value="thptqg">THPT Quốc gia</option>
                                    <option value="hocba">Xét học bạ</option>
                                    <option value="khaac">Khác</option>
                                </select>
                            </div>
                        </div>

                        <h5 class="mb-3 mt-4">Điểm các môn học</h5>
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Toán</label>
                                <input type="number" name="toan" class="form-control" step="0.1" min="0" max="10">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Ngữ văn</label>
                                <input type="number" name="van" class="form-control" step="0.1" min="0" max="10">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Tiếng Anh</label>
                                <input type="number" name="anh" class="form-control" step="0.1" min="0" max="10">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Vật lý</label>
                                <input type="number" name="ly" class="form-control" step="0.1" min="0" max="10">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Hóa học</label>
                                <input type="number" name="hoa" class="form-control" step="0.1" min="0" max="10">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Sinh học</label>
                                <input type="number" name="sinh" class="form-control" step="0.1" min="0" max="10">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Lịch sử</label>
                                <input type="number" name="su" class="form-control" step="0.1" min="0" max="10">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Địa lý</label>
                                <input type="number" name="dia" class="form-control" step="0.1" min="0" max="10">
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Lưu ý:</strong> Vui lòng nhập đầy đủ và chính xác thông tin. Hồ sơ sau khi gửi sẽ không thể chỉnh sửa.
                        </div>

                        <button type="submit" class="btn btn-primary">Gửi hồ sơ</button>
                        <a href="/BTL/views/candidate/dashboard.php" class="btn btn-secondary">Quay lại</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../partials/footer.php'; ?>