<?php 
include 'header.php'; 

// Jika keranjang kosong, redirect ke halaman menu
if (empty($_SESSION['cart'])) {
    echo "<script>window.location.href = 'menu.php';</script>";
    exit();
}
?>

<!-- Custom CSS for this page -->
<style>
    .payment-method .form-check-label {
        display: flex;
        align-items: center;
        border: 1px solid #dee2e6;
        padding: 1rem;
        border-radius: 0.5rem;
        cursor: pointer;
        transition: all 0.2s ease-in-out;
    }
    .payment-method .form-check-label:hover {
        background-color: #f8f9fa;
    }
    .payment-method .form-check-input:checked + .form-check-label {
        border-color: #0d6efd;
        background-color: aliceblue;
    }
    .payment-method img {
        height: 25px;
        margin-right: 15px;
    }
</style>

<div class="container py-4">
    <h2 class="mb-4">Checkout</h2>
    <form action="proses_pesanan.php" method="POST">
        <div class="row g-4">
            <!-- Kolom Kiri: Data Pemesan & Pembayaran -->
            <div class="col-lg-7">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Data Pemesan</h5>
                        <div class="mb-3">
                            <label for="nama_pemesan" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" id="nama_pemesan" name="nama_pemesan" required>
                        </div>
                        <div class="mb-3">
                            <label for="no_hp" class="form-label">No. Handphone (WhatsApp)</label>
                            <input type="tel" class="form-control" id="no_hp" name="no_hp" required>
                        </div>
                        <div class="mb-3">
                            <label for="alamat" class="form-label">Alamat Lengkap Pengiriman</label>
                            <textarea class="form-control" id="alamat" name="alamat" rows="3" required></textarea>
                        </div>
                        <hr class="my-4">
                        <h5 class="card-title mb-3">Metode Pembayaran</h5>
                        <div class="payment-method">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="metode_bayar" id="cod" value="COD" checked>
                                <label class="form-check-label" for="cod"><i class="fas fa-hand-holding-usd fa-2x me-3 text-secondary"></i>Bayar di Tempat (COD)</label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="metode_bayar" id="bri" value="BRI">
                                <label class="form-check-label" for="bri"><img src="https://upload.wikimedia.org/wikipedia/commons/thumb/2/2e/BRI_2020.svg/1280px-BRI_2020.svg.png" alt="BRI">Transfer Bank BRI</label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="metode_bayar" id="dana" value="DANA">
                                <label class="form-check-label" for="dana"><img src="https://upload.wikimedia.org/wikipedia/commons/thumb/7/72/Logo_dana_blue.svg/1280px-Logo_dana_blue.svg.png" alt="DANA">DANA</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan: Ringkasan Pesanan -->
            <div class="col-lg-5">
                <div class="card sticky-top" style="top: 120px;">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Ringkasan Pesanan</h5>
                        <div class="mb-3">
                        <?php
                        $total_belanja = 0;
                        $ongkir = 6000; // Contoh ongkir statis
                        $ids = array_keys($_SESSION['cart']);
                        $sql = "SELECT * FROM menu WHERE id_menu IN (".implode(',', $ids).")";
                        $result = $conn->query($sql);
                        if($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                $jumlah = $_SESSION['cart'][$row['id_menu']]; // BUG FIXED
                                $subtotal = $row['harga'] * $jumlah;
                                $total_belanja += $subtotal;
                        ?>
                            <div class="d-flex justify-content-between text-muted">
                                <span><?php echo $row['nama_menu']; ?> <small>(x<?php echo $jumlah; ?>)</small></span>
                                <span>Rp <?php echo number_format($subtotal, 0, ',', '.'); ?></span>
                            </div>
                        <?php } } ?>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <p>Subtotal</p>
                            <p>Rp <?php echo number_format($total_belanja, 0, ',', '.'); ?></p>
                        </div>
                        <div class="d-flex justify-content-between">
                            <p>Ongkos Kirim</p>
                            <p>Rp <?php echo number_format($ongkir, 0, ',', '.'); ?></p>
                        </div>
                        <hr>
                        <?php $total_bayar = $total_belanja + $ongkir; ?>
                        <div class="d-flex justify-content-between fw-bold fs-5">
                            <p>Total Bayar</p>
                            <p>Rp <?php echo number_format($total_bayar, 0, ',', '.'); ?></p>
                        </div>
                        <input type="hidden" name="total_harga" value="<?php echo $total_bayar; ?>">
                        <div class="d-grid mt-3">
                            <button type="submit" name="konfirmasi_pesanan" class="btn btn-primary btn-lg">Konfirmasi Pesanan</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<?php include 'footer.php'; ?>
