<?php
require_once __DIR__ . '/auth.php';
require_login();

$id = $_GET['id'] ?? null;
$item = null;
if ($id) {
    $stmt = $pdo->prepare('SELECT * FROM news WHERE id = :id');
    $stmt->execute([':id'=>$id]);
    $item = $stmt->fetch();
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $slug = trim($_POST['slug'] ?? '');
    $excerpt = $_POST['excerpt'] ?? null;
    $content = $_POST['content'] ?? null;
    $published_at = $_POST['published_at'] ?? date('Y-m-d H:i:s');

    if (empty($title) || empty($slug)) {
        $error = 'Judul dan slug harus diisi.';
    } else {
        $imagePath = $item['image'] ?? '';
        if (!empty($_FILES['image']['name'])) {
            $uploaddir = __DIR__ . '/../uploads/news/';
            if (!is_dir($uploaddir)) mkdir($uploaddir, 0755, true);
            $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $filename = uniqid('n_', true) . '.' . $ext;
            $dest = $uploaddir . $filename;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $dest)) {
                $imagePath = 'uploads/news/' . $filename;
            }
        }

        if (!empty($id)) {
            $stmt = $pdo->prepare('UPDATE news SET title=:title, slug=:slug, excerpt=:excerpt, content=:content, image=:image, published_at=:pub WHERE id=:id');
            $stmt->execute([':title'=>$title,':slug'=>$slug,':excerpt'=>$excerpt,':content'=>$content,':image'=>$imagePath,':pub'=>$published_at,':id'=>$id]);
        } else {
            $stmt = $pdo->prepare('INSERT INTO news (title, slug, excerpt, content, image, published_at) VALUES (:title,:slug,:excerpt,:content,:image,:pub)');
            $stmt->execute([':title'=>$title,':slug'=>$slug,':excerpt'=>$excerpt,':content'=>$content,':image'=>$imagePath,':pub'=>$published_at]);
        }
        header('Location: news.php');
        exit;
    }
}

?>
<?php include __DIR__ . '/includes/header.php'; ?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3><?= $item ? 'Edit Berita' : 'Tambah Berita' ?></h3>
        <div>
            <a href="news.php" class="btn btn-outline-secondary">Kembali</a>
        </div>
    </div>

    <?php if($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>

    <form method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">Judul</label>
            <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($item['title'] ?? '') ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Slug</label>
            <input type="text" name="slug" class="form-control" value="<?= htmlspecialchars($item['slug'] ?? '') ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Excerpt</label>
            <textarea name="excerpt" class="form-control" rows="3"><?= htmlspecialchars($item['excerpt'] ?? '') ?></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Konten</label>
            <textarea name="content" class="form-control" rows="8"><?= htmlspecialchars($item['content'] ?? '') ?></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Gambar</label>
            <?php if(!empty($item['image'])): ?><div class="mb-2"><img src="<?= htmlspecialchars($item['image']) ?>" style="max-width:150px"></div><?php endif; ?>
            <input type="file" name="image" accept="image/*" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">Tanggal Publish</label>
            <input type="datetime-local" name="published_at" class="form-control" value="<?= !empty($item['published_at']) ? date('Y-m-d\TH:i', strtotime($item['published_at'])) : date('Y-m-d\TH:i') ?>">
        </div>
        <button class="btn btn-primary" type="submit">Simpan</button>
    </form>

</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
