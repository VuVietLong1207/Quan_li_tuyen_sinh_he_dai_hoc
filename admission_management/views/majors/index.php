<?php
session_start();

try {
    // Include files với kiểm tra lỗi
    if (!file_exists(__DIR__ . '/../../config/database.php')) {
        throw new Exception("File database.php không tồn tại");
    }
    
    include_once __DIR__ . '/../../config/database.php';
    include_once __DIR__ . '/../../models/Major.php';
    include_once __DIR__ . '/../../includes/functions.php';

    // Tạo kết nối database
    $database = new Database();
    $db = $database->getConnection();
    
    // KIỂM TRA KẾT NỐI QUAN TRỌNG
    if ($db === null) {
        throw new Exception("Không thể kết nối database. Kết nối trả về NULL");
    }
    
    // Kiểm tra xem database có tồn tại không
    $checkDb = $db->query("SELECT DATABASE() as db_name");
    $dbInfo = $checkDb->fetch(PDO::FETCH_ASSOC);
    
    if (empty($dbInfo['db_name'])) {
        throw new Exception("Database không được chọn. Có thể database 'admission_management' chưa tồn tại.");
    }
    
    $major = new Major($db);
    $stmt = $major->readAll();

} catch (Exception $e) {
    // HIỂN THỊ LỖI CHI TIẾT VÀ HƯỚNG DẪN SỬA
    $error_message = $e->getMessage();
    
    echo "<!DOCTYPE html>
    <html>
    <head>
        <title>Lỗi Hệ Thống</title>
        <style>
            body { font-family: Arial, sans-serif; padding: 20px; background: #f8f9fa; }
            .error-container { max-width: 800px; margin: 50px auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 0 20px rgba(0,0,0,0.1); }
            .error-header { background: #dc3545; color: white; padding: 20px; border-radius: 5px; margin-bottom: 20px; }
            .solution { background: #d4edda; padding: 20px; border-radius: 5px; margin: 20px 0; }
            .btn { display: inline-block; padding: 12px 24px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; margin: 10px 5px; }
            .btn-danger { background: #dc3545; }
            code { background: #f8f9fa; padding: 10px; border-radius: 3px; display: block; margin: 10px 0; }
        </style>
    </head>
    <body>
        <div class='error-container'>
            <div class='error-header'>
                <h1>🚨 LỖI HỆ THỐNG</h1>
                <p><strong>" . htmlspecialchars($error_message) . "</strong></p>
            </div>
            
            <div class='solution'>
                <h3>🔧 CÁCH KHẮC PHỤC:</h3>";
    
    // Kiểm tra loại lỗi và đưa ra giải pháp phù hợp
    if (strpos($error_message, 'database') !== false || strpos($error_message, 'Database') !== false) {
        echo "<p><strong>Vấn đề:</strong> Database chưa được tạo hoặc kết nối thất bại</p>
              <p><strong>Giải pháp:</strong> Chạy file setup database</p>
              <p>
                  <a href='../../../database_setup.php' class='btn'>Chạy Database Setup</a>
                  <a href='../../../index.php' class='btn'>Về Trang Chủ</a>
              </p>";
    } else if (strpos($error_message, 'prepare') !== false) {
        echo "<p><strong>Vấn đề:</strong> Lỗi truy vấn SQL</p>
              <p><strong>Giải pháp:</strong> Kiểm tra cấu trúc database và tables</p>
              <p>
                  <a href='../../../database_setup.php' class='btn'>Chạy Lại Database Setup</a>
              </p>";
    } else {
        echo "<p><strong>Vấn đề:</strong> Lỗi không xác định</p>
              <p><strong>Giải pháp:</strong> Kiểm tra file cấu hình và database</p>
              <p>
                  <a href='../../../database_setup.php' class='btn'>Chạy Database Setup</a>
                  <a href='../../../index.php' class='btn'>Về Trang Chủ</a>
              </p>";
    }
    
    echo "      </div>
            
            <h3>📋 KIỂM TRA:</h3>
            <ol>
                <li>File <code>config/database.php</code> có tồn tại không?</li>
                <li>Thông tin kết nối database có đúng không?</li>
                <li>Database 'admission_management' đã được tạo chưa?</li>
                <li>Table 'majors' đã được tạo chưa?</li>
                <li>XAMPP/WAMP đã chạy MySQL chưa?</li>
            </ol>
            
            <p><a href='javascript:location.reload()' class='btn'>Thử Lại</a></p>
        </div>
    </body>
    </html>";
    exit;
}

include_once __DIR__ . '/../layouts/header.php';
?>

<div class="main-content">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h1 style="color: #333;">Quản Lý Ngành Học</h1>
        <a href="create.php" class="btn btn-primary">Thêm Ngành Mới</a>
    </div>

    <?php 
    // Hiển thị flash message
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
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Mã Ngành</th>
                        <th>Tên Ngành</th>
                        <th>Chỉ Tiêu</th>
                        <th>Số Thí Sinh</th>
                        <th>Mô Tả</th>
                        <th>Thao Tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($stmt->rowCount() > 0) {
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            try {
                                $majorObj = new Major($db);
                                $majorObj->id = $row['id'];
                                $candidateCount = $majorObj->countCandidates();
                                
                                $statusColor = $candidateCount > $row['quota'] ? '#f44336' : '#4CAF50';
                                ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['code']); ?></td>
                                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                                    <td><?php echo number_format($row['quota']); ?></td>
                                    <td>
                                        <span style="background: <?php echo $statusColor; ?>; color: white; padding: 0.3rem 0.8rem; border-radius: 15px; font-size: 0.8rem;">
                                            <?php echo $candidateCount; ?>
                                        </span>
                                    </td>
                                    <td><?php 
                                        if (!empty($row['description'])) {
                                            echo htmlspecialchars(substr($row['description'], 0, 50)) . '...';
                                        } else {
                                            echo '<span style="color: #999;">Chưa có mô tả</span>';
                                        }
                                    ?></td>
                                    <td>
                                        <div style="display: flex; gap: 0.5rem;">
                                            <a href="view.php?id=<?php echo $row['id']; ?>" class="btn btn-primary" style="padding: 0.3rem 0.8rem; font-size: 0.8rem;">Xem</a>
                                            <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn btn-warning" style="padding: 0.3rem 0.8rem; font-size: 0.8rem;">Sửa</a>
                                            <a href="delete.php?id=<?php echo $row['id']; ?>" class="btn btn-danger" style="padding: 0.3rem 0.8rem; font-size: 0.8rem;" 
                                               onclick="return confirm('Bạn có chắc chắn muốn xóa ngành học này?')">Xóa</a>
                                        </div>
                                    </td>
                                </tr>
                                <?php
                            } catch (Exception $e) {
                                echo "<tr>
                                    <td colspan='6' style='color: #f44336; text-align: center;'>
                                        Lỗi khi đếm thí sinh: " . htmlspecialchars($e->getMessage()) . "
                                    </td>
                                </tr>";
                            }
                        }
                    } else {
                        echo "<tr>
                            <td colspan='6' style='text-align: center; padding: 2rem; color: #666;'>
                                📝 Chưa có ngành học nào. 
                                <a href='create.php' style='color: #007bff; text-decoration: none; font-weight: bold;'>Thêm ngành học đầu tiên</a>
                            </td>
                        </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
include_once __DIR__ . '/../layouts/footer.php';
?>