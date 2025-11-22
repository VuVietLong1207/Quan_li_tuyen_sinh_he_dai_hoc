<?php
require_once '../../functions/auth.php';
requireAdmin();
require_once '../../functions/db_connection.php';

$majors = $pdo->query("SELECT * FROM majors ORDER BY major_name")->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include '../partials/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <nav class="col-md-3 col-lg-2 d-md-block bg-light sidebar">
            <?php include '../partials/sidebar_admin.php'; ?>
        </nav>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h2">Quản lý Ngành học</h1>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMajorModal">
                    <i class="fas fa-plus me-2"></i>Thêm ngành
                </button>
            </div>
            
            <div class="card shadow">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Mã ngành</th>
                                    <th>Tên ngành</th>
                                    <th>Chỉ tiêu</th>
                                    <th>Thời gian</th>
                                    <th>Trạng thái</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($majors as $major): ?>
                                <tr>
                                    <td><?php echo $major['major_code']; ?></td>
                                    <td><?php echo $major['major_name']; ?></td>
                                    <td><?php echo $major['quota']; ?></td>
                                    <td><?php echo $major['duration']; ?> năm</td>
                                    <td>
                                        <span class="badge bg-<?php echo $major['status'] == 'active' ? 'success' : 'secondary'; ?>">
                                            <?php echo $major['status'] == 'active' ? 'Hoạt động' : 'Ngừng'; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editMajorModal<?php echo $major['id']; ?>">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger" onclick="deleteMajor(<?php echo $major['id']; ?>)">
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

<!-- Modal Thêm ngành -->
<div class="modal fade" id="addMajorModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Thêm ngành học mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addMajorForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Mã ngành *</label>
                        <input type="text" name="major_code" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tên ngành *</label>
                        <input type="text" name="major_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mô tả</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Chỉ tiêu *</label>
                            <input type="number" name="quota" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Thời gian (năm) *</label>
                            <input type="number" name="duration" class="form-control" value="4" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Thêm ngành</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('addMajorForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    formData.append('action', 'add_major');
    
    fetch('/BTL/handle/application_process.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Lỗi: ' + data.message);
        }
    });
});

function deleteMajor(majorId) {
    if (confirm('Bạn có chắc chắn muốn xóa ngành học này?')) {
        const formData = new FormData();
        formData.append('action', 'delete_major');
        formData.append('major_id', majorId);
        
        fetch('/BTL/handle/application_process.php', {
            method: 'POST',
            body: formData
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