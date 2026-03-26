<?php
$base = $base ?? '/arkafood';
?>

</div> <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<audio id="notifSound" src="https://assets.mixkit.co/active_storage/sfx/2869/2869-preview.mp3" preload="auto"></audio>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
// 1. Script Lama (Helper Konfirmasi Hapus) - JANGAN DIHAPUS
document.addEventListener('click', function(e){
  const t = e.target.closest('[data-confirm]');
  if(t){
    if(!confirm(t.getAttribute('data-confirm'))){ e.preventDefault(); }
  }
});

// ============================================================
// 2. [SCRIPT BARU] SISTEM NOTIFIKASI REAL-TIME & SUARA
// ============================================================
let currentLastId = 0; 
const audio = document.getElementById('notifSound');

function checkNewOrders() {
    // Cek ke database via API
    fetch('api_check_orders.php')
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const serverId = parseInt(data.latest_id);

                // Saat pertama kali load, simpan ID saja tanpa bunyi
                if (currentLastId === 0) {
                    currentLastId = serverId;
                    return;
                }

                // JIKA ADA PESANAN BARU (ID Server > ID Browser)
                if (serverId > currentLastId) {
                    
                    // A. Mainkan Suara
                    try {
                        audio.currentTime = 0;
                        let playPromise = audio.play();
                        if (playPromise !== undefined) {
                            playPromise.catch(error => {
                                console.log('Klik halaman sekali agar suara bisa berbunyi (Kebijakan Browser).');
                            });
                        }
                    } catch (e) { console.log(e); }

                    // B. Munculkan Notifikasi (Tahan 30 Detik)
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: 'PESANAN BARU MASUK!',
                        html: `
                            <div style="font-size: 14px;">
                                <strong>${data.customer_name}</strong><br>
                                Total: <span class="text-success fw-bold">Rp ${data.total}</span>
                            </div>
                            <a href="orders.php" class="btn btn-sm btn-primary w-100 mt-2">Lihat Pesanan</a>
                        `,
                        showConfirmButton: false,
                        timer: 30000, // 30 Detik
                        timerProgressBar: true,
                        background: '#e6fffa', 
                        didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                    });

                    // Update ID terakhir
                    currentLastId = serverId;
                }
            }
        })
        .catch(err => console.error('Menunggu order...'));
}

// Cek setiap 3 detik
setInterval(checkNewOrders, 3000);
</script>


    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-2 mb-2 fade-in">
                    <div class="d-flex align-items-start">
                        <img src="<?= $base ?>/assets/images/logo2.png" alt="Arka Food Logo" class="img-fluid footer-logo me-3">
                    </div>
                </div>
                <div class="col-md-10 mb-2 fade-in">
                  <div class="d-flex align-items-start justify-content-left">
                    <p class="mb-0">WEBSITE BY <a href="https://www.instagram.com/rinaldisatia/" style="text-decoration: none;">Raihan Nouval Yashir</a> &copy; 2025 ARKA FOOD.</p>
                    <div>Logged in as: <?php if(!empty($_SESSION['admin_id'])) echo 'Admin #' . intval($_SESSION['admin_id']); ?></div>
                   <p style="font-style: italic;"> All rights reserved.</p>
                  </div>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script src="<?= $base ?>/assets/js/main.js"></script>

    <a href="https://wa.me/6282116726900" class="floating-wa-button" target="_blank" rel="noopener">
        <i class="fab fa-whatsapp"></i>
        <span>Hubungi via WhatsApp</span>
    </a>
</body>
</html>
</main>