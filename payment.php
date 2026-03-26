<?php
session_start();
require_once 'includes/db.php';
include 'includes/header.php';

if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['customer_id'];

// Ambil data user terbaru dari database
$stmtUser = $pdo->prepare("SELECT name, phone, address FROM customers WHERE id = ?");
$stmtUser->execute([$user_id]);
$user = $stmtUser->fetch();

// Ambil Metode Pembayaran
$stmtPayment = $pdo->query("SELECT * FROM payment_methods WHERE is_active = 1 ORDER BY id ASC");
$payments = $stmtPayment->fetchAll();
?>

<div class="container py-5" style="min-height: 80vh;">
    <h2 class="fw-bold mb-4 text-center">Pilih Pembayaran</h2>
    
    <div class="row justify-content-center">
        <div class="col-md-8">
            <form action="process_order.php" method="POST">
                
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white fw-bold">Alamat Pengiriman</div>
                    <div class="card-body">
                        <div class="mb-2">
                            <i class="fas fa-user me-2 text-primary"></i>
                            <strong><?= htmlspecialchars($user['name'] ?? 'Pelanggan') ?></strong> 
                            <span class="text-muted ms-2">(<?= htmlspecialchars($user['phone'] ?? '-') ?>)</span>
                        </div>
                        
                        <div class="form-group">
                            <label class="small text-muted mb-1">Detail Alamat:</label>
                            <textarea name="delivery_address" class="form-control bg-light" rows="3" placeholder="Wajib diisi: Jalan, No. Rumah, Kecamatan, Kota..." required><?= htmlspecialchars($user['address'] ?? '') ?></textarea>
                            <small class="text-success"><i class="fas fa-check-circle me-1"></i> Pastikan alamat di atas sudah benar.</small>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white fw-bold">Metode Pembayaran</div>
                    <div class="card-body p-0">
                        <?php if(empty($payments)): ?>
                            <div class="p-3 text-center text-muted">Belum ada metode pembayaran tersedia.</div>
                        <?php else: ?>
                            <div class="list-group list-group-flush">
                                <?php foreach($payments as $p): ?>
                                <label class="list-group-item d-flex justify-content-between align-items-center py-3" style="cursor: pointer;">
                                    <div class="d-flex align-items-center">
                                        <input class="form-check-input me-3" type="radio" name="payment_method_id" value="<?= $p['id'] ?>" required>
                                        <div>
                                            <span class="fw-bold d-block"><?= htmlspecialchars($p['name']) ?></span>
                                            <small class="text-muted">
                                                <?= htmlspecialchars($p['number']) ?> 
                                                <?= !empty($p['description']) ? '('.htmlspecialchars($p['description']).')' : '' ?>
                                            </small>
                                        </div>
                                    </div>
                                    <i class="fas fa-chevron-right text-muted"></i>
                                </label>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-lg fw-bold">Buat Pesanan Sekarang</button>
                    <a href="cart.php" class="btn btn-outline-secondary">Kembali ke Keranjang</a>
                </div>

            </form>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>