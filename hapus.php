<?php
require_once __DIR__ . '/includes/auth.php';

// DELETE: hanya lewat POST + token CSRF
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !csrf_valid()) {
    flash_set('error', 'err_csrf');
    redirect('index.php');
}

$id = (int)($_POST['id'] ?? 0);
if ($id <= 0) {
    flash_set('error', 'err_not_found');
    redirect('index.php');
}

try {
    $stmt = $conn->prepare('DELETE FROM barang WHERE id = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $terhapus = $stmt->affected_rows;
    $stmt->close();
    flash_set($terhapus > 0 ? 'success' : 'error', $terhapus > 0 ? 'ok_deleted' : 'err_not_found');
} catch (mysqli_sql_exception $ex) {
    flash_set('error', 'err_db');
}
redirect('index.php');
