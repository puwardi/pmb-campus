<?php
require '../includes/init.php';
require_role('admin');
$counts = $pdo->query("SELECT COUNT(*) total, SUM(test_status = 'passed') passed, SUM(test_status = 'failed') failed, SUM(re_registration_status = 1) registered FROM applicants")->fetch();
$totalQuestions = $pdo->query('SELECT COUNT(*) FROM test_questions')->fetchColumn();
$title = 'Dashboard Admin';
require '../includes/header.php';
?>
<p class="eyebrow">ADMINISTRASI PENERIMAAN</p>
<h1>Ringkasan pendaftaran</h1>
<p class="lead">Kelola data calon mahasiswa dan proses seleksi.</p>
<div class="stats">
<?php
foreach (['Total Pendaftar'=>$counts['total'], 'Total Soal'=>$totalQuestions, 'Lulus'=>$counts['passed'], 'Tidak Lulus'=>$counts['failed'], 'Daftar Ulang'=>$counts['registered']] as $label=>$value): ?><article>
<span><?= e($label) ?></span>
<strong><?= (int)$value ?></strong>
</article><?php
endforeach;
?></div>
<section class="card">
<h2>Kelola penerimaan</h2>
<p>Tambahkan pendaftar, perbarui soal, dan periksa hasil seleksi melalui menu di atas.</p>
<div class="actions">
<a class="button" href="<?= url('admin/applicant_add.php') ?>">+ Tambah Calon Mahasiswa</a>
<a class="button secondary" href="<?= url('admin/question_add.php') ?>">+ Tambah Soal</a>
</div>
</section><?php
require '../includes/footer.php';
?>