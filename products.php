<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../includes/db.php';

$method = $_SERVER['REQUEST_METHOD'];
if ($method === 'GET') {
    // GET /api/products.php or /api/products.php?id=1 or /api/products.php?slug=slug
    $id = isset($_GET['id']) ? intval($_GET['id']) : null;
    $slug = isset($_GET['slug']) ? $_GET['slug'] : null;
    if ($id) {
        $stmt = $pdo->prepare('SELECT * FROM products WHERE id = :id LIMIT 1');
        $stmt->execute([':id'=>$id]);
        $row = $stmt->fetch();
        echo json_encode(['data'=>$row], JSON_UNESCAPED_UNICODE);
        exit;
    }
    if ($slug) {
        $stmt = $pdo->prepare('SELECT * FROM products WHERE slug = :s LIMIT 1');
        $stmt->execute([':s'=>$slug]);
        $row = $stmt->fetch();
        echo json_encode(['data'=>$row], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $stmt = $pdo->query('SELECT id, name, slug, price, image, description FROM products WHERE is_visible IS NULL OR is_visible = 1 ORDER BY created_at DESC');
    $rows = $stmt->fetchAll();
    echo json_encode(['data'=>$rows], JSON_UNESCAPED_UNICODE);
    exit;
}

http_response_code(405);
echo json_encode(['error'=>'Method not allowed']);
