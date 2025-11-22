<?php
session_start();
require_once '../../functions/db_connection.php';
?>

<?php include '../partials/header.php'; ?>

<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <h1 class="display-4 fw-bold">THÔNG TIN TUYỂN SINH 2025</h1>
        <p class="lead">Hướng dẫn chi tiết quy trình và phương thức xét tuyển</p>
    </div>
</section>

<!-- Phương thức tuyển sinh -->
<section class="py-5">
    <div class="container">
        <h2 class="text-center fw-bold mb-5">CÁC PHƯƠNG THỨC TUYỂN SINH</h2>
        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="card method-card border-primary">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <i class="fas fa-file-alt fa-3x text-primary"></i>
                        </div>
                        <h4 class="card-title fw-bold">Xét học bạ</h4>
                        <p class="card-text">Xét tuyển dựa trên điểm trung bình 3 năm học THPT hoặc điểm trung bình lớp 12.</p>
                        <ul class="list-unstyled text-start">
                            <li><i class="fas fa-check text-success me-2"></i>Điểm TB 3 năm ≥ 6.5</li>
                            <li><i class="fas fa-check text-success me-2"></i>Hạnh kiểm Khá trở lên</li>
                            <li><i class="fas fa-check text-success me-2"></i>Tốt nghiệp THPT</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6">
                <div class="card method-card border-success">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <i class="fas fa-university fa-3x text-success"></i>
                        </div>
                        <h4 class="card-title fw-bold">Thi THPT Quốc gia</h4>
                        <p class="card-text">Xét tuyển dựa trên kết quả kỳ thi THPT Quốc gia năm 2025.</p>
                        <ul class="list-unstyled text-start">
                            <li><i class="fas fa-check text-success me-2"></i>Tổ hợp môn theo ngành</li>
                            <li><i class="fas fa-check text-success me-2"></i>Điểm chuẩn từ 18-24</li>
                            <li><i class="fas fa-check text-success me-2"></i>Xét theo nguyện vọng</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6">
                <div class="card method-card border-warning">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <i class="fas fa-trophy fa-3x text-warning"></i>
                        </div>
                        <h4 class="card-title fw-bold">Xét tuyển thẳng</h4>
                        <p class="card-text">Dành cho thí sinh đạt thành tích xuất sắc trong học tập và thi đấu.</p>
                        <ul class="list-unstyled text-start">
                            <li><i class="fas fa-check text-success me-2"></i>Học sinh giỏi Quốc gia</li>
                            <li><i class="fas fa-check text-success me-2"></i>Vận động viên cấp Quốc gia</li>
                            <li><i class="fas fa-check text-success me-2"></i>Thí sinh quốc tế</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Lịch trình tuyển sinh -->
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center fw-bold mb-5">LỊCH TRÌNH TUYỂN SINH</h2>
        <div class="timeline">
            <div class="timeline-item">
                <div class="timeline-content">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="fw-bold text-primary">Đợt 1</h4>
                        <span class="badge bg-primary">01/03 - 31/03/2024</span>
                    </div>
                    <p class="mb-0">Nhận hồ sơ xét tuyển học bạ và hồ sơ ưu tiên xét tuyển</p>
                </div>
            </div>
            
            <div class="timeline-item">
                <div class="timeline-content">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="fw-bold text-success">Đợt 2</h4>
                        <span class="badge bg-success">01/04 - 30/04/2024</span>
                    </div>
                    <p class="mb-0">Nhận hồ sơ xét tuyển theo kết quả thi đánh giá năng lực</p>
                </div>
            </div>
            
            <div class="timeline-item">
                <div class="timeline-content">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="fw-bold text-warning">Đợt 3</h4>
                        <span class="badge bg-warning">01/05 - 31/05/2024</span>
                    </div>
                    <p class="mb-0">Nhận hồ sơ xét tuyển theo kết quả thi THPT Quốc gia</p>
                </div>
            </div>
            
            <div class="timeline-item">
                <div class="timeline-content">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="fw-bold text-info">Công bố kết quả</h4>
                        <span class="badge bg-info">15/07/2024</span>
                    </div>
                    <p class="mb-0">Công bố kết quả trúng tuyển và danh sách thí sinh trúng tuyển</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Hồ sơ cần thiết -->
<section class="py-5">
    <div class="container">
        <h2 class="text-center fw-bold mb-5">HỒ SƠ ĐĂNG KÝ</h2>
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title fw-bold mb-4">Danh mục hồ sơ cần chuẩn bị:</h4>
                        <div class="row">
                            <div class="col-md-6">
                                <ul class="list-unstyled">
                                    <li class="mb-3">
                                        <i class="fas fa-file-contract text-primary me-2"></i>
                                        <strong>Phiếu đăng ký xét tuyển</strong> (theo mẫu)
                                    </li>
                                    <li class="mb-3">
                                        <i class="fas fa-id-card text-primary me-2"></i>
                                        <strong>Bản sao CMND/CCCD</strong> (công chứng)
                                    </li>
                                    <li class="mb-3">
                                        <i class="fas fa-graduation-cap text-primary me-2"></i>
                                        <strong>Bằng tốt nghiệp THPT</strong> (bản sao)
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <ul class="list-unstyled">
                                    <li class="mb-3">
                                        <i class="fas fa-book text-primary me-2"></i>
                                        <strong>Học bạ THPT</strong> (bản sao)
                                    </li>
                                    <li class="mb-3">
                                        <i class="fas fa-image text-primary me-2"></i>
                                        <strong>Ảnh 3x4</strong> (4 tấm)
                                    </li>
                                    <li class="mb-3">
                                        <i class="fas fa-award text-primary me-2"></i>
                                        <strong>Giấy chứng nhận ưu tiên</strong> (nếu có)
                                    </li>
                                </ul>
                            </div>
                        </div>
                        
                        <div class="alert alert-info mt-4">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Lưu ý:</strong> Tất cả các bản sao phải được công chứng trong thời hạn 6 tháng.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Đăng ký -->
<section id="dangky" class="py-5 bg-primary text-white">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h3 class="fw-bold">Sẵn sàng đăng ký?</h3>
                <p class="mb-0">Hãy đăng ký ngay hôm nay để không bỏ lỡ cơ hội trở thành sinh viên Đại học Hà Nội</p>
            </div>
            <div class="col-lg-4 text-end">
                <?php if(isset($_SESSION['user_id']) && $_SESSION['user_type'] === 'candidate'): ?>
                    <a href="/BTL/views/candidate/application.php" class="btn btn-light btn-lg">
                        <i class="fas fa-edit me-2"></i>Đăng ký xét tuyển
                    </a>
                <?php else: ?>
                    <a href="/BTL/views/auth/register.php" class="btn btn-light btn-lg">
                        <i class="fas fa-user-plus me-2"></i>Đăng ký tài khoản
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php include '../partials/footer.php'; ?>