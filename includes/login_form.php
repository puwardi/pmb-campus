<?php
if (!empty($_SESSION['user_id'])) {
    go($_SESSION['role'] . '/dashboard.php');
}
$error = '';
$email = '';
// LOGIN: cari akun dengan prepared statement, lalu cocokkan hash password.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();
    $email = trim($_POST['email'] ?? '');
    $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ? AND role = ?');
    $stmt->execute([$email, $loginRole]);
    $user = $stmt->fetch();
    if ($user && password_verify($_POST['password'] ?? '', $user['password'])) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        go($loginRole . '/dashboard.php');
    }
    $error = 'Email atau password salah.';
}
$title = $loginRole === 'admin' ? 'Masuk Admin' : 'Masuk Calon Mahasiswa';
require __DIR__ . '/header.php';
?>
<section class="auth-card">
<p class="eyebrow"><?= $loginRole === 'admin' ? 'AREA ADMINISTRASI' : 'SELAMAT DATANG KEMBALI' ?></p>
<h1><?= e($title) ?></h1>
<p class="muted">Masuk untuk melanjutkan proses penerimaan.</p>
<?php
if ($error): ?><div class="error" role="alert"><?= e($error) ?></div><?php
endif;
?>
<form method="post"><?php
csrf();
?><label>Email<input name="email" type="email" required maxlength="150" autocomplete="username" value="<?= e($email) ?>">
</label>
<label>Password<input name="password" type="password" required autocomplete="current-password">
</label>
<button>Masuk →</button>
</form>
<?php
if ($loginRole === 'user'): ?><p>Belum punya akun? <a href="<?= url('auth/register.php') ?>">Daftar di sini</a>
</p><?php
endif;
?></section>
<?php
require __DIR__ . '/footer.php';
?>