<?php
require_once __DIR__ . '/auth.php';
require_login();

$id = $_GET['id'] ?? null;
$product = null;
if ($id) {
    $stmt = $pdo->prepare('SELECT * FROM products WHERE id = :id');
    $stmt->execute([':id' => $id]);
    $product = $stmt->fetch();
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $slug = trim($_POST['slug'] ?? '');
    $description = $_POST['description'] ?? null;
    $price = floatval($_POST['price'] ?? 0);
    $stock = intval($_POST['stock'] ?? 0);
    $category = $_POST['category'] ?? ''; // Ambil dari input dinamis
    $size = $_POST['size'] ?? '';
    $is_visible = isset($_POST['is_visible']) ? 1 : 0;

    if (empty($name) || empty($slug)) {
        $error = 'Nama dan slug harus diisi.';
    } else {
        // handle upload
        $imagePath = $product['image'] ?? '';
        if (!empty($_FILES['image']['name'])) {
            $uploaddir = __DIR__ . '/../uploads/products/';
            if (!is_dir($uploaddir)) mkdir($uploaddir, 0755, true);
            $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $filename = uniqid('p_', true) . '.' . $ext;
            $dest = $uploaddir . $filename;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $dest)) {
                $imagePath = 'uploads/products/' . $filename;
            }
        }

        if (!empty($id)) {
            $stmt = $pdo->prepare('UPDATE products SET name=:name, slug=:slug, description=:desc, price=:price, stock=:stock, category=:cat, size=:size, image=:img, is_visible=:vis WHERE id=:id');
            $stmt->execute([
                ':name'=>$name, ':slug'=>$slug, ':desc'=>$description, 
                ':price'=>$price, ':stock'=>$stock, ':cat'=>$category, ':size'=>$size,
                ':img'=>$imagePath, ':vis'=>$is_visible, ':id'=>$id
            ]);
        } else {
            $stmt = $pdo->prepare('INSERT INTO products (name, slug, description, price, stock, category, size, image, is_visible) VALUES (:name, :slug, :desc, :price, :stock, :cat, :size, :img, :vis)');
            $stmt->execute([
                ':name'=>$name, ':slug'=>$slug, ':desc'=>$description, 
                ':price'=>$price, ':stock'=>$stock, ':cat'=>$category, ':size'=>$size,
                ':img'=>$imagePath, ':vis'=>$is_visible
            ]);
        }
        header('Location: products.php');
        exit;
    }
}

?>
<?php include __DIR__ . '/includes/header.php'; ?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3><?= $product ? 'Edit Produk' : 'Tambah Produk' ?></h3>
        <div>
            <a href="products.php" class="btn btn-outline-secondary">Kembali</a>
        </div>
    </div>

    <?php if($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-8">
                <div class="card p-3 mb-3">
                    <h5 class="card-title">Informasi Dasar</h5>
                    <div class="mb-3">
                        <label class="form-label">Nama Produk</label>
                        <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($product['name'] ?? '') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Slug (URL)</label>
                        <input type="text" name="slug" class="form-control" value="<?= htmlspecialchars($product['slug'] ?? '') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="description" class="form-control" rows="5"><?= htmlspecialchars($product['description'] ?? '') ?></textarea>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card p-3 mb-3">
                    <h5 class="card-title">Detail & Stok</h5>
                    <div class="mb-3">
                        <label class="form-label">Harga (Rp)</label>
                        <input type="number" name="price" class="form-control" value="<?= htmlspecialchars($product['price'] ?? 0) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Stok</label>
                        <input type="number" name="stock" class="form-control" value="<?= htmlspecialchars($product['stock'] ?? 0) ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Kategori</label>
                        <select name="category" class="form-select" required>
                            <option value="">-- Pilih Kategori --</option>
                            <?php 
                            // Mengambil kategori dari database tabel 'categories'
                            $cats = $pdo->query("SELECT * FROM categories ORDER BY name ASC")->fetchAll();
                            foreach($cats as $c): 
                                $selected = ($product['category'] ?? '') == $c['name'] ? 'selected' : '';
                            ?>
                                <option value="<?= htmlspecialchars($c['name']) ?>" <?= $selected ?>>
                                    <?= htmlspecialchars($c['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="form-text small">
                            Kategori tidak ada? <a href="categories.php" target="_blank">Tambah kategori baru disini</a>.
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Ukuran / Berat</label>
                        <input type="text" name="size" class="form-control" value="<?= htmlspecialchars($product['size'] ?? '') ?>" placeholder="Cth: 250gr">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Gambar</label>
                        <?php if(!empty($product['image'])): ?>
                            <div class="mb-2"><img src="<?= htmlspecialchars($product['image']) ?>" style="max-width:100%"></div>
                        <?php endif; ?>
                        <input type="file" name="image" accept="image/*" class="form-control">
                    </div>
                    <div class="form-check mb-3">
                        <input type="checkbox" name="is_visible" class="form-check-input" id="is_visible" <?= (!isset($product['is_visible']) || $product['is_visible']) ? 'checked' : '' ?> >
                        <label class="form-check-label" for="is_visible">Tampilkan di website</label>
                    </div>
                    <button class="btn btn-primary w-100" type="submit">Simpan Produk</button>
                </div>
            </div>
        </div>
    </form>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>