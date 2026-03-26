<?php
include 'includes/header.php';
require_once 'includes/db.php';

// AMBIL SETTING DISKON DARI DATABASE
$sets = $pdo->query("SELECT * FROM settings")->fetchAll(PDO::FETCH_KEY_PAIR);
$qty1 = isset($sets['reseller_qty_1']) ? (int)$sets['reseller_qty_1'] : 10;
$disc1 = isset($sets['reseller_disc_1']) ? (int)$sets['reseller_disc_1'] : 0;
$qty2 = isset($sets['reseller_qty_2']) ? (int)$sets['reseller_qty_2'] : 20;
$disc2 = isset($sets['reseller_disc_2']) ? (int)$sets['reseller_disc_2'] : 0;

// Inisialisasi Keranjang
$cart = $_SESSION['cart'] ?? [];
$products = [];
$subtotal = 0;

// MEMBERSIHKAN KERANJANG
if (!empty($cart)) {
    $ids = array_keys($cart);
    $in  = str_repeat('?,', count($ids) - 1) . '?';
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id IN ($in)");
    $stmt->execute($ids);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $found_ids = array_column($products, 'id');
    foreach ($cart as $id => $qty) {
        if (!in_array($id, $found_ids)) {
            unset($_SESSION['cart'][$id]);
            unset($cart[$id]);
        }
    }
}
?>

<div class="container py-5" style="min-height: 80vh;">
    <h2 class="fw-bold mb-3">Keranjang Belanja</h2>

    <?php if($disc1 > 0 || $disc2 > 0): ?>
    <div class="alert alert-info border-0 shadow-sm mb-4">
        <h6 class="fw-bold mb-1"><i class="fas fa-tags me-2"></i>Info Grosir / Reseller:</h6>
        <ul class="mb-0 small">
            <?php if($disc1 > 0): ?>
                <li>Beli minimal <strong><?= $qty1 ?> pcs</strong>: Hemat <strong>Rp <?= number_format($disc1) ?></strong>/pcs</li>
            <?php endif; ?>
            <?php if($disc2 > 0): ?>
                <li>Beli minimal <strong><?= $qty2 ?> pcs</strong>: Hemat <strong>Rp <?= number_format($disc2) ?></strong>/pcs</li>
            <?php endif; ?>
        </ul>
    </div>
    <?php endif; ?>

    <?php if (empty($products)): ?>
        <div class="text-center py-5">
            <img src="assets/images/empty-cart.png" alt="Keranjang Kosong" style="width: 150px; opacity: 0.6;" onerror="this.style.display='none'">
            <h4 class="mt-3 text-muted">Keranjang masih kosong</h4>
            <a href="products.php" class="btn btn-primary px-4 py-2 mt-2">Belanja Sekarang</a>
        </div>
    <?php else: ?>
        <div class="row">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4 py-3">Produk</th>
                                        <th class="py-3">Harga</th>
                                        <th class="py-3 text-center">Jumlah</th>
                                        <th class="py-3 text-end">Total</th>
                                        <th class="pe-4 py-3 text-end"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($products as $p): 
                                        $qty = $cart[$p['id']];
                                        
                                        // LOGIKA DISKON DINAMIS
                                        $normal_price = $p['price'];
                                        $final_price = $normal_price;
                                        $badge_reseller = "";

                                        if ($qty >= $qty2 && $disc2 > 0) {
                                            $final_price = $normal_price - $disc2;
                                            $badge_reseller = '<div class="badge bg-success" style="font-size:10px">Harga Agen (-'.number_format($disc2/1000).'rb)</div>';
                                        } elseif ($qty >= $qty1 && $disc1 > 0) {
                                            $final_price = $normal_price - $disc1;
                                            $badge_reseller = '<div class="badge bg-info text-dark" style="font-size:10px">Harga Reseller (-'.number_format($disc1).')</div>';
                                        }

                                        $line_total = $final_price * $qty;
                                        $subtotal += $line_total;
                                        
                                        $img = $p['image'] ?: 'assets/images/placeholder.png';
                                        if (strpos($img, 'uploads/') !== 0) $img = $base . '/' . $img;
                                    ?>
                                    <tr>
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center">
                                                <img src="<?= htmlspecialchars($img) ?>" style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;" class="me-3">
                                                <div>
                                                    <h6 class="mb-0 fw-bold"><?= htmlspecialchars($p['name']) ?></h6>
                                                    <small class="text-muted"><?= htmlspecialchars($p['category']) ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <?php if($final_price < $normal_price): ?>
                                                <small class="text-decoration-line-through text-muted">Rp <?= number_format($normal_price,0,',','.') ?></small><br>
                                                <span class="fw-bold text-success">Rp <?= number_format($final_price,0,',','.') ?></span>
                                                <?= $badge_reseller ?>
                                            <?php else: ?>
                                                <span>Rp <?= number_format($normal_price,0,',','.') ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group btn-group-sm border rounded" role="group">
                                                <a href="update_cart.php?id=<?= $p['id'] ?>&change=-1" class="btn btn-white text-dark px-2 border-end">-</a>
                                                <input type="text" class="form-control form-control-sm text-center border-0" value="<?= $qty ?>" style="width: 40px;" readonly>
                                                <a href="update_cart.php?id=<?= $p['id'] ?>&change=1" class="btn btn-white text-dark px-2 border-start">+</a>
                                            </div>
                                        </td>
                                        <td class="text-end fw-bold">
                                            Rp <?= number_format($line_total, 0, ',', '.') ?>
                                        </td>
                                        <td class="pe-4 text-end">
                                            <a href="update_cart.php?id=<?= $p['id'] ?>&remove=1" class="text-danger" onclick="return confirm('Hapus produk ini?')">
                                                <i class="fas fa-trash-alt"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <a href="products.php" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-2"></i> Tambah Produk Lain</a>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3">Ringkasan</h5>
                        
                        <form action="apply_voucher.php" method="POST" class="mb-3">
                            <label class="small text-muted mb-1">Kode Voucher</label>
                            <div class="input-group">
                                <input type="text" name="code" class="form-control" placeholder="Masukkan kode...">
                                <button class="btn btn-dark" type="submit">Pakai</button>
                            </div>
                        </form>

                        <hr>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Total Pesanan</span>
                            <span class="fw-bold">Rp <?= number_format($subtotal, 0, ',', '.') ?></span>
                        </div>
                        
                        <?php if(isset($_SESSION['voucher'])): ?>
                            <?php 
                                $disc = $_SESSION['voucher']['discount_amount']; 
                                $subtotal -= $disc;
                                if($subtotal < 0) $subtotal = 0;
                            ?>
                            <div class="d-flex justify-content-between mb-2 text-success">
                                <span>Voucher (<?= $_SESSION['voucher']['code'] ?>)</span>
                                <span>- Rp <?= number_format($disc, 0, ',', '.') ?></span>
                            </div>
                        <?php endif; ?>

                        <div class="d-flex justify-content-between mb-4 mt-3 pt-2 border-top">
                            <h5 class="fw-bold">Total Bayar</h5>
                            <h5 class="fw-bold text-primary">Rp <?= number_format($subtotal, 0, ',', '.') ?></h5>
                        </div>

                        <a href="checkout.php" class="btn btn-primary w-100 py-2 fw-bold shadow-sm">
                            Checkout Sekarang
                        </a>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>