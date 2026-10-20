<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/validasi.php';

// ID unik dari URL (GET) atau form (POST)
$id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);
if ($id <= 0) {
    flash_set('error', 'err_not_found');
    redirect('index.php');
}

// Ambil data lama
$stmt = $conn->prepare('SELECT * FROM barang WHERE id = ? LIMIT 1');
$stmt->bind_param('i', $id);
$stmt->execute();
$barang = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$barang) {
    flash_set('error', 'err_not_found');
    redirect('index.php');
}

$data = [
    'kode_barang' => $barang['kode_barang'],
    'nama_barang' => $barang['nama_barang'],
    'kategori'    => $barang['kategori'],
    'jumlah'      => $barang['jumlah'],
    'harga'       => $barang['harga'],
];
$errors = [];

// UPDATE
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_valid()) {
        $errors['_form'] = t('err_csrf');
        $data = array_merge($data, array_intersect_key($_POST, $data));
    } else {
        [$data, $errors] = validasi_barang($_POST, $conn, $id);

        if (empty($errors)) {
            try {
                $stmt = $conn->prepare('UPDATE barang SET kode_barang = ?, nama_barang = ?, kategori = ?, jumlah = ?, harga = ? WHERE id = ?');
                $stmt->bind_param('sssiii', $data['kode_barang'], $data['nama_barang'], $data['kategori'], $data['jumlah'], $data['harga'], $id);
                $stmt->execute();
                $stmt->close();
                flash_set('success', 'ok_updated');
                redirect('index.php');
            } catch (mysqli_sql_exception $ex) {
                $errors['_form'] = ($ex->getCode() === 1062) ? t('err_code_dup') : t('err_db');
            }
        }
    }
}

$judul_halaman = t('edit_title');
include __DIR__ . '/includes/header.php';
?>
<a class="back" href="index.php"><?= icon('arrow-left') ?><span><?= e(t('back_list')) ?></span></a>
<section class="card card-form">
    <h1><?= e(t('edit_title')) ?></h1>
    <p class="muted"><?= e(t('edit_sub')) ?></p>
    <?php if (isset($errors['_form'])): ?>
        <div class="alert alert-error" role="alert"><?= icon('alert') ?><span><?= e($errors['_form']) ?></span></div>
    <?php endif; ?>
    <?php
    $aksi = 'edit.php?id=' . $id;
    $label_tombol = t('btn_update');
    include __DIR__ . '/includes/form_barang.php';
    ?>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>
