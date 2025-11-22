<?php
require_once '../../functions/auth.php';
requireAdmin();
require_once '../../functions/db_connection.php';

$users = $pdo->query("
    SELECT u.*, c.cmnd_cccd, c.phone 
    FROM users u 
    LEFT JOIN candidates c ON u.id = c.user_id 
    ORDER BY u.created_at DESC
")->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include '../partials/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <nav class="col-md-3 col-lg-2 d-md-block bg-light sidebar">
            <?php include '../partials/sidebar_admin.php'; ?>
        </nav>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <h1 class="h2">Quản lý Người dùng</h1>
            
            <div class="card shadow">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Username</th>
                                    <th>Họ tên</th>
                                    <th>Email</th>
                                    <th>Loại</th>
                                    <th>CMND/CCCD</th>
                                    <th>Điện thoại</th>
                                    <th>Ngày tạo</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($users as $user): ?>
                                <tr>
                                    <td><?php echo $user['username']; ?></td>
                                    <td><?php echo $user['full_name']; ?></td>
                                    <td><?php echo $user['email']; ?></td>
                                    <td>
                                        <span class="badge bg-<?php echo $user['user_type'] == 'admin' ? 'danger' : 'primary'; ?>">
                                            <?php echo $user['user_type'] == 'admin' ? 'Quản trị' : 'Thí sinh'; ?>
                                        </span>
                                    </td>
                                    <td><?php echo $user['cmnd_cccd'] ?? 'N/A'; ?></td>
                                    <td><?php echo $user['phone'] ?? 'N/A'; ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($user['created_at'])); ?></td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-outline-warning" onclick="resetPassword(<?php echo $user['id']; ?>)">
                                                <i class="fas fa-key"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger" onclick="deleteUser(<?php echo $user['id']; ?>)">
                                                <i class="fas fa-trash"></i>
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
function resetPassword(userId) {
    if (confirm('Bạn có chắc chắn muốn reset mật khẩu người dùng này?')) {
        fetch('/BTL/handle/application_process.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                action: 'reset_password',
                user_id: userId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Mật khẩu đã được reset thành công!');
            } else {
                alert('Lỗi: ' + data.message);
            }
        });
    }
}

function deleteUser(userId) {
    if (confirm('Bạn có chắc chắn muốn xóa người dùng này?')) {
        fetch('/BTL/handle/application_process.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                action: 'delete_user',
                user_id: userId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Lỗi: ' + data.message);
            }
        });
    }
}
</script>

<?php include '../partials/footer.php'; ?>