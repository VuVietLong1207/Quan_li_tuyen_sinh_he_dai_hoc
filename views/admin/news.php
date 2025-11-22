<?php
require_once '../../functions/auth.php';
requireAdmin();
require_once '../../functions/db_connection.php';

$news = $pdo->query("
    SELECT n.*, u.full_name 
    FROM news n 
    JOIN users u ON n.author_id = u.id 
    ORDER BY n.published_at DESC
")->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include '../partials/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <nav class="col-md-3 col-lg-2 d-md-block bg-light sidebar">
            <?php include '../partials/sidebar_admin.php'; ?>
        </nav>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h2">Quản lý Tin tức</h1>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addNewsModal">
                    <i class="fas fa-plus me-2"></i>Thêm tin
                </button>
            </div>
            
            <div class="card shadow">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Tiêu đề</th>
                                    <th>Danh mục</th>
                                    <th>Tác giả</th>
                                    <th>Ngày đăng</th>
                                    <th>Trạng thái</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($news as $item): ?>
                                <tr>
                                    <td><?php echo $item['title']; ?></td>
                                    <td>
                                        <span class="badge bg-primary">
                                            <?php 
                                            $categories = [
                                                'tuyensinh' => 'Tuyển sinh',
                                                'sukien' => 'Sự kiện',
                                                'thongbao' => 'Thông báo'
                                            ];
                                            echo $categories[$item['category']] ?? $item['category'];
                                            ?>
                                        </span>
                                    </td>
                                    <td><?php echo $item['full_name']; ?></td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($item['published_at'])); ?></td>
                                    <td>
                                        <span class="badge bg-<?php echo $item['is_published'] ? 'success' : 'warning'; ?>">
                                            <?php echo $item['is_published'] ? 'Đã đăng' : 'Bản nháp'; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="/BTL/views/public/news_detail.php?id=<?php echo $item['id']; ?>" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <button class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#editNewsModal<?php echo $item['id']; ?>">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger" onclick="deleteNews(<?php echo $item['id']; ?>)">
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

<!-- Modal Thêm tin -->
<div class="modal fade" id="addNewsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Thêm tin tức mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addNewsForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tiêu đề *</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Danh mục *</label>
                        <select name="category" class="form-select" required>
                            <option value="tuyensinh">Tuyển sinh</option>
                            <option value="sukien">Sự kiện</option>
                            <option value="thongbao">Thông báo</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nội dung *</label>
                        <textarea name="content" class="form-control" rows="10" required></textarea>
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_published" value="1" checked>
                            <label class="form-check-label">Đăng ngay</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Đăng tin</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('addNewsForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    formData.append('action', 'add_news');
    formData.append('author_id', <?php echo $_SESSION['user_id']; ?>);
    
    fetch('/BTL/handle/news_process.php', {
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

function deleteNews(newsId) {
    if (confirm('Bạn có chắc chắn muốn xóa tin này?')) {
        const formData = new FormData();
        formData.append('action', 'delete_news');
        formData.append('news_id', newsId);
        
        fetch('/BTL/handle/news_process.php', {
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