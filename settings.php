<?php
require_once __DIR__ . '/auth.php';
require_login();

// Handle Update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        foreach ($_POST as $key => $val) {
            // Validasi nama key agar aman
            if (in_array($key, ['reseller_qty_1', 'reseller_disc_1', 'reseller_qty_2', 'reseller_disc_2'])) {
                $stmt = $pdo->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = ?");
                $stmt->execute([$key, $val, $val]);
            }
        }
        echo "<script>alert('Pengaturan harga berhasil disimpan!'); window.location='settings.php';</script>";
    } catch (Exception $e) {
        echo "<script>alert('Gagal menyimpan: " . $e->getMessage() . "');</script>";
    }
}

// Ambil Data Saat Ini
$settings = $pdo->query("SELECT * FROM settings")->fetchAll(PDO::FETCH_KEY_PAIR);

// Helper function biar ga error kalo kosong
function getSet($key, $data) {
    return isset($data[$key]) ? htmlspecialchars($data[$key]) : '0';
}
?>
<?php include __DIR__ . '/includes/header.php'; ?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white fw-bold">
                    <i class="fas fa-tags me-2"></i> Pengaturan Harga Reseller / Grosir
                </div>
                <div class="card-body p-4">
                    <form method="POST">
                        <div class="alert alert-info border-0 d-flex align-items-center mb-4">
                            <i class="fas fa-info-circle fa-2x me-3"></i>
                            <div>
                                <strong>Cara Kerja Sistem:</strong><br>
                                Sistem akan otomatis memotong harga saat pembeli memasukkan jumlah tertentu ke keranjang.
                            </div>
                        </div>

                        <div class="card mb-4 border-info">
                            <div class="card-header bg-info text-dark fw-bold">Level 1 (Reseller Pemula)</div>
                            <div class="card-body bg-light">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">Minimal Beli (Pcs)</label>
                                        <input type="number" name="reseller_qty_1" class="form-control" value="<?= getSet('reseller_qty_1', $settings) ?>" required>
                                        <small class="text-muted">Misal: 10 bungkus</small>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">Diskon Per Pcs (Rp)</label>
                                        <div class="input-group">
                                            <span class="input-group-text">Rp</span>
                                            <input type="number" name="reseller_disc_1" class="form-control" value="<?= getSet('reseller_disc_1', $settings) ?>" required>
                                        </div>
                                        <small class="text-muted">Misal: Potongan 500 rupiah</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card mb-4 border-success">
                            <div class="card-header bg-success text-white fw-bold">Level 2 (Agen Besar)</div>
                            <div class="card-body bg-light">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">Minimal Beli (Pcs)</label>
                                        <input type="number" name="reseller_qty_2" class="form-control" value="<?= getSet('reseller_qty_2', $settings) ?>" required>
                                        <small class="text-muted">Misal: 20 bungkus</small>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">Diskon Per Pcs (Rp)</label>
                                        <div class="input-group">
                                            <span class="input-group-text">Rp</span>
                                            <input type="number" name="reseller_disc_2" class="form-control" value="<?= getSet('reseller_disc_2', $settings) ?>" required>
                                        </div>
                                        <small class="text-muted">Misal: Potongan 1000 rupiah</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary fw-bold py-2">
                                <i class="fas fa-save me-2"></i> SIMPAN PENGATURAN
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>