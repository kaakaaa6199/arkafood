<?php include 'includes/header.php'; ?>
<?php require_once __DIR__ . '/includes/db.php'; ?>

    <!-- Main Content -->
    <section class="py-5">
        <div class="container">
            <!-- Back Button -->
            <div class="mb-4 fade-in">
                <a href="news.php" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left me-2"></i> Kembali ke Berita
                </a>
            </div>

            <?php
            $id = $_GET['id'] ?? null;
            $stmt = $pdo->prepare('SELECT * FROM news WHERE id = :id LIMIT 1');
            $stmt->execute([':id'=>$id]);
            $news = $stmt->fetch();
            $recent = $pdo->query('SELECT id, title FROM news ORDER BY published_at DESC LIMIT 5')->fetchAll();
            ?>

            <!-- News Detail Container -->
            <div class="news-detail-container fade-in" id="news-detail-container">
                <!-- Main Content -->
                <div class="news-content">
                    <?php if(!$news): ?>
                        <div class="alert alert-warning">Berita tidak ditemukan.</div>
                    <?php else: ?>
                    <!-- Gallery -->
                    <div class="news-detail-gallery">
                        <div class="news-gallery-track" id="news-gallery-track">
                            <div class="p-3"><img src="<?= htmlspecialchars((strpos($news['image'],'uploads/')===0? $base . '/' . $news['image'] : ($news['image']?:'assets/images/news/newsproduk.png'))) ?>" class="img-fluid" alt="<?= htmlspecialchars($news['title']) ?>"></div>
                        </div>
                    </div>

                    <!-- Article Title -->
                    <h1 class="news-title"><?= htmlspecialchars($news['title']) ?></h1>

                    <!-- Article Meta Information -->
                    <div class="news-meta mb-3">
                        <span>
                            <i class="fas fa-calendar"></i> 
                            <?= htmlspecialchars($news['published_at']) ?>
                        </span>
                    </div>

                    <!-- Article Content -->
                    <div class="news-content-text">
                        <?= nl2br(htmlspecialchars($news['content'])) ?>
                    </div>

                    <!-- Share Section -->
                    <div style="padding: 20px 0; border-top: 1px solid #eee; border-bottom: 1px solid #eee; margin-bottom: 30px;">
                        <h6 class="mb-3">Bagikan Berita Ini:</h6>
                        <div class="d-flex gap-2">
                            <a href="https://www.facebook.com/arkafood.official?locale=id_ID" target="_blank" class="btn btn-sm btn-outline-primary" title="Bagikan ke Facebook">
                                <i class="fab fa-facebook"></i> Facebook
                            </a>
                            <a href="https://wa.me/082116726900" class="btn btn-sm btn-outline-success" target="_blank" title="Bagikan ke WhatsApp">
                                <i class="fab fa-whatsapp"></i> WhatsApp
                            </a>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Sidebar - Recent News -->
                <aside class="news-sidebar">
                    <div class="sidebar-widget" id="news-sidebar">
                        <h6>Berita Terbaru</h6>
                        <ul class="list-unstyled">
                            <?php foreach($recent as $r): ?>
                                <li><a href="news-detail.php?id=<?= $r['id'] ?>"><?= htmlspecialchars($r['title']) ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </aside>
            </div>
        </div>
    </section>

<?php include 'includes/footer.php'; ?>
