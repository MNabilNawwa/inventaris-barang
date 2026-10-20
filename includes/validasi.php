<?php
// Validasi + sanitasi data barang.

/** Daftar kategori yang diizinkan: nilai di DB => kunci terjemahan. */
function daftar_kategori(): array
{
    return [
        'Elektronik' => 'cat_elektronik',
        'Alat Tulis' => 'cat_alat_tulis',
        'Furnitur'   => 'cat_furnitur',
        'Perkakas'   => 'cat_perkakas',
        'Lainnya'    => 'cat_lainnya',
    ];
}

/**
 * Validasi input form barang.
 * Mengembalikan [$data_bersih, $errors].
 */
function validasi_barang(array $post, mysqli $conn, int $id_abaikan = 0): array
{
    $errors = [];

    // Sanitasi: trim + hapus tag HTML
    $kode     = trim(strip_tags($post['kode_barang'] ?? ''));
    $nama     = trim(strip_tags($post['nama_barang'] ?? ''));
    $kategori = trim(strip_tags($post['kategori'] ?? ''));
    $jumlah   = trim((string)($post['jumlah'] ?? ''));
    $harga    = trim((string)($post['harga'] ?? ''));

    // Kode barang (if-else)
    if ($kode === '') {
        $errors['kode_barang'] = t('err_code_empty');
    } elseif (!preg_match('/^[A-Za-z0-9-]{3,20}$/', $kode)) {
        $errors['kode_barang'] = t('err_code_format');
    } else {
        $kode = strtoupper($kode);
        $stmt = $conn->prepare('SELECT id FROM barang WHERE kode_barang = ? AND id <> ? LIMIT 1');
        $stmt->bind_param('si', $kode, $id_abaikan);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            $errors['kode_barang'] = t('err_code_dup');
        }
        $stmt->close();
    }

    // Nama barang
    if ($nama === '') {
        $errors['nama_barang'] = t('err_name_empty');
    } elseif (mb_strlen($nama) < 3 || mb_strlen($nama) > 100) {
        $errors['nama_barang'] = t('err_name_len');
    }

    // Kategori (switch)
    switch (true) {
        case $kategori === '':
            $errors['kategori'] = t('err_cat_empty');
            break;
        case !array_key_exists($kategori, daftar_kategori()):
            $errors['kategori'] = t('err_cat_invalid');
            break;
        default:
            // valid
            break;
    }

    // Jumlah (filter_var)
    if ($jumlah === '') {
        $errors['jumlah'] = t('err_qty_empty');
    } else {
        $jumlah_int = filter_var($jumlah, FILTER_VALIDATE_INT, ['options' => ['min_range' => 0, 'max_range' => 1000000]]);
        if ($jumlah_int === false) {
            $errors['jumlah'] = t('err_qty_invalid');
        } else {
            $jumlah = $jumlah_int;
        }
    }

    // Harga (filter_var)
    if ($harga === '') {
        $errors['harga'] = t('err_price_empty');
    } else {
        $harga_int = filter_var($harga, FILTER_VALIDATE_INT, ['options' => ['min_range' => 0, 'max_range' => 2000000000]]);
        if ($harga_int === false) {
            $errors['harga'] = t('err_price_invalid');
        } else {
            $harga = $harga_int;
        }
    }

    $data = [
        'kode_barang' => $kode,
        'nama_barang' => $nama,
        'kategori'    => $kategori,
        'jumlah'      => $jumlah,
        'harga'       => $harga,
    ];
    return [$data, $errors];
}
