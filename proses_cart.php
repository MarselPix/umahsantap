<?php
include 'config.php';

// Inisialisasi keranjang jika belum ada
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$redirect_page = 'menu.php'; // Default redirect

// Menambahkan item ke keranjang (dari halaman menu)
if (isset($_GET['add'])) {
    $id_menu = (int)$_GET['add'];
    if (isset($_SESSION['cart'][$id_menu])) {
        $_SESSION['cart'][$id_menu]['jumlah']++;
    } else {
        $_SESSION['cart'][$id_menu] = ['id' => $id_menu, 'jumlah' => 1];
    }
    $redirect_page = 'menu.php';
}

// Menambah jumlah item di keranjang
if (isset($_GET['increase'])) {
    $id_menu = (int)$_GET['increase'];
    if (isset($_SESSION['cart'][$id_menu])) {
        $_SESSION['cart'][$id_menu]['jumlah']++;
    }
    $redirect_page = 'cart.php';
}

// Mengurangi jumlah item di keranjang
if (isset($_GET['decrease'])) {
    $id_menu = (int)$_GET['decrease'];
    if (isset($_SESSION['cart'][$id_menu])) {
        $_SESSION['cart'][$id_menu]['jumlah']--;
        if ($_SESSION['cart'][$id_menu]['jumlah'] <= 0) {
            unset($_SESSION['cart'][$id_menu]);
        }
    }
    $redirect_page = 'cart.php';
}

// Menghapus item dari keranjang
if (isset($_GET['remove'])) {
    $id_menu = (int)$_GET['remove'];
    if (isset($_SESSION['cart'][$id_menu])) {
        unset($_SESSION['cart'][$id_menu]);
    }
    $redirect_page = 'cart.php';
}

// Mengarahkan kembali ke halaman yang sesuai
header("Location: " . $redirect_page);
exit();
?>