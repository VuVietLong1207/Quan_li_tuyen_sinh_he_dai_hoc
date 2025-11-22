<?php
$servername = "localhost";
$username = "root";
$password = "hoaphun2";
$dbname = "admission_management";
$port = 3306;

try {
    $pdo = new PDO("mysql:host=$servername;port=$port;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    error_log("Database connection failed: " . $e->getMessage());
    die("Lỗi kết nối cơ sở dữ liệu. Vui lòng thử lại sau.");
}
?>