<?php
session_start();
require_once __DIR__ . '/includes/db.php';

$id = $_GET['id'] ?? null;

// Hapus dari Session
if ($id && isset($_SESSION['cart'][$id])) {
    unset($_SESSION['cart'][$id]);
}

// Hapus dari Database (Jika Login)
if ($id && isset($_SESSION['customer_id'])) {
    $stmt = $pdo->prepare("DELETE FROM cart WHERE customer_id = ? AND product_id = ?");
    $stmt->execute([$_SESSION['customer_id'], $id]);
}

header("Location: cart.php");
exit;
?>