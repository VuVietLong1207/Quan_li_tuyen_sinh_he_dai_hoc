<?php
require_once '../../functions/auth.php';
requireAdmin();
require_once '../../functions/db_connection.php';

$applications = $pdo->query("
    SELECT a.*, u.full_name, u.email, m.major_name, m.major_code 
    FROM applications a 
    JOIN users u ON a.candidate_id = u.id 
    JOIN majors m ON a.major_id = m.id 
    ORDER BY a.applied_at DESC
")->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include '../partials/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <nav class="col-md-3 col-lg-2 d-md-block bg-light sidebar">
            <?php include '../partials/sidebar_admin.php'; ?>
        </nav>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <h1 class="h2">Quản lý Hồ sơ</h1>
            
            <div class="card shadow">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Mã HS</th>
                                    <th>Thí sinh</th>
                                    <th>Email</th>
                                    <th>Ngành</th>
                                    <th>Phương thức</th>
                                    <th>Trạng thái</th>
                                    <th>Ngày đăng ký</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($applications as $app): ?>
                                <tr>
                                    <td><?php echo $app['application_code']; ?></td>
                                    <td><?php echo $app['full_name']; ?></td>
                                    <td><?php echo $app['email']; ?></td>
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
                                    <td><?php echo date('d/m/Y H:i', strtotime($app['applied_at'])); ?></td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#viewApplication<?php echo $app['id']; ?>">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-success" onclick="updateStatus(<?php echo $app['id']; ?>, 'approved')">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger" onclick="updateStatus(<?php echo $app['id']; ?>, 'rejected')">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<script>
function updateStatus(applicationId, status) {
    if (confirm('Bạn có chắc chắn muốn thay đổi trạng thái?')) {
        fetch('/BTL/handle/application_process.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                action: 'update_status',
                application_id: applicationId,
                status: status
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Có lỗi xảy ra: ' + data.message);
            }
        });
    }
}
</script>

<?php include '../partials/footer.php'; ?>