<?php 
include 'includes/header.php'; 
require_once 'includes/db.php';

// Ambil parameter kategori (jika ada)
$cat = $_GET['category'] ?? '';

// Query Produk
$sql = "SELECT * FROM products WHERE is_visible = 1";
$params = [];

if ($cat) {
    $sql .= " AND category = ?";
    $params[] = $cat;
}

$sql .= " ORDER BY id DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();

// Ambil daftar kategori untuk tombol filter
$cats = $pdo->query("SELECT DISTINCT category FROM products WHERE is_visible = 1")->fetchAll(PDO::FETCH_COLUMN);
?>

<div class="container py-5">
    <div class="text-center mb-5">
        <h2 class="fw-bold">Katalog Menu</h2>
        <p class="text-muted">Pilih camilan favoritmu sekarang.</p>
        
        <div class="d-flex justify-content-center gap-2 flex-wrap mt-3">
            <a href="products.php" class="btn btn-sm <?= $cat == '' ? 'btn-dark' : 'btn-outline-dark' ?> rounded-pill px-3">Semua</a>
            <?php foreach($cats as $c): ?>
                <a href="products.php?category=<?= urlencode($c) ?>" class="btn btn-sm <?= $cat == $c ? 'btn-danger' : 'btn-outline-danger' ?> rounded-pill px-3">
                    <?= htmlspecialchars($c) ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="row g-4">
        <?php if(empty($products)): ?>
            <div class="col-12 text-center py-5">
                <div class="text-muted fst-italic">Belum ada produk di kategori ini.</div>
            </div>
        <?php else: ?>
            <?php foreach($products as $p): 
                $img = $p['image'] ?: 'assets/images/placeholder.png';
                if (strpos($img, 'uploads/') !== 0) $img = $base . '/' . $img;
                
                // LINK MENUJU DETAIL (product.php)
                $linkDetail = "product.php?slug=" . ($p['slug'] ?? $p['id']);
            ?>
            <div class="col-md-3 col-6">
                <div class="card h-100 border-0 shadow-sm product-card">
                    
                    <a href="<?= $linkDetail ?>" class="text-decoration-none">
                        <div style="height: 200px; overflow: hidden; display: flex; align-items: center; justify-content: center; background: #f8f9fa;">
                            <img src="<?= htmlspecialchars($img) ?>" alt="<?= htmlspecialchars($p['name']) ?>" style="max-height: 100%; max-width: 100%;">
                        </div>
                    </a>

                    <div class="card-body d-flex flex-column">
                        <a href="<?= $linkDetail ?>" class="text-decoration-none text-dark">
                            <h6 class="card-title fw-bold mb-1"><?= htmlspecialchars($p['name']) ?></h6>
                        </a>
                        
                        <div class="mb-2">
                            <span class="badge bg-secondary" style="font-size: 10px;"><?= htmlspecialchars($p['category']) ?></span>
                            <?php if($p['stock'] <= 5 && $p['stock'] > 0): ?>
                                <span class="badge bg-warning text-dark" style="font-size: 10px;">Sisa <?= $p['stock'] ?></span>
                            <?php endif; ?>
                        </div>

                        <p class="card-text text-primary fw-bold">Rp <?= number_format($p['price'], 0, ',', '.') ?></p>
                        
                        <div class="mt-auto pt-2">
                            <?php if($p['stock'] > 0): ?>
                                <form action="cart_add.php" method="POST" class="d-grid">
                                    <input type="hidden" name="product_id" value="<?= $p['id'] ?>">
                                    <input type="hidden" name="quantity" value="1">
                                    <input type="hidden" name="redirect" value="products"> <button type="submit" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-cart-plus me-1"></i> +Keranjang
                                    </button>
                                </form>
                            <?php else: ?>
                                <button class="btn btn-secondary btn-sm w-100" disabled>Stok Habis</button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>