
<?php

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Generate CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Database connection
require_once __DIR__ . '/db.php';


// ==============================
// HTML ESCAPING
// ==============================

function e($value) {
    return htmlspecialchars(
        (string)$value,
        ENT_QUOTES,
        'UTF-8'
    );
}


// ==============================
// PROJECT URL
// ==============================

function url($path = '') {

    $base = '/Vaccination Registration System';

    return $base . '/' . ltrim($path, '/');
}


// ==============================
// REDIRECT
// ==============================

function redirect($path) {
    header('Location: ' . url($path));
    exit;
}


// ==============================
// CURRENT LOGGED-IN USER
// ==============================

function user() {

    return $_SESSION['user'] ?? null;

}


// ==============================
// CHECK LOGIN
// ==============================

function is_logged_in() {

    return user() !== null;

}


// ==============================
// ROLE-BASED DASHBOARD
// ==============================

function home_for($role) {

    $dashboards = [
        'admin'    => 'admin/dashboard.php',
        'parent'   => 'parent/dashboard.php',
        'hospital' => 'hospital/dashboard.php'
    ];

    return $dashboards[$role] ?? 'index.php';

}


// ==============================
// REQUIRE LOGIN
// ==============================

function require_login() {

    if (!is_logged_in()) {
        redirect('login.php');
    }

}


// ==============================
// REQUIRE SPECIFIC ROLE
// ==============================

function require_role($role) {

    require_login();

    if (user()['role'] !== $role) {
        redirect('index.php');
    }

}


// ==============================
// FLASH MESSAGES
// ==============================

function set_flash($message, $type = 'success') {

    $_SESSION['flash'] = [
        'message' => $message,
        'type' => $type
    ];

}


function get_flash() {

    if (!isset($_SESSION['flash'])) {
        return null;
    }

    $flash = $_SESSION['flash'];

    unset($_SESSION['flash']);

    return $flash;

}


// ==============================
// DATABASE VALUE HELPER
// ==============================

function val($sql, $params = []) {

    global $pdo;

    $stmt = $pdo->prepare($sql);

    $stmt->execute($params);

    return $stmt->fetchColumn();

}

/**
 * Verify CSRF token submitted by a form.
 */
function verify_csrf_token() {

    $token = $_POST['csrf_token'] ?? '';

    return is_string($token)
        && isset($_SESSION['csrf_token'])
        && hash_equals($_SESSION['csrf_token'], $token);

}

?>
