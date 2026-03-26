<?php 
// Simpan sebagai: register.php
include 'includes/header.php'; 
require_once __DIR__ . '/includes/db.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = $_POST['password'];
    
    // Cek email duplikat
    $stmt = $pdo->prepare("SELECT id FROM customers WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->rowCount() > 0) {
        $error = "Email sudah terdaftar!";
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO customers (name, email, phone, password) VALUES (?, ?, ?, ?)");
        if ($stmt->execute([$name, $email, $phone, $hash])) {
            $success = "Pendaftaran berhasil! Silakan login.";
        } else {
            $error = "Gagal mendaftar.";
        }
    }
}
?>

<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <h3 class="text-center mb-4" style="color: var(--primary-color-2);">Daftar Akun</h3>
                        
                        <?php if($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
                        <?php if($success): ?>
                            <div class="alert alert-success"><?= $success ?></div>
                            <script>setTimeout(() => { window.location = 'login.php'; }, 2000);</script>
                        <?php endif; ?>

                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">Nama Lengkap</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">No. WhatsApp</label>
                                <input type="text" name="phone" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Daftar Sekarang</button>
                        </form>
                        <div class="text-center mt-3">
                            Sudah punya akun? <a href="login.php">Login disini</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>