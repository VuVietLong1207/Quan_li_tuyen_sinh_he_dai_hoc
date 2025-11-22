<?php
require_once '../../functions/auth.php';
requireAdmin();
require_once '../../functions/db_connection.php';

$stats = [
    'total_candidates' => $pdo->query("SELECT COUNT(*) FROM users WHERE user_type = 'candidate'")->fetchColumn(),
    'total_applications' => $pdo->query("SELECT COUNT(*) FROM applications")->fetchColumn(),
    'pending_applications' => $pdo->query("SELECT COUNT(*) FROM applications WHERE status = 'pending'")->fetchColumn(),
    'total_majors' => $pdo->query("SELECT COUNT(*) FROM majors WHERE status = 'active'")->fetchColumn()
];

$recent_applications = $pdo->query("
    SELECT a.*, u.full_name, m.major_name 
    FROM applications a 
    JOIN users u ON a.candidate_id = u.id 
    JOIN majors m ON a.major_id = m.id 
    ORDER BY a.applied_at DESC 
    LIMIT 5
")->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include '../partials/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <nav class="col-md-3 col-lg-2 d-md-block bg-light sidebar">
            <?php include '../partials/sidebar_admin.php'; ?>
        </nav>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <h1 class="h2">Dashboard</h1>
            
            <!-- Thống kê -->
            <div class="row my-4">
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Tổng thí sinh</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        <?php echo $stats['total_candidates']; ?>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-users fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                        Tổng hồ sơ</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        <?php echo $stats['total_applications']; ?>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-file-alt fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-warning shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                        Hồ sơ chờ duyệt</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        <?php echo $stats['pending_applications']; ?>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-clock fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-info shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                        Ngành đào tạo</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        <?php echo $stats['total_majors']; ?>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-graduation-cap fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Hồ sơ mới nhất -->
            <div class="row">
                <div class="col-12">
                    <div class="card shadow">
                        <div class="card-header">
                            <h6 class="m-0 font-weight-bold text-primary">Hồ sơ mới nhất</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Mã hồ sơ</th>
                                            <th>Thí sinh</th>
                                            <th>Ngành</th>
                                            <th>Phương thức</th>
                                            <th>Trạng thái</th>
                                            <th>Ngày đăng ký</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($recent_applications as $app): ?>
                                        <tr>
                                            <td><?php echo $app['application_code']; ?></td>
                                            <td><?php echo $app['full_name']; ?></td>
                                            <td><?php echo $app['major_name']; ?></td>
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
                                                    <?php echo $app['status']; ?>
                                                </span>
                                            </td>
                                            <td><?php echo date('d/m/Y', strtotime($app['applied_at'])); ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<?php include '../partials/footer.php'; ?>