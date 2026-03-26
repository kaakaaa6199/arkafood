<?php
require_once __DIR__ . '/auth.php';
require_login();

// Handle Tambah/Hapus Voucher
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_voucher'])) {
        $code = strtoupper($_POST['code']);
        $type = $_POST['type'];
        $value = $_POST['value'];
        $min = $_POST['min_purchase'];
        $exp = $_POST['expiry_date'];
        
        $sql = "INSERT INTO vouchers (code, type, value, min_purchase, expiry_date, is_active) VALUES (?, ?, ?, ?, ?, 1)";
        $pdo->prepare($sql)->execute([$code, $type, $value, $min, $exp]);
    } 
    elseif (isset($_POST['delete_id'])) {
        $pdo->prepare("DELETE FROM vouchers WHERE id = ?")->execute([$_POST['delete_id']]);
    }
    header("Location: vouchers.php");
    exit;
}

// Pastikan tabel vouchers ada
$pdo->exec("CREATE TABLE IF NOT EXISTS vouchers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(50) NOT NULL UNIQUE,
    type ENUM('percent','fixed') DEFAULT 'percent',
    value DECIMAL(10,2) NOT NULL,
    min_purchase DECIMAL(10,2) DEFAULT 0,
    expiry_date DATE,
    is_active TINYINT DEFAULT 1
)");

$vouchers = $pdo->query("SELECT * FROM vouchers ORDER BY id DESC")->fetchAll();
?>
<?php include __DIR__ . '/includes/header.php'; ?>

<div class="container py-4">
    <h3>Kelola Voucher Diskon</h3>
    
    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card p-3 mb-3">
                <h5>Buat Voucher Baru</h5>
                <form method="POST">
                    <div class="mb-3">
                        <label>Kode Voucher</label>
                        <input type="text" name="code" class="form-control" placeholder="Cth: DISKON50" required style="text-transform:uppercase;">
                    </div>
                    <div class="mb-3">
                        <label>Tipe Diskon</label>
                        <select name="type" class="form-select">
                            <option value="percent">Persen (%)</option>
                            <option value="fixed">Nominal (Rp)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Nilai Potongan</label>
                        <input type="number" name="value" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Min. Belanja</label>
                        <input type="number" name="min_purchase" class="form-control" value="0">
                    </div>
                    <div class="mb-3">
                        <label>Berlaku Sampai</label>
                        <input type="date" name="expiry_date" class="form-control" required>
                    </div>
                    <button type="submit" name="add_voucher" class="btn btn-primary w-100">Simpan</button>
                </form>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card p-3">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Nilai</th>
                            <th>Min. Blj</th>
                            <th>Exp</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($vouchers as $v): ?>
                        <tr>
                            <td><span class="badge bg-primary"><?= htmlspecialchars($v['code']) ?></span></td>
                            <td><?= $v['type']=='percent' ? intval($v['value']).'%' : 'Rp '.number_format($v['value']) ?></td>
                            <td>Rp <?= number_format($v['min_purchase']) ?></td>
                            <td><?= $v['expiry_date'] ?></td>
                            <td>
                                <form method="POST" onsubmit="return confirm('Hapus voucher?');">
                                    <input type="hidden" name="delete_id" value="<?= $v['id'] ?>">
                                    <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>