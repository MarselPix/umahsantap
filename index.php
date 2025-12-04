<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Umah Santap - Pesan Makanan Online</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <style>
        body {
            font-family: 'Open Sans', sans-serif;
            background-color: #FFF9E6; /* Cream */
        }
        .navbar, .footer {
            background-color: #2C3E50; /* Dark Grey */
            color: white;
        }
        .navbar-brand, .footer a {
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            color: #FF7F00; /* Orange */
        }
        .nav-link {
            color: white !important;
        }
        .hero-section {
            background: url('https://images.unsplash.com/photo-1504674900247-0877df9cc836?q=80&w=2070&auto=format&fit=crop') no-repeat center center;
            background-size: cover;
            color: white;
            padding: 100px 0;
            text-align: center;
            position: relative;
        }
        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.5);
        }
        .hero-section .container {
            position: relative;
            z-index: 2;
        }
        .hero-section h1 {
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            font-size: 3.5rem;
        }
        .btn-primary {
            background-color: #FF7F00;
            border-color: #FF7F00;
            font-family: 'Poppins', sans-serif;
            font-weight: 500;
        }
        .btn-primary:hover {
            background-color: #D35400;
            border-color: #D35400;
        }
        .section-title {
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            color: #2C3E50;
            text-align: center;
            margin-bottom: 40px;
        }
        .menu-card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        .menu-card:hover {
            transform: translateY(-10px);
        }
        .menu-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        .menu-card .card-body {
            background: white;
        }
        .card-title {
            font-family: 'Poppins', sans-serif;
            font-weight: 500;
            color: #2C3E50;
        }
        .card-price {
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            color: #FF7F00;
            font-size: 1.2rem;
        }
        .rating .fa-star {
            color: #FFC107;
        }
        .footer {
            padding: 40px 0;
        }
    </style>
</head>
<body>

    <?php include 'config.php'; ?>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand" href="#">Umah Santap</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="menu.php">Menu</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="cart.php">
                            <i class="fas fa-shopping-cart"></i>
                            <span class="badge bg-danger" id="cart-count">0</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <h1>Pesan Menu Andalan Kami Sekarang!</h1>
            <p class="lead">Cepat, Lezat, dan Higienis. Siap diantar ke depan pintumu.</p>
            <a href="menu.php" class="btn btn-primary btn-lg">PESAN SEKARANG</a>
        </div>
    </section>

    <!-- Menu Populer Section -->
    <section class="py-5">
        <div class="container">
            <h2 class="section-title">MENU POPULER</h2>
            <div class="row g-4">
                <?php
                $sql = "SELECT * FROM menu ORDER BY rating DESC LIMIT 4";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                ?>
                <div class="col-md-6 col-lg-3">
                    <div class="menu-card">
                        <img src="assets/img/<?php echo $row['gambar']; ?>" class="card-img-top" alt="<?php echo $row['nama_menu']; ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $row['nama_menu']; ?></h5>
                            <p class="card-price"><?php echo format_rupiah($row['harga']); ?></p>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="rating">
                                    <?php for($i=0; $i<floor($row['rating']); $i++) echo '<i class="fas fa-star"></i>'; ?>
                                    <?php if($row['rating'] - floor($row['rating']) > 0) echo '<i class="fas fa-star-half-alt"></i>'; ?>
                                </div>
                                <button class="btn btn-sm btn-primary">
                                    <i class="fas fa-cart-plus"></i> Tambah
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                    }
                } else {
                    echo "<p class='text-center'>Menu belum tersedia.</p>";
                }
                $conn->close();
                ?>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer text-white">
        <div class="container text-center">
            <p>&copy; 2025 Umah Santap. All Rights Reserved.</p>
            <p>Klewang, Joho, Kec. Bawang, Kab. Banjarnegara, Jawa Tengah 53471</p>
            <div>
                <a href="#" class="text-white me-2"><i class="fab fa-instagram"></i></a>
                <a href="#" class="text-white me-2"><i class="fab fa-facebook"></i></a>
                <a href="#" class="text-white"><i class="fab fa-whatsapp"></i></a>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>