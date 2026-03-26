<?php include 'includes/header.php'; ?>
<?php require_once __DIR__ . '/includes/db.php'; ?>

<style>
    /* Styling Galeri & Input Jumlah */
    .product-gallery { position: relative; overflow: hidden; border-radius: 12px; border: 1px solid #eee; }
    .gallery-item img { width:100%; height:420px; object-fit:contain; background:#fff; }
    
    /* Input Group Custom */
    .qty-input-group { width: 140px; }
    .qty-input-group input { font-weight: bold; border-left: 0; border-right: 0; }
    .qty-btn { border-color: #ced4da; }
    
    @media (max-width:576px){ .gallery-item img{ height:300px } }
</style>

<?php
$slug = $_GET['slug'] ?? null;
$id = $_GET['id'] ?? null;

if ($slug) {
    $stmt = $pdo->prepare('SELECT * FROM products WHERE slug = :s LIMIT 1');
    $stmt->execute([':s'=>$slug]);
    $product = $stmt->fetch();
} elseif ($id) {
    $stmt = $pdo->prepare('SELECT * FROM products WHERE id = :id LIMIT 1');
    $stmt->execute([':id'=>$id]);
    $product = $stmt->fetch();
} else {
    $product = null;
}
?>

<main class="py-5 bg-light">
    <div class="container">
        <?php if(!$product): ?>
            <div class="alert alert-warning text-center py-5">
                <h3>Produk tidak ditemukan</h3>
                <a href="products.php" class="btn btn-primary mt-3">Lihat Katalog Lainnya</a>
            </div>
        <?php else:
            $img = $product['image'] ?: 'assets/images/produk/placeholder.png';
            if (strpos($img,'uploads/') !== 0) $img = $base . '/' . $img;
        ?>
        
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Beranda</a></li>
                <li class="breadcrumb-item"><a href="products.php">Produk</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($product['name']) ?></li>
            </ol>
        </nav>

        <div class="card border-0 shadow-sm p-3">
            <div class="row g-4">
                <div class="col-lg-5">
                    <div class="product-gallery">
                        <div class="gallery-item">
                            <img src="<?= htmlspecialchars($img) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                        </div>
                    </div>
                </div>

                <div class="col-lg-7">
                    <h2 class="fw-bold mb-2"><?= htmlspecialchars($product['name']) ?></h2>
                    <div class="mb-3">
                        <span class="badge bg-info text-dark me-2"><?= htmlspecialchars($product['category'] ?? 'Umum') ?></span>
                        <?php if($product['stock'] > 0): ?>
                            <span class="badge bg-success"><i class="fas fa-check me-1"></i> Stok Tersedia: <?= $product['stock'] ?></span>
                        <?php else: ?>
                            <span class="badge bg-danger">Stok Habis</span>
                        <?php endif; ?>
                    </div>

                    <h3 class="text-primary fw-bold mb-4">Rp <?= number_format($product['price'],0,',','.') ?></h3>

                    <p class="text-muted" style="white-space: pre-line;"><?= htmlspecialchars($product['description']) ?></p>

                    <hr class="my-4">

                    <?php if($product['stock'] > 0): ?>
                    <form action="cart_add.php" method="POST">
                        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                        <input type="hidden" name="redirect" value="product"> 
                        <input type="hidden" name="slug" value="<?= $product['slug'] ?>"> 

                        <div class="d-flex align-items-end gap-3 mb-4">
                            <div>
                                <label class="fw-bold mb-1 small text-muted">Jumlah</label>
                                <div class="input-group qty-input-group">
                                    <button type="button" class="btn btn-outline-secondary qty-btn" onclick="updateQty(-1)">-</button>
                                    <input type="number" name="quantity" id="qtyInput" class="form-control text-center" value="1" min="1" max="<?= $product['stock'] ?>" readonly>
                                    <button type="button" class="btn btn-outline-secondary qty-btn" onclick="updateQty(1)">+</button>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold">
                                    <i class="fas fa-shopping-cart me-2"></i> + Keranjang
                                </button>
                            </div>
                        </div>
                    </form>
                    <?php else: ?>
                        <div class="alert alert-danger py-2"><i class="fas fa-times-circle me-2"></i> Maaf, produk ini sedang habis.</div>
                    <?php endif; ?>
                    
                    <a href="products.php" class="btn btn-link text-decoration-none px-0"><i class="fas fa-arrow-left me-1"></i> Kembali ke Katalog</a>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</main>

<script>
function updateQty(change) {
    const input = document.getElementById('qtyInput');
    const maxStock = parseInt(input.getAttribute('max'));
    let newVal = parseInt(input.value) + change;
    
    if (newVal >= 1 && newVal <= maxStock) {
        input.value = newVal;
    }
}
</script>

<?php include 'includes/footer.php'; ?>