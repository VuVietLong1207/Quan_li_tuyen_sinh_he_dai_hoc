<?php
include_once '../../views/layouts/header.php';
?>

<div class="main-content">
    <div class="card">
        <h1 style="color: #333; margin-bottom: 2rem;">Đăng Ký Thí Sinh Mới</h1>

        <form action="#" method="POST" enctype="multipart/form-data">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
                <!-- Thông tin cá nhân -->
                <div>
                    <h3 style="color: #667eea; margin-bottom: 1rem; padding-bottom: 0.5rem; border-bottom: 2px solid #667eea;">Thông Tin Cá Nhân</h3>
                    
                    <div class="form-group">
                        <label class="form-label">Họ và tên *</label>
                        <input type="text" class="form-control" name="full_name" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Ngày sinh *</label>
                        <input type="date" class="form-control" name="birth_date" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Giới tính *</label>
                        <select class="form-control" name="gender" required>
                            <option value="">Chọn giới tính</option>
                            <option value="male">Nam</option>
                            <option value="female">Nữ</option>
                            <option value="other">Khác</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Số CMND/CCCD *</label>
                        <input type="text" class="form-control" name="id_number" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Số điện thoại *</label>
                        <input type="tel" class="form-control" name="phone" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Email *</label>
                        <input type="email" class="form-control" name="email" required>
                    </div>
                </div>

                <!-- Thông tin tuyển sinh -->
                <div>
                    <h3 style="color: #4CAF50; margin-bottom: 1rem; padding-bottom: 0.5rem; border-bottom: 2px solid #4CAF50;">Thông Tin Tuyển Sinh</h3>

                    <div class="form-group">
                        <label class="form-label">Ngành đăng ký *</label>
                        <select class="form-control" name="major_id" required>
                            <option value="">Chọn ngành học</option>
                            <option value="1">Công Nghệ Thông Tin</option>
                            <option value="2">Quản Trị Kinh Doanh</option>
                            <option value="3">Kỹ Thuật Điện Tử</option>
                            <option value="4">Công Nghệ Thực Phẩm</option>
                            <option value="5">Ngôn Ngữ Anh</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Tổ hợp môn xét tuyển *</label>
                        <select class="form-control" name="subject_group" required>
                            <option value="">Chọn tổ hợp môn</option>
                            <option value="A00">A00 (Toán, Lý, Hóa)</option>
                            <option value="A01">A01 (Toán, Lý, Anh)</option>
                            <option value="D01">D01 (Toán, Văn, Anh)</option>
                            <option value="D07">D07 (Toán, Hóa, Anh)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Điểm trung bình lớp 12 *</label>
                        <input type="number" step="0.01" class="form-control" name="gpa" min="0" max="10" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Ảnh thẻ (3x4)</label>
                        <input type="file" class="form-control" name="photo" accept="image/*">
                    </div>

                    <div class="form-group">
                        <label class="form-label">File hồ sơ (PDF)</label>
                        <input type="file" class="form-control" name="document" accept=".pdf">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Ghi chú</label>
                        <textarea class="form-control" name="notes" rows="3"></textarea>
                    </div>
                </div>
            </div>

            <div style="margin-top: 2rem; text-align: center;">
                <button type="submit" class="btn btn-primary" style="padding: 1rem 3rem; font-size: 1.1rem;">Đăng Ký</button>
                <a href="index.php" class="btn" style="padding: 1rem 3rem; font-size: 1.1rem; margin-left: 1rem;">Hủy</a>
            </div>
        </form>
    </div>
</div>  

<?php
include_once '../../views/layouts/footer.php';
?>