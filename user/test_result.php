<?php
require '../includes/init.php';
require_role('user');
$a = applicant();
$title = 'Hasil Test';
require '../includes/header.php';
?>
<p class="eyebrow">HASIL SELEKSI</p>
<h1>Hasil test kamu</h1>
<section class="card form-card">
<?php
if ($a['test_status'] === 'pending'): ?><h2>Belum mengerjakan test</h2>
<a class="button" href="<?= url('user/test.php') ?>">Mulai Test</a>
<?php
else: ?><p><?= e($a['name']) ?> · <?= e($a['test_number']) ?></p>
<div class="score"><?= e($a['test_score']) ?><small>/ 100</small>
</div>
<h2><?= status_label($a['test_status']) ?></h2>
<p>Batas kelulusan: <?= PASSING_SCORE ?>.</p>
<?php
if ($a['test_status'] === 'passed'): ?><p>Kamu memenuhi persyaratan nilai seleksi.</p><?php
if (!$a['re_registration_status']): ?><a class="button" href="<?= url('user/re_registration.php') ?>">Lanjut Daftar Ulang →</a><?php
else: ?><p>NIM: <strong><?= e($a['nim']) ?></strong>
</p><?php
endif;
?>
<?php
else: ?><p>Nilai belum memenuhi batas kelulusan. Kamu tidak dapat melakukan daftar ulang atau memperoleh NIM.</p><?php
endif;
endif;
?></section><?php
require '../includes/footer.php';
?>