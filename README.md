# Sistem Informasi Inventaris Barang

Tugas mata kuliah **Penerapan Teknologi Internet** - aplikasi CRUD dengan PHP native, MySQL/MariaDB, autentikasi session, dan dua bahasa (Indonesia / English).

## Fitur
- Login/logout berbasis `$_SESSION` (halaman CRUD terproteksi)
- CRUD barang (Create, Read, Update, Delete) + pencarian
- Validasi form (if-else & switch) dan sanitasi (`trim`, `strip_tags`, `filter_var`, `htmlspecialchars`)
- Dual bahasa ID/EN, pilihan disimpan di `$_SESSION['lang']`
- Keamanan: prepared statement, `password_hash`/`password_verify`, token CSRF, `session_regenerate_id`, hapus lewat POST

## Cara Instalasi (XAMPP)
1. Salin folder proyek ke `C:\xampp\htdocs\inventaris`.
2. Jalankan **Apache** dan **MySQL** di XAMPP.
3. Buka `http://localhost/phpmyadmin`, tab **Import**, pilih `database.sql`, lalu **Go**.
4. Jika perlu, sesuaikan kredensial di `config/koneksi.php`.
5. Buka `http://localhost/inventaris/`.

## Akun Login Default
| Username | Password |
|---|---|
| `admin` | `admin123` |

## Struktur Folder
```
config/koneksi.php      koneksi database
lang/id.php, en.php     kamus bahasa
includes/               init, auth, validasi, header, footer, form
login.php logout.php    autentikasi
index.php               Read
tambah.php              Create
edit.php                Update
hapus.php               Delete
ganti_bahasa.php        ganti bahasa
database.sql            struktur + data awal
```
