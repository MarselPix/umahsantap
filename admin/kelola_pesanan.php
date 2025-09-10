<?php
include 'includes/header.php';

// Proses update status jika ada data yang dikirim
if (isset($_POST['update_status'])) {
    $id_pesanan = $_POST['id_pesanan'];
    $status_baru = $_POST['status'];

    $stmt = $conn->prepare("UPDATE pesanan SET status = ? WHERE id_pesanan = ?");
    $stmt->bind_param("si", $status_baru, $id_pesanan);
    if ($stmt->execute()) {
        // Redirect menggunakan JavaScript untuk menghindari error header
        echo "<script>window.location.href = 'kelola_pesanan.php?status_updated=true';</script>";
    } else {
        echo '<div class="alert alert-danger">Gagal memperbarui status.</div>';
    }
    $stmt->close();
    exit();
}
?>

<h1 class="mb-4">Kelola Pesanan</h1>

<?php 
if(isset($_GET['status_updated'])) { 
    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
              Status pesanan berhasil diperbarui!
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>';
}
?>

<div class="card">
    <div class="card-header">
        Daftar Semua Pesanan
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Pemesan</th>
                        <th>Total</th>
                        <th>Metode Bayar</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT * FROM pesanan ORDER BY tanggal_pesan DESC";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                    ?>
                    <tr>
                        <td><strong>#<?php echo $row['id_pesanan']; ?></strong></td>
                        <td><?php echo htmlspecialchars($row['nama_pemesan']); ?></td>
                        <td>Rp <?php echo number_format($row['total_harga'], 0, ',', '.'); ?></td>
                        <td><span class="badge bg-secondary"><?php echo $row['metode_bayar']; ?></span></td>
                        <td><?php echo date("d M Y, H:i", strtotime($row['tanggal_pesan'])); ?></td>
                        <td>
                            <form action="kelola_pesanan.php" method="POST" class="d-flex">
                                <input type="hidden" name="id_pesanan" value="<?php echo $row['id_pesanan']; ?>">
                                <select name="status" class="form-select form-select-sm">
                                    <option value="dipesan" <?php echo ($row['status'] == 'dipesan') ? 'selected' : ''; ?>>Dipesan</option>
                                    <option value="diproses" <?php echo ($row['status'] == 'diproses') ? 'selected' : ''; ?>>Diproses</option>
                                    <option value="diantar" <?php echo ($row['status'] == 'diantar') ? 'selected' : ''; ?>>Diantar</option>
                                    <option value="selesai" <?php echo ($row['status'] == 'selesai') ? 'selected' : ''; ?>>Selesai</option>
                                </select>
                                <button type="submit" name="update_status" class="btn btn-sm btn-primary ms-2">Simpan</button>
                            </form>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-info detail-btn" data-bs-toggle="modal" data-bs-target="#detailModal" data-id="<?php echo $row['id_pesanan']; ?>"><i class="fas fa-eye"></i></button>
                        </td>
                    </tr>
                    <?php
                        }
                    } else {
                        echo "<tr><td colspan='7' class='text-center'>Belum ada pesanan masuk.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Detail Pesanan -->
<div class="modal fade" id="detailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Pesanan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="detailPesananContent">
                <p class="text-center">Memuat detail...</p>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const detailModal = document.getElementById('detailModal');
    if(detailModal) {
        detailModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const id_pesanan = button.getAttribute('data-id');
            const modalBody = document.getElementById('detailPesananContent');
            
            modalBody.innerHTML = '<p class="text-center">Memuat detail...</p>';
            
            fetch('get_detail_pesanan.php?id=' + id_pesanan)
                .then(response => response.text())
                .then(data => {
                    modalBody.innerHTML = data;
                })
                .catch(error => {
                    modalBody.innerHTML = `<p class="text-danger">Gagal memuat detail pesanan.</p>`;
                });
        });
    }
});
</script>
