<?php
require_once __DIR__ . '/../functions/db_connection.php';

try {
    $majors = [
        ['major_code' => 'CNTT', 'major_name' => 'Công nghệ thông tin', 'description' => 'Đào tạo chuyên sâu về lập trình, hệ thống và mạng.', 'quota' => 200, 'duration' => 4],
        ['major_code' => 'KTKT', 'major_name' => 'Kinh tế', 'description' => 'Đào tạo về kinh tế học, quản trị và phân tích.', 'quota' => 150, 'duration' => 4],
        ['major_code' => 'QTKD', 'major_name' => 'Quản trị kinh doanh', 'description' => 'Quản trị doanh nghiệp, marketing, tài chính.', 'quota' => 180, 'duration' => 4],
        ['major_code' => 'SP', 'major_name' => 'Sư phạm', 'description' => 'Đào tạo giáo viên cho bậc phổ thông.', 'quota' => 100, 'duration' => 4]
    ];

    $stmt = $pdo->prepare("INSERT INTO majors (major_code, major_name, description, quota, duration) VALUES (?, ?, ?, ?, ?)");

    $count = 0;
    foreach ($majors as $m) {
        try {
            $stmt->execute([$m['major_code'], $m['major_name'], $m['description'], $m['quota'], $m['duration']]);
            $count++;
        } catch (PDOException $e) {
            // ignore duplicates or errors for individual rows
        }
    }

    echo "Đã chèn $count ngành mẫu (nếu chưa tồn tại).";
} catch (PDOException $e) {
    echo "Lỗi: " . $e->getMessage();
}

?>
