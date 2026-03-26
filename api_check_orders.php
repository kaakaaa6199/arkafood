<?php
// Matikan error reporting agar tidak merusak format JSON
error_reporting(0);
ini_set('display_errors', 0);

require_once __DIR__ . '/../includes/db.php'; 

header('Content-Type: application/json');

try {
    // Ambil ID order terakhir dan Totalnya
    $stmt = $pdo->query("SELECT id, customer_name, total_price FROM orders ORDER BY id DESC LIMIT 1");
    $latestOrder = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($latestOrder) {
        echo json_encode([
            'status' => 'success',
            'latest_id' => (int)$latestOrder['id'],
            'customer_name' => $latestOrder['customer_name'],
            'total' => number_format($latestOrder['total_price'], 0, ',', '.')
        ]);
    } else {
        echo json_encode(['status' => 'empty', 'latest_id' => 0]);
    }
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'msg' => $e->getMessage()]);
}
?>