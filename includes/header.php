<?php
// Variabel opsional: $judul_halaman, $tampil_nav (default true), $body_class
$tampil_nav = $tampil_nav ?? true;
$judul_halaman = $judul_halaman ?? t('app_name');
$body_class = $body_class ?? '';
$halaman_ini = basename($_SERVER['SCRIPT_NAME']);
$balik = $halaman_ini . (!empty($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : '');
$flash = flash_get();
$inisial = strtoupper(mb_substr($_SESSION['username'] ?? '', 0, 1));
?>
<!DOCTYPE html>
<html lang="<?= e($_SESSION['lang']) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($judul_halaman) ?> - <?= e(t('app_name')) ?></title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body class="<?= e($body_class) ?>">
<header class="topbar">
    <div class="topbar-in">
        <a class="brand" href="index.php">
            <span class="brand-mark"><?= icon('box') ?></span>
            <span><?= e(t('app_name')) ?></span>
        </a>
        <nav class="topnav">
            <span class="lang-switch" role="group" aria-label="<?= e(t('lang_label')) ?>">
                <a href="ganti_bahasa.php?lang=id&amp;back=<?= urlencode($balik) ?>" class="<?= $_SESSION['lang'] === 'id' ? 'active' : '' ?>">ID</a>
                <a href="ganti_bahasa.php?lang=en&amp;back=<?= urlencode($balik) ?>" class="<?= $_SESSION['lang'] === 'en' ? 'active' : '' ?>">EN</a>
            </span>
            <?php if ($tampil_nav && !empty($_SESSION['login'])): ?>
                <span class="userchip">
                    <span class="avatar"><?= e($inisial) ?></span>
                    <span class="uname"><?= e($_SESSION['username'] ?? '') ?></span>
                </span>
                <a class="btn btn-ghost btn-sm" href="logout.php"><?= icon('logout') ?><span><?= e(t('nav_logout')) ?></span></a>
            <?php endif; ?>
        </nav>
    </div>
</header>
<main class="container">
<?php if ($flash): ?>
    <div class="alert alert-<?= e($flash['type']) ?>" role="status">
        <?= icon($flash['type'] === 'success' ? 'check' : 'alert') ?>
        <span><?= e(t($flash['key'])) ?></span>
    </div>
<?php endif; ?>
