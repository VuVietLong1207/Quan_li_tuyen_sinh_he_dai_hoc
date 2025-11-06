<?php
class Candidate {
    private $conn;
    private $table_name = "candidates";

    public $id;
    public $candidate_code;
    public $full_name;
    public $birth_date;
    public $gender;
    public $id_number;
    public $phone;
    public $email;
    public $address;
    public $major_id;
    public $subject_group;
    public $gpa;
    public $photo;
    public $document;
    public $status;
    public $notes;
    public $created_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Tạo thí sinh mới
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                 SET candidate_code=:candidate_code, full_name=:full_name, birth_date=:birth_date, 
                     gender=:gender, id_number=:id_number, phone=:phone, email=:email, 
                     address=:address, major_id=:major_id, subject_group=:subject_group, 
                     gpa=:gpa, photo=:photo, document=:document, notes=:notes, status='pending'";

        $stmt = $this->conn->prepare($query);

        // Làm sạch dữ liệu
        $this->candidate_code = htmlspecialchars(strip_tags($this->candidate_code));
        $this->full_name = htmlspecialchars(strip_tags($this->full_name));
        $this->id_number = htmlspecialchars(strip_tags($this->id_number));
        $this->phone = htmlspecialchars(strip_tags($this->phone));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->address = htmlspecialchars(strip_tags($this->address));
        $this->notes = htmlspecialchars(strip_tags($this->notes));

        // Bind parameters
        $stmt->bindParam(":candidate_code", $this->candidate_code);
        $stmt->bindParam(":full_name", $this->full_name);
        $stmt->bindParam(":birth_date", $this->birth_date);
        $stmt->bindParam(":gender", $this->gender);
        $stmt->bindParam(":id_number", $this->id_number);
        $stmt->bindParam(":phone", $this->phone);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":address", $this->address);
        $stmt->bindParam(":major_id", $this->major_id);
        $stmt->bindParam(":subject_group", $this->subject_group);
        $stmt->bindParam(":gpa", $this->gpa);
        $stmt->bindParam(":photo", $this->photo);
        $stmt->bindParam(":document", $this->document);
        $stmt->bindParam(":notes", $this->notes);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Đọc tất cả thí sinh
    public function readAll() {
        $query = "SELECT c.*, m.name as major_name 
                 FROM " . $this->table_name . " c 
                 LEFT JOIN majors m ON c.major_id = m.id 
                 ORDER BY c.created_at DESC";

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) {
            // Friendly feedback for missing table or other DB errors
            if (function_exists('flashMessage')) {
                flashMessage('Database error: ' . $e->getMessage() . ' — If the "candidates" table is missing, run database/create_tables.sql', 'error');
            }
            return false;
        }
    }

    // Đọc thí sinh theo ID
    public function readOne() {
        $query = "SELECT c.*, m.name as major_name 
                 FROM " . $this->table_name . " c 
                 LEFT JOIN majors m ON c.major_id = m.id 
                 WHERE c.id = ? 
                 LIMIT 0,1";

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $this->id);
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            if (function_exists('flashMessage')) {
                flashMessage('Database error: ' . $e->getMessage(), 'error');
            }
            return false;
        }

        if ($row) {
            $this->candidate_code = $row['candidate_code'];
            $this->full_name = $row['full_name'];
            $this->birth_date = $row['birth_date'];
            $this->gender = $row['gender'];
            $this->id_number = $row['id_number'];
            $this->phone = $row['phone'];
            $this->email = $row['email'];
            $this->address = $row['address'];
            $this->major_id = $row['major_id'];
            $this->subject_group = $row['subject_group'];
            $this->gpa = $row['gpa'];
            $this->photo = $row['photo'];
            $this->document = $row['document'];
            $this->status = $row['status'];
            $this->notes = $row['notes'];
            $this->created_at = $row['created_at'];
            return true;
        }
        return false;
    }

    // Cập nhật thí sinh
    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                 SET full_name=:full_name, birth_date=:birth_date, gender=:gender, 
                     id_number=:id_number, phone=:phone, email=:email, address=:address, 
                     major_id=:major_id, subject_group=:subject_group, gpa=:gpa, 
                     photo=:photo, document=:document, status=:status, notes=:notes 
                 WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        // Làm sạch dữ liệu
        $this->full_name = htmlspecialchars(strip_tags($this->full_name));
        $this->id_number = htmlspecialchars(strip_tags($this->id_number));
        $this->phone = htmlspecialchars(strip_tags($this->phone));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->address = htmlspecialchars(strip_tags($this->address));
        $this->notes = htmlspecialchars(strip_tags($this->notes));

        // Bind parameters
        $stmt->bindParam(":full_name", $this->full_name);
        $stmt->bindParam(":birth_date", $this->birth_date);
        $stmt->bindParam(":gender", $this->gender);
        $stmt->bindParam(":id_number", $this->id_number);
        $stmt->bindParam(":phone", $this->phone);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":address", $this->address);
        $stmt->bindParam(":major_id", $this->major_id);
        $stmt->bindParam(":subject_group", $this->subject_group);
        $stmt->bindParam(":gpa", $this->gpa);
        $stmt->bindParam(":photo", $this->photo);
        $stmt->bindParam(":document", $this->document);
        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":notes", $this->notes);
        $stmt->bindParam(":id", $this->id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Xóa thí sinh
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Tìm kiếm thí sinh
    public function search($keywords) {
        $query = "SELECT c.*, m.name as major_name 
                 FROM " . $this->table_name . " c 
                 LEFT JOIN majors m ON c.major_id = m.id 
                 WHERE c.full_name LIKE ? OR c.candidate_code LIKE ? OR c.email LIKE ? 
                 ORDER BY c.created_at DESC";
        try {
            $stmt = $this->conn->prepare($query);

            $keywords = htmlspecialchars(strip_tags($keywords));
            $keywords = "%{$keywords}%";

            $stmt->bindParam(1, $keywords);
            $stmt->bindParam(2, $keywords);
            $stmt->bindParam(3, $keywords);

            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) {
            if (function_exists('flashMessage')) {
                flashMessage('Database error: ' . $e->getMessage(), 'error');
            }
            return false;
        }
    }
}
?>