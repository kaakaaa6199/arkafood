<?php
require 'includes/db.php';
$stmt = $pdo->query("SELECT COUNT(*) as c FROM products");
$row = $stmt->fetch();
echo 'Products count: ' . ($row['c'] ?? '0');