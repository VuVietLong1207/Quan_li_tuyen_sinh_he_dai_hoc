<?php
session_start();

// Sử dụng đường dẫn tuyệt đối
$root_dir = dirname(dirname(dirname(__FILE__)));
include_once $root_dir . '/config/database.php';
include_once $root_dir . '/models/Candidate.php';
include_once $root_dir . '/models/Major.php';
include_once $root_dir . '/includes/functions.php';

$database = new Database();
$db = $database->getConnection();

$candidate = new Candidate($db);
$major = new Major($db);

// Thống kê tổng quan
$totalCandidates = $candidate->readAll()->rowCount();

// Tạo các phương thức tìm kiếm theo status nếu chưa có
$pendingCandidates = 0;
$approvedCandidates = 0;
$rejectedCandidates = 0;

// Đếm theo status
$query = "SELECT status, COUNT(*) as count FROM candidates GROUP BY status";
$stmt = $db->prepare($query);
$stmt->execute();

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    switch ($row['status']) {
        case 'pending':
            $pendingCandidates = $row['count'];
            break;
        case 'approved':
            $approvedCandidates = $row['count'];
            break;
        case 'rejected':
            $rejectedCandidates = $row['count'];
            break;
    }
}

// Thống kê theo ngành
$majors = $major->readAll();

include_once dirname(__FILE__) . '/../layouts/header.php';
?>

<div class="main-content">
    <h1 style="color: #333; margin-bottom: 2rem;">Báo Cáo & Thống Kê</h1>

    <div class="stats-grid">
        <div class="stat-card">
            <span class="stat-number"><?php echo $totalCandidates; ?></span>
            <span class="stat-label">Tổng Số Thí Sinh</span>
        </div>
        <div class="stat-card">
            <span class="stat-number"><?php echo $pendingCandidates; ?></span>
            <span class="stat-label">Chờ Duyệt</span>
        </div>
        <div class="stat-card">
            <span class="stat-number"><?php echo $approvedCandidates; ?></span>
            <span class="stat-label">Đã Duyệt</span>
        </div>
        <div class="stat-card">
            <span class="stat-number"><?php echo $rejectedCandidates; ?></span>
            <span class="stat-label">Từ Chối</span>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2rem;">
        <!-- Thống kê theo ngành -->
        <div class="card">
            <h3 style="color: #667eea; margin-bottom: 1rem;">Thống Kê Theo Ngành</h3>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Ngành Học</th>
                            <th>Số Thí Sinh</th>
                            <th>Chỉ Tiêu</th>
                            <th>Tỷ Lệ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $majors = $major->readAll(); // Reset pointer
                        while ($row = $majors->fetch(PDO::FETCH_ASSOC)) {
                            $majorObj = new Major($db);
                            $majorObj->id = $row['id'];
                            $candidateCount = $majorObj->countCandidates();
                            $percentage = $row['quota'] > 0 ? round(($candidateCount / $row['quota']) * 100, 1) : 0;
                            ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['name']); ?></td>
                                <td><?php echo $candidateCount; ?></td>
                                <td><?php echo $row['quota']; ?></td>
                                <td>
                                    <span class="<?php echo $percentage > 100 ? 'status-rejected' : ($percentage > 80 ? 'status-pending' : 'status-approved'); ?>">
                                        <?php echo $percentage; ?>%
                                    </span>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Thống kê theo trạng thái -->
        <div class="card">
            <h3 style="color: #4CAF50; margin-bottom: 1rem;">Phân Bổ Trạng Thái</h3>
            <div style="height: 300px; display: flex; align-items: center; justify-content: center;">
                <div style="text-align: center;">
                    <div style="display: flex; justify-content: center; gap: 2rem; margin-bottom: 2rem;">
                        <div style="text-align: center;">
                            <div style="width: 100px; height: 100px; border-radius: 50%; background: #4CAF50; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; margin: 0 auto 1rem;">
                                <?php echo $approvedCandidates; ?>
                            </div>
                            <div>Đã Duyệt</div>
                        </div>
                        <div style="text-align: center;">
                            <div style="width: 100px; height: 100px; border-radius: 50%; background: #ff9800; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; margin: 0 auto 1rem;">
                                <?php echo $pendingCandidates; ?>
                            </div>
                            <div>Chờ Duyệt</div>
                        </div>
                        <div style="text-align: center;">
                            <div style="width: 100px; height: 100px; border-radius: 50%; background: #f44336; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; margin: 0 auto 1rem;">
                                <?php echo $rejectedCandidates; ?>
                            </div>
                            <div>Từ Chối</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Xuất báo cáo -->
    <div class="card">
        <h3 style="color: #9c27b0; margin-bottom: 1rem;">Xuất Báo Cáo</h3>
        <div style="display: flex; gap: 1rem;">
            <button class="btn btn-danger" onclick="alert('Tính năng đang phát triển')">
                📄 Xuất PDF
            </button>
            <button class="btn btn-success" onclick="alert('Tính năng đang phát triển')">
                📊 Xuất Excel
            </button>
            <a href="../candidates/index.php" class="btn btn-primary">
                👥 Danh Sách Thí Sinh
            </a>
        </div>
    </div>
</div>

<?php
include_once dirname(__FILE__) . '/../layouts/footer.php';
?>