<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../includes/db.php';

$method = $_SERVER['REQUEST_METHOD'];
if ($method === 'POST') {
    // Basic input validation and sanitization
    $data = json_decode(file_get_contents('php://input'), true);
    if (!$data) $data = $_POST;
    $product_id = isset($data['product_id']) ? intval($data['product_id']) : null;
    $customer_name = trim($data['customer_name'] ?? '');
    $phone = trim($data['phone'] ?? '');
    $address = trim($data['address'] ?? '');
    $quantity = isset($data['quantity']) ? intval($data['quantity']) : 1;

    if (empty($customer_name) || empty($phone)) {
        http_response_code(422);
        echo json_encode(['error'=>'Nama dan nomor telepon harus diisi']);
        exit;
    }

    // calculate total price if product_id provided
    $total_price = 0;
    if ($product_id) {
        $stmt = $pdo->prepare('SELECT price FROM products WHERE id = :id LIMIT 1');
        $stmt->execute([':id'=>$product_id]);
        $p = $stmt->fetch();
        if ($p) $total_price = $p['price'] * $quantity;
    }

    $stmt = $pdo->prepare('INSERT INTO orders (product_id, customer_name, phone, address, quantity, total_price) VALUES (:pid,:name,:phone,:addr,:qty,:total)');
    $stmt->execute([':pid'=>$product_id,':name'=>$customer_name,':phone'=>$phone,':addr'=>$address,':qty'=>$quantity,':total'=>$total_price]);
    $id = $pdo->lastInsertId();
    echo json_encode(['success'=>true,'order_id'=>$id]);
    exit;
}

if ($method === 'GET') {
    // list orders (should be admin-protected in production)
    $stmt = $pdo->query('SELECT * FROM orders ORDER BY created_at DESC LIMIT 100');
    $rows = $stmt->fetchAll();
    echo json_encode(['data'=>$rows], JSON_UNESCAPED_UNICODE);
    exit;
}

http_response_code(405);
echo json_encode(['error'=>'Method not allowed']);
