<?php
require_once __DIR__ . '/auth.php';

// Prevent cached page
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
header('Expires: 0');

// If already logged in, go to dashboard
if (is_logged_in()) {
    header('Location: dashboard.php');
    exit;
}

$base = $base ?? '/arkafood';

$error = '';
$success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? 'login';
    if ($action === 'login') {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        $stmt = $pdo->prepare('SELECT id, password_hash FROM admins WHERE username = :u LIMIT 1');
        $stmt->execute([':u' => $username]);
        $user = $stmt->fetch();
        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['admin_id'] = $user['id'];
            header('Location: dashboard.php');
            exit;
        } else {
            $error = 'Username atau password salah.';
        }
    } elseif ($action === 'register') {
        $username = trim($_POST['r_username'] ?? '');
        $password = $_POST['r_password'] ?? '';
        $password2 = $_POST['r_password2'] ?? '';
        if (empty($username) || empty($password)) {
            $error = 'Isi username dan password.';
        } elseif ($password !== $password2) {
            $error = 'Password konfirmasi tidak cocok.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            try {
                $stmt = $pdo->prepare('INSERT INTO admins (username, password_hash) VALUES (:u, :p)');
                $stmt->execute([':u' => $username, ':p' => $hash]);
                $success = 'Akun berhasil dibuat. Silakan login.';
            } catch (PDOException $e) {
                $error = 'Gagal membuat akun: ' . $e->getMessage();
            }
        }
    }
}

?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login - Arka Food</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= $base ?>/assets/css/style.css">
    <style>
        /* Combined auth panel styles */
        body{background: linear-gradient(180deg,#f6f9fc 0,#fff 100%);}
        .auth-wrapper{min-height:100vh;display:flex;align-items:center;justify-content:center;padding:40px 16px}
        .auth-card{width:920px;max-width:96%;background:#fff;border-radius:12px;box-shadow:0 10px 30px rgba(0,0,0,0.08);overflow:hidden;display:flex;transition:transform .6s ease}
        .auth-left, .auth-right{width:50%;padding:40px;box-sizing:border-box}
        .auth-left{background:linear-gradient(135deg,#0b3b5a,#175a86);color:#fff;display:flex;flex-direction:column;align-items:center;justify-content:center;position:relative}
        .auth-left .logo{max-width:220px}
        .auth-right{background:#fff}
        .auth-form{max-width:420px;margin:0 auto}
        .toggle-links{display:flex;gap:12px;justify-content:center;margin-top:12px}

        /* sliding effect */
        .auth-card.right-active .auth-left{transform:translateX(100%);}
        .auth-card.right-active .auth-right{transform:translateX(-100%);} 
        .auth-left, .auth-right{transition:transform .6s ease}

        @media(max-width:720px){
            .auth-card{flex-direction:column}
            .auth-left, .auth-right{width:100%;}
            .auth-card.right-active .auth-left, .auth-card.right-active .auth-right{transform:none}
        }
    </style>
</head>
<body>

<main>
    <div class="auth-wrapper">
        <div class="auth-card" id="authCard">
            <div class="auth-left">
                <img src="<?= $base ?>/assets/images/logo2.png" alt="Arka Food" class="logo mb-3">
                <h3>Selamat Datang</h3>
                <p class="text-center px-3">Masuk untuk mengelola konten website Arka Food atau buat akun admin baru.</p>
            </div>
            <div class="auth-right">
                <div class="auth-form">
                    <?php if($error): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                    <?php elseif($success): ?>
                        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
                    <?php endif; ?>

                    <!-- Login Form -->
                    <form id="loginForm" method="post" style="display:block;">
                        <input type="hidden" name="action" value="login">
                        <h4 class="mb-3">Admin Login</h4>
                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" name="username" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <div class="input-group">
                                <input type="password" id="password" name="password" class="form-control" required>
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">Show</button>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="toggle-links">
                                <button type="button" class="btn btn-link" id="showRegister">Daftar</button>
                            </div>
                            <button class="btn btn-primary" type="submit">Login</button>
                        </div>
                    </form>

                    <!-- Register Form -->
                    <form id="registerForm" method="post" style="display:none;">
                        <input type="hidden" name="action" value="register">
                        <h4 class="mb-3">Buat Akun Admin</h4>
                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" name="r_username" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <div class="input-group">
                                <input type="password" id="r_password" name="r_password" class="form-control" required>
                                <button class="btn btn-outline-secondary" type="button" id="toggleRPassword">Show</button>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Konfirmasi Password</label>
                            <div class="input-group">
                                <input type="password" id="r_password2" name="r_password2" class="form-control" required>
                                <button class="btn btn-outline-secondary" type="button" id="toggleRPassword2">Show</button>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="toggle-links">
                                <button type="button" class="btn btn-link" id="showLogin">Kembali ke Login</button>
                            </div>
                            <button class="btn btn-primary" type="submit">Buat Akun</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include __DIR__ . '/../includes/footer.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', function(){
    const authCard = document.getElementById('authCard');
    const showRegister = document.getElementById('showRegister');
    const showLogin = document.getElementById('showLogin');
    const loginForm = document.getElementById('loginForm');
    const registerForm = document.getElementById('registerForm');

    function setRegisterMode(on){
        if(on){
            authCard.classList.add('right-active');
            loginForm.style.display = 'none';
            registerForm.style.display = 'block';
        } else {
            authCard.classList.remove('right-active');
            loginForm.style.display = 'block';
            registerForm.style.display = 'none';
        }
    }

    showRegister && showRegister.addEventListener('click', function(){ setRegisterMode(true); });
    showLogin && showLogin.addEventListener('click', function(){ setRegisterMode(false); });

    // password toggles
    function makeToggle(buttonId, inputId){
        const btn = document.getElementById(buttonId);
        const inp = document.getElementById(inputId);
        if(btn && inp){ btn.addEventListener('click', function(){ if(inp.type==='password'){ inp.type='text'; btn.textContent='Hide'; } else { inp.type='password'; btn.textContent='Show'; } }); }
    }
    makeToggle('togglePassword','password');
    makeToggle('toggleRPassword','r_password');
    makeToggle('toggleRPassword2','r_password2');
});
</script>

</body>

</html>
