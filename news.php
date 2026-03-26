<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../includes/db.php';

$method = $_SERVER['REQUEST_METHOD'];
if ($method === 'GET') {
    $id = isset($_GET['id']) ? intval($_GET['id']) : null;
    $slug = isset($_GET['slug']) ? $_GET['slug'] : null;
    if ($id) {
        $stmt = $pdo->prepare('SELECT * FROM news WHERE id = :id LIMIT 1');
        $stmt->execute([':id'=>$id]);
        $row = $stmt->fetch();
        echo json_encode(['data'=>$row], JSON_UNESCAPED_UNICODE);
        exit;
    }
    if ($slug) {
        $stmt = $pdo->prepare('SELECT * FROM news WHERE slug = :s LIMIT 1');
        $stmt->execute([':s'=>$slug]);
        $row = $stmt->fetch();
        echo json_encode(['data'=>$row], JSON_UNESCAPED_UNICODE);
        exit;
    }
    $stmt = $pdo->query('SELECT id, title, slug, excerpt, image, published_at FROM news ORDER BY published_at DESC');
    $rows = $stmt->fetchAll();
    echo json_encode(['data'=>$rows], JSON_UNESCAPED_UNICODE);
    exit;
}

http_response_code(405);
echo json_encode(['error'=>'Method not allowed']);
