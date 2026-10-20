<?php
require_once __DIR__ . '/includes/init.php';

$lang = $_GET['lang'] ?? '';
// Whitelist: hanya 'id' atau 'en'
if (in_array($lang, BAHASA_TERSEDIA, true)) {
    $_SESSION['lang'] = $lang;
}

// Kembali ke halaman sebelumnya (hanya file .php lokal, mencegah open redirect)
$back = $_GET['back'] ?? 'index.php';
if (!preg_match('/^[a-z_]+\.php(\?[A-Za-z0-9=&_%.-]*)?$/', $back)) {
    $back = 'index.php';
}
redirect($back);
