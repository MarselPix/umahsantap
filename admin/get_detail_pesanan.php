<?php
include '../config.php';

// Cek jika admin belum login
if (!isset($_SESSION['is_login']) || $_SESSION['is_login'] !== true) {
    exit('Akses ditolak.');
}

if (!isset($_GET['id'])) {
    exit('ID Pesanan tidak ditemukan.');
}

$id_pesanan = (int)$_GET['id'];

// 1. Ambil data pesanan utama
$stmt_pesanan = $conn->prepare("SELECT * FROM pesanan WHERE id_pesanan = ?");
$stmt_pesanan->bind_param("i", $id_pesanan);
$stmt_pesanan->execute();
$pesanan = $stmt_pesanan->get_result()->fetch_assoc();
$stmt_pesanan->close();

if (!$pesanan) {
    exit('Pesanan tidak ditemukan.');
}

// 2. Ambil item detail pesanan
$stmt_detail = $conn->prepare("SELECT m.nama_menu, dp.jumlah, dp.subtotal FROM detail_pesanan dp JOIN menu m ON dp.id_menu = m.id_menu WHERE dp.id_pesanan = ?");
$stmt_detail->bind_param("i", $id_pesanan);
$stmt_detail->execute();
$detail_items = $stmt_detail->get_result();

// 3. Hasilkan output HTML
?>

<h4>ID Pesanan: #<?php echo $pesanan['id_pesanan']; ?></h4>
<div class="row">
    <div class="col-md-6">
        <strong>Nama Pemesan:</strong>
        <p><?php echo htmlspecialchars($pesanan['nama_pemesan']); ?></p>
    </div>
    <div class="col-md-6">
        <strong>No. HP:</strong>
        <p><?php echo htmlspecialchars($pesanan['no_hp']); ?></p>
    </div>
    <div class="col-md-12">
        <strong>Alamat:</strong>
        <p><?php echo nl2br(htmlspecialchars($pesanan['alamat'])); ?></p>
    </div>
</div>

<hr>

<h5>Item yang Dipesan:</h5>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Nama Menu</th>
            <th>Jumlah</th>
            <th>Subtotal</th>
        </tr>
    </thead>
    <tbody>
        <?php while($item = $detail_items->fetch_assoc()): ?>
        <tr>
            <td><?php echo htmlspecialchars($item['nama_menu']); ?></td>
            <td>x <?php echo $item['jumlah']; ?></td>
            <td><?php echo format_rupiah($item['subtotal']); ?></td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<div class="text-end">
    <p><strong>Total Harga:</strong> <?php echo format_rupiah($pesanan['total_harga']); ?></p>
    <p><strong>Metode Pembayaran:</strong> <?php echo $pesanan['metode_bayar']; ?></p>
    <p><strong>Status Saat Ini:</strong> <span class="badge bg-primary"><?php echo ucfirst($pesanan['status']); ?></span></p>
</div>

<?php
$stmt_detail->close();
$conn->close();
?>