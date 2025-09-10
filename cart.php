<?php include 'header.php'; ?>

<div class="container py-5">
    <h2 class="mb-4">Keranjang Belanja Anda</h2>

    <div id="cart-container">
        <?php if (!empty($_SESSION['cart'])): ?>
            <div class="card">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th scope="col" class="ps-4">Produk</th>
                                <th scope="col">Harga</th>
                                <th scope="col" class="text-center">Jumlah</th>
                                <th scope="col">Subtotal</th>
                                <th scope="col" class="text-end pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $total_belanja = 0;
                            $ids = array_keys($_SESSION['cart']);
                            $sql = "SELECT * FROM menu WHERE id_menu IN (" . implode(',', $ids) . ")";
                            $result = $conn->query($sql);
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $id_menu = $row['id_menu'];
                                    $jumlah = $_SESSION['cart'][$id_menu]; // LOGIC FIXED HERE
                                    $subtotal = $row['harga'] * $jumlah;
                                    $total_belanja += $subtotal;
                            ?>
                                    <tr id="row-<?php echo $id_menu; ?>">
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center">
                                                <img src="assets/img/<?php echo $row['gambar']; ?>" alt="<?php echo $row['nama_menu']; ?>" style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px; margin-right: 15px;">
                                                <span class="fw-bold"><?php echo $row['nama_menu']; ?></span>
                                            </div>
                                        </td>
                                        <td>Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?></td>
                                        <td>
                                            <div class="d-flex justify-content-center align-items-center">
                                                <button class="btn btn-outline-secondary btn-sm update-quantity" data-id="<?php echo $id_menu; ?>" data-action="decrease">-</button>
                                                <input type="text" class="form-control form-control-sm text-center mx-2" value="<?php echo $jumlah; ?>" readonly style="width: 60px;">
                                                <button class="btn btn-outline-secondary btn-sm update-quantity" data-id="<?php echo $id_menu; ?>" data-action="increase">+</button>
                                            </div>
                                        </td>
                                        <td class="subtotal">Rp <?php echo number_format($subtotal, 0, ',', '.'); ?></td>
                                        <td class="text-end pe-4">
                                            <button class="btn btn-danger btn-sm remove-item" data-id="<?php echo $id_menu; ?>"><i class="fas fa-trash"></i></button>
                                        </td>
                                    </tr>
                            <?php
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="row mt-4 justify-content-end">
                <div class="col-md-5">
                    <h4>Ringkasan Belanja</h4>
                    <div class="card">
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Total
                                    <span id="grand-total" class="fw-bold">Rp <?php echo number_format($total_belanja, 0, ',', '.'); ?></span>
                                </li>
                            </ul>
                            <div class="d-grid mt-3">
                                <a href="checkout.php" class="btn btn-primary">Lanjut ke Checkout</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        <?php else: ?>
            <div class="text-center py-5">
                <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                <h4>Keranjang Anda kosong</h4>
                <p>Yuk, mulai belanja dan nikmati menunya!</p>
                <a href="menu.php" class="btn btn-primary">Lihat Menu</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'footer.php'; ?>

<script>
$(document).ready(function() {
    function updateCart(productId, action) {
        $.ajax({
            url: 'update_cart_ajax.php',
            type: 'POST',
            data: {
                product_id: productId,
                action: action
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    if (response.cart_empty) {
                        location.reload(); // Reload page if cart becomes empty
                    } else {
                        // Update grand total and cart count
                        $('#grand-total').text(response.grand_total_formatted);
                        $('#cart-count').text(response.cart_count);

                        // If an item was removed, remove its row from the table
                        if (action === 'remove' || (action === 'decrease' && $('#row-' + productId).find('input').val() == 1)) {
                             $('#row-' + productId).fadeOut(300, function() { $(this).remove(); });
                        } else {
                            // Just update the quantity and subtotal for the specific row
                            // This part is complex, for now we will just reload for simplicity
                            location.reload(); 
                        }
                    }
                } else {
                    alert(response.message);
                }
            },
            error: function() {
                alert('Gagal terhubung ke server.');
            }
        });
    }

    $('.update-quantity').click(function() {
        var productId = $(this).data('id');
        var action = $(this).data('action');
        updateCart(productId, action);
    });

    $('.remove-item').click(function() {
        var productId = $(this).data('id');
        if(confirm('Yakin ingin menghapus item ini dari keranjang?')){
            updateCart(productId, 'remove');
        }
    });
});
</script>
