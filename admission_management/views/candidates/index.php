<?php
session_start();
// Sửa đường dẫn include
include_once '../../config/database.php';
include_once '../../models/Candidate.php';
include_once '../../includes/functions.php';

$database = new Database();
$db = $database->getConnection();
$candidate = new Candidate($db);

// Xử lý tìm kiếm
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $stmt = $candidate->search($_GET['search']);
} else {
    $stmt = $candidate->readAll();
}

include_once '../layouts/header.php';
?>

<div class="main-content">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h1 style="color: #333;">Quản Lý Thí Sinh</h1>
        <a href="create.php" class="btn btn-primary">Thêm Thí Sinh Mới</a>
    </div>

    <?php displayFlashMessage(); ?>

    <div class="card">
        <form method="GET" action="" style="margin-bottom: 1.5rem;">
            <div style="display: flex; gap: 1rem;">
                <input type="text" name="search" class="form-control" placeholder="Tìm kiếm thí sinh theo tên, mã hoặc email..." 
                       value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                <button type="submit" class="btn btn-primary">Tìm kiếm</button>
                <?php if (isset($_GET['search'])): ?>
                    <a href="index.php" class="btn">Xóa tìm kiếm</a>
                <?php endif; ?>
            </div>
        </form>

        <?php if ($stmt === false): ?>
            <div style="padding:1rem; margin-bottom:1.5rem; border-radius:5px; background:#fff3cd; color:#856404;">
                Lỗi cơ sở dữ liệu: không thể đọc danh sách thí sinh. Nếu bảng "candidates" chưa tồn tại, hãy tạo nó bằng file <strong>database/create_tables.sql</strong> trong thư mục dự án (chạy trong phpMyAdmin hoặc MySQL CLI).
            </div>
        <?php else: ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Mã Thí Sinh</th>
                    <th>Họ Tên</th>
                    <th>Ngày Sinh</th>
                    <th>Giới Tính</th>
                    <th>Ngành ĐK</th>
                    <th>Điểm TB</th>
                    <th>Trạng Thái</th>
                    <th>Thao Tác</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($stmt->rowCount() > 0) {
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {yy
                        $statusColor = '';
                        $statusText = '';
                        switch ($row['status']) {
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
                        
                        $genderText = '';
                        switch ($row['gender']) {
                            case 'male':
                                $genderText = 'Nam';
                                break;
                            case 'female':
                                $genderText = 'Nữ';
                                break;
                            case 'other':
                                $genderText = 'Khác';
                                break;
                        }
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['candidate_code']); ?></td>
                            <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                            <td><?php echo date('d/m/Y', strtotime($row['birth_date'])); ?></td>
                            <td><?php echo $genderText; ?></td>
                            <td><?php echo htmlspecialchars($row['major_name']); ?></td>
                            <td><?php echo number_format($row['gpa'], 2); ?></td>
                            <td>
                                <span style="background: <?php echo $statusColor; ?>; color: white; padding: 0.3rem 0.8rem; border-radius: 15px; font-size: 0.8rem;">
                                    <?php echo $statusText; ?>
                                </span>
                            </td>
                            <td>
                                <a href="view.php?id=<?php echo $row['id']; ?>" class="btn btn-primary" style="padding: 0.3rem 0.8rem; font-size: 0.8rem;">Xem</a>
                                <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn btn-warning" style="padding: 0.3rem 0.8rem; font-size: 0.8rem;">Sửa</a>
                                <a href="delete.php?id=<?php echo $row['id']; ?>" class="btn btn-danger" style="padding: 0.3rem 0.8rem; font-size: 0.8rem;" 
                                   onclick="return confirm('Bạn có chắc chắn muốn xóa thí sinh này?')">Xóa</a>
                            </td>
                        </tr>
                        <?php
                    }
                } else {
                    echo "<tr><td colspan='8' style='text-align: center; padding: 2rem;'>Không tìm thấy thí sinh nào</td></tr>";
                }
                ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
</div>

<?php
include_once '../layouts/footer.php';
?>