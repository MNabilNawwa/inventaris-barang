<?php
// Koneksi database (MySQLi). Sesuaikan dengan pengaturan XAMPP Anda.
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'inventaris_db';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
    $conn->set_charset('utf8mb4');
} catch (mysqli_sql_exception $ex) {
    http_response_code(500);
    die('Koneksi database gagal / Database connection failed. Periksa config/koneksi.php');
}
