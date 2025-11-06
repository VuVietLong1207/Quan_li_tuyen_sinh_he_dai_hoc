<?php
session_start();
include_once __DIR__ . '/../../config/database.php';
include_once __DIR__ . '/../../models/Major.php';
include_once __DIR__ . '/../../includes/functions.php';

$database = new Database();
$db = $database->getConnection();

include_once __DIR__ . '/../layouts/header.php';
?>

<div class="main-content">
    <div class="card">
        <h1 style="color: #333; margin-bottom: 2rem;">Thêm Ngành Học Mới</h1>

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

        <form action="../../controllers/MajorController.php?action=create" method="POST">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
                <!-- Thông tin cơ bản -->
                <div>
                    <h3 style="color: #667eea; margin-bottom: 1rem; padding-bottom: 0.5rem; border-bottom: 2px solid #667eea;">
                        Thông Tin Cơ Bản
                    </h3>
                    
                    <div class="form-group">
                        <label class="form-label">Mã ngành *</label>
                        <input type="text" class="form-control" name="code" required 
                               placeholder="VD: CNTT, QTKD, KTDT">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Tên ngành *</label>
                        <input type="text" class="form-control" name="name" required 
                               placeholder="VD: Công Nghệ Thông Tin">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Chỉ tiêu *</label>
                        <input type="number" class="form-control" name="quota" required 
                               min="1" max="1000" placeholder="Số lượng sinh viên tối đa">
                    </div>
                </div>

                <!-- Thông tin bổ sung -->
                <div>
                    <h3 style="color: #4CAF50; margin-bottom: 1rem; padding-bottom: 0.5rem; border-bottom: 2px solid #4CAF50;">
                        Thông Tin Bổ Sung
                    </h3>

                    <div class="form-group">
                        <label class="form-label">Mô tả ngành học</label>
                        <textarea class="form-control" name="description" rows="8" 
                                  placeholder="Mô tả chi tiết về ngành học, chương trình đào tạo, cơ hội việc làm..."></textarea>
                    </div>
                </div>
            </div>

            <div style="margin-top: 2rem; text-align: center;">
                <button type="submit" class="btn btn-primary" style="padding: 1rem 3rem; font-size: 1.1rem;">Thêm Ngành</button>
                <a href="index.php" class="btn" style="padding: 1rem 3rem; font-size: 1.1rem; margin-left: 1rem;">Hủy</a>
            </div>
        </form>
    </div>
</div>

<?php
include_once __DIR__ . '/../layouts/footer.php';
?>