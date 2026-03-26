<?php
require_once __DIR__ . '/auth.php';
require_login();

// 1. DATA RINGKASAN
$counts = [];
$counts['products'] = $pdo->query('SELECT COUNT(*) FROM products')->fetchColumn();
$counts['orders']   = $pdo->query('SELECT COUNT(*) FROM orders')->fetchColumn();
$counts['users']    = $pdo->query('SELECT COUNT(*) FROM customers')->fetchColumn();
$income             = $pdo->query("SELECT SUM(total_price) FROM orders WHERE status != 'cancelled'")->fetchColumn();

// 2. DATA GRAFIK PENJUALAN (Omzet 6 Bulan)
$salesData = [];
$months = [];
for ($i = 5; $i >= 0; $i--) {
    $month = date('Y-m', strtotime("-$i months"));
    $monthName = date('M Y', strtotime("-$i months"));
    
    $stmt = $pdo->prepare("SELECT SUM(total_price) FROM orders WHERE status != 'cancelled' AND DATE_FORMAT(created_at, '%Y-%m') = ?");
    $stmt->execute([$month]);
    $total = $stmt->fetchColumn() ?: 0;
    
    $salesData[] = $total;
    $months[] = $monthName;
}

// 3. DATA GRAFIK STATUS PESANAN (Status Order)
$stmtStatus = $pdo->query("SELECT status, COUNT(*) as count FROM orders GROUP BY status");
$statusData = $stmtStatus->fetchAll(PDO::FETCH_KEY_PAIR);
$pieData = [
    $statusData['pending'] ?? 0,
    $statusData['confirmed'] ?? 0,
    $statusData['shipped'] ?? 0,
    $statusData['completed'] ?? 0,
    $statusData['cancelled'] ?? 0
];

// 4. [BARU] DATA GRAFIK KATEGORI TERLARIS
// Menghitung jumlah pcs terjual per kategori
$stmtCat = $pdo->query("
    SELECT p.category, SUM(oi.quantity) as total_qty
    FROM order_items oi
    JOIN products p ON oi.product_id = p.id
    JOIN orders o ON oi.order_id = o.id
    WHERE o.status != 'cancelled'
    GROUP BY p.category
    ORDER BY total_qty DESC
    LIMIT 5
");
$catData = $stmtCat->fetchAll(PDO::FETCH_ASSOC);

$catLabels = [];
$catValues = [];
foreach($catData as $row) {
    $catLabels[] = $row['category'];
    $catValues[] = $row['total_qty'];
}

// 5. PESANAN TERBARU
$recentOrders = $pdo->query("SELECT * FROM orders ORDER BY created_at DESC LIMIT 5")->fetchAll();

?>
<?php include __DIR__ . '/includes/header.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="container py-4">
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-white" style="background: linear-gradient(45deg, #11998e, #38ef7d);">
                <div class="card-body">
                    <h6 class="card-title"><i class="fas fa-box-open me-2"></i>Total Produk</h6>
                    <h3 class="mb-0"><?= number_format($counts['products']) ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-white" style="background: linear-gradient(45deg, #3a7bd5, #00d2ff);">
                <div class="card-body">
                    <h6 class="card-title"><i class="fas fa-shopping-cart me-2"></i>Total Pesanan</h6>
                    <h3 class="mb-0"><?= number_format($counts['orders']) ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-white" style="background: linear-gradient(45deg, #FF8008, #FFC837);">
                <div class="card-body">
                    <h6 class="card-title"><i class="fas fa-users me-2"></i>Pelanggan</h6>
                    <h3 class="mb-0"><?= number_format($counts['users']) ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-white" style="background: linear-gradient(45deg, #EB3349, #F45C43);">
                <div class="card-body">
                    <h6 class="card-title"><i class="fas fa-wallet me-2"></i>Total Pendapatan</h6>
                    <h3 class="mb-0">Rp <?= number_format($income, 0, ',', '.') ?></h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white fw-bold py-3">
                    <i class="fas fa-chart-bar me-2 text-primary"></i> Tren Penjualan (Omzet)
                </div>
                <div class="card-body">
                    <canvas id="salesChart" style="max-height: 250px;"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-3 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white fw-bold py-3">
                    <i class="fas fa-chart-pie me-2 text-success"></i> Kategori Terlaris
                </div>
                <div class="card-body">
                    <canvas id="categoryChart" style="max-height: 250px;"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-3 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white fw-bold py-3">
                    <i class="fas fa-tasks me-2 text-info"></i> Status Pesanan
                </div>
                <div class="card-body">
                    <canvas id="statusChart" style="max-height: 250px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white fw-bold py-3 d-flex justify-content-between align-items-center">
            <span><i class="fas fa-clock me-2 text-warning"></i> Pesanan Terbaru Masuk</span>
            <a href="orders.php" class="btn btn-sm btn-primary">Lihat Semua</a>
        </div>
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">ID</th>
                        <th>Pelanggan</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($recentOrders)): ?>
                        <tr><td colspan="5" class="text-center py-3">Belum ada pesanan.</td></tr>
                    <?php else: ?>
                        <?php foreach($recentOrders as $o): ?>
                        <tr>
                            <td class="ps-4 fw-bold text-primary">#<?= $o['id'] ?></td>
                            <td><?= htmlspecialchars($o['customer_name']) ?></td>
                            <td class="fw-bold text-success">Rp <?= number_format($o['total_price'],0,',','.') ?></td>
                            <td>
                                <?php 
                                    $badge = 'secondary';
                                    if($o['status']=='completed') $badge='success';
                                    elseif($o['status']=='confirmed') $badge='info text-white';
                                    elseif($o['status']=='pending') $badge='warning';
                                    elseif($o['status']=='cancelled') $badge='danger';
                                ?>
                                <span class="badge bg-<?= $badge ?>"><?= strtoupper($o['status']) ?></span>
                            </td>
                            <td class="text-muted small"><?= date('d/m/Y H:i', strtotime($o['created_at'])) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    // 1. CHART PENJUALAN
    new Chart(document.getElementById('salesChart'), {
        type: 'bar',
        data: {
            labels: <?= json_encode($months) ?>,
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: <?= json_encode($salesData) ?>,
                backgroundColor: '#3a7bd5',
                borderRadius: 5
            }]
        },
        options: { responsive: true, maintainAspectRatio: false }
    });

    // 2. CHART STATUS
    new Chart(document.getElementById('statusChart'), {
        type: 'doughnut',
        data: {
            labels: ['Pending', 'Confirmed', 'Shipped', 'Completed', 'Cancelled'],
            datasets: [{
                data: <?= json_encode($pieData) ?>,
                backgroundColor: ['#ffc107', '#0dcaf0', '#0d6efd', '#198754', '#dc3545'],
                borderWidth: 0
            }]
        },
        options: { 
            responsive: true, 
            maintainAspectRatio: false,
            plugins: { legend: { display: false } } // Hilangkan legend biar rapi
        }
    });

    // 3. [BARU] CHART KATEGORI TERLARIS
    new Chart(document.getElementById('categoryChart'), {
        type: 'pie',
        data: {
            labels: <?= json_encode($catLabels) ?>, // Nama Kategori
            datasets: [{
                data: <?= json_encode($catValues) ?>, // Jumlah Terjual
                backgroundColor: [
                    '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF'
                ],
                borderWidth: 1
            }]
        },
        options: { 
            responsive: true, 
            maintainAspectRatio: false,
            plugins: { legend: { position: 'bottom', labels: { boxWidth: 10, font: { size: 10 } } } }
        }
    });
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>