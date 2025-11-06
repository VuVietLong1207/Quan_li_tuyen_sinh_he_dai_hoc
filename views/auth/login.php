<?php
session_start();
include_once __DIR__ . '/../layouts/header.php';
include_once __DIR__ . '/../index';
?>

<div class="login-container">
    <div class="login-card">
        <h2 class="login-title">Đăng Nhập Hệ Thống</h2>
        
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
        
        <form action="../../controllers/AuthController.php?action=login" method="POST">
            <div class="form-group">
                <label class="form-label">Tên đăng nhập hoặc Email</label>
                <input type="text" class="form-control" name="username" required>
            </div>

            <div class="form-group">
                <label class="form-label">Mật khẩu</label>
                <input type="password" class="form-control" name="password" required>
            </div>

            <div class="form-group" style="display: flex; justify-content: space-between; align-items: center;">
                <label style="display: flex; align-items: center;">
                    <input type="checkbox" name="remember" style="margin-right: 0.5rem;">
                    Ghi nhớ đăng nhập
                </label>
                <a href="#" style="color: #667eea; text-decoration: none;">Quên mật khẩu?</a>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 1rem; margin-top: 1rem;">Đăng Nhập</button>
        </form>

        <div style="text-align: center; margin-top: 2rem; padding-top: 2rem; border-top: 1px solid #e1e5e9;">
            <p style="color: #666;">Chưa có tài khoản? <a href="register.php" style="color: #667eea; text-decoration: none;">Đăng ký ngay</a></p>
        </div>
    </div>
</div>

<?php
include_once __DIR__ . '/../layouts/footer.php';
?>