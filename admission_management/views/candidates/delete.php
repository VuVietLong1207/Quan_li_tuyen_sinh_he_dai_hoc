<?php
session_start();
include_once __DIR__ . '/../../controllers/CandidateController.php';

// Controller sẽ xử lý xóa và chuyển hướng
header('Location: ../../controllers/CandidateController.php?action=delete&id=' . $_GET['id']);
exit();
?>