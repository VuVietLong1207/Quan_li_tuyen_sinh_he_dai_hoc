<?php
// Sửa đường dẫn include
include_once '../config/database.php';
include_once '../models/Candidate.php';
include_once '../includes/functions.php';

class CandidateController {
    private $db;
    private $candidate;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->candidate = new Candidate($this->db);
    }

    // Tạo thí sinh mới
    public function create() {
        if ($_POST) {
            try {
                // Generate candidate code
                $this->candidate->candidate_code = generateCandidateCode();
                $this->candidate->full_name = $_POST['full_name'];
                $this->candidate->birth_date = $_POST['birth_date'];
                $this->candidate->gender = $_POST['gender'];
                $this->candidate->id_number = $_POST['id_number'];
                $this->candidate->phone = $_POST['phone'];
                $this->candidate->email = $_POST['email'];
                $this->candidate->address = $_POST['address'] ?? '';
                $this->candidate->major_id = $_POST['major_id'];
                $this->candidate->subject_group = $_POST['subject_group'];
                $this->candidate->gpa = $_POST['gpa'];
                $this->candidate->notes = $_POST['notes'] ?? '';

                // Upload photo
                if (!empty($_FILES['photo']['name'])) {
                    $uploadResult = uploadFile($_FILES['photo'], '../uploads/photos/', 
                        ['image/jpeg', 'image/png', 'image/jpg']);
                    if ($uploadResult['success']) {
                        $this->candidate->photo = $uploadResult['file_name'];
                    }
                }

                // Upload document
                if (!empty($_FILES['document']['name'])) {
                    $uploadResult = uploadFile($_FILES['document'], '../uploads/documents/', 
                        ['application/pdf']);
                    if ($uploadResult['success']) {
                        $this->candidate->document = $uploadResult['file_name'];
                    }
                }

                if ($this->candidate->create()) {
                    flashMessage('Thêm thí sinh thành công!', 'success');
                    redirect('../views/candidates/index.php');
                } else {
                    flashMessage('Có lỗi xảy ra khi thêm thí sinh!', 'error');
                }
            } catch (Exception $e) {
                flashMessage('Lỗi: ' . $e->getMessage(), 'error');
            }
        }
    }

    // Cập nhật thí sinh
    public function update() {
        if ($_POST) {
            try {
                $this->candidate->id = $_POST['id'];
                $this->candidate->full_name = $_POST['full_name'];
                $this->candidate->birth_date = $_POST['birth_date'];
                $this->candidate->gender = $_POST['gender'];
                $this->candidate->id_number = $_POST['id_number'];
                $this->candidate->phone = $_POST['phone'];
                $this->candidate->email = $_POST['email'];
                $this->candidate->address = $_POST['address'] ?? '';
                $this->candidate->major_id = $_POST['major_id'];
                $this->candidate->subject_group = $_POST['subject_group'];
                $this->candidate->gpa = $_POST['gpa'];
                $this->candidate->status = $_POST['status'];
                $this->candidate->notes = $_POST['notes'] ?? '';

                // Upload photo mới nếu có
                if (!empty($_FILES['photo']['name'])) {
                    $uploadResult = uploadFile($_FILES['photo'], '../uploads/photos/', 
                        ['image/jpeg', 'image/png', 'image/jpg']);
                    if ($uploadResult['success']) {
                        $this->candidate->photo = $uploadResult['file_name'];
                    }
                }

                // Upload document mới nếu có
                if (!empty($_FILES['document']['name'])) {
                    $uploadResult = uploadFile($_FILES['document'], '../uploads/documents/', 
                        ['application/pdf']);
                    if ($uploadResult['success']) {
                        $this->candidate->document = $uploadResult['file_name'];
                    }
                }

                if ($this->candidate->update()) {
                    flashMessage('Cập nhật thí sinh thành công!', 'success');
                    redirect('../views/candidates/index.php');
                } else {
                    flashMessage('Có lỗi xảy ra khi cập nhật thí sinh!', 'error');
                }
            } catch (Exception $e) {
                flashMessage('Lỗi: ' . $e->getMessage(), 'error');
            }
        }
    }

    // Xóa thí sinh
    public function delete() {
        if (isset($_GET['id'])) {
            try {
                $this->candidate->id = $_GET['id'];
                
                if ($this->candidate->delete()) {
                    flashMessage('Xóa thí sinh thành công!', 'success');
                } else {
                    flashMessage('Có lỗi xảy ra khi xóa thí sinh!', 'error');
                }
            } catch (Exception $e) {
                flashMessage('Lỗi: ' . $e->getMessage(), 'error');
            }
        }
        redirect('../views/candidates/index.php');
    }
}

// Xử lý các action
if (isset($_GET['action'])) {
    $controller = new CandidateController();
    
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