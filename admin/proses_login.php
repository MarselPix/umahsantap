<?php
// Memulai session dan menyertakan config.php
include '../config.php';

// Mengambil data dari form
$username = $_POST['username'];
$password = md5($_POST['password']); // Enkripsi password dengan MD5

// Menyiapkan statement untuk mencegah SQL Injection
$stmt = $conn->prepare("SELECT id_admin, username FROM admin WHERE username = ? AND password = ?");
$stmt->bind_param("ss", $username, $password);

// Eksekusi statement
$stmt->execute();
$result = $stmt->get_result();

// Cek jika admin ditemukan
if ($result->num_rows > 0) {
    // Ambil data admin
    $admin = $result->fetch_assoc();

    // Simpan data ke session
    $_SESSION['id_admin'] = $admin['id_admin'];
    $_SESSION['username'] = $admin['username'];
    $_SESSION['is_login'] = true;

    // Redirect ke dashboard
    header("Location: dashboard.php");
    exit();
} else {
    // Jika gagal, redirect kembali ke halaman login dengan pesan error
    header("Location: login.php?error=1");
    exit();
}

// Menutup statement dan koneksi
$stmt->close();
$conn->close();
?>