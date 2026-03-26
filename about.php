<?php
require_once __DIR__ . '/auth.php';
require_login();

// Ensure settings table exists
$pdo->exec("CREATE TABLE IF NOT EXISTS settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    `key` VARCHAR(100) NOT NULL UNIQUE,
    `value` TEXT
)");

function get_setting($k) {
    global $pdo;
    $stmt = $pdo->prepare('SELECT value FROM settings WHERE `key` = :k LIMIT 1');
    $stmt->execute([':k'=>$k]);
    $r = $stmt->fetch();
    return $r ? $r['value'] : '';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $history = $_POST['history'] ?? '';
    $vision = $_POST['vision'] ?? '';
    $mission = $_POST['mission'] ?? '';
    $stmt = $pdo->prepare('INSERT INTO settings (`key`,`value`) VALUES (:k,:v) ON DUPLICATE KEY UPDATE `value` = :v2');
    $stmt->execute([':k'=>'history',':v'=>$history,':v2'=>$history]);
    $stmt->execute([':k'=>'vision',':v'=>$vision,':v2'=>$vision]);
    $stmt->execute([':k'=>'mission',':v'=>$mission,':v2'=>$mission]);
    $saved = true;
}

$history = get_setting('history');
$vision = get_setting('vision');
$mission = get_setting('mission');

?>
<?php include __DIR__ . '/includes/header.php'; ?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Kelola Tentang Kami</h3>
        <div>
            <a href="dashboard.php" class="btn btn-outline-secondary">Kembali</a>
        </div>
    </div>

    <?php if(!empty($saved)): ?><div class="alert alert-success">Perubahan disimpan.</div><?php endif; ?>

    <form method="post">
        <div class="mb-3">
            <label class="form-label">Sejarah</label>
            <textarea name="history" class="form-control" rows="6"><?= htmlspecialchars($history) ?></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Visi</label>
            <textarea name="vision" class="form-control" rows="3"><?= htmlspecialchars($vision) ?></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Misi</label>
            <textarea name="mission" class="form-control" rows="4"><?= htmlspecialchars($mission) ?></textarea>
        </div>
        <button class="btn btn-primary" type="submit">Simpan</button>
    </form>

    <hr>
    <h5 class="mt-4">Direksi</h5>
    <?php
    // Create directors table if not exists
    $pdo->exec("CREATE TABLE IF NOT EXISTS directors (id INT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(255), title VARCHAR(255), image VARCHAR(255), ord INT DEFAULT 0)");
    $directors = $pdo->query('SELECT * FROM directors ORDER BY ord ASC')->fetchAll();
    ?>
    <a href="director_edit.php" class="btn btn-sm btn-primary mb-2">Tambah Direktur</a>
    <table class="table table-sm">
        <thead><tr><th>#</th><th>Gambar</th><th>Nama</th><th>Title</th><th>Aksi</th></tr></thead>
        <tbody>
            <?php foreach($directors as $d): ?>
            <tr>
                <td><?= $d['id'] ?></td>
                <td><img src="<?= htmlspecialchars($d['image'] ?: 'assets/images/placeholder.png') ?>" style="max-width:80px"></td>
                <td><?= htmlspecialchars($d['name']) ?></td>
                <td><?= htmlspecialchars($d['title']) ?></td>
                <td>
                    <a href="director_edit.php?id=<?= $d['id'] ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                    <a href="director_delete.php?id=<?= $d['id'] ?>&token=<?= urlencode(generate_csrf_token()) ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus direktur?')">Hapus</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
