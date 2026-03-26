<?php
require_once __DIR__ . '/auth.php';
require_login();

// 1. Setup Filter Tanggal
$startDate = $_GET['start'] ?? date('Y-m-01');
$endDate = $_GET['end'] ?? date('Y-m-d');

// 2. Query Data Lengkap
$sql = "
    SELECT 
        o.*, 
        pm.name as payment_method
    FROM orders o
    LEFT JOIN payment_methods pm ON o.payment_method_id = pm.id
    WHERE o.status != 'cancelled' 
    AND DATE(o.created_at) BETWEEN ? AND ?
    ORDER BY o.created_at DESC
";
$stmt = $pdo->prepare($sql);
$stmt->execute([$startDate, $endDate]);
$orders = $stmt->fetchAll();

// 3. Hitung Total
$total_revenue = 0;
foreach($orders as $o) {
    $total_revenue += $o['total_price'];
}

// 4. Ambil Nama Admin yang sedang login (Untuk Tanda Tangan)
$admin_id = $_SESSION['admin_id'];
$stmtAdmin = $pdo->prepare("SELECT username FROM admins WHERE id = ?");
$stmtAdmin->execute([$admin_id]);
$adminName = $stmtAdmin->fetchColumn() ?: "Admin #$admin_id";

// Base URL untuk gambar logo (Naik satu folder dari admin)
$logoPath = '../assets/images/logo3.png'; 
?>

<?php include __DIR__ . '/includes/header.php'; ?>

<style>
    /* Tampilan di Layar Biasa */
    .print-only { display: none; }
    
    /* Tampilan Saat Dicetak (Ctrl+P) */
    @media print {
        /* Sembunyikan elemen admin yang tidak perlu */
        .navbar, .btn, .no-print, footer, form, .alert-info { 
            display: none !important; 
        }
        
        /* Tampilkan elemen khusus print */
        .print-only { display: block !important; }

        /* Atur Layout Kertas */
        body { 
            background-color: white !important; 
            font-family: 'Times New Roman', serif; /* Font resmi */
            color: black;
            font-size: 12pt;
        }

        .container { 
            max-width: 100% !important; 
            padding: 0 !important; 
            margin: 0 !important;
        }

        .card { border: none !important; box-shadow: none !important; }

        /* Style Tabel Cetak */
        .table { width: 100% !important; border-collapse: collapse !important; }
        .table th, .table td { 
            border: 1px solid #000 !important; 
            padding: 8px !important; 
            font-size: 11pt;
        }
        .table thead { background-color: #ddd !important; -webkit-print-color-adjust: exact; }

        /* Header Kop Surat */
        .kop-surat {
            border-bottom: 3px double #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
        }
        .kop-text { flex: 1; text-align: center; }
        .kop-logo { width: 80px; height: auto; }

        /* Tanda Tangan */
        .signature-section {
            margin-top: 50px;
            display: flex;
            justify-content: flex-end;
        }
        .sign-box {
            text-align: center;
            width: 200px;
        }
    }
</style>

<div class="container py-4">
    
    <div class="print-only">
        <div class="kop-surat">
            <img src="<?= $logoPath ?>" class="kop-logo" alt="Logo">
            <div class="kop-text">
                <h2 style="margin:0; font-weight:bold; text-transform:uppercase;">ARKA FOOD</h2>
                <p style="margin:0; font-size:10pt;">Jl. RTA Prawira Adiningrat, Tasikmalaya, Jawa Barat</p>
                <p style="margin:0; font-size:10pt;">Telp: 0821-1672-6900 | Email: admin@arkafood.com</p>
            </div>
        </div>
        <h3 class="text-center mb-4" style="text-decoration: underline;">LAPORAN KEUANGAN PENJUALAN</h3>
        <p><strong>Periode Laporan:</strong> <?= date('d F Y', strtotime($startDate)) ?> s/d <?= date('d F Y', strtotime($endDate)) ?></p>
    </div>
    <div class="d-flex justify-content-between align-items-center mb-4 no-print">
        <h3 class="text-primary fw-bold"><i class="fas fa-chart-line me-2"></i>Laporan Keuangan</h3>
        <button class="btn btn-dark" onclick="window.print()">
            <i class="fas fa-print me-2"></i> Cetak / Simpan PDF
        </button>
    </div>

    <div class="card p-3 mb-4 bg-white shadow-sm no-print border-0">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-bold">Dari Tanggal</label>
                <input type="date" name="start" class="form-control" value="<?= $startDate ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label fw-bold">Sampai Tanggal</label>
                <input type="date" name="end" class="form-control" value="<?= $endDate ?>">
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary w-100 fw-bold">Tampilkan Data</button>
            </div>
        </form>
    </div>

    <div class="card bg-success text-white p-4 mb-4 border-0 shadow-sm no-print">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h6 class="mb-1 text-white-50">TOTAL PENDAPATAN BERSIH</h6>
                <h2 class="mb-0 fw-bold">Rp <?= number_format($total_revenue, 0, ',', '.') ?></h2>
            </div>
            <i class="fas fa-coins fa-3x opacity-50"></i>
        </div>
    </div>

    <div class="card shadow-none border-0">
        <div class="card-body p-0">
            <table class="table table-bordered align-middle">
                <thead class="table-dark text-center">
                    <tr>
                        <th style="width: 5%;">No</th>
                        <th style="width: 15%;">Tgl & Order ID</th>
                        <th style="width: 20%;">Data Pembeli</th>
                        <th style="width: 25%;">Detail Produk</th>
                        <th style="width: 15%;">Pembayaran</th>
                        <th style="width: 20%;">Total (Rp)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($orders)): ?>
                        <tr><td colspan="6" class="text-center py-4 fst-italic">Tidak ada data transaksi.</td></tr>
                    <?php else: ?>
                        <?php 
                        $no = 1;
                        foreach($orders as $o): 
                        ?>
                        <tr>
                            <td class="text-center"><?= $no++ ?></td>
                            <td>
                                <strong>#<?= $o['id'] ?></strong><br>
                                <span class="small"><?= date('d/m/Y H:i', strtotime($o['created_at'])) ?></span>
                            </td>
                            <td>
                                <strong><?= htmlspecialchars($o['customer_name']) ?></strong><br>
                                <small class="text-muted"><?= htmlspecialchars($o['phone']) ?></small>
                            </td>
                            <td>
                                <ul class="list-unstyled mb-0 small" style="padding-left: 0;">
                                <?php
                                    $stmtItems = $pdo->prepare("
                                        SELECT oi.quantity, p.name, oi.subtotal 
                                        FROM order_items oi 
                                        JOIN products p ON oi.product_id = p.id 
                                        WHERE oi.order_id = ?
                                    ");
                                    $stmtItems->execute([$o['id']]);
                                    $items = $stmtItems->fetchAll();
                                    foreach($items as $item):
                                ?>
                                    <li class="d-flex justify-content-between border-bottom pb-1 mb-1">
                                        <span><?= $item['quantity'] ?>x <?= htmlspecialchars($item['name']) ?></span>
                                    </li>
                                <?php endforeach; ?>
                                </ul>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border no-print">
                                    <?= htmlspecialchars($o['payment_method'] ?? 'Manual') ?>
                                </span>
                                <span class="print-only">
                                    <?= htmlspecialchars($o['payment_method'] ?? 'Manual') ?>
                                </span>
                                
                                <?php if($o['voucher_code']): ?>
                                    <br><small class="text-danger">Voucher: <?= $o['voucher_code'] ?></small>
                                <?php endif; ?>
                            </td>
                            <td class="text-end fw-bold">
                                Rp <?= number_format($o['total_price'], 0, ',', '.') ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        
                        <tr class="table-active fw-bold" style="background-color: #f8f9fa;">
                            <td colspan="5" class="text-end">TOTAL PENDAPATAN PERIODE INI</td>
                            <td class="text-end text-success" style="font-size: 1.1em;">Rp <?= number_format($total_revenue, 0, ',', '.') ?></td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="print-only signature-section">
        <div class="sign-box">
            <p>Tasikmalaya, <?= date('d F Y') ?></p>
            <p>Dibuat Oleh,</p>
            <br><br><br><br>
            <p style="text-decoration: underline; font-weight: bold;"><?= htmlspecialchars(strtoupper($adminName)) ?></p>
            <p>Admin Keuangan</p>
        </div>
    </div>

</div>

<?php include __DIR__ . '/includes/footer.php'; ?>