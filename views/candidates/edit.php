<?php
session_start();
include_once __DIR__ . '/../../config/database.php';
include_once __DIR__ . '/../../models/Candidate.php';
include_once __DIR__ . '/../../models/Major.php';
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

// Lấy danh sách ngành học
$major = new Major($db);
$majors = $major->readAll();

include_once __DIR__ . '/../layouts/header.php';
?>

<div class="main-content">
    <div class="card">
        <h1 style="color: #333; margin-bottom: 2rem;">Chỉnh Sửa Thông Tin Thí Sinh</h1>

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

        <form action="../../controllers/CandidateController.php?action=update" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $candidate->id; ?>">
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
                <!-- Thông tin cá nhân -->
                <div>
                    <h3 style="color: #667eea; margin-bottom: 1rem; padding-bottom: 0.5rem; border-bottom: 2px solid #667eea;">
                        Thông Tin Cá Nhân
                    </h3>
                    
                    <div class="form-group">
                        <label class="form-label">Mã thí sinh</label>
                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($candidate->candidate_code); ?>" disabled>
                        <small style="color: #666;">Mã thí sinh không thể thay đổi</small>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Họ và tên *</label>
                        <input type="text" class="form-control" name="full_name" value="<?php echo htmlspecialchars($candidate->full_name); ?>" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Ngày sinh *</label>
                        <input type="date" class="form-control" name="birth_date" value="<?php echo $candidate->birth_date; ?>" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Giới tính *</label>
                        <select class="form-control" name="gender" required>
                            <option value="male" <?php echo $candidate->gender == 'male' ? 'selected' : ''; ?>>Nam</option>
                            <option value="female" <?php echo $candidate->gender == 'female' ? 'selected' : ''; ?>>Nữ</option>
                            <option value="other" <?php echo $candidate->gender == 'other' ? 'selected' : ''; ?>>Khác</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Số CMND/CCCD *</label>
                        <input type="text" class="form-control" name="id_number" value="<?php echo htmlspecialchars($candidate->id_number); ?>" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Địa chỉ</label>
                        <textarea class="form-control" name="address" rows="2"><?php echo htmlspecialchars($candidate->address); ?></textarea>
                    </div>
                </div>

                <!-- Thông tin liên hệ & tuyển sinh -->
                <div>
                    <h3 style="color: #4CAF50; margin-bottom: 1rem; padding-bottom: 0.5rem; border-bottom: 2px solid #4CAF50;">
                        Thông Tin Liên Hệ & Tuyển Sinh
                    </h3>

                    <div class="form-group">
                        <label class="form-label">Số điện thoại *</label>
                        <input type="tel" class="form-control" name="phone" value="<?php echo htmlspecialchars($candidate->phone); ?>" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Email *</label>
                        <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($candidate->email); ?>" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Ngành đăng ký *</label>
                        <select class="form-control" name="major_id" required>
                            <option value="">Chọn ngành học</option>
                            <?php 
                            $majors = $major->readAll(); // Reset pointer
                            while ($row = $majors->fetch(PDO::FETCH_ASSOC)): ?>
                                <option value="<?php echo $row['id']; ?>" 
                                    <?php echo $candidate->major_id == $row['id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($row['name']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Tổ hợp môn xét tuyển *</label>
                        <select class="form-control" name="subject_group" required>
                            <option value="A00" <?php echo $candidate->subject_group == 'A00' ? 'selected' : ''; ?>>A00 (Toán, Lý, Hóa)</option>
                            <option value="A01" <?php echo $candidate->subject_group == 'A01' ? 'selected' : ''; ?>>A01 (Toán, Lý, Anh)</option>
                            <option value="D01" <?php echo $candidate->subject_group == 'D01' ? 'selected' : ''; ?>>D01 (Toán, Văn, Anh)</option>
                            <option value="D07" <?php echo $candidate->subject_group == 'D07' ? 'selected' : ''; ?>>D07 (Toán, Hóa, Anh)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Điểm trung bình lớp 12 *</label>
                        <input type="number" step="0.01" class="form-control" name="gpa" 
                               value="<?php echo $candidate->gpa; ?>" min="0" max="10" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Trạng thái *</label>
                        <select class="form-control" name="status" required>
                            <option value="pending" <?php echo $candidate->status == 'pending' ? 'selected' : ''; ?>>Chờ duyệt</option>
                            <option value="approved" <?php echo $candidate->status == 'approved' ? 'selected' : ''; ?>>Đã duyệt</option>
                            <option value="rejected" <?php echo $candidate->status == 'rejected' ? 'selected' : ''; ?>>Từ chối</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- File đính kèm -->
            <div style="margin-top: 2rem;">
                <h3 style="color: #ff9800; margin-bottom: 1rem; padding-bottom: 0.5rem; border-bottom: 2px solid #ff9800;">
                    File Đính Kèm
                </h3>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
                    <div class="form-group">
                        <label class="form-label">Ảnh thẻ (3x4)</label>
                        <?php if ($candidate->photo): ?>
                            <div style="margin-bottom: 0.5rem;">
                                <img src="../../uploads/photos/<?php echo $candidate->photo; ?>" 
                                     alt="Ảnh hiện tại" style="max-width: 100px; border-radius: 5px;">
                                <br>
                                <small>Ảnh hiện tại</small>
                            </div>
                        <?php endif; ?>
                        <input type="file" class="form-control" name="photo" accept="image/*">
                    </div>

                    <div class="form-group">
                        <label class="form-label">File hồ sơ (PDF)</label>
                        <?php if ($candidate->document): ?>
                            <div style="margin-bottom: 0.5rem;">
                                <a href="../../uploads/documents/<?php echo $candidate->document; ?>" 
                                   target="_blank" class="btn btn-primary">Xem PDF hiện tại</a>
                            </div>
                        <?php endif; ?>
                        <input type="file" class="form-control" name="document" accept=".pdf">
                    </div>
                </div>
            </div>

            <!-- Ghi chú -->
            <div style="margin-top: 2rem;">
                <h3 style="color: #9c27b0; margin-bottom: 1rem; padding-bottom: 0.5rem; border-bottom: 2px solid #9c27b0;">
                    Ghi Chú
                </h3>
                <div class="form-group">
                    <textarea class="form-control" name="notes" rows="4" placeholder="Ghi chú về thí sinh..."><?php echo htmlspecialchars($candidate->notes); ?></textarea>
                </div>
            </div>

            <div style="margin-top: 2rem; text-align: center;">
                <button type="submit" class="btn btn-primary" style="padding: 1rem 3rem; font-size: 1.1rem;">Cập Nhật</button>
                <a href="view.php?id=<?php echo $candidate->id; ?>" class="btn" style="padding: 1rem 3rem; font-size: 1.1rem; margin-left: 1rem;">Hủy</a>
            </div>
        </form>
    </div>
</div>

<?php
include_once __DIR__ . '/../layouts/footer.php';
?>