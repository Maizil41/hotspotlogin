<?php
// Pengaturan session sama seperti di login
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 0);
ini_set('session.use_only_cookies', 1);

session_set_cookie_params([
    'lifetime' => 86400,
    'path' => '/',
    'domain' => '',
    'secure' => false,
    'httponly' => true,
    'samesite' => 'Strict',
]);

session_name('hotspot_session');
session_start();

// Hapus semua data session
$_SESSION = array();

// Jika ingin menghancurkan session cookie juga
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params['path'], $params['domain'],
        $params['secure'], $params['httponly']
    );
}

// Akhiri session
session_destroy();

// Redirect ke halaman login atau halaman lain setelah logout
header('Location: login.php');
exit;
?>
