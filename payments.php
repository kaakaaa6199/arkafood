<?php
require_once __DIR__ . '/auth.php';
require_login();

// 1. HANDLE POST REQUEST (TAMBAH, EDIT, HAPUS)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // --> LOGIKA TAMBAH BARU
    if (isset($_POST['add_payment'])) {
        $name = trim($_POST['name']);
        $number = trim($_POST['number']);
        $desc = trim($_POST['description']);
        
        if(!empty($name) && !empty($number)){
            $stmt = $pdo->prepare("INSERT INTO payment_methods (name, number, description, is_active) VALUES (?, ?, ?, 1)");
            $stmt->execute([$name, $number, $desc]);
        }
        header("Location: payments.php"); // Refresh agar form bersih
        exit;
    } 
    
    // --> LOGIKA UPDATE (EDIT)
    elseif (isset($_POST['update_payment'])) {
        $id = $_POST['id'];
        $name = trim($_POST['name']);
        $number = trim($_POST['number']);
        $desc = trim($_POST['description']);
        
        $stmt = $pdo->prepare("UPDATE payment_methods SET name=?, number=?, description=? WHERE id=?");
        $stmt->execute([$name, $number, $desc, $id]);
        
        header("Location: payments.php");
        exit;
    }

    // --> LOGIKA HAPUS
    elseif (isset($_POST['delete_id'])) {
        $pdo->prepare("DELETE FROM payment_methods WHERE id = ?")->execute([$_POST['delete_id']]);
        header("Location: payments.php");
        exit;
    }
}

// 2. AMBIL DATA UNTUK DIEDIT (JIKA ADA PARAMETER ?edit=ID)
$editData = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM payment_methods WHERE id = ?");
    $stmt->execute([$_GET['edit']]);
    $editData = $stmt->fetch();
}

// 3. AMBIL SEMUA DATA UNTUK DITAMPILKAN DI TABEL
$payments = $pdo->query("SELECT * FROM payment_methods ORDER BY id DESC")->fetchAll();
?>

<?php include __DIR__ . '/includes/header.php'; ?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Kelola Metode Pembayaran</h3>
        <a href="dashboard.php" class="btn btn-outline-secondary">Kembali ke Dashboard</a>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card shadow-sm p-3 mb-3 bg-light">
                <h5 class="card-title text-primary">
                    <?= $editData ? '<i class="fas fa-edit"></i> Edit Metode' : '<i class="fas fa-plus-circle"></i> Tambah Metode' ?>
                </h5>
                
                <form method="POST">
                    <?php if($editData): ?>
                        <input type="hidden" name="id" value="<?= $editData['id'] ?>">
                    <?php endif; ?>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Bank / E-Wallet</label>
                        <input type="text" name="name" class="form-control" 
                               value="<?= htmlspecialchars($editData['name'] ?? '') ?>" 
                               placeholder="Cth: Bank BCA" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nomor Rekening / HP</label>
                        <input type="text" name="number" class="form-control" 
                               value="<?= htmlspecialchars($editData['number'] ?? '') ?>" 
                               placeholder="Cth: 1234567890" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Keterangan (Atas Nama)</label>
                        <input type="text" name="description" class="form-control" 
                               value="<?= htmlspecialchars($editData['description'] ?? '') ?>" 
                               placeholder="Cth: a.n Arka Food">
                    </div>

                    <div class="d-grid gap-2">
                        <?php if($editData): ?>
                            <button type="submit" name="update_payment" class="btn btn-success">Simpan Perubahan</button>
                            <a href="payments.php" class="btn btn-outline-secondary">Batal Edit</a>
                        <?php else: ?>
                            <button type="submit" name="add_payment" class="btn btn-primary">Simpan Metode Baru</button>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow-sm p-3">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>Metode</th>
                                <th>Nomor</th>
                                <th>Info</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(empty($payments)): ?>
                                <tr><td colspan="4" class="text-center py-3">Belum ada metode pembayaran.</td></tr>
                            <?php else: ?>
                                <?php foreach($payments as $p): ?>
                                <tr class="<?= ($editData && $editData['id'] == $p['id']) ? 'table-warning' : '' ?>">
                                    <td class="fw-bold"><?= htmlspecialchars($p['name']) ?></td>
                                    <td class="font-monospace text-primary"><?= htmlspecialchars($p['number']) ?></td>
                                    <td class="small text-muted"><?= htmlspecialchars($p['description']) ?></td>
                                    <td class="text-end">
                                        <a href="payments.php?edit=<?= $p['id'] ?>" class="btn btn-sm btn-outline-primary" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        
                                        <form method="POST" onsubmit="return confirm('Hapus metode pembayaran ini?');" style="display:inline;">
                                            <input type="hidden" name="delete_id" value="<?= $p['id'] ?>">
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
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>