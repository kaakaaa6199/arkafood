<?php
session_start();
require_once 'includes/db.php';

// Ambil parameter dari URL
$id = $_GET['id'] ?? null;
$change = $_GET['change'] ?? 0; // +1 atau -1
$remove = $_GET['remove'] ?? null; // Jika tombol hapus ditekan

if ($id && isset($_SESSION['cart'][$id])) {
    
    // KASUS 1: MENGHAPUS ITEM (Tombol Sampah)
    if ($remove) {
        unset($_SESSION['cart'][$id]);
    } 
    // KASUS 2: UBAH JUMLAH (+ / -)
    elseif ($change != 0) {
        // Cek stok dulu sebelum nambah
        $stmt = $pdo->prepare("SELECT stock FROM products WHERE id = ?");
        $stmt->execute([$id]);
        $product = $stmt->fetch();

        if ($product) {
            $newQty = $_SESSION['cart'][$id] + $change;

            // Jika jumlah jadi 0 atau kurang, hapus item
            if ($newQty <= 0) {
                unset($_SESSION['cart'][$id]);
            } 
            // Jika melebihi stok, set ke stok maks
            elseif ($newQty > $product['stock']) {
                $_SESSION['cart'][$id] = $product['stock'];
                echo "<script>alert('Stok maksimal tercapai!');</script>";
            } 
            // Update normal
            else {
                $_SESSION['cart'][$id] = $newQty;
            }
        }
    }
}

// Redirect kembali ke keranjang
header("Location: cart.php");
exit;
?>