<?php
session_start();
require_once __DIR__ . '/includes/db.php'; // Panggil DB koneksi

// 1. Tangkap Data
$product_id = $_POST['product_id'];
$product_name = $_POST['product_name'];
$qty = 1;

// 2. Logic Session (Agar keranjang tetap jalan meski belum login)
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id] += $qty;
} else {
    $_SESSION['cart'][$product_id] = $qty;
}

// 3. LOGIC DATABASE (Simpan Permanen jika User Login)
if (isset($_SESSION['customer_id'])) {
    $user_id = $_SESSION['customer_id'];
    $current_qty = $_SESSION['cart'][$product_id];
    
    // Gunakan REPLACE INTO (Otomatis Insert atau Update)
    $stmt = $pdo->prepare("REPLACE INTO cart (customer_id, product_id, quantity) VALUES (?, ?, ?)");
    $stmt->execute([$user_id, $product_id, $current_qty]);
}

// 4. Set Pesan Popup
$_SESSION['flash_message'] = [
    'message' => "<strong>$product_name</strong> berhasil ditambahkan ke keranjang!"
];

// 5. Redirect
header("Location: " . $_SERVER['HTTP_REFERER']);
exit;
?>