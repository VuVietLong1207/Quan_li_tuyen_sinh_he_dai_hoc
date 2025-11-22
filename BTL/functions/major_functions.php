<?php
require_once __DIR__ . '/db_connection.php';
require_once __DIR__ . '/utilities.php';

/**
 * Lấy danh sách ngành
 * @param bool $onlyActive nếu true chỉ lấy những ngành có status = 'active'
 * @return array
 */
function getAllMajors($onlyActive = false) {
    global $pdo;
    try {
        if ($onlyActive) {
            $stmt = $pdo->prepare("SELECT * FROM majors WHERE status = 'active' ORDER BY major_name");
            $stmt->execute();
        } else {
            $stmt = $pdo->prepare("SELECT * FROM majors ORDER BY major_name");
            $stmt->execute();
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log('getAllMajors error: ' . $e->getMessage());
        return [];
    }
}

/**
 * Thêm ngành mới
 * @param array $data các khoá: major_code, major_name, description, quota, duration, status
 * @return bool
 */
function addMajor(array $data) {
    global $pdo;
    $major_code = sanitizeInput($data['major_code'] ?? '');
    $major_name = sanitizeInput($data['major_name'] ?? '');
    $description = sanitizeInput($data['description'] ?? null);
    $quota = isset($data['quota']) ? (int)$data['quota'] : 0;
    $duration = isset($data['duration']) ? (int)$data['duration'] : 4;
    $status = isset($data['status']) ? sanitizeInput($data['status']) : 'active';

    try {
        $stmt = $pdo->prepare("INSERT INTO majors (major_code, major_name, description, quota, duration, status) VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$major_code, $major_name, $description, $quota, $duration, $status]);
    } catch (PDOException $e) {
        error_log('addMajor error: ' . $e->getMessage());
        return false;
    }
}

/**
 * Lấy thông tin ngành theo id
 * @param int $id
 * @return array|null
 */
function getMajorById($id) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT * FROM majors WHERE id = ? LIMIT 1");
        $stmt->execute([(int)$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    } catch (PDOException $e) {
        error_log('getMajorById error: ' . $e->getMessage());
        return null;
    }
}

/**
 * Cập nhật ngành
 * @param int $id
 * @param array $data các khoá: major_code, major_name, description, quota, duration, status
 * @return bool
 */
function updateMajor($id, array $data) {
    global $pdo;
    $fields = [];
    $values = [];

    if (isset($data['major_code'])) { $fields[] = 'major_code = ?'; $values[] = sanitizeInput($data['major_code']); }
    if (isset($data['major_name'])) { $fields[] = 'major_name = ?'; $values[] = sanitizeInput($data['major_name']); }
    if (array_key_exists('description', $data)) { $fields[] = 'description = ?'; $values[] = sanitizeInput($data['description']); }
    if (isset($data['quota'])) { $fields[] = 'quota = ?'; $values[] = (int)$data['quota']; }
    if (isset($data['duration'])) { $fields[] = 'duration = ?'; $values[] = (int)$data['duration']; }
    if (isset($data['status'])) { $fields[] = 'status = ?'; $values[] = sanitizeInput($data['status']); }

    if (empty($fields)) return false;

    $values[] = (int)$id;
    $sql = "UPDATE majors SET " . implode(', ', $fields) . " WHERE id = ?";

    try {
        $stmt = $pdo->prepare($sql);
        return $stmt->execute($values);
    } catch (PDOException $e) {
        error_log('updateMajor error: ' . $e->getMessage());
        return false;
    }
}

/**
 * Xóa ngành
 * @param int $id
 * @return bool
 */
function deleteMajor($id) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("DELETE FROM majors WHERE id = ?");
        return $stmt->execute([(int)$id]);
    } catch (PDOException $e) {
        error_log('deleteMajor error: ' . $e->getMessage());
        return false;
    }
}

?>
