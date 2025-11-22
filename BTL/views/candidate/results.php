<?php
require_once '../../functions/auth.php';
requireLogin();
require_once '../../functions/db_connection.php';

if ($_SESSION['user_type'] !== 'candidate') {
    header('Location: /BTL/index.php');
    exit();
}

$applications = $pdo->prepare("
    SELECT a.*, m.major_name, m.major_code 
    FROM applications a 
    JOIN majors m ON a.major_id = m.id 
    WHERE a.candidate_id = ? 
    ORDER BY a.applied_at DESC
");
$applications->execute([$_SESSION['user_id']]);
$applications = $applications->fetchAll(PDO::FETCH_ASSOC);
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
                    <a href="/BTL/views/candidate/application.php" class="list-group-item list-group-item-action">
                        <i class="fas fa-file-alt me-2"></i>Đăng ký xét tuyển
                    </a>
                    <a href="/BTL/views/candidate/results.php" class="list-group-item list-group-item-action active">
                        <i class="fas fa-chart-bar me-2"></i>Kết quả xét tuyển
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Kết quả xét tuyển</h4>
                </div>
                <div class="card-body">
                    <?php if(empty($applications)): ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Bạn chưa có hồ sơ xét tuyển nào. <a href="/BTL/views/candidate/application.php" class="alert-link">Đăng ký ngay</a>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Mã hồ sơ</th>
                                        <th>Ngành học</th>
                                        <th>Phương thức</th>
                                        <th>Điểm TB</th>
                                        <th>Trạng thái</th>
                                        <th>Ngày nộp</th>
                                        <th>Ghi chú</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($applications as $app): 
                                        $scores = json_decode($app['subject_scores'], true);
                                        $average_score = $scores ? array_sum($scores) / count(array_filter($scores)) : 0;
                                    ?>
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
                                            <strong><?php echo number_format($average_score, 2); ?></strong>
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
                                        <td><?php echo $app['notes'] ?? '---'; ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            <h5>Chú thích trạng thái:</h5>
                            <div class="row">
                                <div class="col-md-3">
                                    <span class="badge bg-warning">Chờ duyệt</span> - Hồ sơ đang chờ xét duyệt
                                </div>
                                <div class="col-md-3">
                                    <span class="badge bg-success">Đã duyệt</span> - Hồ sơ đã được duyệt
                                </div>
                                <div class="col-md-3">
                                    <span class="badge bg-danger">Từ chối</span> - Hồ sơ không đạt yêu cầu
                                </div>
                                <div class="col-md-3">
                                    <span class="badge bg-primary">Trúng tuyển</span> - Đã trúng tuyển
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../partials/footer.php'; ?>