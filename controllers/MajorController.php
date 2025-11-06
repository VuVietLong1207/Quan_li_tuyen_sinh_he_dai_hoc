<?php
// Khởi động session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Sử dụng đường dẫn tuyệt đối
$root_dir = dirname(dirname(__FILE__));
include_once $root_dir . '/config/database.php';
include_once $root_dir . '/models/Major.php';
include_once $root_dir . '/includes/functions.php';

class MajorController {
    private $db;
    private $major;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->major = new Major($this->db);
    }

    // Tạo ngành mới
    public function create() {
        if ($_POST) {
            try {
                $this->major->name = $_POST['name'];
                $this->major->code = $_POST['code'];
                $this->major->quota = $_POST['quota'];
                $this->major->description = $_POST['description'] ?? '';

                if ($this->major->create()) {
                    flashMessage('Thêm ngành học thành công!', 'success');
                    redirect('views/majors/index.php');
                } else {
                    flashMessage('Có lỗi xảy ra khi thêm ngành học!', 'error');
                }
            } catch (Exception $e) {
                flashMessage('Lỗi: ' . $e->getMessage(), 'error');
            }
        }
    }

    // Cập nhật ngành
    public function update() {
        if ($_POST) {
            try {
                $this->major->id = $_POST['id'];
                $this->major->name = $_POST['name'];
                $this->major->code = $_POST['code'];
                $this->major->quota = $_POST['quota'];
                $this->major->description = $_POST['description'] ?? '';

                if ($this->major->update()) {
                    flashMessage('Cập nhật ngành học thành công!', 'success');
                    redirect('views/majors/index.php');
                } else {
                    flashMessage('Có lỗi xảy ra khi cập nhật ngành học!', 'error');
                }
            } catch (Exception $e) {
                flashMessage('Lỗi: ' . $e->getMessage(), 'error');
            }
        }
    }

    // Xóa ngành
    public function delete() {
        if (isset($_GET['id'])) {
            try {
                $this->major->id = $_GET['id'];
                
                // Kiểm tra xem ngành có thí sinh không
                $candidateCount = $this->major->countCandidates();
                if ($candidateCount > 0) {
                    flashMessage('Không thể xóa ngành học vì đã có thí sinh đăng ký!', 'error');
                } else {
                    if ($this->major->delete()) {
                        flashMessage('Xóa ngành học thành công!', 'success');
                    } else {
                        flashMessage('Có lỗi xảy ra khi xóa ngành học!', 'error');
                    }
                }
            } catch (Exception $e) {
                flashMessage('Lỗi: ' . $e->getMessage(), 'error');
            }
        }
        redirect('views/majors/index.php');
    }
}

// Xử lý các action
if (isset($_GET['action'])) {
    $controller = new MajorController();
    
    switch ($_GET['action']) {
        case 'create':
            $controller->create();
            break;
        case 'update':
            $controller->update();
            break;
        case 'delete':
            $controller->delete();
            break;
    }
}
?>