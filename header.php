<?php
// 1. START SESSION & PANGGIL DB (SOLUSI HALAMAN BLANK)
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/db.php'; // <--- INI KUNCINYA

// 2. HITUNG KERANJANG
$cart_count = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $qty) {
        $cart_count += $qty;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arka Food</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .badge-cart { font-size: 0.6rem; top: 0; right: 0; }
        .toast-container { z-index: 9999; }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark sticky-top" style="background-color: #113f67;">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <img src="assets/images/logo2.png" alt="Arka Food" height="40">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" href="about.php">Tentang Kami</a></li>
                    <li class="nav-item"><a class="nav-link" href="products.php">Produk</a></li>
                    <li class="nav-item"><a class="nav-link" href="contact.php">Kontak</a></li>
                </ul>
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item me-3 position-relative">
                        <a class="nav-link" href="cart.php">
                            <i class="fas fa-shopping-cart fa-lg"></i>
                            <?php if($cart_count > 0): ?>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger badge-cart">
                                <?= $cart_count ?>
                            </span>
                            <?php endif; ?>
                        </a>
                    </li>
                    
                    <?php if(isset($_SESSION['customer_id'])): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle btn btn-outline-light border-0 py-1" href="#" data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle me-1"></i> <?= htmlspecialchars(explode(' ', $_SESSION['customer_name'])[0]) ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="riwayat.php">Riwayat Pesanan</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="logout.php">Logout</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                        <li class="nav-item ms-2"><a class="btn btn-light text-primary btn-sm rounded-pill px-3" href="register.php">Daftar</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="toast-container position-fixed top-0 end-0 p-3" style="margin-top: 70px;">
        <?php if(isset($_SESSION['flash_message'])): ?>
        <div id="liveToast" class="toast show shadow border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header bg-success text-white">
                <strong class="me-auto"><i class="fas fa-check-circle me-2"></i>Berhasil</strong>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-body bg-white">
                <?= $_SESSION['flash_message']['message'] ?>
                <div class="mt-2 pt-2 border-top">
                    <a href="cart.php" class="btn btn-primary btn-sm w-100">Lanjut ke Pembayaran <i class="fas fa-arrow-right ms-1"></i></a>
                </div>
            </div>
        </div>
        <?php unset($_SESSION['flash_message']); ?>
        <script>
            setTimeout(() => {
                var toastEl = document.getElementById('liveToast');
                if(toastEl) { 
                    toastEl.classList.remove('show'); 
                    setTimeout(()=> toastEl.remove(), 500);
                }
            }, 5000);
        </script>
        <?php endif; ?>
    </div>