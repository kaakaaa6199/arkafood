<footer class="text-white py-5 mt-auto" style="background-color: #0b2a45;">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5 class="fw-bold mb-3">Arka Food</h5>
                    <p class="small text-white-50">Menghadirkan produk makanan premium berkualitas untuk kepuasan pelanggan. Cemilan Ningrat Harga Merakyat.</p>
                </div>
                <div class="col-md-4 mb-4">
                    <h5 class="fw-bold mb-3">Kontak</h5>
                    <ul class="list-unstyled small text-white-50">
                        <li class="mb-2"><i class="fas fa-map-marker-alt me-2"></i>Jl. RTA Prawira Adiningrat, Tasikmalaya</li>
                        <li class="mb-2"><i class="fas fa-phone me-2"></i>082116726900</li>
                        <li class="mb-2"><i class="fas fa-envelope me-2"></i>inalinul2123@gmail.com</li>
                    </ul>
                </div>
                <div class="col-md-4 mb-4">
                    <h5 class="fw-bold mb-3">Ikuti Kami</h5>
                    <div class="d-flex gap-3 social-icons">
                        <a href="#" class="text-white"><i class="fab fa-instagram fa-lg"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-facebook fa-lg"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-whatsapp fa-lg"></i></a>
                    </div>
                </div>
            </div>
            <div class="border-top border-secondary pt-3 mt-3 text-center small text-white-50">
                &copy; <?= date('Y') ?> ARKA FOOD. All rights reserved. WEBSITE BY <a href="#" class="text-white text-decoration-none fw-bold">Arkafood</a>.
            </div>
        </div>
    </footer>

    <style>
        /* PERBAIKAN UTAMA: Paksa konten muncul jika script animasi macet */
        .fade-in {
            opacity: 1 !important; 
            transform: none !important;
            visibility: visible !important;
        }

        /* Tombol WA Floating */
        .btn-wa-floating {
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 9999;
            background-color: #25d366;
            color: white !important;
            padding: 12px 24px;
            border-radius: 50px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.3);
            font-weight: bold;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
            border: 2px solid white;
            transition: transform 0.3s;
        }
        .btn-wa-floating:hover {
            transform: scale(1.05);
            background-color: #1ebe57;
        }

        /* Widget Promo Floating */
        .promo-bubble-container {
            position: fixed;
            bottom: 25px;
            left: 25px;
            z-index: 9999;
            width: 100px;
            cursor: pointer;
            animation: floatingBounce 3s infinite ease-in-out;
            transition: transform 0.3s;
        }
        .promo-bubble-container:hover {
            transform: scale(1.1);
        }
        .promo-bubble-container img {
            width: 100%;
            height: auto;
            display: block;
            filter: drop-shadow(0 5px 10px rgba(0,0,0,0.3));
        }
        .promo-close-btn {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #ff3b30;
            color: white;
            border: 2px solid white;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            font-size: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 10000;
            padding: 0;
            font-weight: bold;
        }
        
        @keyframes floatingBounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-8px); }
        }
    </style>

    <a href="https://wa.me/6282116726900?text=Halo+Admin+Arka+Food,+saya+tertarik+dengan+produk+Anda..." target="_blank" class="btn-wa-floating">
        <i class="fab fa-whatsapp fa-2x"></i> 
        <span>Hubungi Kami</span>
    </a>

    <?php
    $promoImage = 'https://cdn-icons-png.flaticon.com/512/726/726476.png'; 
    $availVouchers = [];

    if(isset($pdo)) {
        try {
            // Cek jika admin set gambar custom
            $stmt = $pdo->prepare("SELECT `value` FROM settings WHERE `key` = 'promo_image'");
            $stmt->execute();
            $dbImg = $stmt->fetchColumn();
            if($dbImg && file_exists($dbImg)) $promoImage = $dbImg;

            // Ambil Voucher
            $stmtV = $pdo->query("SELECT * FROM vouchers WHERE is_active = 1");
            $availVouchers = $stmtV->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) { }
    }
    
    // Dummy Data jika kosong (agar tetap tampil)
    if(empty($availVouchers)) {
        $availVouchers[] = ['code'=>'DISKON50', 'value'=>50, 'type'=>'percent', 'min_purchase'=>0];
    }
    ?>

    <div id="promo-bubble-widget" class="promo-bubble-container">
        <button class="promo-close-btn" onclick="document.getElementById('promo-bubble-widget').style.display='none'">&times;</button>
        <div onclick="var myModal = new bootstrap.Modal(document.getElementById('voucherModal')); myModal.show();" title="Klaim Diskon">
            <img src="<?= htmlspecialchars($promoImage) ?>" alt="Klaim Voucher">
            <div class="text-center mt-1">
                <span class="badge bg-danger border border-white" style="font-size: 0.65rem;">Voucher Diskon</span>
            </div>
        </div>
    </div>

    <div class="modal fade" id="voucherModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-danger text-white border-0">
                    <h5 class="modal-title fw-bold"><i class="fas fa-ticket-alt me-2"></i>Klaim Voucher</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body bg-light">
                    <?php foreach($availVouchers as $v): ?>
                    <div class="card mb-3 border-0 shadow-sm" style="border-left: 5px solid #dc3545 !important;">
                        <div class="card-body d-flex justify-content-between align-items-center p-3">
                            <div>
                                <h5 class="fw-bold text-dark mb-0"><?= (int)$v['value'] ?><?= $v['type']=='percent'?'%':'' ?> OFF</h5>
                                <small class="text-muted d-block mt-1">Kode: <strong><?= $v['code'] ?></strong></small>
                            </div>
                            <form action="apply_voucher.php" method="POST">
                                <input type="hidden" name="voucher_code" value="<?= htmlspecialchars($v['code']) ?>">
                                <button type="submit" class="btn btn-outline-danger btn-sm px-3 fw-bold">Pakai</button>
                            </form>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>