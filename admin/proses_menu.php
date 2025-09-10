<?php
include '../config.php';

// Cek jika admin belum login, redirect ke halaman login
if (!isset($_SESSION['is_login']) || $_SESSION['is_login'] !== true) {
    header("Location: login.php");
    exit();
}

// --- PROSES TAMBAH MENU ---
if (isset($_POST['tambah'])) {
    $nama_menu = $_POST['nama_menu'];
    $kategori = $_POST['kategori'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];
    $gambar = $_FILES['gambar']['name'];
    $target_dir = "../assets/img/";
    $target_file = $target_dir . basename($gambar);

    if (!empty($gambar)) {
        if (move_uploaded_file($_FILES['gambar']['tmp_name'], $target_file)) {
            $stmt = $conn->prepare("INSERT INTO menu (nama_menu, kategori, harga, stok, gambar) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("ssiis", $nama_menu, $kategori, $harga, $stok, $gambar);
            if ($stmt->execute()) {
                header("Location: kelola_menu.php?status=sukses_tambah");
            } else {
                header("Location: kelola_menu.php?status=gagal");
            }
            $stmt->close();
        } else {
            header("Location: kelola_menu.php?status=gagal_upload");
        }
    } else {
        // Jika tidak ada gambar diupload
        $stmt = $conn->prepare("INSERT INTO menu (nama_menu, kategori, harga, stok) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssii", $nama_menu, $kategori, $harga, $stok);
        if ($stmt->execute()) {
            header("Location: kelola_menu.php?status=sukses_tambah");
        } else {
            header("Location: kelola_menu.php?status=gagal");
        }
        $stmt->close();
    }
}

// --- PROSES EDIT MENU ---
if (isset($_POST['edit'])) {
    $id_menu = $_POST['id_menu'];
    $nama_menu = $_POST['nama_menu'];
    $kategori = $_POST['kategori'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];
    $gambar_baru = $_FILES['gambar']['name'];

    if (!empty($gambar_baru)) {
        // Jika ada gambar baru diupload
        $target_dir = "../assets/img/";
        $target_file = $target_dir . basename($gambar_baru);
        if (move_uploaded_file($_FILES['gambar']['tmp_name'], $target_file)) {
            $stmt = $conn->prepare("UPDATE menu SET nama_menu=?, kategori=?, harga=?, stok=?, gambar=? WHERE id_menu=?");
            $stmt->bind_param("ssiisi", $nama_menu, $kategori, $harga, $stok, $gambar_baru, $id_menu);
        } else {
            header("Location: kelola_menu.php?status=gagal_upload");
            exit();
        }
    } else {
        // Jika tidak ada gambar baru
        $stmt = $conn->prepare("UPDATE menu SET nama_menu=?, kategori=?, harga=?, stok=? WHERE id_menu=?");
        $stmt->bind_param("ssiii", $nama_menu, $kategori, $harga, $stok, $id_menu);
    }

    if ($stmt->execute()) {
        header("Location: kelola_menu.php?status=sukses_update");
    } else {
        header("Location: kelola_menu.php?status=gagal");
    }
    $stmt->close();
}

// --- PROSES HAPUS MENU ---
if (isset($_GET['hapus'])) {
    $id_menu = $_GET['hapus'];

    // 1. Ambil nama file gambar dari DB
    $stmt = $conn->prepare("SELECT gambar FROM menu WHERE id_menu = ?");
    $stmt->bind_param("i", $id_menu);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $gambar_untuk_dihapus = $row['gambar'];
    $stmt->close();

    // 2. Hapus record dari DB
    $stmt_delete = $conn->prepare("DELETE FROM menu WHERE id_menu = ?");
    $stmt_delete->bind_param("i", $id_menu);
    if ($stmt_delete->execute()) {
        // 3. Jika record DB berhasil dihapus, hapus file gambar
        if (!empty($gambar_untuk_dihapus) && file_exists("../assets/img/" . $gambar_untuk_dihapus)) {
            unlink("../assets/img/" . $gambar_untuk_dihapus);
        }
        header("Location: kelola_menu.php?status=sukses_hapus");
    } else {
        header("Location: kelola_menu.php?status=gagal");
    }
    $stmt_delete->close();
}

$conn->close();
?>