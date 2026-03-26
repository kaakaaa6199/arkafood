<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/includes/db.php';
include 'includes/header.php';
?>

<section class="hero d-flex align-items-center" style="min-height: 90vh;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 order-2 order-lg-1">
                <h1 class="display-4 fw-bold mb-4">Cemilan Ningrat Harga Merakyat.</h1>
                <p class="lead mb-5" style="opacity: 0.9;">Menghadirkan produk makanan berkualitas tinggi untuk memenuhi kebutuhan kuliner Anda dengan rasa yang tak terlupakan.</p>
                <a href="produk.php" class="btn btn-outline-light btn-lg px-5 rounded-pill fw-bold">Lihat Produk</a>
            </div>
            
            <div class="col-lg-6 order-1 order-lg-2 text-center hero-image-container">
                <img src="<?php echo $base; ?>/assets/images/logo.png" alt="Arka Food Logo" class="img-fluid" 
                    style="max-height: 450px; filter: drop-shadow(0 10px 20px rgba(0,0,0,0.3));">
            </div>
        </div>
    </div>
</section>

<section class="py-5 bg-white">
    <div class="container py-5">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0 text-center about-image-container">
                <img src="<?php echo $base; ?>/assets/images/logo.png" alt="Tentang Arka Food" class="img-fluid" 
                     style="width: auto; max-height: 300px; object-fit: contain; filter: invert(1) grayscale(100%) contrast(150%); opacity: 0.8;">
            </div>
            <div class="col-lg-6">
                <h2 class="fw-bold mb-4" style="color: var(--primary-color);">Tentang Arka Food</h2>
                <p class="text-muted mb-4" style="line-height: 1.8;">Arka Food adalah rumah bagi camilan lezat dan berkualitas yang dirancang untuk memuaskan hasrat ngemil Anda kapan saja. Kami menawarkan beragam pilihan rasa, dari gurih hingga manis, yang dibuat dengan bahan-bahan pilihan.</p>
                <a href="about.php" class="btn btn-primary px-4 rounded-pill">Pelajari Lebih Lanjut</a>
            </div>
        </div>
    </div>
</section>

<section class="py-5" style="background-color: #f8f9fa;">
    <div class="container py-5">
        <div class="text-center mb-5">
            <h2 class="fw-bold" style="color: var(--primary-color);">Produk Unggulan</h2>
            <p class="text-muted">Pilihan terbaik favorit pelanggan kami.</p>
        </div>
        
        <div class="row">
            <?php
            if(isset($pdo)) {
                $stmt = $pdo->query('SELECT * FROM products WHERE is_visible = 1 LIMIT 3');
                while ($row = $stmt->fetch()) {
                    $img_path = !empty($row['image']) ? $row['image'] : 'assets/images/no-image.png';
                    if (strpos($img_path, 'http') !== 0) $img_path = $base . '/' . ltrim($img_path, '/');
            ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm border-0 bg-white position-relative">
                    <div class="position-absolute top-0 start-0 bg-warning text-dark px-3 py-1 m-2 rounded-pill fw-bold small shadow-sm" style="z-index: 2; font-size: 0.75rem;">
                        <?= htmlspecialchars($row['category'] ?? 'Umum') ?>
                    </div>

                    <div style="height: 250px; overflow: hidden;" class="d-flex align-items-center justify-content-center p-3">
                        <img src="<?php echo htmlspecialchars($img_path); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($row['name']); ?>">
                    </div>
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title fw-bold text-dark mb-1"><?php echo htmlspecialchars($row['name']); ?></h5>
                        
                        <div class="text-muted small mb-3">
                            <i class="fas fa-weight-hanging me-1"></i> <?= htmlspecialchars($row['size'] ?? '-') ?> &bull; 
                            <i class="fas fa-cubes me-1"></i> Stok: <strong><?= $row['stock'] ?? 0 ?></strong>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-auto border-top pt-3">
                            <h5 class="fw-bold mb-0 text-primary">Rp <?php echo number_format($row['price'], 0, ',', '.'); ?></h5>
                            <form action="add_to_cart.php" method="POST">
                                <input type="hidden" name="product_id" value="<?= $row['id'] ?>">
                                <input type="hidden" name="product_name" value="<?= htmlspecialchars($row['name']) ?>">
                                
                                <?php if(($row['stock'] ?? 0) > 0): ?>
                                    <button type="submit" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                        <i class="fas fa-shopping-cart me-1"></i> Pesan
                                    </button>
                                <?php else: ?>
                                    <button type="button" class="btn btn-sm btn-secondary rounded-pill px-3" disabled>Habis</button>
                                <?php endif; ?>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <?php 
                } 
            }
            ?>
        </div>
        <div class="text-center mt-4">
            <a href="produk.php" class="btn btn-outline-dark px-5 rounded-pill">Lihat Semua Produk</a>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <h2 class="text-center mb-5 fw-bold" style="color: var(--primary-color);">Berita Terbaru</h2>
        <div class="row">
            <?php
            if(isset($pdo)) {
                $stmt = $pdo->query('SELECT id, title, slug, excerpt, image, published_at FROM news ORDER BY published_at DESC LIMIT 3');
                $news = $stmt->fetchAll();
                if (count($news) > 0):
                    foreach($news as $n):
                        $nimg = !empty($n['image']) ? $n['image'] : 'assets/images/news/newskk.png';
                        if (strpos($nimg, 'http') !== 0) $nimg = $base . '/' . ltrim($nimg, '/');
            ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm border-0 news-card">
                    <div class="news-card-image">
                        <img src="<?= htmlspecialchars($nimg) ?>" alt="<?= htmlspecialchars($n['title']) ?>">
                    </div>
                    <div class="card-body">
                        <h5 class="card-title fw-bold" style="color: var(--primary-color);"><?= htmlspecialchars($n['title']) ?></h5>
                        <p class="text-muted small"><?= date('d M Y', strtotime($n['published_at'])) ?></p>
                        <a href="news-detail.php?id=<?= $n['id'] ?>" class="text-decoration-none fw-bold">Baca Selengkapnya &rarr;</a>
                    </div>
                </div>
            </div>
            <?php endforeach; endif; } ?>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>