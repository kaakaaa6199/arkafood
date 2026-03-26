<?php include 'includes/header.php'; ?>
<?php require_once __DIR__ . '/includes/db.php'; ?>

    <!-- News Section -->
    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-5" style="color: var(--primary-color-2);">Berita & Informasi</h2>
            <?php
            $hero = $pdo->query('SELECT id, title, slug, excerpt, image, published_at FROM news ORDER BY published_at DESC LIMIT 1')->fetch();
            $stmt = $pdo->query('SELECT id, title, slug, excerpt, image, published_at FROM news ORDER BY published_at DESC LIMIT 4');
            $all = $stmt->fetchAll();
            ?>
            <?php if($hero): ?>
            <div class="row mb-5 fade-in">
                <div class="col-lg-8 mx-auto">
                    <div class="card featured-news-card">
                        <div class="featured-news-image">
                            <?php $himg = $hero['image'] ?: 'assets/images/news/newsproduk.png'; if(strpos($himg,'uploads/')!==0) $himg = $base . '/' . $himg; ?>
                            <img src="<?= htmlspecialchars($himg) ?>" alt="<?= htmlspecialchars($hero['title']) ?>">
                        </div>
                        <div class="card-body">
                            <h3 class="card-title"><?= htmlspecialchars($hero['title']) ?></h3>
                            <p class="text-muted"><i class="fas fa-calendar me-2"></i><?= htmlspecialchars($hero['published_at']) ?></p>
                            <p class="card-text"><?= htmlspecialchars(substr($hero['excerpt'],0,300)) ?></p>
                            <a href="news-detail.php?id=<?= $hero['id'] ?>" class="btn btn-primary">Baca Selengkapnya</a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            <div class="row">
                <?php foreach($all as $n): if($hero && $n['id']==$hero['id']) continue; $nimg = $n['image']?:'assets/images/news/newskk.png'; if(strpos($nimg,'uploads/')!==0) $nimg = $base . '/' . $nimg; ?>
                <div class="col-md-4 mb-4 fade-in">
                    <div class="card h-100 news-card">
                        <div class="news-card-image">
                            <img src="<?= htmlspecialchars($nimg) ?>" class="card-img-top" alt="<?= htmlspecialchars($n['title']) ?>">
                        </div>
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($n['title']) ?></h5>
                            <p class="text-muted"><i class="fas fa-calendar me-2"></i><?= htmlspecialchars($n['published_at']) ?></p>
                            <p class="card-text"><?= htmlspecialchars(substr($n['excerpt'],0,140)) ?><?= strlen($n['excerpt'])>140? '...':'' ?></p>
                            <a href="news-detail.php?id=<?= $n['id'] ?>" class="btn btn-outline-primary">Baca Selengkapnya</a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

<?php include 'includes/footer.php'; ?>
