<?php
require '../includes/init.php';
require_role('user');
$a = applicant();
$title = 'Dashboard';
require '../includes/header.php';
?>
<p class="eyebrow">PORTAL CALON MAHASISWA</p>
<h1>Selamat datang, <?= e($a['name']) ?>.</h1>
<p class="lead">Pantau pendaftaran dan lanjutkan langkah berikutnya di sini.</p>
<div class="stats">
<article>
<span>Nomor Test</span>
<strong><?= e($a['test_number']) ?></strong>
</article>
<article>
<span>Status Test</span>
<strong><?= status_label($a['test_status']) ?></strong>
</article>
<article>
<span>Daftar Ulang</span>
<strong><?= $a['re_registration_status'] ? 'Sudah' : 'Belum' ?></strong>
</article>
<article>
<span>NIM</span>
<strong><?= e($a['nim'] ?? 'Belum tersedia') ?></strong>
</article>
</div>
<section class="card">
<p class="eyebrow">LANGKAH BERIKUTNYA</p>
<?php
if ($a['test_status'] === 'pending'): ?><h2>Siap mengikuti test seleksi?</h2>
<p>Test hanya dapat dikerjakan satu kali. Pastikan seluruh jawaban sudah diperiksa sebelum dikirim.</p>
<a class="button" href="<?= url('user/test.php') ?>">Mulai Test →</a>
<?php
else: ?><h2><?= $a['test_status'] === 'passed' ? 'Selamat, kamu lulus seleksi.' : 'Terima kasih sudah mengikuti seleksi.' ?></h2>
<p><?= $a['test_status'] === 'failed' ? 'Nilai kamu belum memenuhi batas kelulusan. Daftar ulang dan NIM belum tersedia.' : 'Lihat hasil test dan status penerimaanmu.' ?></p>
<div class="actions">
<a class="button secondary" href="<?= url('user/test_result.php') ?>">Lihat Hasil Test</a>
<?php
if ($a['test_status'] === 'passed' && !$a['re_registration_status']): ?><a class="button" href="<?= url('user/re_registration.php') ?>">Daftar Ulang →</a><?php
endif;
?></div><?php
endif;
?></section>
<?php
require '../includes/footer.php';
?>