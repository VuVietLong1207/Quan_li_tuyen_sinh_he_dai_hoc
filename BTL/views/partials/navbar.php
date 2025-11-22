<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary sticky-top">
    <div class="container">
        <a class="navbar-brand" href="/BTL/index.php">
            <i class="fas fa-university me-2"></i>
            <strong>ĐẠI HỌC HÀ NỘI</strong>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link <?php echo $current_page == 'index.php' ? 'active' : ''; ?>" href="/BTL/index.php">
                        <i class="fas fa-home me-1"></i> Trang chủ
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $current_page == 'majors.php' ? 'active' : ''; ?>" href="/BTL/views/public/majors.php">
                        <i class="fas fa-graduation-cap me-1"></i> Ngành đào tạo
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $current_page == 'admission.php' ? 'active' : ''; ?>" href="/BTL/views/public/admission.php">
                        <i class="fas fa-info-circle me-1"></i> Thông tin tuyển sinh
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $current_page == 'news.php' ? 'active' : ''; ?>" href="/BTL/views/public/news.php">
                        <i class="fas fa-newspaper me-1"></i> Tin tức
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $current_page == 'contact.php' ? 'active' : ''; ?>" href="/BTL/views/public/contact.php">
                        <i class="fas fa-phone me-1"></i> Liên hệ
                    </a>
                </li>
            </ul>
            <div class="navbar-nav">
                <?php if(isset($_SESSION['user_id'])): ?>
                    <a class="nav-link" href="/BTL/views/<?php echo $_SESSION['user_type']; ?>/dashboard.php">
                        <i class="fas fa-user me-1"></i> <?php echo $_SESSION['full_name']; ?>
                    </a>
                    <a class="nav-link" href="/BTL/logout.php">
                        <i class="fas fa-sign-out-alt me-1"></i> Đăng xuất
                    </a>
                <?php else: ?>
                    <a class="nav-link" href="/BTL/views/auth/login.php">
                        <i class="fas fa-sign-in-alt me-1"></i> Đăng nhập
                    </a>
                    <a class="nav-link btn btn-light text-primary mx-2" href="/BTL/views/auth/register.php" style="padding: 5px 15px !important;">
                        <i class="fas fa-user-plus me-1"></i> Đăng ký
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>