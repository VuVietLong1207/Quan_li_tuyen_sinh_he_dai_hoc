<?php
include_once 'views/layouts/header.php';
?>

<div class="main-content">
    <div class="hero-section" style="text-align: center; padding: 4rem 0; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 15px; margin-bottom: 3rem;">
        <h1 style="font-size: 3rem; margin-bottom: 1rem;">HỆ THỐNG QUẢN LÝ TUYỂN SINH</h1>
        <p style="font-size: 1.2rem; margin-bottom: 2rem;">Đại Học Quốc Gia - Nơi ươm mầm tài năng tương lai</p>
        <a href="/admission_management/views/candidates/create.php" class="btn btn-primary" style="font-size: 1.1rem; padding: 1rem 2rem;">ĐĂNG KÝ XÉT TUYỂN NGAY</a>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <span class="stat-number">5,247</span>
            <span class="stat-label">Thí Sinh Đã Đăng Ký</span>
        </div>
        <div class="stat-card">
            <span class="stat-number">25</span>
            <span class="stat-label">Ngành Đào Tạo</span>
        </div>
        <div class="stat-card">
            <span class="stat-number">98.5%</span>
            <span class="stat-label">Tỷ Lệ Hài Lòng</span>
        </div>
        <div class="stat-card">
            <span class="stat-number">15</span>
            <span class="stat-label">Năm Kinh Nghiệm</span>
        </div>
    </div>

    <div class="card">
        <h2 style="margin-bottom: 1.5rem; color: #333;">Thông Báo Tuyển Sinh</h2>
        <div class="announcements">
            <div class="announcement-item" style="padding: 1rem; border-left: 4px solid #667eea; background: #f8f9fa; margin-bottom: 1rem; border-radius: 0 5px 5px 0;">
                <h3 style="color: #667eea; margin-bottom: 0.5rem;">Thông báo mở đơn đăng ký xét tuyển</h3>
                <p style="color: #666;">Thời gian nhận hồ sơ: Từ 01/03/2024 đến 30/06/2024</p>
                <small style="color: #999;">Đăng ngày: 15/02/2024</small>
            </div>
            <div class="announcement-item" style="padding: 1rem; border-left: 4px solid #4CAF50; background: #f8f9fa; margin-bottom: 1rem; border-radius: 0 5px 5px 0;">
                <h3 style="color: #4CAF50; margin-bottom: 0.5rem;">Lịch thi đánh giá năng lực</h3>
                <p style="color: #666;">Các đợt thi: 15/04, 20/05, 25/06/2024</p>
                <small style="color: #999;">Đăng ngày: 10/02/2024</small>
            </div>
        </div>
    </div>

    <div class="card">
        <h2 style="margin-bottom: 1.5rem; color: #333;">Các Ngành Đào Tạo Nổi Bật</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
            <div style="padding: 1.5rem; border: 1px solid #e1e5e9; border-radius: 8px; text-align: center;">
                <h3 style="color: #667eea; margin-bottom: 1rem;">Công Nghệ Thông Tin</h3>
                <p style="color: #666; margin-bottom: 1rem;">Đào tạo chuyên sâu về lập trình, AI, và công nghệ mới</p>
                <span style="background: #667eea; color: white; padding: 0.3rem 0.8rem; border-radius: 15px; font-size: 0.9rem;">Chỉ tiêu: 300</span>
            </div>
            <div style="padding: 1.5rem; border: 1px solid #e1e5e9; border-radius: 8px; text-align: center;">
                <h3 style="color: #4CAF50; margin-bottom: 1rem;">Quản Trị Kinh Doanh</h3>
                <p style="color: #666; margin-bottom: 1rem;">Phát triển kỹ năng quản lý và khởi nghiệp</p>
                <span style="background: #4CAF50; color: white; padding: 0.3rem 0.8rem; border-radius: 15px; font-size: 0.9rem;">Chỉ tiêu: 250</span>
            </div>
            <div style="padding: 1.5rem; border: 1px solid #e1e5e9; border-radius: 8px; text-align: center;">
                <h3 style="color: #ff9800; margin-bottom: 1rem;">Kỹ Thuật Điện Tử</h3>
                <p style="color: #666; margin-bottom: 1rem;">Chuyên ngành IoT và hệ thống nhúng</p>
                <span style="background: #ff9800; color: white; padding: 0.3rem 0.8rem; border-radius: 15px; font-size: 0.9rem;">Chỉ tiêu: 200</span>
            </div>
        </div>
    </div>
</div>

<?php
include_once 'views/layouts/footer.php';
?>