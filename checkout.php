<?php 
// Cek Login Wajib
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php?redirect=checkout.php");
    exit;
}
include 'includes/header.php'; 
?>

<section class="py-5">
    <div class="container">
        <h2 class="text-center mb-4">Pengiriman</h2>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card p-4">
                    <form action="payment.php" method="POST">
                        <h5 class="mb-3">Alamat Pengiriman</h5>
                        <div class="mb-3">
                            <label>Nama Penerima</label>
                            <input type="text" name="name" class="form-control" value="<?= $_SESSION['customer_name'] ?>" required>
                        </div>
                        <div class="mb-3">
                            <label>Nomor WhatsApp</label>
                            <input type="text" name="phone" class="form-control" value="<?= $_SESSION['customer_phone'] ?>" required>
                        </div>
                        <div class="mb-3">
                            <label>Alamat Lengkap</label>
                            <textarea name="address" class="form-control" rows="3" placeholder="Jl. Contoh No. 123, Kecamatan, Kota..." required></textarea>
                        </div>
                        <div class="mb-3">
                            <label>Catatan Tambahan (Opsional)</label>
                            <input type="text" name="note" class="form-control">
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Lanjut ke Pembayaran <i class="fas fa-arrow-right ms-2"></i></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<?php include 'includes/footer.php'; ?>