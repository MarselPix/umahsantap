<?php
include 'config.php';

// Cek jika form disubmit dan keranjang tidak kosong
if (isset($_POST['konfirmasi_pesanan']) && !empty($_SESSION['cart'])) {

    // 1. Ambil data dari form
    $nama_pemesan = $conn->real_escape_string($_POST['nama_pemesan']);
    $no_hp = $conn->real_escape_string($_POST['no_hp']);
    $alamat = $conn->real_escape_string($_POST['alamat']);
    $metode_bayar = $conn->real_escape_string($_POST['metode_bayar']);
    $total_harga = (int)$_POST['total_harga'];
    $status = 'dipesan'; // Status awal

    // 2. Masukkan data ke tabel `pesanan`
    $stmt = $conn->prepare("INSERT INTO pesanan (nama_pemesan, no_hp, alamat, metode_bayar, status, total_harga, tanggal_pesan) VALUES (?, ?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("sssssi", $nama_pemesan, $no_hp, $alamat, $metode_bayar, $status, $total_harga);
    
    if ($stmt->execute()) {
        // 3. Ambil id_pesanan yang baru dibuat
        $id_pesanan = $stmt->insert_id;
        $stmt->close();

        // 4. Masukkan data ke tabel `detail_pesanan`
        $stmt_detail = $conn->prepare("INSERT INTO detail_pesanan (id_pesanan, id_menu, jumlah, subtotal) VALUES (?, ?, ?, ?)");
        
        $ids = array_keys($_SESSION['cart']);
        $sql_menu = "SELECT * FROM menu WHERE id_menu IN (".implode(',', $ids).")";
        $result_menu = $conn->query($sql_menu);
        $menu_data = [];
        while($row = $result_menu->fetch_assoc()) {
            $menu_data[$row['id_menu']] = $row;
        }

        foreach ($_SESSION['cart'] as $id_menu => $jumlah) {
            $harga_menu = $menu_data[$id_menu]['harga'];
            $subtotal = $harga_menu * $jumlah;
            $stmt_detail->bind_param("iiid", $id_pesanan, $id_menu, $jumlah, $subtotal);
            $stmt_detail->execute();
        }
        $stmt_detail->close();

        // 5. Kosongkan keranjang
        unset($_SESSION['cart']);

        // 6. Redirect ke halaman yang sesuai
        if ($metode_bayar == 'COD') {
            header("Location: order_status.php?id=" . $id_pesanan);
        } else {
            header("Location: instruksi_pembayaran.php?id=" . $id_pesanan);
        }
        exit();

    } else {
        // Jika gagal menyimpan pesanan utama
        echo "Error: " . $stmt->error;
    }

} else {
    // Jika akses langsung atau keranjang kosong
    header('Location: index.php');
    exit();
}

$conn->close();
?>