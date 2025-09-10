<?php
session_start();
include 'config.php'; // Sertakan config untuk koneksi DB

$response = [
    'success' => false,
    'message' => 'Aksi tidak valid.',
    'cart_empty' => false
];

if (isset($_POST['product_id']) && isset($_POST['action'])) {
    $productId = (int)$_POST['product_id'];
    $action = $_POST['action'];

    if (isset($_SESSION['cart'][$productId])) {
        switch ($action) {
            case 'increase':
                $_SESSION['cart'][$productId]++;
                $response['success'] = true;
                break;
            case 'decrease':
                $_SESSION['cart'][$productId]--;
                if ($_SESSION['cart'][$productId] <= 0) {
                    unset($_SESSION['cart'][$productId]);
                }
                $response['success'] = true;
                break;
            case 'remove':
                unset($_SESSION['cart'][$productId]);
                $response['success'] = true;
                break;
            case 'set':
                $quantity = (int)$_POST['quantity'];
                if ($quantity > 0) {
                    $_SESSION['cart'][$productId] = $quantity;
                } else {
                    unset($_SESSION['cart'][$productId]);
                }
                $response['success'] = true;
                break;
        }
    }
}

// Hitung ulang total setelah aksi
$total_items = 0;
$grand_total = 0;

if (!empty($_SESSION['cart'])) {
    $ids = array_keys($_SESSION['cart']);
    if(count($ids) > 0){
        $sql = "SELECT id_menu, harga FROM menu WHERE id_menu IN (" . implode(',', $ids) . ")";
        $result = $conn->query($sql);
        $products = [];
        while ($row = $result->fetch_assoc()) {
            $products[$row['id_menu']] = $row;
        }

        foreach ($_SESSION['cart'] as $id => $qty) {
            $total_items += $qty;
            if (isset($products[$id])) {
                $grand_total += $products[$id]['harga'] * $qty;
            }
        }
    }
} else {
    $response['cart_empty'] = true;
}

// Siapkan data untuk dikirim balik sebagai JSON
$response['cart_count'] = $total_items;
$response['grand_total'] = $grand_total;
$response['grand_total_formatted'] = 'Rp ' . number_format($grand_total, 0, ',', '.');


header('Content-Type: application/json');
echo json_encode($response);
?>
