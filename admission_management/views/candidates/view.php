<?php
session_start();
include_once __DIR__ . '/../../config/database.php';
include_once __DIR__ . '/../../models/Candidate.php';
include_once __DIR__ . '/../../includes/functions.php';

if (!isset($_GET['id'])) {
    flashMessage('Không tìm thấy thí sinh!', 'error');
    redirect('index.php');
}

$database = new Database();
$db = $database->getConnection();
$candidate = new Candidate($db);

$candidate->id = $_GET['id'];
if (!$candidate->readOne()) {
    flashMessage('Không tìm thấy thí sinh!', 'error');
    redirect('index.php');
}

include_once __DIR__ . '/../layouts/header.php';

// Chuyển đổi giá trị để hiển thị
$genderText = '';
switch ($candidate->gender) {
    case 'male': $genderText = 'Nam'; break;
    case 'female': $genderText = 'Nữ'; break;
    case 'other': $genderText = 'Khác'; break;
}

$statusColor = '';
$statusText = '';
switch ($candidate->status) {
    case 'approved':
        $statusColor = '#4CAF50';
        $statusText = 'Đã duyệt';
        break;
    case 'pending':
        $statusColor = '#ff9800';
        $statusText = 'Chờ duyệt';
        break;
    case 'rejected':
        $statusColor = '#f44336';
        $statusText = 'Từ chối';
        break;
}
?>

<div class="main-content">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h1 style="color: #333;">Chi Tiết Thí Sinh</h1>
        <div>
            <a href="edit.php?id=<?php echo $candidate->id; ?>" class="btn btn-warning">Sửa Thông Tin</a>
            <a href="index.php" class="btn">Quay Lại</a>
        </div>
    </div>

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

    <div class="card">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
            <!-- Thông tin cá nhân -->
            <div>
                <h3 style="color: #667eea; margin-bottom: 1rem; padding-bottom: 0.5rem; border-bottom: 2px solid #667eea;">
                    Thông Tin Cá Nhân
                </h3>
                
                <div style="display: grid; gap: 1rem;">
                    <div>
                        <strong>Mã thí sinh:</strong>
                        <div style="padding: 0.5rem; background: #f8f9fa; border-radius: 5px; margin-top: 0.5rem;">
                            <?php echo htmlspecialchars($candidate->candidate_code); ?>
                        </div>
                    </div>
                    
                    <div>
                        <strong>Họ và tên:</strong>
                        <div style="padding: 0.5rem; background: #f8f9fa; border-radius: 5px; margin-top: 0.5rem;">
                            <?php echo htmlspecialchars($candidate->full_name); ?>
                        </div>
                    </div>
                    
                    <div>
                        <strong>Ngày sinh:</strong>
                        <div style="padding: 0.5rem; background: #f8f9fa; border-radius: 5px; margin-top: 0.5rem;">
                            <?php echo date('d/m/Y', strtotime($candidate->birth_date)); ?>
                        </div>
                    </div>
                    
                    <div>
                        <strong>Giới tính:</strong>
                        <div style="padding: 0.5rem; background: #f8f9fa; border-radius: 5px; margin-top: 0.5rem;">
                            <?php echo $genderText; ?>
                        </div>
                    </div>
                    
                    <div>
                        <strong>Số CMND/CCCD:</strong>
                        <div style="padding: 0.5rem; background: #f8f9fa; border-radius: 5px; margin-top: 0.5rem;">
                            <?php echo htmlspecialchars($candidate->id_number); ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Thông tin liên hệ & tuyển sinh -->
            <div>
                <h3 style="color: #4CAF50; margin-bottom: 1rem; padding-bottom: 0.5rem; border-bottom: 2px solid #4CAF50;">
                    Thông Tin Liên Hệ & Tuyển Sinh
                </h3>
                
                <div style="display: grid; gap: 1rem;">
                    <div>
                        <strong>Số điện thoại:</strong>
                        <div style="padding: 0.5rem; background: #f8f9fa; border-radius: 5px; margin-top: 0.5rem;">
                            <?php echo htmlspecialchars($candidate->phone); ?>
                        </div>
                    </div>
                    
                    <div>
                        <strong>Email:</strong>
                        <div style="padding: 0.5rem; background: #f8f9fa; border-radius: 5px; margin-top: 0.5rem;">
                            <?php echo htmlspecialchars($candidate->email); ?>
                        </div>
                    </div>
                    
                    <div>
                        <strong>Địa chỉ:</strong>
                        <div style="padding: 0.5rem; background: #f8f9fa; border-radius: 5px; margin-top: 0.5rem;">
                            <?php echo htmlspecialchars($candidate->address); ?>
                        </div>
                    </div>
                    
                    <div>
                        <strong>Điểm trung bình:</strong>
                        <div style="padding: 0.5rem; background: #f8f9fa; border-radius: 5px; margin-top: 0.5rem;">
                            <?php echo number_format($candidate->gpa, 2); ?>
                        </div>
                    </div>
                    
                    <div>
                        <strong>Trạng thái:</strong>
                        <div style="padding: 0.5rem; background: <?php echo $statusColor; ?>; color: white; border-radius: 5px; margin-top: 0.5rem; display: inline-block;">
                            <?php echo $statusText; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- File đính kèm -->
        <div style="margin-top: 2rem;">
            <h3 style="color: #ff9800; margin-bottom: 1rem; padding-bottom: 0.5rem; border-bottom: 2px solid #ff9800;">
                File Đính Kèm
            </h3>
            
            <div style="display: flex; gap: 2rem;">
                <?php if ($candidate->photo): ?>
                <div>
                    <strong>Ảnh thẻ:</strong>
                    <div style="margin-top: 0.5rem;">
                        <img src="../../uploads/photos/<?php echo $candidate->photo; ?>" 
                             alt="Ảnh thẻ" style="max-width: 200px; border-radius: 5px;">
                    </div>
                </div>
                <?php endif; ?>
                
                <?php if ($candidate->document): ?>
                <div>
                    <strong>Hồ sơ:</strong>
                    <div style="margin-top: 0.5rem;">
                        <a href="../../uploads/documents/<?php echo $candidate->document; ?>" 
                           target="_blank" class="btn btn-primary">Xem PDF</a>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Ghi chú -->
        <?php if ($candidate->notes): ?>
        <div style="margin-top: 2rem;">
            <h3 style="color: #9c27b0; margin-bottom: 1rem; padding-bottom: 0.5rem; border-bottom: 2px solid #9c27b0;">
                Ghi Chú
            </h3>
            <div style="padding: 1rem; background: #f8f9fa; border-radius: 5px;">
                <?php echo nl2br(htmlspecialchars($candidate->notes)); ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php
include_once __DIR__ . '/../layouts/footer.php';
?>