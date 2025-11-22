<?php
session_start();
require_once '../../functions/db_connection.php';

// Xử lý lỗi khi bảng không tồn tại
try {
    $news = $pdo->query("
        SELECT n.*, u.full_name 
        FROM news n 
        JOIN users u ON n.author_id = u.id 
        WHERE n.is_published = TRUE 
        ORDER BY n.published_at DESC
    ")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Nếu bảng không tồn tại, sử dụng mảng rỗng và ghi log lỗi
    error_log("Lỗi truy vấn news: " . $e->getMessage());
    $news = [];
}
?>

<?php include '../partials/header.php'; ?>

<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <h1 class="display-4 fw-bold">TIN TỨC & SỰ KIỆN</h1>
        <p class="lead">Cập nhật những thông tin mới nhất về tuyển sinh và hoạt động của trường</p>
    </div>
</section>

<!-- Danh sách tin tức -->
<section class="py-5">
    <div class="container">
        <div class="row mb-4">
            <div class="col-md-6">
                <h2 class="fw-bold">Tin tức mới nhất</h2>
            </div>
            <div class="col-md-6 text-end">
                <div class="btn-group">
                    <button class="btn btn-outline-primary active" data-filter="all">Tất cả</button>
                    <button class="btn btn-outline-primary" data-filter="tuyensinh">Tuyển sinh</button>
                    <button class="btn btn-outline-primary" data-filter="sukien">Sự kiện</button>
                    <button class="btn btn-outline-primary" data-filter="thongbao">Thông báo</button>
                </div>
            </div>
        </div>

        <div class="row" id="newsList">
            <?php if(!empty($news)): ?>
                <?php foreach($news as $item): ?>
                <div class="col-lg-4 col-md-6" data-category="<?php echo $item['category']; ?>">
                    <div class="card news-card shadow-sm">
                        <div class="position-relative">
                            <div class="news-image">
                                <i class="fas fa-newspaper"></i>
                            </div>
                            <span class="news-category badge 
                                <?php 
                                switch($item['category']) {
                                    case 'tuyensinh': echo 'bg-primary'; break;
                                    case 'sukien': echo 'bg-success'; break;
                                    case 'thongbao': echo 'bg-warning'; break;
                                    default: echo 'bg-secondary';
                                }
                                ?>
                            ">
                                <?php 
                                $categories = [
                                    'tuyensinh' => 'Tuyển sinh',
                                    'sukien' => 'Sự kiện', 
                                    'thongbao' => 'Thông báo'
                                ];
                                echo $categories[$item['category']] ?? $item['category'];
                                ?>
                            </span>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title fw-bold"><?php echo htmlspecialchars($item['title']); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars(substr(strip_tags($item['content']), 0, 150)); ?>...</p>
                            
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <small class="text-muted">
                                    <i class="fas fa-user me-1"></i>
                                    <?php echo htmlspecialchars($item['full_name']); ?>
                                </small>
                                <small class="text-muted">
                                    <i class="fas fa-calendar me-1"></i>
                                    <?php echo date('d/m/Y', strtotime($item['published_at'])); ?>
                                </small>
                            </div>
                            
                            <a href="/BTL/views/public/news_detail.php?id=<?php echo $item['id']; ?>" class="btn btn-outline-primary btn-sm mt-3 w-100">
                                <i class="fas fa-eye me-2"></i>Xem chi tiết
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Hệ thống đang bảo trì</strong>
                        <p class="mb-0 mt-2">Tin tức đang được cập nhật. Vui lòng quay lại sau!</p>
                        
                        <!-- Hiển thị tin tức mẫu cho demo -->
                        <div class="row mt-4">
                            <div class="col-lg-4 col-md-6">
                                <div class="card news-card shadow-sm">
                                    <div class="position-relative">
                                        <div class="news-image">
                                            <i class="fas fa-newspaper"></i>
                                        </div>
                                        <span class="news-category badge bg-primary">Tuyển sinh</span>
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title fw-bold">Thông báo tuyển sinh năm 2025</h5>
                                        <p class="card-text">Đại học Hà Nội thông báo tuyển sinh hệ đại học chính quy năm 2025 với nhiều ngành học mới và chính sách học bổng hấp dẫn...</p>
                                        
                                        <div class="d-flex justify-content-between align-items-center mt-3">
                                            <small class="text-muted">
                                                <i class="fas fa-user me-1"></i>
                                                Ban Tuyển sinh
                                            </small>
                                            <small class="text-muted">
                                                <i class="fas fa-calendar me-1"></i>
                                                15/03/2025
                                            </small>
                                        </div>
                                        
                                        <a href="#" class="btn btn-outline-primary btn-sm mt-3 w-100">
                                            <i class="fas fa-eye me-2"></i>Xem chi tiết
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6">
                                <div class="card news-card shadow-sm">
                                    <div class="position-relative">
                                        <div class="news-image">
                                            <i class="fas fa-calendar-check"></i>
                                        </div>
                                        <span class="news-category badge bg-success">Sự kiện</span>
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title fw-bold">Ngày hội Open Day 2025</h5>
                                        <p class="card-text">Tham gia ngày hội Open Day để trải nghiệm môi trường học tập và gặp gỡ giảng viên, sinh viên Đại học Hà Nội...</p>
                                        
                                        <div class="d-flex justify-content-between align-items-center mt-3">
                                            <small class="text-muted">
                                                <i class="fas fa-user me-1"></i>
                                                Phòng CTSV
                                            </small>
                                            <small class="text-muted">
                                                <i class="fas fa-calendar me-1"></i>
                                                10/03/2025
                                            </small>
                                        </div>
                                        
                                        <a href="#" class="btn btn-outline-primary btn-sm mt-3 w-100">
                                            <i class="fas fa-eye me-2"></i>Xem chi tiết
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Tin nổi bật -->
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center fw-bold mb-5">TIN NỔI BẬT</h2>
        <div class="row">
            <div class="col-lg-6 mb-4">
                <div class="card bg-primary text-white">
                    <div class="card-body p-4">
                        <span class="badge bg-light text-primary mb-3">MỚI NHẤT</span>
                        <h3 class="card-title fw-bold">Ngày hội Open Day 2025</h3>
                        <p class="card-text">Tham gia ngày hội Open Day để trải nghiệm môi trường học tập và gặp gỡ giảng viên, sinh viên Đại học Hà Nội.</p>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-calendar me-2"></i>
                            <span>15/04/2025 | 8:00 - 17:00</span>
                        </div>
                        <div class="d-flex align-items-center mt-2">
                            <i class="fas fa-map-marker-alt me-2"></i>
                            <span>Hội trường A - Đại học Hà Nội</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6 mb-4">
                <div class="card bg-success text-white">
                    <div class="card-body p-4">
                        <span class="badge bg-light text-success mb-3">QUAN TRỌNG</span>
                        <h3 class="card-title fw-bold">Hội thảo tuyển sinh trực tuyến</h3>
                        <p class="card-text">Tham gia hội thảo trực tuyến để được tư vấn trực tiếp về các ngành học, phương thức tuyển sinh và học bổng.</p>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-calendar me-2"></i>
                            <span>20/04/2025 | 19:00 - 21:00</span>
                        </div>
                        <div class="d-flex align-items-center mt-2">
                            <i class="fas fa-video me-2"></i>
                            <span>Zoom Meeting & Facebook Live</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
document.querySelectorAll('.btn-group .btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.btn-group .btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        
        const filter = this.getAttribute('data-filter');
        const newsItems = document.querySelectorAll('#newsList .col-lg-4');
        
        newsItems.forEach(item => {
            if (filter === 'all' || item.getAttribute('data-category') === filter) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    });
});
</script>

<?php include '../partials/footer.php'; ?>