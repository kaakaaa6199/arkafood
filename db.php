<?php
// FILE: includes/db.php

$host = 'localhost';
$db   = 'arkafood';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

// --- DEFINISI BASE URL (PENTING UNTUK MENGATASI ERROR BLANK) ---
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
$host_server = $_SERVER['HTTP_HOST'];
$path = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
$base = $protocol . "://" . $host_server . $path;

// Bersihkan path jika dijalankan dari dalam subfolder includes
$base = str_replace('/includes', '', $base);
$base = str_replace('/admin', '', $base);
// -----------------------------------------------------------

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    die("Koneksi Database Gagal: " . $e->getMessage());
}
?>