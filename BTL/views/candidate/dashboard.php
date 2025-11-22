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

$applications_stmt = $pdo->prepare("
    SELECT a.*, m.major_name, m.major_code 
    FROM applications a 
    JOIN majors m ON a.major_id = m.id 
    WHERE a.candidate_id = ? 
    ORDER BY a.applied_at DESC
");
$applications_stmt->execute([$_SESSION['user_id']]);
$applications = $applications_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include '../partials/header.php'; ?>

<div class="container-fluid py-4">
    <div class="row">
        <!-- Sidebar Candidate -->
        <div class="col-md-3">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">TÀI KHOẢN THÍ SINH</h5>
                </div>
                <div class="list-group list-group-flush">
                    <a href="/BTL/views/candidate/dashboard.php" class="list-group-item list-group-item-action active">
                        <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                    </a>
                    <a href="/BTL/views/candidate/profile.php" class="list-group-item list-group-item-action">
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

        <!-- Main content -->
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Tổng quan</h4>
                </div>
                <div class="card-body">
                    <!-- Thông tin cá nhân -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5>Thông tin cá nhân</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <th width="40%">Họ tên:</th>
                                    <td><?php echo $candidate['full_name']; ?></td>
                                </tr>
                                <tr>
                                    <th>CMND/CCCD:</th>
                                    <td><?php echo $candidate['cmnd_cccd']; ?></td>
                                </tr>
                                <tr>
                                    <th>Email:</th>
                                    <td><?php echo $candidate['email']; ?></td>
                                </tr>
                                <tr>
                                    <th>Số điện thoại:</th>
                                    <td><?php echo $candidate['phone'] ?? 'Chưa cập nhật'; ?></td>
                                </tr>
                            </table>
                            <a href="/BTL/views/candidate/profile.php" class="btn btn-primary">Cập nhật thông tin</a>
                        </div>
                        <div class="col-md-6">
                            <h5>Thống kê hồ sơ</h5>
                            <div class="row text-center">
                                <div class="col-6 mb-3">
                                    <div class="card bg-primary text-white">
                                        <div class="card-body">
                                            <h3><?php echo count($applications); ?></h3>
                                            <p>Tổng hồ sơ</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 mb-3">
                                    <div class="card bg-warning text-white">
                                        <div class="card-body">
                                            <h3><?php echo count(array_filter($applications, function($app) { 
                                                return $app['status'] === 'pending'; 
                                            })); ?></h3>
                                            <p>Đang chờ duyệt</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Hồ sơ đã nộp -->
                    <h5>Hồ sơ đã nộp</h5>
                    <?php if(empty($applications)): ?>
                        <div class="alert alert-info">
                            Bạn chưa nộp hồ sơ nào. <a href="/BTL/views/candidate/application.php" class="alert-link">Đăng ký ngay</a>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Mã hồ sơ</th>
                                        <th>Ngành học</th>
                                        <th>Phương thức</th>
                                        <th>Trạng thái</th>
                                        <th>Ngày nộp</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($applications as $app): ?>
                                    <tr>
                                        <td><?php echo $app['application_code']; ?></td>
                                        <td><?php echo $app['major_name']; ?> (<?php echo $app['major_code']; ?>)</td>
                                        <td>
                                            <?php 
                                            $methods = [
                                                'thptqg' => 'THPT QG',
                                                'hocba' => 'Học bạ',
                                                'khaac' => 'Khác'
                                            ];
                                            echo $methods[$app['method']] ?? $app['method'];
                                            ?>
                                        </td>
                                        <td>
                                            <span class="badge bg-<?php 
                                                switch($app['status']) {
                                                    case 'approved': echo 'success'; break;
                                                    case 'rejected': echo 'danger'; break;
                                                    case 'accepted': echo 'primary'; break;
                                                    default: echo 'warning';
                                                }
                                            ?>">
                                                <?php 
                                                $statuses = [
                                                    'pending' => 'Chờ duyệt',
                                                    'approved' => 'Đã duyệt',
                                                    'rejected' => 'Từ chối',
                                                    'accepted' => 'Trúng tuyển'
                                                ];
                                                echo $statuses[$app['status']] ?? $app['status'];
                                                ?>
                                            </span>
                                        </td>
                                        <td><?php echo date('d/m/Y', strtotime($app['applied_at'])); ?></td>
                                        <td>
                                            <a href="/BTL/views/candidate/application.php?view=<?php echo $app['id']; ?>" class="btn btn-sm btn-outline-primary">Xem</a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../partials/footer.php'; ?>