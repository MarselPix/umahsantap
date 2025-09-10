<?php include 'includes/header.php'; ?>

<h1 class="mb-4">Dashboard</h1>

<?php
// Ambil statistik dari database
$total_pesanan = $conn->query("SELECT COUNT(*) as total FROM pesanan")->fetch_assoc()['total'];
$total_menu = $conn->query("SELECT COUNT(*) as total FROM menu")->fetch_assoc()['total'];
$pesanan_baru = $conn->query("SELECT COUNT(*) as total FROM pesanan WHERE status = 'dipesan'")->fetch_assoc()['total'];
?>

<div class="row">
    <div class="col-lg-4">
        <div class="card text-white bg-primary mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title fs-2"><?php echo $pesanan_baru; ?></h5>
                        <p class="card-text">Pesanan Baru</p>
                    </div>
                    <i class="fas fa-receipt fa-3x opacity-50"></i>
                </div>
            </div>
            <a href="kelola_pesanan.php" class="card-footer text-white text-decoration-none">
                Lihat Detail <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card text-white bg-success mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title fs-2"><?php echo $total_pesanan; ?></h5>
                        <p class="card-text">Total Semua Pesanan</p>
                    </div>
                    <i class="fas fa-chart-bar fa-3x opacity-50"></i>
                </div>
            </div>
             <a href="kelola_pesanan.php" class="card-footer text-white text-decoration-none">
                Lihat Detail <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card text-white bg-info mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title fs-2"><?php echo $total_menu; ?></h5>
                        <p class="card-text">Jumlah Menu</p>
                    </div>
                    <i class="fas fa-utensils fa-3x opacity-50"></i>
                </div>
            </div>
             <a href="kelola_menu.php" class="card-footer text-white text-decoration-none">
                Lihat Detail <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
</div>

<div class="card mt-4">
    <div class="card-body">
        <h5 class="card-title">Selamat Datang!</h5>
        <p class="card-text">Selamat datang di panel admin Umah Santap. Silakan gunakan menu di samping untuk mengelola aplikasi.</p>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
