<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/validasi.php';

$data = ['kode_barang' => '', 'nama_barang' => '', 'kategori' => '', 'jumlah' => '', 'harga' => ''];
$errors = [];

// CREATE
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_valid()) {
        $errors['_form'] = t('err_csrf');
        $data = array_merge($data, array_intersect_key($_POST, $data));
    } else {
        [$data, $errors] = validasi_barang($_POST, $conn);

        if (empty($errors)) {
            try {
                $stmt = $conn->prepare('INSERT INTO barang (kode_barang, nama_barang, kategori, jumlah, harga) VALUES (?, ?, ?, ?, ?)');
                $stmt->bind_param('sssii', $data['kode_barang'], $data['nama_barang'], $data['kategori'], $data['jumlah'], $data['harga']);
                $stmt->execute();
                $stmt->close();
                flash_set('success', 'ok_added');
                redirect('index.php');
            } catch (mysqli_sql_exception $ex) {
                $errors['_form'] = ($ex->getCode() === 1062) ? t('err_code_dup') : t('err_db');
            }
        }
    }
}

$judul_halaman = t('add_title');
include __DIR__ . '/includes/header.php';
?>
<a class="back" href="index.php"><?= icon('arrow-left') ?><span><?= e(t('back_list')) ?></span></a>
<section class="card card-form">
    <h1><?= e(t('add_title')) ?></h1>
    <p class="muted"><?= e(t('add_sub')) ?></p>
    <?php if (isset($errors['_form'])): ?>
        <div class="alert alert-error" role="alert"><?= icon('alert') ?><span><?= e($errors['_form']) ?></span></div>
    <?php endif; ?>
    <?php
    $aksi = 'tambah.php';
    $label_tombol = t('btn_save');
    include __DIR__ . '/includes/form_barang.php';
    ?>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>
