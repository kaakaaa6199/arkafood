<?php
require_once __DIR__ . '/auth.php';
require_login();

$id = $_GET['id'] ?? null;
$dir = null;
if ($id) {
    $stmt = $pdo->prepare('SELECT * FROM directors WHERE id = :id');
    $stmt->execute([':id'=>$id]);
    $dir = $stmt->fetch();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $title = $_POST['title'] ?? '';
    $ord = intval($_POST['ord'] ?? 0);
    $imagePath = $dir['image'] ?? '';
    if (!empty($_FILES['image']['name'])) {
        $uploaddir = __DIR__ . '/../uploads/directors/';
        if (!is_dir($uploaddir)) mkdir($uploaddir, 0755, true);
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $filename = uniqid('d_', true) . '.' . $ext;
        $dest = $uploaddir . $filename;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $dest)) {
            $imagePath = 'uploads/directors/' . $filename;
        }
    }
    if ($id) {
        $stmt = $pdo->prepare('UPDATE directors SET name=:name, title=:title, image=:img, ord=:ord WHERE id=:id');
        $stmt->execute([':name'=>$name,':title'=>$title,':img'=>$imagePath,':ord'=>$ord,':id'=>$id]);
    } else {
        $stmt = $pdo->prepare('INSERT INTO directors (name,title,image,ord) VALUES (:name,:title,:img,:ord)');
        $stmt->execute([':name'=>$name,':title'=>$title,':img'=>$imagePath,':ord'=>$ord]);
    }
    header('Location: about.php');
    exit;
}

?>
<?php include __DIR__ . '/includes/header.php'; ?>

<div class="container py-4">
    <h3><?= $dir ? 'Edit Direktur' : 'Tambah Direktur' ?></h3>
    <form method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">Nama</label>
            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($dir['name'] ?? '') ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Jabatan</label>
            <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($dir['title'] ?? '') ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Urutan</label>
            <input type="number" name="ord" class="form-control" value="<?= htmlspecialchars($dir['ord'] ?? 0) ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Gambar</label>
            <?php if(!empty($dir['image'])): ?><div class="mb-2"><img src="<?= htmlspecialchars($dir['image']) ?>" style="max-width:120px"></div><?php endif; ?>
            <input type="file" name="image" accept="image/*" class="form-control">
        </div>
        <button class="btn btn-primary" type="submit">Simpan</button>
        <a href="about.php" class="btn btn-outline-secondary">Batal</a>
    </form>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
