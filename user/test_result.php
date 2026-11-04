<?php
require '../includes/init.php';
require_role('user');
$a = applicant();
$title = 'Hasil Test';
require '../includes/header.php';
?>
<p class="eyebrow">HASIL SELEKSI</p>
<h1>Hasil test kamu</h1>
<section class="card form-card result-card">
<?php
if ($a['test_status'] === 'pending'): ?><h2>Belum mengerjakan test</h2>
<p>Test seleksi belum kamu kerjakan. Kamu bisa mulai sekarang atau kembali melihat status pendaftaran.</p>
<div class="actions"><a class="button" href="<?= url('user/test.php') ?>">Mulai Test →</a><a class="button secondary" href="<?= url('user/dashboard.php') ?>">← Kembali ke Dashboard</a></div>
<?php
else: ?><p><?= e($a['name']) ?> · <?= e($a['test_number']) ?></p>
<div class="score-panel"><div class="score-label">NILAI TEST</div><div class="score"><?= e($a['test_score']) ?><small>/ 100</small>
</div>
</div>
<h2><span class="status-pill <?= $a['test_status'] === 'passed' ? 'status-passed' : 'status-failed' ?>"><?= status_label($a['test_status']) ?></span></h2>
<p>Batas kelulusan: <?= PASSING_SCORE ?>.</p>
<?php
if ($a['test_status'] === 'passed'): ?><p>Kamu memenuhi persyaratan nilai seleksi.</p><?php
if (!$a['re_registration_status']): ?><div class="actions"><a class="button" href="<?= url('user/re_registration.php') ?>">Lanjut Daftar Ulang →</a><a class="button secondary" href="<?= url('user/dashboard.php') ?>">← Kembali ke Dashboard</a></div><?php
else: ?><p>NIM: <strong><?= e($a['nim']) ?></strong>
</p><a class="button secondary" href="<?= url('user/dashboard.php') ?>">← Kembali ke Dashboard</a><?php
endif;
?>
<?php
else: ?><p>Nilai belum memenuhi batas kelulusan. Kamu tidak dapat melakukan daftar ulang atau memperoleh NIM.</p><a class="button secondary" href="<?= url('user/dashboard.php') ?>">← Kembali ke Dashboard</a><?php
endif;
endif;
?></section><?php
require '../includes/footer.php';
?>
