<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/validasi.php';

const STOK_MENIPIS = 5;   // batas stok dianggap "menipis"

// READ: ambil data (dengan pencarian opsional)
$q = trim($_GET['q'] ?? '');
if ($q !== '') {
    $like = '%' . $q . '%';
    $stmt = $conn->prepare('SELECT * FROM barang WHERE kode_barang LIKE ? OR nama_barang LIKE ? OR kategori LIKE ? ORDER BY id DESC');
    $stmt->bind_param('sss', $like, $like, $like);
} else {
    $stmt = $conn->prepare('SELECT * FROM barang ORDER BY id DESC');
}
$stmt->execute();
$rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Ringkasan (selalu dari seluruh data, bukan hasil pencarian)
$ringkas = $conn->query(
    'SELECT COUNT(*) AS jenis, COALESCE(SUM(jumlah),0) AS stok,
            COALESCE(SUM(jumlah*harga),0) AS nilai, COALESCE(SUM(jumlah=0),0) AS habis
     FROM barang'
)->fetch_assoc();

$kat_map = daftar_kategori();

$judul_halaman = t('list_title');
include __DIR__ . '/includes/header.php';
?>
<div class="page-head">
    <h1><?= e(t('list_title')) ?></h1>
    <a href="tambah.php" class="btn btn-primary"><?= icon('plus') ?><span><?= e(t('btn_add')) ?></span></a>
</div>

<section class="stats">
    <div class="stat"><span class="stat-label"><?= e(t('stat_items')) ?></span><span class="stat-value"><?= (int)$ringkas['jenis'] ?></span></div>
    <div class="stat"><span class="stat-label"><?= e(t('stat_stock')) ?></span><span class="stat-value"><?= number_format((int)$ringkas['stok'], 0, ',', '.') ?></span></div>
    <div class="stat"><span class="stat-label"><?= e(t('stat_value')) ?></span><span class="stat-value"><?= e(rupiah($ringkas['nilai'])) ?></span></div>
    <div class="stat"><span class="stat-label"><?= e(t('stat_empty')) ?></span><span class="stat-value <?= (int)$ringkas['habis'] > 0 ? 'text-danger' : '' ?>"><?= (int)$ringkas['habis'] ?></span></div>
</section>

<form method="GET" action="index.php" class="searchbar">
    <div class="search-field">
        <?= icon('search') ?>
        <input type="search" name="q" value="<?= e($q) ?>" placeholder="<?= e(t('search_ph')) ?>" aria-label="<?= e(t('search_ph')) ?>">
    </div>
    <button type="submit" class="btn btn-primary"><?= e(t('btn_search')) ?></button>
    <?php if ($q !== ''): ?><a href="index.php" class="btn btn-ghost"><?= e(t('btn_reset')) ?></a><?php endif; ?>
</form>

<div class="table-wrap">
<table class="table">
    <thead>
        <tr>
            <th><?= e(t('col_no')) ?></th>
            <th><?= e(t('col_code')) ?></th>
            <th><?= e(t('col_name')) ?></th>
            <th><?= e(t('col_category')) ?></th>
            <th class="num"><?= e(t('col_qty')) ?></th>
            <th class="num"><?= e(t('col_price')) ?></th>
            <th class="th-actions"><?= e(t('col_action')) ?></th>
        </tr>
    </thead>
    <tbody>
    <?php if (count($rows) === 0): ?>
        <tr><td colspan="7" class="empty"><?= e($q !== '' ? t('empty_search') : t('empty_list')) ?></td></tr>
    <?php endif; ?>
    <?php foreach ($rows as $i => $r): ?>
        <?php $jml = (int)$r['jumlah']; ?>
        <tr>
            <td class="no" data-label="<?= e(t('col_no')) ?>"><?= $i + 1 ?></td>
            <td class="code" data-label="<?= e(t('col_code')) ?>"><?= e($r['kode_barang']) ?></td>
            <td class="nama" data-label="<?= e(t('col_name')) ?>"><?= e($r['nama_barang']) ?></td>
            <td data-label="<?= e(t('col_category')) ?>"><span class="badge"><?= e(isset($kat_map[$r['kategori']]) ? t($kat_map[$r['kategori']]) : $r['kategori']) ?></span></td>
            <td class="num" data-label="<?= e(t('col_qty')) ?>">
                <span class="qty"><?= $jml ?></span>
                <?php if ($jml === 0): ?>
                    <span class="badge badge-out"><?= e(t('status_out')) ?></span>
                <?php elseif ($jml <= STOK_MENIPIS): ?>
                    <span class="badge badge-low"><?= e(t('status_low')) ?></span>
                <?php endif; ?>
            </td>
            <td class="num" data-label="<?= e(t('col_price')) ?>"><?= e(rupiah($r['harga'])) ?></td>
            <td class="row-actions" data-label="<?= e(t('col_action')) ?>">
                <div class="act">
                    <a class="btn btn-sm btn-soft" href="edit.php?id=<?= (int)$r['id'] ?>"><?= icon('edit') ?><span><?= e(t('btn_edit')) ?></span></a>
                    <form method="POST" action="hapus.php" onsubmit="return confirm('<?= e(addslashes(t('confirm_delete'))) ?>');">
                        <?= csrf_field() ?>
                        <input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
                        <button type="submit" class="btn btn-sm btn-danger-soft"><?= icon('trash') ?><span><?= e(t('btn_delete')) ?></span></button>
                    </form>
                </div>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>
