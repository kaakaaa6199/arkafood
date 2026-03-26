<?php
session_start();
// Hapus data voucher dari session
unset($_SESSION['voucher']);
unset($_SESSION['success']);
unset($_SESSION['error']);

// Kembali ke keranjang
header("Location: cart.php");
exit;
?>