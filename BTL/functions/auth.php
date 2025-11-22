<?php
if (session_status() === PHP_SESSION_NONE) {
    // set safer cookie params (httponly). secure=true only on HTTPS.
    $secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'domain' => $_SERVER['HTTP_HOST'] ?? '',
        'secure' => $secure,
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
    session_start();
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'admin';
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: /BTL/views/auth/login.php');
        exit();
    }
}

function requireAdmin() {
    requireLogin();
    if (!isAdmin()) {
        header('Location: /BTL/index.php');
        exit();
    }
}

function loginUser($user) {
    // regenerate session id to prevent fixation
    session_regenerate_id(true);

    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['full_name'] = $user['full_name'];
    $_SESSION['user_type'] = $user['user_type'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['last_login'] = date('Y-m-d H:i:s');
    // Set last activity to prevent immediate timeout and update last_login in DB if possible
    $_SESSION['last_activity'] = time();

    // store a simple fingerprint to reduce risk of session hijacking
    $_SESSION['fingerprint'] = hash('sha256', ($_SERVER['HTTP_USER_AGENT'] ?? '') . ($_SERVER['REMOTE_ADDR'] ?? ''));

    if (isset($user['id'])) {
        try {
            if (isset($GLOBALS['pdo'])) {
                $stmt = $GLOBALS['pdo']->prepare("UPDATE users SET last_login = ? WHERE id = ?");
                $stmt->execute([$_SESSION['last_login'], $user['id']]);
            }
        } catch (Exception $e) {
            // ignore DB update errors here
        }
    }
}

function logoutUser() {
    $_SESSION = array();
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params['path'], $params['domain'], $params['secure'], $params['httponly']);
    }
    session_destroy();
}

function redirectBasedOnUserType() {
    if (isLoggedIn()) {
        $location = '/BTL/views/' . $_SESSION['user_type'] . '/dashboard.php';
        header('Location: ' . $location);
        exit();
    }
}

function checkSessionTimeout($timeout = 1800) {
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $timeout)) {
        logoutUser();
        header('Location: /BTL/views/auth/login.php?timeout=1');
        exit();
    }
    $_SESSION['last_activity'] = time();
}

// Kiểm tra timeout session khi có user đăng nhập
if (isLoggedIn()) {
    checkSessionTimeout();
}
?>