<?php
session_start();
require_once __DIR__ . '/includes/db.php';

if (!isset($_SESSION['customer_id'])) {
    $_SESSION['error'] = "Login dulu untuk pakai voucher.";
    header("Location: cart.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = strtoupper(trim($_POST['voucher_code']));
    $total_belanja = floatval($_POST['total_price'] ?? 0);
    $customer_id = $_SESSION['customer_id'];

    try {
        // 1. Cek Data Voucher
        $stmt = $pdo->prepare("SELECT * FROM vouchers WHERE code = ? AND is_active = 1");
        $stmt->execute([$code]);
        $voucher = $stmt->fetch();

        if (!$voucher) throw new Exception("Voucher tidak valid.");

        // 2. Cek Minimal Belanja
        if ($total_belanja < $voucher['min_purchase']) {
            throw new Exception("Min. belanja Rp " . number_format($voucher['min_purchase']));
        }

        // 3. CEK APAKAH SUDAH PERNAH DIPAKAI (Baru)
        $stmtCek = $pdo->prepare("SELECT COUNT(*) FROM voucher_usage WHERE voucher_code = ? AND customer_id = ?");
        $stmtCek->execute([$code, $customer_id]);
        if ($stmtCek->fetchColumn() > 0) {
            throw new Exception("Voucher ini sudah pernah Anda gunakan.");
        }

        // 4. Hitung Diskon
        $discount = 0;
        if ($voucher['type'] == 'percent') {
            $discount = $total_belanja * ($voucher['value'] / 100);
        } else {
            $discount = $voucher['value'];
        }

        // Batasi diskon agar tidak melebihi total (Mentok di Rp 0)
        if ($discount > $total_belanja) {
            $discount = $total_belanja;
        }

        // Simpan ke Session
        $_SESSION['voucher'] = [
            'code' => $code,
            'discount_amount' => $discount,
            'type' => $voucher['type'],
            'value' => $voucher['value']
        ];

        $_SESSION['success'] = "Voucher berhasil!";

    } catch (Exception $e) {
        unset($_SESSION['voucher']);
        $_SESSION['error'] = $e->getMessage();
    }
}

header("Location: cart.php");
exit;
?>