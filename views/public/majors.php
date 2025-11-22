<?php
session_start();
require_once '../../functions/db_connection.php';

// Xử lý lỗi khi bảng không tồn tại
try {
    $majors = $pdo->query("SELECT * FROM majors WHERE status = 'active' ORDER BY major_name")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Nếu bảng không tồn tại, sử dụng mảng rỗng và ghi log lỗi
    error_log("Lỗi truy vấn majors: " . $e->getMessage());
    $majors = [];
    
    // Hoặc có thể tạo bảng tạm thời cho demo
    // $majors = createTempMajorsData();
}
?>

<?php include '../partials/header.php'; ?>

<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <h1 class="display-4 fw-bold">NGÀNH ĐÀO TẠO</h1>
        <p class="lead">Khám phá các chương trình đào tạo chất lượng cao</p>
    </div>
</section>

<!-- Danh sách ngành học -->
<section class="py-5">
    <div class="container">
        <div class="row mb-4">
            <div class="col-md-6">
                <h2 class="fw-bold">Tất cả ngành đào tạo</h2>
                <p class="text-muted">Chọn ngành học phù hợp với đam mê và năng lực của bạn</p>
            </div>
            <div class="col-md-6 text-end">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Tìm kiếm ngành học..." id="searchMajor">
                    <button class="btn btn-primary" type="button">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="row" id="majorsList">
            <?php if(!empty($majors)): ?>
                <?php foreach($majors as $major): ?>
                <div class="col-lg-4 col-md-6">
                    <div class="card major-card shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="major-icon">
                                <i class="fas fa-graduation-cap"></i>
                            </div>
                            <h4 class="card-title fw-bold text-primary"><?php echo htmlspecialchars($major['major_name']); ?></h4>
                            <h6 class="card-subtitle mb-3 text-muted">Mã ngành: <?php echo htmlspecialchars($major['major_code']); ?></h6>
                            <p class="card-text"><?php echo htmlspecialchars($major['description']); ?></p>
                            
                            <div class="row text-center mb-3">
                                <div class="col-6">
                                    <strong class="text-primary">Thời gian</strong>
                                    <p class="mb-0"><?php echo $major['duration']; ?> năm</p>
                                </div>
                                <div class="col-6">
                                    <strong class="text-primary">Chỉ tiêu</strong>
                                    <p class="mb-0"><?php echo $major['quota']; ?> SV</p>
                                </div>
                            </div>
                            
                            <div class="d-grid gap-2">
                                <span class="quota-badge">Còn <?php echo $major['quota']; ?> chỉ tiêu</span>
                                <?php if(isset($_SESSION['user_id']) && $_SESSION['user_type'] === 'candidate'): ?>
                                    <a href="/BTL/views/candidate/application.php" class="btn btn-outline-primary">
                                        <i class="fas fa-edit me-2"></i>Đăng ký ngay
                                    </a>
                                <?php else: ?>
                                    <a href="/BTL/views/auth/register.php" class="btn btn-outline-primary">
                                        <i class="fas fa-user-plus me-2"></i>Đăng ký tài khoản
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Đang bảo trì hệ thống</strong>
                        <p class="mb-0 mt-2">Thông tin ngành học đang được cập nhật. Vui lòng quay lại sau!</p>
                        
                        <!-- Hiển thị dữ liệu mẫu cho demo -->
                        <div class="row mt-4">
                            <div class="col-lg-4 col-md-6">
                                <div class="card major-card shadow-sm">
                                    <div class="card-body text-center p-4">
                                        <div class="major-icon">
                                            <i class="fas fa-laptop-code"></i>
                                        </div>
                                        <h4 class="card-title fw-bold text-primary">Công nghệ Thông tin</h4>
                                        <h6 class="card-subtitle mb-3 text-muted">Mã ngành: CNTT</h6>
                                        <p class="card-text">Đào tạo kỹ sư công nghệ thông tin chất lượng cao</p>
                                        
                                        <div class="row text-center mb-3">
                                            <div class="col-6">
                                                <strong class="text-primary">Thời gian</strong>
                                                <p class="mb-0">4 năm</p>
                                            </div>
                                            <div class="col-6">
                                                <strong class="text-primary">Chỉ tiêu</strong>
                                                <p class="mb-0">200 SV</p>
                                            </div>
                                        </div>
                                        
                                        <div class="d-grid gap-2">
                                            <span class="quota-badge">Còn 200 chỉ tiêu</span>
                                            <a href="/BTL/views/auth/register.php" class="btn btn-outline-primary">
                                                <i class="fas fa-user-plus me-2"></i>Đăng ký tài khoản
                                            </a>
                                        </div>
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

<!-- Thông tin thêm -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <h3 class="fw-bold mb-4">Thông tin tuyển sinh</h3>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-calendar-alt text-primary me-3 fa-2x"></i>
                            <div>
                                <h5 class="fw-bold">Thời gian tuyển sinh</h5>
                                <p class="mb-0">Từ 01/03/2025 đến 30/06/2025</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-file-alt text-primary me-3 fa-2x"></i>
                            <div>
                                <h5 class="fw-bold">Hồ sơ cần thiết</h5>
                                <p class="mb-0">Bằng tốt nghiệp, Học bạ, CMND/CCCD</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <h3 class="fw-bold mb-4">Cần hỗ trợ?</h3>
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h5><i class="fas fa-phone-alt me-2"></i>Hotline tuyển sinh</h5>
                        <p class="h4 fw-bold mb-3">(024) 3854 3388</p>
                        <p class="mb-0">Liên hệ ngay để được tư vấn chi tiết về ngành học</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
document.getElementById('searchMajor').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const majors = document.querySelectorAll('#majorsList .col-lg-4');
    
    majors.forEach(major => {
        const majorName = major.querySelector('.card-title').textContent.toLowerCase();
        const majorCode = major.querySelector('.card-subtitle').textContent.toLowerCase();
        
        if (majorName.includes(searchTerm) || majorCode.includes(searchTerm)) {
            major.style.display = 'block';
        } else {
            major.style.display = 'none';
        }
    });
});
</script>

<?php include '../partials/footer.php'; ?>