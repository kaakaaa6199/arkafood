<?php
session_start();
require_once __DIR__ . '/includes/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_SESSION['cart']) || empty($_SESSION['customer_id'])) {
    header("Location: index.php");
    exit;
}

try {
    $pdo->beginTransaction();

    $user_id = $_SESSION['customer_id'];
    $payment_id = $_POST['payment_method_id'];
    
    // Validasi Alamat
    $address = trim($_POST['delivery_address']); 
    if (empty($address) || strlen($address) < 5) {
        throw new Exception("Alamat pengiriman wajib diisi dengan jelas.");
    }

    // Update Alamat User
    $pdo->prepare("UPDATE customers SET address = ? WHERE id = ?")->execute([$address, $user_id]);

    // Data Tambahan
    $data = $_SESSION['checkout_data'] ?? [];
    $name = $data['name'] ?? $_SESSION['customer_name'];
    $phone = $data['phone'] ?? $_SESSION['customer_phone'];
    $note = $data['note'] ?? '';

    // Data Voucher
    $voucher_code = null;
    $discount_amount = 0;
    if (isset($_SESSION['voucher'])) {
        $voucher_code = $_SESSION['voucher']['code'];
        $discount_amount = $_SESSION['voucher']['discount_amount'];
    }

    $courier = "J&T Express";
    $tracking_number = "JP" . rand(1000000000, 9999999999); 

    // Insert Order Header
    $stmt = $pdo->prepare("INSERT INTO orders (customer_id, customer_name, phone, address, total_price, payment_method_id, voucher_code, discount_amount, courier, tracking_number, status, created_at) VALUES (?, ?, ?, ?, 0, ?, ?, ?, ?, ?, 'pending', NOW())");
    $stmt->execute([$user_id, $name, $phone, $address . ($note ? " (Catatan: $note)" : ""), $payment_id, $voucher_code, $discount_amount, $courier, $tracking_number]); 
    $order_id = $pdo->lastInsertId();

    // --- AMBIL SETTING DISKON DARI DATABASE ---
    $sets = $pdo->query("SELECT * FROM settings")->fetchAll(PDO::FETCH_KEY_PAIR);
    $qty1 = isset($sets['reseller_qty_1']) ? (int)$sets['reseller_qty_1'] : 10;
    $disc1 = isset($sets['reseller_disc_1']) ? (int)$sets['reseller_disc_1'] : 0;
    $qty2 = isset($sets['reseller_qty_2']) ? (int)$sets['reseller_qty_2'] : 20;
    $disc2 = isset($sets['reseller_disc_2']) ? (int)$sets['reseller_disc_2'] : 0;

    // Proses Item
    $subtotal_produk = 0;
    $ids = array_keys($_SESSION['cart']);
    $in  = str_repeat('?,', count($ids) - 1) . '?';
    
    $stmtProducts = $pdo->prepare("SELECT id, name, price, stock FROM products WHERE id IN ($in)");
    $stmtProducts->execute($ids);
    $products = $stmtProducts->fetchAll();

    foreach ($products as $p) {
        $qty = $_SESSION['cart'][$p['id']];
        
        if ($p['stock'] < $qty) {
            throw new Exception("Stok produk '{$p['name']}' tidak mencukupi (Sisa: {$p['stock']}).");
        }

        // Hitung Harga Final per Item
        $final_price = $p['price'];
        if ($qty >= $qty2 && $disc2 > 0) {
            $final_price = $p['price'] - $disc2;
        } elseif ($qty >= $qty1 && $disc1 > 0) {
            $final_price = $p['price'] - $disc1;
        }

        $subtotal_item = $final_price * $qty;
        $subtotal_produk += $subtotal_item;
        
        // Simpan Item
        $stmtItem = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price, subtotal) VALUES (?, ?, ?, ?, ?)");
        $stmtItem->execute([$order_id, $p['id'], $qty, $final_price, $subtotal_item]);

        // Potong Stok
        $stmtUpdateStock = $pdo->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");
        $stmtUpdateStock->execute([$qty, $p['id']]);
    }

    // Update Total Akhir
    $final_total = $subtotal_produk - $discount_amount;
    if ($final_total < 0) $final_total = 0;

    $pdo->prepare("UPDATE orders SET total_price = ? WHERE id = ?")->execute([$final_total, $order_id]);

    // Catat Penggunaan Voucher
    if ($voucher_code) {
        $stmtUsage = $pdo->prepare("INSERT INTO voucher_usage (voucher_code, customer_id) VALUES (?, ?)");
        $stmtUsage->execute([$voucher_code, $user_id]);
    }

    // Bersihkan Session
    $pdo->prepare("DELETE FROM cart WHERE customer_id = ?")->execute([$user_id]);
    unset($_SESSION['cart']);
    unset($_SESSION['checkout_data']);
    unset($_SESSION['voucher']); 

    $pdo->commit();

    header("Location: riwayat.php?new_order=" . $order_id);
    exit;

} catch (Exception $e) {
    $pdo->rollBack();
    die("<div style='padding:20px; text-align:center;'><h3>Gagal Memproses Pesanan</h3><p>".$e->getMessage()."</p><a href='cart.php'>Kembali ke Keranjang</a></div>");
}
?>