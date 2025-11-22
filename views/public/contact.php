<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];
    
    $success = true;
}
?>

<?php include '../partials/header.php'; ?>

<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <h1 class="display-4 fw-bold">LIÊN HỆ</h1>
        <p class="lead">Chúng tôi luôn sẵn sàng hỗ trợ và giải đáp mọi thắc mắc của bạn</p>
    </div>
</section>

<!-- Thông tin liên hệ -->
<section class="py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="card contact-info-card border-primary">
                    <div class="card-body text-center p-4">
                        <div class="contact-icon bg-primary text-white mx-auto">
                            <i class="fas fa-map-marker-alt fa-2x"></i>
                        </div>
                        <h4 class="fw-bold">Địa chỉ</h4>
                        <p class="mb-0">Km9, Nguyễn Trãi, Thanh Xuân, Hà Nội</p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6">
                <div class="card contact-info-card border-success">
                    <div class="card-body text-center p-4">
                        <div class="contact-icon bg-success text-white mx-auto">
                            <i class="fas fa-phone-alt fa-2x"></i>
                        </div>
                        <h4 class="fw-bold">Điện thoại</h4>
                        <p class="mb-0">(024) 3854 3388</p>
                        <small class="text-muted">Hotline: 1800 1234</small>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6">
                <div class="card contact-info-card border-warning">
                    <div class="card-body text-center p-4">
                        <div class="contact-icon bg-warning text-white mx-auto">
                            <i class="fas fa-envelope fa-2x"></i>
                        </div>
                        <h4 class="fw-bold">Email</h4>
                        <p class="mb-0">tuyensinh@hanu.edu.vn</p>
                        <small class="text-muted">info@hanu.edu.vn</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Form liên hệ và Map -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 mb-4">
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <h3 class="fw-bold mb-4">Gửi tin nhắn cho chúng tôi</h3>
                        
                        <?php if(isset($success) && $success): ?>
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle me-2"></i>
                                Cảm ơn bạn! Tin nhắn đã được gửi thành công. Chúng tôi sẽ phản hồi sớm nhất.
                            </div>
                        <?php endif; ?>
                        
                        <form method="POST">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Họ và tên *</label>
                                    <input type="text" name="name" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Email *</label>
                                    <input type="email" name="email" class="form-control" required>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Số điện thoại</label>
                                    <input type="tel" name="phone" class="form-control">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Chủ đề *</label>
                                    <select name="subject" class="form-select" required>
                                        <option value="">Chọn chủ đề</option>
                                        <option value="tuyensinh">Thông tin tuyển sinh</option>
                                        <option value="daotao">Chương trình đào tạo</option>
                                        <option value="hocbong">Học bổng</option>
                                        <option value="khac">Khác</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Nội dung tin nhắn *</label>
                                <textarea name="message" class="form-control" rows="5" required></textarea>
                            </div>
                            
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-paper-plane me-2"></i>Gửi tin nhắn
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="card shadow-sm">
                    <div class="card-body p-0">
                        <div class="map-container">
                            <iframe 
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3724.096484166299!2d105.78047431540227!3d21.028814785998!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3135ab4cd0c66f05%3A0xea31563511af2e54!2zVHLGsOG7nW5nIMSQ4bqhaSBo4buNYyBIw6AgTuG7mWk!5e0!3m2!1svi!2s!4v1647421234567!5m2!1svi!2s" 
                                width="100%" 
                                height="400" 
                                style="border:0;" 
                                allowfullscreen="" 
                                loading="lazy">
                            </iframe>
                        </div>
                    </div>
                </div>
                
                <!-- Thời gian làm việc -->
                <div class="card mt-4">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3"><i class="fas fa-clock me-2"></i>Thời gian làm việc</h5>
                        <div class="row">
                            <div class="col-6">
                                <p class="mb-1"><strong>Thứ 2 - Thứ 6</strong></p>
                                <p class="mb-1"><strong>Thứ 7</strong></p>
                                <p class="mb-0"><strong>Chủ nhật</strong></p>
                            </div>
                            <div class="col-6">
                                <p class="mb-1">7:30 - 17:00</p>
                                <p class="mb-1">7:30 - 12:00</p>
                                <p class="mb-0">Nghỉ</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Các khoa phòng -->
<section class="py-5">
    <div class="container">
        <h2 class="text-center fw-bold mb-5">CÁC KHOA - PHÒNG LIÊN QUAN</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-user-graduate fa-3x text-primary mb-3"></i>
                        <h5 class="fw-bold">Phòng Đào tạo</h5>
                        <p class="text-muted">ĐT: (024) 3854 3389</p>
                        <p class="mb-0">daotao@hanu.edu.vn</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-book fa-3x text-success mb-3"></i>
                        <h5 class="fw-bold">Phòng Công tác Sinh viên</h5>
                        <p class="text-muted">ĐT: (024) 3854 3390</p>
                        <p class="mb-0">ctsv@hanu.edu.vn</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-dollar-sign fa-3x text-warning mb-3"></i>
                        <h5 class="fw-bold">Phòng Kế hoạch Tài chính</h5>
                        <p class="text-muted">ĐT: (024) 3854 3391</p>
                        <p class="mb-0">khtc@hanu.edu.vn</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include '../partials/footer.php'; ?>