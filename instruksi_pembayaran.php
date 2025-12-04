<?php 
include 'header.php'; 

// Redirect jika tidak ada ID pesanan
if (!isset($_GET['id'])) {
    echo "<script>window.location.href = 'index.php';</script>";
    exit();
}

$id_pesanan = (int)$_GET['id'];

// Ambil data pesanan dari database
$stmt = $conn->prepare("SELECT * FROM pesanan WHERE id_pesanan = ?");
$stmt->bind_param("i", $id_pesanan);
$stmt->execute();
$pesanan = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Jika pesanan tidak ditemukan, tampilkan error
if (!$pesanan) {
    echo '<div class="container py-5"><div class="alert alert-danger">Pesanan tidak ditemukan.</div></div>';
    include 'footer.php';
    exit();
}

$metode_bayar = $pesanan['metode_bayar'];
$total_harga_formatted = 'Rp ' . number_format($pesanan['total_harga'], 0, ',', '.');

?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header text-center bg-light">
                    <h4 class="my-2">Selesaikan Pembayaran Anda</h4>
                </div>
                <div class="card-body text-center p-4">
                    <p class="text-muted">Total Tagihan Anda:</p>
                    <h2 class="fw-bold mb-4 text-primary"><?php echo $total_harga_formatted; ?></h2>

                    <?php if ($metode_bayar == 'BRI'): ?>
                        <h5>Transfer Bank BRI</h5>
                        <p>Silakan lakukan transfer ke nomor Virtual Account di bawah ini:</p>
                        <div class="alert alert-info fs-4 fw-bold">
                            88808 123 456 7890
                        </div>
                        <p class="small text-muted">a/n Umah Santap</p>

                    <?php elseif ($metode_bayar == 'DANA'): ?>
                        <h5>Pembayaran via DANA</h5>
                        <p>Silakan scan QR Code di bawah ini menggunakan aplikasi DANA:</p>
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=220x220&data=bayar-umahsantap-<?php echo $id_pesanan; ?>" alt="QR Code DANA" class="img-fluid my-3 border rounded">
                        <p class="small text-muted">Atau transfer ke nomor: <strong>089912345678</strong> (a/n Umah Santap)</p>
                    
                    <?php elseif ($metode_bayar == 'SHOPEEPAY'): ?>
                        <h5>Pembayaran via ShopeePay</h5>
                        <p>Silakan scan QR Code di bawah ini menggunakan aplikasi Shopee:</p>
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=220x220&data=bayar-umahsantap-<?php echo $id_pesanan; ?>" alt="QR Code ShopeePay" class="img-fluid my-3 border rounded">
                        <p class="small text-muted">Atau transfer ke nomor: <strong>089912345678</strong> (a/n Umah Santap)</p>

                    <?php else: ?>
                        <div class="alert alert-warning">Metode pembayaran tidak dikenali atau tidak memerlukan instruksi lebih lanjut.</div>
                    <?php endif; ?>

                    <hr class="my-4">
                    <p>Setelah melakukan pembayaran, Anda dapat melihat status pesanan Anda.</p>
                    <a href="order_status.php?id=<?php echo $id_pesanan; ?>" class="btn btn-primary">Saya Sudah Bayar, Lihat Status Pesanan</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
