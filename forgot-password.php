<?php include 'includes/header.php'; ?>

<section class="py-5" style="background-color: #f8f9fa; min-height: 80vh;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card shadow border-0 rounded-3">
                    <div class="card-body p-5 text-center">
                        <div class="mb-4">
                            <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                <i class="fas fa-lock fa-2x text-primary"></i>
                            </div>
                        </div>
                        
                        <h3 class="fw-bold mb-2">Lupa Password?</h3>
                        <p class="text-muted mb-4">Masukkan email Anda. Kami akan mengarahkan Anda ke Admin WhatsApp untuk reset password.</p>

                        <form onsubmit="redirectToWa(event)">
                            <div class="mb-3 text-start">
                                <label class="form-label fw-bold small text-muted">Email Terdaftar</label>
                                <input type="email" id="emailReset" class="form-control" placeholder="nama@email.com" required>
                            </div>
                            
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary fw-bold">
                                    <i class="fab fa-whatsapp me-2"></i> Reset via WhatsApp
                                </button>
                                <a href="login.php" class="btn btn-light text-muted">Kembali ke Login</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
function redirectToWa(e) {
    e.preventDefault();
    const email = document.getElementById('emailReset').value;
    const adminPhone = "6282116726900"; // Sesuaikan No Admin
    const text = `Halo Admin Arka Food, saya lupa password akun saya.\n\nEmail saya: ${email}\nMohon bantuannya untuk reset password.`;
    window.open(`https://wa.me/${adminPhone}?text=${encodeURIComponent(text)}`, '_blank');
}
</script>

<?php include 'includes/footer.php'; ?>