<?php
session_start();
require_once 'functions/db_connection.php';
require_once 'functions/auth.php';

try {
    $majors_stmt = $pdo->query("SELECT * FROM majors WHERE status = 'active' LIMIT 4");
    $majors = $majors_stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $news_stmt = $pdo->query("
        SELECT n.*, u.full_name 
        FROM news n 
        JOIN users u ON n.author_id = u.id 
        WHERE n.is_published = TRUE 
        ORDER BY n.published_at DESC 
        LIMIT 3
    ");
    $news = $news_stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $majors = [];
    $news = [];
}
?>

<?php include 'views/partials/header.php'; ?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-3">ĐẠI HỌC HÀ NỘI</h1>
                <h2 class="mb-4">Tuyển Sinh Đại Học 2025</h2>
                <p class="lead mb-4"><i class="fas fa-quote-left me-2"></i>Nơi ươm mầm tri thức - Kiến tạo tương lai<i class="fas fa-quote-right ms-2"></i></p>
                <div class="d-flex gap-3 flex-wrap">
                    <a href="/BTL/views/auth/register.php" class="btn btn-primary btn-lg">
                        <i class="fas fa-edit me-2"></i>Đăng ký ngay
                    </a>
                    <a href="/BTL/views/public/admission.php" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-search me-2"></i>Tìm hiểu thêm
                    </a>
                </div>
            </div>
            <div class="col-lg-6 text-center">
                <div class="hero-image">
                    <i class="fas fa-graduation-cap" style="font-size: 300px; opacity: 0.3;"></i>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Thống kê nhanh -->
<section class="stats-section">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-3 col-6 stat-item">
                <span class="stat-number">50+</span>
                <p class="mb-0 fw-bold">Ngành đào tạo</p>
            </div>
            <div class="col-md-3 col-6 stat-item">
                <span class="stat-number">15,000+</span>
                <p class="mb-0 fw-bold">Sinh viên</p>
            </div>
            <div class="col-md-3 col-6 stat-item">
                <span class="stat-number">95%</span>
                <p class="mb-0 fw-bold">Có việc làm sau tốt nghiệp</p>
            </div>
            <div class="col-md-3 col-6 stat-item">
                <span class="stat-number">60+</span>
                <p class="mb-0 fw-bold">Năm kinh nghiệm</p>
            </div>
        </div>
    </div>
</section>

<!-- Ngành học nổi bật -->
<section class="py-5">
    <div class="container">
        <h2 class="text-center section-title">Các Ngành Đào Tạo Nổi Bật</h2>
        <div class="row">
            <?php if(!empty($majors)): ?>
                <?php foreach($majors as $major): ?>
                <div class="col-md-3 mb-4">
                    <div class="card major-card h-100 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="mb-3">
                                <i class="fas fa-laptop-code fa-2x text-primary"></i>
                            </div>
                            <h5 class="card-title fw-bold"><?php echo htmlspecialchars($major['major_name']); ?></h5>
                            <h6 class="card-subtitle mb-2 text-muted"><?php echo htmlspecialchars($major['major_code']); ?></h6>
                            <p class="card-text"><?php echo htmlspecialchars(substr($major['description'], 0, 100)); ?>...</p>
                            <p class="text-primary fw-bold">Chỉ tiêu: <?php echo htmlspecialchars($major['quota']); ?></p>
                            <a href="/BTL/views/public/majors.php" class="btn btn-outline-primary btn-sm">Xem chi tiết</a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Đang cập nhật thông tin ngành học...
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <div class="text-center mt-4">
            <a href="/BTL/views/public/majors.php" class="btn btn-primary">
                <i class="fas fa-list me-2"></i>Xem tất cả ngành học
            </a>
        </div>
    </div>
</section>

<!-- Tin tức mới -->
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center section-title">Tin Tức Mới Nhất</h2>
        <div class="row">
            <?php if(!empty($news)): ?>
                <?php foreach($news as $item): ?>
                <div class="col-md-4 mb-4">
                    <div class="card news-card h-100 shadow">
                        <div class="card-body">
                            <span class="badge bg-primary mb-3">
                                <?php 
                                $categories = [
                                    'tuyensinh' => 'Tuyển sinh',
                                    'sukien' => 'Sự kiện', 
                                    'thongbao' => 'Thông báo'
                                ];
                                echo $categories[$item['category']] ?? $item['category'];
                                ?>
                            </span>
                            <h5 class="card-title"><?php echo htmlspecialchars($item['title']); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars(substr(strip_tags($item['content']), 0, 150)); ?>...</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    <i class="fas fa-user me-1"></i><?php echo htmlspecialchars($item['full_name']); ?>
                                </small>
                                <small class="text-muted">
                                    <i class="fas fa-calendar me-1"></i>
                                    <?php echo date('d/m/Y', strtotime($item['published_at'])); ?>
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Đang cập nhật tin tức...
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php include 'views/partials/footer.php'; ?>