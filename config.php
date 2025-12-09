<?php
// Konfigurasi Database
define('DB_HOST', 'localhost');
define('DB_USER', 'root'); // User default XAMPP
define('DB_PASS', ''); // Password default XAMPP kosong
define('DB_NAME', 'umahsantap');   

// Membuat Koneksi
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Cek Koneksi
if ($conn->connect_error) {
    die("Koneksi Gagal: " . $conn->connect_error);
}

// Pengaturan Dasar Aplikasi
define('BASE_URL', 'http://localhost/umahsantap/');

// Mulai Session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Fungsi Bantuan (jika diperlukan)
function format_rupiah($angka){
    $hasil_rupiah = "Rp " . number_format($angka,0,',','.');
    return $hasil_rupiah;
}
?>