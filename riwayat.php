<?php
session_start();
require_once __DIR__ . '/includes/db.php';
include 'includes/header.php';

if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php");
    exit;
}

$customer_id = $_SESSION['customer_id'];

// Ambil data pesanan
$stmt = $pdo->prepare("SELECT * FROM orders WHERE customer_id = ? ORDER BY created_at DESC");
$stmt->execute([$customer_id]);
$orders = $stmt->fetchAll();
?>

<style>
    /* CSS PROGRESS BAR (STEPPER) */
    .stepper-wrapper {
        display: flex;
        justify-content: space-between;
        margin-bottom: 20px;
        position: relative;
    }
    .stepper-wrapper::before {
        content: "";
        position: absolute;
        top: 15px;
        left: 0;
        width: 100%;
        height: 4px;
        background: #e0e0e0;
        z-index: 0;
    }
    .stepper-item {
        position: relative;
        display: flex;
        flex-direction: column;
        align-items: center;
        flex: 1;
        z-index: 1;
    }
    .stepper-item::before {
        content: "";
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background-color: #fff;
        border: 4px solid #e0e0e0;
        margin-bottom: 10px;
        transition: all 0.3s;
    }
    .stepper-item.completed::before, .stepper-item.active::before {
        background-color: #25d366; /* Warna Hijau WA/Success */
        border-color: #25d366;
    }
    .stepper-item.active .step-name {
        font-weight: bold;
        color: #000;
    }
    .step-name {
        font-size: 12px;
        color: #999;
        text-align: center;
    }
    /* FILL LINE COLOR */
    .stepper-item.completed + .stepper-item::after {
        content: "";
        position: absolute;
        top: 15px;
        left: -50%;
        width: 100%;
        height: 4px;
        background: #25d366;
        z-index: -1;
    }
    /* HIDE Line for first item */
    .stepper-item:first-child::after { content: none; }
</style>

<div class="container py-5" style="min-height: 80vh;">
    <h2 class="fw-bold mb-4 text-center" style="color: var(--primary-color);">Lacak Pesanan</h2>

    <?php if (isset($_GET['new_order'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i> Pesanan berhasil dibuat! Mohon tunggu konfirmasi pembayaran.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (empty($orders)): ?>
        <div class="text-center text-muted py-5">
            <i class="fas fa-box-open fa-4x mb-3 opacity-50"></i>
            <p>Belum ada riwayat pesanan.</p>
            <a href="produk.php" class="btn btn-primary rounded-pill px-4">Mulai Belanja</a>
        </div>
    <?php else: ?>
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <?php foreach ($orders as $order): 
                    // LOGIKA PROGRESS BAR & ESTIMASI
                    $status = $order['status'];
                    $step = 1; // Default Pending
                    if ($status == 'confirmed') $step = 2; // Dikemas
                    if ($status == 'shipped') $step = 3;   // Dikirim
                    if ($status == 'completed') $step = 4; // Selesai
                    if ($status == 'cancelled') $step = 0; // Batal

                    // Estimasi Sampai (Contoh: +3 Hari dari tanggal order)
                    $estimasi = date('d M Y', strtotime($order['created_at'] . ' +3 days'));
                    
                    // Tracking Number (Jika kosong, isi dummy untuk demo jika sudah confirmed)
                    $resi = $order['tracking_number'];
                    if(empty($resi) && $step >= 2) { 
                        $resi = "JP" . rand(1000000000, 9999999999) . " (Otomatis)"; 
                    }
                ?>
                
                <div class="card shadow-sm border-0 mb-5 fade-in">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3 border-bottom">
                        <div>
                            <strong class="text-primary">Order #<?= $order['id'] ?></strong>
                            <small class="text-muted ms-2"><?= date('d M Y', strtotime($order['created_at'])) ?></small>
                        </div>
                        <?php if($step >= 2 && $step < 4): ?>
                            <div class="text-success small fw-bold">
                                <i class="fas fa-shipping-fast me-1"></i> Estimasi Tiba: <?= $estimasi ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="card-body">
                        <?php if($step > 0): ?>
                        <div class="stepper-wrapper">
                            <div class="stepper-item <?= $step >= 1 ? 'completed' : '' ?>">
                                <div class="step-name">Bayar</div>
                            </div>
                            <div class="stepper-item <?= $step >= 2 ? 'completed' : '' ?> <?= $step == 2 ? 'active' : '' ?>">
                                <div class="step-name">Dikemas</div>
                            </div>
                            <div class="stepper-item <?= $step >= 3 ? 'completed' : '' ?> <?= $step == 3 ? 'active' : '' ?>">
                                <div class="step-name">Dikirim</div>
                            </div>
                            <div class="stepper-item <?= $step >= 4 ? 'completed' : '' ?>">
                                <div class="step-name">Selesai</div>
                            </div>
                        </div>
                        <?php else: ?>
                            <div class="alert alert-danger text-center">Pesanan Dibatalkan</div>
                        <?php endif; ?>

                        <div class="row mt-4">
                            <div class="col-md-8">
                                <h6 class="fw-bold mb-3">Produk</h6>
                                <ul class="list-group list-group-flush mb-3">
                                    <?php
                                    $stmtItems = $pdo->prepare("SELECT oi.*, p.name FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?");
                                    $stmtItems->execute([$order['id']]);
                                    foreach ($stmtItems->fetchAll() as $item):
                                    ?>
                                    <li class="list-group-item d-flex justify-content-between px-0 py-2 border-0">
                                        <div>
                                            <span class="fw-bold"><?= htmlspecialchars($item['name']) ?></span>
                                            <span class="text-muted small ms-2">x<?= $item['quantity'] ?></span>
                                        </div>
                                        <span>Rp <?= number_format($item['subtotal'], 0, ',', '.') ?></span>
                                    </li>
                                    <?php endforeach; ?>
                                </ul>

                                <?php if($step >= 2): ?>
                                <div class="bg-light p-3 rounded d-flex align-items-center justify-content-between flex-wrap gap-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <i class="fas fa-truck fa-2x text-primary opacity-50"></i>
                                        <div>
                                            <small class="text-muted d-block">Jasa Kirim: <strong><?= htmlspecialchars($order['courier'] ?? 'J&T Express') ?></strong></small>
                                            <span class="fw-bold text-dark fs-5"><?= $resi ?></span>
                                        </div>
                                    </div>
                                    
                                    <a href="https://jet.co.id/track" target="_blank" onclick="copyResi('<?= $resi ?>')" class="btn btn-primary rounded-pill px-4 shadow-sm text-decoration-none">
                                        <i class="fas fa-search-location me-2"></i>Lacak Paket
                                    </a>
                                </div>
                                <?php endif; ?>
                            </div>

                            <div class="col-md-4 border-start">
                                <h6 class="fw-bold mb-3">Info Pembayaran</h6>
                                <div class="d-flex justify-content-between mb-1">
                                    <span>Total Harga</span>
                                    <span>Rp <?= number_format($order['total_price'], 0, ',', '.') ?></span>
                                </div>
                                <hr>
                                <small class="text-muted d-block">Alamat Pengiriman:</small>
                                <p class="small mb-0 fw-bold"><?= htmlspecialchars($order['address']) ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function copyResi(resi) {
    // 1. Bersihkan teks (hapus kata Otomatis jika ada)
    let cleanResi = resi.split(' ')[0]; 
    
    // 2. Salin ke Clipboard
    navigator.clipboard.writeText(cleanResi);

    // 3. Tampilkan Notifikasi Kecil (Toast) agar tidak mengganggu tab baru
    Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'success',
        title: 'Resi disalin: ' + cleanResi,
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true
    });
    
    // Tab baru akan otomatis terbuka karena kita menggunakan tag <a target="_blank">
}
</script>

<?php include 'includes/footer.php'; ?>