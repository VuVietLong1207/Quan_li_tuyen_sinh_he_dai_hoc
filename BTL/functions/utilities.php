<?php
function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

function formatDate($date) {
    return date('d/m/Y', strtotime($date));
}

function formatDateTime($datetime) {
    return date('d/m/Y H:i', strtotime($datetime));
}

function getMajorMethods() {
    return [
        'thptqg' => 'THPT Quốc gia',
        'hocba' => 'Học bạ',
        'khaac' => 'Khác'
    ];
}

function getApplicationStatuses() {
    return [
        'pending' => 'Chờ duyệt',
        'approved' => 'Đã duyệt', 
        'rejected' => 'Từ chối',
        'accepted' => 'Trúng tuyển'
    ];
}

function getNewsCategories() {
    return [
        'tuyensinh' => 'Tuyển sinh',
        'sukien' => 'Sự kiện',
        'thongbao' => 'Thông báo'
    ];
}

// CSRF helpers
function generateCsrfToken() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verifyCsrfToken($token) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (empty($token) || empty($_SESSION['csrf_token'])) return false;
    return hash_equals($_SESSION['csrf_token'], $token);
}

// Simple login throttling using session
function loginAttemptsExceeded($limit = 5, $window = 900) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $now = time();
    if (!isset($_SESSION['login_attempts'])) {
        $_SESSION['login_attempts'] = [];
    }
    // remove old attempts
    $_SESSION['login_attempts'] = array_filter($_SESSION['login_attempts'], function($ts) use ($now, $window) {
        return ($now - $ts) <= $window;
    });
    return count($_SESSION['login_attempts']) >= $limit;
}

function recordLoginAttempt() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $_SESSION['login_attempts'][] = time();
}

function getLoginAttemptsRemaining($limit = 5, $window = 900) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $now = time();
    if (!isset($_SESSION['login_attempts'])) {
        $_SESSION['login_attempts'] = [];
    }
    $_SESSION['login_attempts'] = array_filter($_SESSION['login_attempts'], function($ts) use ($now, $window) {
        return ($now - $ts) <= $window;
    });
    $count = count($_SESSION['login_attempts']);
    $remaining = max(0, $limit - $count);
    return $remaining;
}
?>