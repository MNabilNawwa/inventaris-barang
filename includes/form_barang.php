<?php
// Partial form (dipakai tambah.php dan edit.php).
// Variabel: $data, $errors, $aksi (url), $label_tombol
function field_error(array $errors, string $name): string
{
    return isset($errors[$name]) ? '<small class="error">' . e($errors[$name]) . '</small>' : '';
}
?>
<form method="POST" action="<?= e($aksi) ?>" class="form" novalidate>
    <?= csrf_field() ?>

    <div class="field <?= isset($errors['kode_barang']) ? 'has-error' : '' ?>">
        <label for="kode_barang"><?= e(t('f_code')) ?></label>
        <input type="text" id="kode_barang" name="kode_barang" maxlength="20" value="<?= e($data['kode_barang']) ?>">
        <small class="hint"><?= e(t('f_code_hint')) ?></small>
        <?= field_error($errors, 'kode_barang') ?>
    </div>

    <div class="field <?= isset($errors['nama_barang']) ? 'has-error' : '' ?>">
        <label for="nama_barang"><?= e(t('f_name')) ?></label>
        <input type="text" id="nama_barang" name="nama_barang" maxlength="100" value="<?= e($data['nama_barang']) ?>">
        <?= field_error($errors, 'nama_barang') ?>
    </div>

    <div class="field <?= isset($errors['kategori']) ? 'has-error' : '' ?>">
        <label for="kategori"><?= e(t('f_category')) ?></label>
        <select id="kategori" name="kategori">
            <option value=""><?= e(t('f_choose')) ?></option>
            <?php foreach (daftar_kategori() as $nilai => $kunci): ?>
                <option value="<?= e($nilai) ?>" <?= $data['kategori'] === $nilai ? 'selected' : '' ?>><?= e(t($kunci)) ?></option>
            <?php endforeach; ?>
        </select>
        <?= field_error($errors, 'kategori') ?>
    </div>

    <div class="row">
        <div class="field <?= isset($errors['jumlah']) ? 'has-error' : '' ?>">
            <label for="jumlah"><?= e(t('f_qty')) ?></label>
            <input type="text" inputmode="numeric" id="jumlah" name="jumlah" value="<?= e($data['jumlah']) ?>">
            <?= field_error($errors, 'jumlah') ?>
        </div>
        <div class="field <?= isset($errors['harga']) ? 'has-error' : '' ?>">
            <label for="harga"><?= e(t('f_price')) ?></label>
            <div class="affix"><span>Rp</span><input type="text" inputmode="numeric" id="harga" name="harga" value="<?= e($data['harga']) ?>"></div>
            <?= field_error($errors, 'harga') ?>
        </div>
    </div>

    <div class="actions">
        <button type="submit" class="btn btn-primary"><?= e($label_tombol) ?></button>
        <a href="index.php" class="btn btn-ghost"><?= e(t('btn_cancel')) ?></a>
    </div>
</form>
