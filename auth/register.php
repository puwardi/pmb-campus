<?php
require '../includes/init.php';
if (!empty($_SESSION['user_id'])) {
    go($_SESSION['role'] . '/dashboard.php');
}
$error = '';
$data = [];
// REGISTRASI: validasi biodata dan simpan password dalam bentuk hash.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();
    $data = applicant_data();
    $error = validate_applicant($data);
    if (!$error) {
        try {
            create_applicant($data);
            notice('Registrasi berhasil. Silakan masuk untuk melihat nomor test.');
            go('auth/login.php');
        }
        catch (PDOException $e) {
            if ($e->getCode() !== '23000') {
                throw $e;
            }
            $error = 'Email sudah terdaftar. Gunakan email lain.';
        }
    }
}
$title = 'Registrasi';
require '../includes/header.php';
?>
<section class="card form-card">
<p class="eyebrow">LANGKAH 01 · PENDAFTARAN</p>
<h1>Buat akun calon mahasiswa</h1>
<p class="muted">Isi biodata dengan benar. Semua kolom wajib diisi.</p>
<?php
if ($error): ?><div class="error" role="alert"><?= e($error) ?></div><?php
endif;
?>
<form method="post"><?php
csrf();
require '../includes/applicant_fields.php';
?><button>Daftar sekarang →</button>
</form>
<p>Sudah terdaftar? <a href="<?= url('auth/login.php') ?>">Masuk</a>
</p>
</section>
<?php
require '../includes/footer.php';
?>