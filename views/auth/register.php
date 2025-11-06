<?php
session_start();
include_once __DIR__ . '/../layouts/header.php';
?>

<div class="login-container">
    <div class="login-card">
        <h2 class="login-title">Đăng Ký Tài Khoản</h2>
        
        <?php 
        if (isset($_SESSION['flash_message'])) {
            $message = $_SESSION['flash_message']['message'];
            $type = $_SESSION['flash_message']['type'];
            
            $alertClass = '';
            switch ($type) {
                case 'success':
                    $alertClass = 'alert-success';
                    break;
                case 'error':
                    $alertClass = 'alert-danger';
                    break;
                case 'warning':
                    $alertClass = 'alert-warning';
                    break;
                default:
                    $alertClass = 'alert-info';
            }
            
            echo "<div class='alert $alertClass' style='padding: 1rem; margin-bottom: 1rem; border-radius: 5px;'>
                    $message
                    <button type='button' class='close' onclick='this.parentElement.remove()' style='float: right; background: none; border: none; font-size: 1.2rem;'>&times;</button>
                  </div>";
            
            unset($_SESSION['flash_message']);
        }
        ?>

        <form action="../../controllers/AuthController.php?action=register" method="POST">
            <div class="form-group">
                <label class="form-label">Họ và tên *</label>
                <input type="text" class="form-control" name="full_name" required>
            </div>

            <div class="form-group">
                <label class="form-label">Email *</label>
                <input type="email" class="form-control" name="email" required>
            </div>

            <div class="form-group">
                <label class="form-label">Số điện thoại *</label>
                <input type="tel" class="form-control" name="phone" required>
            </div>

            <div class="form-group">
                <label class="form-label">Tên đăng nhập *</label>
                <input type="text" class="form-control" name="username" required>
            </div>

            <div class="form-group">
                <label class="form-label">Mật khẩu *</label>
                <input type="password" class="form-control" name="password" required>
            </div>

            <div class="form-group">
                <label class="form-label">Xác nhận mật khẩu *</label>
                <input type="password" class="form-control" name="confirm_password" required>
            </div>

            <div class="form-group">
                <label class="form-label">Loại tài khoản *</label>
                <select class="form-control" name="role" required>
                    <option value="candidate">Thí sinh</option>
                    <option value="staff">Nhân viên</option>
                    <option value="admin">Quản trị viên</option>
                </select>
            </div>

            <div class="form-group">
                <label style="display: flex; align-items: center;">
                    <input type="checkbox" name="agree_terms" required style="margin-right: 0.5rem;">
                    Tôi đồng ý với <a href="#" style="color: #667eea;">điều khoản sử dụng</a>
                </label>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 1rem; margin-top: 1rem;">Đăng Ký</button>
        </form>

        <div style="text-align: center; margin-top: 2rem; padding-top: 2rem; border-top: 1px solid #e1e5e9;">
            <p style="color: #666;">Đã có tài khoản? <a href="login.php" style="color: #667eea; text-decoration: none;">Đăng nhập ngay</a></p>
        </div>
    </div>
</div>

<?php
include_once __DIR__ . '/../layouts/footer.php';
?>