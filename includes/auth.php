<?php
// Proteksi halaman: wajib login. Di-include di awal setiap halaman CRUD.
require_once __DIR__ . '/init.php';

if (empty($_SESSION['login']) || $_SESSION['login'] !== true) {
    redirect('login.php');
}
