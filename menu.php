<?php include 'header.php'; ?>

<!-- Menu Section -->
<div class="py-5">
    <div class="container">
        <h2 class="text-center mb-4 fw-bold">Pilihan Menu</h2>

        <!-- Filter and Search -->
        <div class="row mb-4">
            <div class="col-md-7">
                <ul class="nav nav-pills">
                    <li class="nav-item"><a class="nav-link <?php echo !isset($_GET['kategori']) ? 'active' : ''; ?>" href="menu.php">Semua</a></li>
                    <li class="nav-item"><a class="nav-link <?php echo (isset($_GET['kategori']) && $_GET['kategori'] == 'makanan') ? 'active' : ''; ?>" href="menu.php?kategori=makanan">Makanan</a></li>
                    <li class="nav-item"><a class="nav-link <?php echo (isset($_GET['kategori']) && $_GET['kategori'] == 'minuman') ? 'active' : ''; ?>" href="menu.php?kategori=minuman">Minuman</a></li>
                </ul>
            </div>
            <div class="col-md-5">
                <form action="menu.php" method="GET" class="d-flex">
                    <input type="text" class="form-control" placeholder="Cari menu favoritmu..." name="search" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                    <button class="btn btn-primary ms-2" type="submit">Cari</button>
                </form>
            </div>
        </div>

        <div class="row g-4">
            <?php
            $sql = "SELECT * FROM menu";
            $where = [];
            if (isset($_GET['kategori']) && !empty($_GET['kategori'])) {
                $kategori = $conn->real_escape_string($_GET['kategori']);
                $where[] = "kategori = '$kategori'";
            }
            if (isset($_GET['search']) && !empty($_GET['search'])) {
                $search = $conn->real_escape_string($_GET['search']);
                $where[] = "nama_menu LIKE '%$search%'";
            }
            if (!empty($where)) {
                $sql .= " WHERE " . implode(' AND ', $where);
            }
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
            ?>
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 shadow-sm border-0">
                    <img src="assets/img/<?php echo $row['gambar']; ?>" class="card-img-top" alt="<?php echo $row['nama_menu']; ?>" style="height: 200px; object-fit: cover;">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title"><?php echo $row['nama_menu']; ?></h5>
                        <h6 class="card-subtitle mb-2 fw-bold text-danger">Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?></h6>
                        <div class="mt-auto">
                           <button class="btn btn-sm btn-primary w-100 add-to-cart-btn"
                                   data-bs-toggle="modal"
                                   data-bs-target="#addToCartModal"
                                   data-id="<?php echo $row['id_menu']; ?>"
                                   data-nama="<?php echo htmlspecialchars($row['nama_menu']); ?>">
                                Tambah ke Keranjang
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <?php
                }
            } else {
                echo "<div class='col-12'><p class='text-center alert alert-warning'>Menu tidak ditemukan.</p></div>";
            }
            ?>
        </div>
    </div>
</div>

<!-- Add to Cart Modal -->
<div class="modal fade" id="addToCartModal" tabindex="-1" aria-labelledby="addToCartModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addToCartModalLabel">Tambah Menu</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <h6 id="modal-item-name"></h6>
        <div class="d-flex justify-content-center align-items-center my-3">
            <button class="btn btn-outline-secondary" type="button" id="quantity-minus">-</button>
            <input type="text" class="form-control text-center mx-2" value="1" id="quantity-input" readonly style="max-width: 70px;">
            <button class="btn btn-outline-secondary" type="button" id="quantity-plus">+</button>
        </div>
        <input type="hidden" id="modal-item-id" value="">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-primary" id="modal-add-to-cart-btn">Tambahkan</button>
      </div>
    </div>
  </div>
</div>

<!-- Toast Notification -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
  <div id="cart-toast" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="toast-header">
      <strong class="me-auto">Umah Santap</strong>
      <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
    <div class="toast-body">
      Berhasil ditambahkan ke keranjang!
    </div>
  </div>
</div>

<?php include 'footer.php'; ?>

<script>
$(document).ready(function(){
    var addToCartModal = new bootstrap.Modal(document.getElementById('addToCartModal'), {});
    var cartToast = new bootstrap.Toast(document.getElementById('cart-toast'), {delay: 2000});

    // When modal is about to be shown
    $('#addToCartModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var id = button.data('id');
        var nama = button.data('nama');

        var modal = $(this);
        modal.find('#modal-item-name').text(nama);
        modal.find('#modal-item-id').val(id);
        modal.find('#quantity-input').val(1); // Reset quantity to 1
    });

    // Quantity controls
    $('#quantity-plus').click(function(){
        var qty = parseInt($('#quantity-input').val());
        $('#quantity-input').val(qty + 1);
    });

    $('#quantity-minus').click(function(){
        var qty = parseInt($('#quantity-input').val());
        if (qty > 1) {
            $('#quantity-input').val(qty - 1);
        }
    });

    // AJAX add to cart
    $('#modal-add-to-cart-btn').click(function(){
        var productId = $('#modal-item-id').val();
        var quantity = $('#quantity-input').val();

        $.ajax({
            url: 'proses_cart_ajax.php',
            type: 'POST',
            data: {
                product_id: productId,
                quantity: quantity
            },
            dataType: 'json',
            success: function(response) {
                if(response.success) {
                    addToCartModal.hide();
                    // Update cart count in navbar
                    $('#cart-count').text(response.cart_count);
                    // Show success toast
                    cartToast.show();
                } else {
                    alert(response.message);
                }
            },
            error: function() {
                alert('Error connecting to the server.');
            }
        });
    });
});
</script>