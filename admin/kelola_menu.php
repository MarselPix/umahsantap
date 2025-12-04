<?php
include '../config.php';

// Cek jika admin belum login, redirect ke halaman login
if (!isset($_SESSION['is_login']) || $_SESSION['is_login'] !== true) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Menu - Umah Santap</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }
        .sidebar {
            background-color: #2C3E50;
            color: white;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            padding-top: 20px;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
            display: block;
            padding: 10px 15px;
            border-radius: 5px;
            margin: 5px 10px;
        }
        .sidebar a:hover, .sidebar a.active {
            background-color: #FF7F00;
        }
        .sidebar .logo {
            font-weight: 700;
            font-size: 1.5rem;
            text-align: center;
            margin-bottom: 20px;
            color: #FF7F00;
        }
        .main-content {
            margin-left: 250px;
            padding: 20px;
        }
        .table img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 5px;
        }
    </style>
</head>
<body>

    <div class="sidebar">
        <div class="logo">Umah Santap</div>
        <a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <a href="kelola_menu.php" class="active"><i class="fas fa-utensils"></i> Kelola Menu</a>
        <a href="kelola_pesanan.php"><i class="fas fa-clipboard-list"></i> Kelola Pesanan</a>
        <a href="logout.php" class="mt-auto"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <div class="main-content">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>Kelola Menu</h1>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#menuModal" id="tambahMenuBtn"><i class="fas fa-plus"></i> Tambah Menu Baru</button>
            </div>

            <?php
            if(isset($_GET['status'])) {
                if($_GET['status'] == 'sukses_tambah') {
                    echo '<div class="alert alert-success">Menu baru berhasil ditambahkan!</div>';
                } else if($_GET['status'] == 'sukses_update') {
                    echo '<div class="alert alert-success">Menu berhasil diperbarui!</div>';
                } else if($_GET['status'] == 'sukses_hapus') {
                    echo '<div class="alert alert-success">Menu berhasil dihapus!</div>';
                } else {
                    echo '<div class="alert alert-danger">Terjadi kesalahan!</div>';
                }
            }
            ?>

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th>Gambar</th>
                                    <th>Nama Menu</th>
                                    <th>Kategori</th>
                                    <th>Harga</th>
                                    <th>Stok</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql = "SELECT * FROM menu ORDER BY id_menu DESC";
                                $result = $conn->query($sql);
                                if ($result->num_rows > 0) {
                                    while($row = $result->fetch_assoc()) {
                                ?>
                                <tr>
                                    <td><img src="../assets/img/<?php echo $row['gambar']; ?>" alt="<?php echo $row['nama_menu']; ?>"></td>
                                    <td><?php echo $row['nama_menu']; ?></td>
                                    <td><?php echo ucfirst($row['kategori']); ?></td>
                                    <td><?php echo format_rupiah($row['harga']); ?></td>
                                    <td><?php echo $row['stok']; ?></td>
                                    <td>
                                        <button class="btn btn-sm btn-warning edit-btn" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#menuModal" 
                                                data-id="<?php echo $row['id_menu']; ?>" 
                                                data-nama="<?php echo $row['nama_menu']; ?>" 
                                                data-kategori="<?php echo $row['kategori']; ?>" 
                                                data-harga="<?php echo $row['harga']; ?>" 
                                                data-stok="<?php echo $row['stok']; ?>">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <a href="proses_menu.php?hapus=<?php echo $row['id_menu']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Anda yakin ingin menghapus menu ini?')"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>
                                <?php
                                    }
                                } else {
                                    echo "<tr><td colspan='6' class='text-center'>Belum ada menu.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah/Edit Menu -->
    <div class="modal fade" id="menuModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Menu Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="proses_menu.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id_menu" id="id_menu">
                        <div class="mb-3">
                            <label for="nama_menu" class="form-label">Nama Menu</label>
                            <input type="text" class="form-control" id="nama_menu" name="nama_menu" required>
                        </div>
                        <div class="mb-3">
                            <label for="kategori" class="form-label">Kategori</label>
                            <select class="form-select" id="kategori" name="kategori" required>
                                <option value="makanan">Makanan</option>
                                <option value="minuman">Minuman</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="harga" class="form-label">Harga</label>
                            <input type="number" class="form-control" id="harga" name="harga" required>
                        </div>
                        <div class="mb-3">
                            <label for="stok" class="form-label">Stok</label>
                            <input type="number" class="form-control" id="stok" name="stok" value="100" required>
                        </div>
                        <div class="mb-3">
                            <label for="gambar" class="form-label">Gambar</label>
                            <input type="file" class="form-control" id="gambar" name="gambar">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" name="tambah" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const menuModal = document.getElementById('menuModal');
        const modalTitle = menuModal.querySelector('.modal-title');
        const form = menuModal.querySelector('form');
        const idMenuInput = menuModal.querySelector('#id_menu');
        const namaMenuInput = menuModal.querySelector('#nama_menu');
        const kategoriInput = menuModal.querySelector('#kategori');
        const hargaInput = menuModal.querySelector('#harga');
        const stokInput = menuModal.querySelector('#stok');
        const submitButton = menuModal.querySelector('button[type="submit"]');

        // Reset modal on show for adding new menu
        document.getElementById('tambahMenuBtn').addEventListener('click', function(){
            modalTitle.textContent = 'Tambah Menu Baru';
            form.action = 'proses_menu.php';
            form.reset();
            idMenuInput.value = '';
            submitButton.name = 'tambah';
            submitButton.textContent = 'Simpan';
        });

        // Populate modal for editing
        const editButtons = document.querySelectorAll('.edit-btn');
        editButtons.forEach(button => {
            button.addEventListener('click', function () {
                modalTitle.textContent = 'Edit Menu';
                form.action = 'proses_menu.php';
                
                idMenuInput.value = this.dataset.id;
                namaMenuInput.value = this.dataset.nama;
                kategoriInput.value = this.dataset.kategori;
                hargaInput.value = this.dataset.harga;
                stokInput.value = this.dataset.stok;

                submitButton.name = 'edit';
                submitButton.textContent = 'Update';
            });
        });
    });
    </script>
</body>
</html>