    <?php
    class Major {
        private $conn;
        private $table_name = "majors";

        public $id;
        public $name;
        public $code;
        public $quota;
        public $description;
        public $created_at;

        public function __construct($db) {
            $this->conn = $db;
        }

        // Tạo ngành mới
        public function create() {
            $query = "INSERT INTO " . $this->table_name . " 
                    SET name=:name, code=:code, quota=:quota, description=:description";

            $stmt = $this->conn->prepare($query);

            // Làm sạch dữ liệu
            $this->name = htmlspecialchars(strip_tags($this->name));
            $this->code = htmlspecialchars(strip_tags($this->code));
            $this->description = htmlspecialchars(strip_tags($this->description));

            // Bind parameters
            $stmt->bindParam(":name", $this->name);
            $stmt->bindParam(":code", $this->code);
            $stmt->bindParam(":quota", $this->quota);
            $stmt->bindParam(":description", $this->description);

            if ($stmt->execute()) {
                return true;
            }
            return false;
        }

        // Đọc tất cả ngành
        public function readAll() {
            $query = "SELECT * FROM " . $this->table_name . " ORDER BY name";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt;
        }

        // Đọc ngành theo ID
        public function readOne() {
            $query = "SELECT * FROM " . $this->table_name . " WHERE id = ? LIMIT 0,1";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $this->id);
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                $this->name = $row['name'];
                $this->code = $row['code'];
                $this->quota = $row['quota'];
                $this->description = $row['description'];
                $this->created_at = $row['created_at'];
                return true;
            }
            return false;
        }

        // Cập nhật ngành
        public function update() {
            $query = "UPDATE " . $this->table_name . " 
                    SET name=:name, code=:code, quota=:quota, description=:description 
                    WHERE id = :id";

            $stmt = $this->conn->prepare($query);

            // Làm sạch dữ liệu
            $this->name = htmlspecialchars(strip_tags($this->name));
            $this->code = htmlspecialchars(strip_tags($this->code));
            $this->description = htmlspecialchars(strip_tags($this->description));

            // Bind parameters
            $stmt->bindParam(":name", $this->name);
            $stmt->bindParam(":code", $this->code);
            $stmt->bindParam(":quota", $this->quota);
            $stmt->bindParam(":description", $this->description);
            $stmt->bindParam(":id", $this->id);

            if ($stmt->execute()) {
                return true;
            }
            return false;
        }

        // Xóa ngành
        public function delete() {
            $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $this->id);

            if ($stmt->execute()) {
                return true;
            }
            return false;
        }

        // Đếm số thí sinh theo ngành
        public function countCandidates() {
            $query = "SELECT COUNT(*) as total FROM candidates WHERE major_id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $this->id);
            $stmt->execute();
            
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row['total'];
        }
    }
    ?>