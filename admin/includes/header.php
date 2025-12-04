<?php
session_start();
// Jika admin belum login, tendang ke halaman login
if (!isset($_SESSION['id_admin'])) {
    header("Location: login.php");
    exit();
}
include '../config.php'; // Lokasi config.php satu level di atas
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Umah Santap</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            width: 250px;
            padding: 20px;
            background-color: #343a40;
            color: white;
        }
        .sidebar .nav-link {
            color: #c2c7d0;
            padding: 10px 15px;
            border-radius: .25rem;
        }
        .sidebar .nav-link.active,
        .sidebar .nav-link:hover {
            color: #fff;
            background-color: #495057;
        }
        .sidebar .nav-link .fa {
            width: 20px;
            text-align: center;
        }
        .content {
            margin-left: 250px;
            padding: 2rem;
        }
    </style>
</head>
<body>

<div class="sidebar">
    <h4 class="text-white mb-4">Umah Santap</h4>
    <ul class="nav flex-column">
        <li class="nav-item mb-2">
            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>" href="dashboard.php"><i class="fa fa-tachometer-alt me-2"></i>Dashboard</a>
        </li>
        <li class="nav-item mb-2">
            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'kelola_pesanan.php' ? 'active' : ''; ?>" href="kelola_pesanan.php"><i class="fa fa-receipt me-2"></i>Kelola Pesanan</a>
        </li>
        <li class="nav-item mb-2">
            <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'kelola_menu.php' ? 'active' : ''; ?>" href="kelola_menu.php"><i class="fa fa-utensils me-2"></i>Kelola Menu</a>
        </li>
    </ul>
    <ul class="nav flex-column mt-auto">
        <li class="nav-item">
             <a class="nav-link" href="logout.php"><i class="fa fa-sign-out-alt me-2"></i>Logout</a>
        </li>
    </ul>
</div>

<main class="content">
