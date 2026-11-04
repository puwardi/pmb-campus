<?php
require '../includes/init.php';
require_role('user');
$a = applicant();
// DAFTAR ULANG: hanya pendaftar lulus boleh memperoleh NIM.
if ($a['test_status'] !== 'passed') {
    notice('Daftar ulang hanya tersedia untuk peserta yang lulus.');
    go('user/test_result.php');
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();
    if (($_POST['confirm'] ?? '') === '1') {
        // Update bersyarat membuat permintaan berulang tetap menghasilkan satu NIM.
        $nim = date('Y') . str_pad($a['id'], 4, '0', STR_PAD_LEFT);
        $stmt = $pdo->prepare("UPDATE applicants SET re_registration_status = 1, nim = ? WHERE id = ? AND test_status = 'passed' AND re_registration_status = 0");
        $stmt->execute([$nim, $a['id']]);
        notice('Daftar ulang berhasil. NIM tersedia di dashboard.');
        go('user/dashboard.php');
    }
    $error = 'Centang konfirmasi untuk melanjutkan.';
}
$title = 'Daftar Ulang';
require '../includes/header.php';
?>
<p class="eyebrow">LANGKAH 03 · KONFIRMASI</p>
<h1>Daftar ulang mahasiswa</h1>
<section class="card form-card">
<?php
if ($a['re_registration_status']): ?><h2>Daftar ulang sudah selesai</h2>
<p>NIM kamu: <strong><?= e($a['nim']) ?></strong>
</p>
<?php
else: ?><h2>Konfirmasi kelanjutan pendaftaran</h2>
<p><?= e($a['name']) ?> · <?= e($a['test_number']) ?></p>
<p>Setelah konfirmasi, sistem akan membuat Nomor Induk Mahasiswa (NIM) untukmu.</p><?php
if (!empty($error)): ?><div class="error"><?= e($error) ?></div><?php
endif;
?><form method="post"><?php
csrf();
?><label class="choice">
<input type="checkbox" name="confirm" value="1" required>
<span>Saya menyatakan biodata saya benar dan ingin melanjutkan sebagai mahasiswa.</span>
</label>
<button>Konfirmasi daftar ulang →</button>
</form><?php
endif;
?></section><?php
require '../includes/footer.php';
?>