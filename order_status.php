<?php include 'header.php'; ?>

<div class="container py-5">

<?php
// ====================================================================
// KASUS 1: ID Pesanan Disediakan -> Tampilkan Status Pesanan
// ====================================================================
if (isset($_GET['id']) && !empty($_GET['id'])):
    $id_pesanan = (int)$_GET['id'];

    // Ambil data pesanan
    $stmt = $conn->prepare("SELECT * FROM pesanan WHERE id_pesanan = ?");
    $stmt->bind_param("i", $id_pesanan);
    $stmt->execute();
    $pesanan = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    // Jika pesanan tidak ditemukan, tampilkan pesan
    if (!$pesanan) {
        echo '<div class="alert alert-danger text-center">Pesanan dengan nomor ' . $id_pesanan . ' tidak ditemukan.</div>';
    } else {
        // Ambil detail item pesanan
        $stmt_detail = $conn->prepare("SELECT dp.*, m.nama_menu, m.gambar FROM detail_pesanan dp JOIN menu m ON dp.id_menu = m.id_menu WHERE dp.id_pesanan = ?");
        $stmt_detail->bind_param("i", $id_pesanan);
        $stmt_detail->execute();
        $detail_pesanan = $stmt_detail->get_result();

        $status_list = ['dipesan', 'diproses', 'diantar', 'selesai'];
        $current_status_index = array_search($pesanan['status'], $status_list);
?>

<!-- Custom CSS for Progress Bar -->
<style>
    .progress-bar-container { display: flex; justify-content: space-between; position: relative; margin: 40px 0; }
    .progress-bar-line { position: absolute; top: 50%; left: 0; width: 100%; height: 4px; background-color: #ddd; transform: translateY(-50%); z-index: 1; }
    .progress-bar-fill { position: absolute; top: 50%; left: 0; height: 4px; background-color: #27AE60; transform: translateY(-50%); z-index: 2; transition: width 0.5s ease; width: <?php echo ($current_status_index / (count($status_list) - 1)) * 100; ?>%; }
    .progress-step { position: relative; z-index: 3; text-align: center; }
    .progress-step .icon { width: 40px; height: 40px; border-radius: 50%; background-color: #ddd; color: white; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px; border: 3px solid #ddd; transition: all 0.3s ease; }
    .progress-step.active .icon { background-color: #27AE60; border-color: #27AE60; }
</style>

<div class="card col-md-10 mx-auto">
    <div class="card-body text-center p-lg-5">
        <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
        <h2 class="card-title">Terima Kasih Atas Pesanan Anda!</h2>
        <p class="text-muted">Pesanan Anda sedang kami proses. Berikut adalah detailnya:</p>
        
        <div class="text-start my-4 p-3 bg-light rounded">
            <h5>Nomor Pesanan: #<?php echo $pesanan['id_pesanan']; ?></h5>
            <p><strong>Tanggal:</strong> <?php echo date("d M Y, H:i", strtotime($pesanan['tanggal_pesan'])); ?></p>
            <p><strong>Nama:</strong> <?php echo htmlspecialchars($pesanan['nama_pemesan']); ?></p>
            <p><strong>Total Bayar:</strong> Rp <?php echo number_format($pesanan['total_harga'], 0, ',', '.'); ?></p>
            <p><strong>Metode Bayar:</strong> <?php echo htmlspecialchars($pesanan['metode_bayar']); ?></p>
        </div>

        <!-- Progress Bar -->
        <div class="progress-bar-container">
            <div class="progress-bar-line"></div>
            <div class="progress-bar-fill"></div>
            <?php foreach ($status_list as $index => $status): ?>
            <div class="progress-step <?php echo ($index <= $current_status_index) ? 'active' : ''; ?>">
                <div class="icon"><i class="fas <?php 
                    switch($status) {
                        case 'dipesan': echo 'fa-receipt'; break;
                        case 'diproses': echo 'fa-utensils'; break;
                        case 'diantar': echo 'fa-truck'; break;
                        case 'selesai': echo 'fa-house-user'; break;
                    }
                ?>"></i></div>
                <small><?php echo ucfirst($status); ?></small>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php
    } // End of else (if pesanan found)
// ====================================================================
// KASUS 2: ID Pesanan TIDAK Disediakan -> Tampilkan Form Pencarian
// ====================================================================
else:
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body p-5">
                <h4 class="card-title text-center mb-4">Lacak Pesanan Anda</h4>
                <p class="text-center text-muted">Masukkan nomor pesanan Anda untuk melihat status pengiriman.</p>
                <form action="order_status.php" method="GET">
                    <div class="mb-3">
                        <label for="id" class="form-label">Nomor Pesanan</label>
                        <input type="text" class="form-control" name="id" id="id" placeholder="Contoh: 123" required>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Lacak Pesanan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
endif; // End of main if/else
?>

</div> <!-- /container -->

<?php include 'footer.php'; ?>
