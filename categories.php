<?php
require_once __DIR__ . '/auth.php';
require_login();

// Handle Tambah & Hapus Kategori
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_category'])) {
        $name = trim($_POST['name']);
        if(!empty($name)){
            // Cek duplikasi nama
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM categories WHERE name = ?");
            $stmt->execute([$name]);
            if($stmt->fetchColumn() == 0){
                $pdo->prepare("INSERT INTO categories (name) VALUES (?)")->execute([$name]);
            }
        }
    } 
    elseif (isset($_POST['delete_id'])) {
        $pdo->prepare("DELETE FROM categories WHERE id = ?")->execute([$_POST['delete_id']]);
    }
    header("Location: categories.php");
    exit;
}

// Ambil semua kategori
$categories = $pdo->query("SELECT * FROM categories ORDER BY name ASC")->fetchAll();
?>
<?php include __DIR__ . '/includes/header.php'; ?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Kelola Kategori Menu</h3>
        <a href="products.php" class="btn btn-outline-secondary">Kembali ke Produk</a>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card shadow-sm p-3 mb-3">
                <h5 class="card-title">Tambah Kategori Baru</h5>
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Nama Kategori</label>
                        <input type="text" name="name" class="form-control" placeholder="Contoh: Frozen Food" required>
                    </div>
                    <button type="submit" name="add_category" class="btn btn-primary w-100">
                        <i class="fas fa-plus-circle me-1"></i> Simpan
                    </button>
                </form>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow-sm p-3">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Nama Kategori</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($categories)): ?>
                            <tr><td colspan="2" class="text-center text-muted">Belum ada kategori.</td></tr>
                        <?php else: ?>
                            <?php foreach($categories as $c): ?>
                            <tr>
                                <td><span class="badge bg-info text-dark"><?= htmlspecialchars($c['name']) ?></span></td>
                                <td class="text-end">
                                    <form method="POST" onsubmit="return confirm('Yakin hapus kategori ini? Produk dengan kategori ini mungkin akan kehilangan labelnya.');" style="display:inline;">
                                        <input type="hidden" name="delete_id" value="<?= $c['id'] ?>">
                                        <button class="btn btn-sm btn-outline-danger" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>