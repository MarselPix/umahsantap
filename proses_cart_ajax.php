<?php
session_start();

// Inisialisasi keranjang jika belum ada
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$response = [
    'success' => false,
    'message' => 'Permintaan tidak valid.',
];

if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
    $productId = (int)$_POST['product_id'];
    $quantity = (int)$_POST['quantity'];

    if ($productId > 0 && $quantity > 0) {
        // Jika barang sudah ada di keranjang, tambahkan jumlahnya. Jika belum, buat baru.
        if (isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId] += $quantity;
        } else {
            $_SESSION['cart'][$productId] = $quantity;
        }
        
        $response['success'] = true;
        $response['message'] = 'Barang berhasil ditambahkan.';

    } else {
        $response['message'] = 'ID produk atau kuantitas tidak valid.';
    }
}

// Hitung total item di keranjang
$total_items = 0;
if (!empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $qty) {
        $total_items += $qty;
    }
}
$response['cart_count'] = $total_items;

header('Content-Type: application/json');
echo json_encode($response);
?>
