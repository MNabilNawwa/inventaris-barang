<?php
// Inisialisasi: session, koneksi, bahasa, dan fungsi bantu.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../config/koneksi.php';

// --- Dual bahasa (disimpan di $_SESSION['lang']) ---
const BAHASA_TERSEDIA = ['id', 'en'];
if (!isset($_SESSION['lang']) || !in_array($_SESSION['lang'], BAHASA_TERSEDIA, true)) {
    $_SESSION['lang'] = 'id';
}
$LANG = require __DIR__ . '/../lang/' . $_SESSION['lang'] . '.php';

/** Ambil teks terjemahan berdasarkan kunci. */
function t(string $key): string
{
    global $LANG;
    return $LANG[$key] ?? $key;
}

/** Escape output HTML (mencegah XSS). */
function e($value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function redirect(string $url): void
{
    header('Location: ' . $url);
    exit;
}

// --- CSRF token ---
function csrf_token(): string
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="csrf" value="' . e(csrf_token()) . '">';
}

function csrf_valid(): bool
{
    return isset($_POST['csrf'], $_SESSION['csrf'])
        && hash_equals($_SESSION['csrf'], (string)$_POST['csrf']);
}

// --- Flash message ---
function flash_set(string $type, string $key): void
{
    $_SESSION['flash'] = ['type' => $type, 'key' => $key];
}

function flash_get(): ?array
{
    if (!empty($_SESSION['flash'])) {
        $f = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $f;
    }
    return null;
}

function rupiah($angka): string
{
    return 'Rp ' . number_format((int)$angka, 0, ',', '.');
}

/** Ikon SVG inline (gaya garis, ikut warna teks). */
function icon(string $name): string
{
    $p = [
        'plus'       => '<path d="M12 5v14M5 12h14"/>',
        'search'     => '<circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/>',
        'edit'       => '<path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/>',
        'trash'      => '<path d="M3 6h18"/><path d="M8 6V4h8v2"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/>',
        'logout'     => '<path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><path d="m16 17 5-5-5-5"/><path d="M21 12H9"/>',
        'box'        => '<path d="M21 8 12 3 3 8v8l9 5 9-5Z"/><path d="m3 8 9 5 9-5"/><path d="M12 13v8"/>',
        'arrow-left' => '<path d="M19 12H5"/><path d="m12 19-7-7 7-7"/>',
        'alert'      => '<circle cx="12" cy="12" r="9"/><path d="M12 8v5M12 16h.01"/>',
        'check'      => '<circle cx="12" cy="12" r="9"/><path d="m8 12 3 3 5-6"/>',
    ];
    return '<svg class="ico" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" '
         . 'stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">'
         . ($p[$name] ?? '') . '</svg>';
}
