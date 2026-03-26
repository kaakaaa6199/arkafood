<?php
require_once __DIR__ . '/auth.php';
require_login();

$id = $_GET['id'] ?? null;
$token = $_GET['token'] ?? '';
if (!$id || !verify_csrf_token($token)) {
    die('Invalid request');
}

$stmt = $pdo->prepare('SELECT image FROM products WHERE id = :id');
$stmt->execute([':id'=>$id]);
$row = $stmt->fetch();
if ($row) {
    // try to unlink image file if in uploads
    if (!empty($row['image']) && strpos($row['image'],'uploads/') === 0) {
        $path = __DIR__ . '/../' . $row['image'];
        if (file_exists($path)) @unlink($path);
    }
    $del = $pdo->prepare('DELETE FROM products WHERE id = :id');
    $del->execute([':id'=>$id]);
}
header('Location: products.php');
exit;
