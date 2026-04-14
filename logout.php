<?php
// Memulai session agar sistem tahu session mana yang akan dihapus
session_start();

// Menghapus semua variabel session
$_SESSION = array();

// Jika ingin benar-benar menghapus cookie session di browser (opsional tapi disarankan)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Menghancurkan session secara total
session_destroy();

// Mengarahkan kembali ke halaman login (index.php)
header("Location: index.php");
exit;
?>