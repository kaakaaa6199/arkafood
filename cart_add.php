<?php
session_start();
require_once 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = $_POST['product_id'];
    $quantity   = intval($_POST['quantity']); // Ambil jumlah dari input
    $redirect   = $_POST['redirect'] ?? 'cart';

    if ($quantity < 1) $quantity = 1;

    // Cek Stok di Database
    // PENTING: Kita tambahkan pengambilan kolom 'slug' untuk keperluan redirect balik ke detail produk
    $stmt = $pdo->prepare("SELECT stock, name, slug FROM products WHERE id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch();

    if ($product && $product['stock'] >= $quantity) {
        
        // Inisialisasi Keranjang jika belum ada
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // Jika produk sudah ada di cart, tambahkan jumlahnya
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id] += $quantity;
        } else {
            $_SESSION['cart'][$product_id] = $quantity;
        }

        // Cek lagi apakah total di cart melebihi stok (validasi ganda)
        if ($_SESSION['cart'][$product_id] > $product['stock']) {
            $_SESSION['cart'][$product_id] = $product['stock']; // Mentok di stok max
        }

        // --- LOGIKA REDIRECT (Diperbarui) ---
        
        if ($redirect == 'product') {
            // Jika request dari halaman Detail Produk, balik lagi ke halaman itu
            $target = 'product.php?slug=' . $product['slug'];
            // Gunakan JavaScript agar bisa menampilkan alert sukses sebelum pindah
            echo "<script>alert('Berhasil ditambahkan ke keranjang!'); window.location.href='$target';</script>";
            exit;
        } 
        elseif ($redirect == 'products' || $redirect == 'index') {
            // Jika request dari halaman Katalog Utama (products.php)
            header("Location: products.php");
            exit;
        } 
        else {
            // Default: Masuk ke halaman Keranjang
            header("Location: cart.php");
            exit;
        }

    } else {
        // Stok habis/kurang
        echo "<script>alert('Maaf, stok tidak mencukupi!'); window.history.back();</script>";
        exit;
    }
}
?>