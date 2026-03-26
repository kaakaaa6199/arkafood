<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (isset($_SESSION['customer_id'])) { header("Location: index.php"); exit; }
require_once __DIR__ . '/includes/db.php';

$error = '';
$redirect_to = $_GET['redirect'] ?? 'index.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM customers WHERE email = ? LIMIT 1");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        // Set Session User
        $_SESSION['customer_id'] = $user['id'];
        $_SESSION['customer_name'] = $user['name'];
        $_SESSION['customer_email'] = $user['email'];
        $_SESSION['customer_phone'] = $user['phone'];

        // --- FITUR RESTORE KERANJANG (Cart Persistence) ---
        // 1. Ambil keranjang lama dari database
        $stmtCart = $pdo->prepare("SELECT product_id, quantity FROM cart WHERE customer_id = ?");
        $stmtCart->execute([$user['id']]);
        
        // 2. Gabungkan dengan keranjang saat ini (jika ada)
        if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
        
        while ($row = $stmtCart->fetch()) {
            $pid = $row['product_id'];
            $qty = $row['quantity'];
            // Masukkan ke session (timpa atau tambah logika sesuai kebutuhan, disini kita timpa agar sinkron)
            $_SESSION['cart'][$pid] = $qty;
        }
        // --------------------------------------------------

        header("Location: " . $redirect_to);
        exit;
    } else {
        $error = "Email atau password salah!";
    }
}
include 'includes/header.php'; 
?>

<section class="py-5" style="background-color: #f8f9fa; min-height: 80vh;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card shadow border-0 p-4">
                    <h3 class="text-center mb-4 fw-bold" style="color: #113f67;">Login Pelanggan</h3>
                    
                    <?php if($error): ?>
                        <div class="alert alert-danger py-2"><?= $error ?></div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold small text-muted">Password</label>
                            <div class="input-group">
                                <input type="password" name="password" id="passwordInput" class="form-control" required>
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 fw-bold mb-3">Masuk Sekarang</button>
                        
                        <div class="d-flex justify-content-between small">
                            <a href="forgot-password.php" class="text-muted">Lupa Password?</a>
                            <a href="register.php" class="fw-bold text-primary">Daftar Baru &rarr;</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
document.getElementById('togglePassword').addEventListener('click', function (e) {
    const passwordInput = document.getElementById('passwordInput');
    const icon = this.querySelector('i');
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
});
</script>

<?php include 'includes/footer.php'; ?>