<?php
require_once '../functions/db_connection.php';
require_once '../functions/utilities.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    switch($action) {
        case 'add_news':
            $title = sanitizeInput($_POST['title']);
            $content = sanitizeInput($_POST['content']);
            $category = $_POST['category'];
            $author_id = $_POST['author_id'];
            $is_published = isset($_POST['is_published']) ? 1 : 0;
            
            try {
                $stmt = $pdo->prepare("INSERT INTO news (title, content, category, author_id, is_published) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$title, $content, $category, $author_id, $is_published]);
                echo json_encode(['success' => true, 'message' => 'Thêm tin tức thành công!']);
            } catch (PDOException $e) {
                echo json_encode(['success' => false, 'message' => 'Lỗi thêm tin: ' . $e->getMessage()]);
            }
            break;
            
        case 'delete_news':
            $news_id = $_POST['news_id'];
            
            try {
                $stmt = $pdo->prepare("DELETE FROM news WHERE id = ?");
                $stmt->execute([$news_id]);
                echo json_encode(['success' => true, 'message' => 'Xóa tin tức thành công!']);
            } catch (PDOException $e) {
                echo json_encode(['success' => false, 'message' => 'Lỗi xóa tin: ' . $e->getMessage()]);
            }
            break;
            
        default:
            echo json_encode(['success' => false, 'message' => 'Hành động không hợp lệ!']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Phương thức không hợp lệ!']);
}
?>