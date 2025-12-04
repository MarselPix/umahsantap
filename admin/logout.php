<?php
// Memulai session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Menghapus semua variabel session
$_SESSION = array();

// Menghancurkan session
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

session_destroy();

// Redirect ke halaman login
header("Location: login.php");
exit();
?>