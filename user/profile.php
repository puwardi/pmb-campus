<?php
require '../includes/init.php';
require_role('user');
$a = applicant();
$title = 'Profil Saya';
require '../includes/header.php';
?>
<p class="eyebrow">BIODATA PENDAFTAR</p>
<h1>Profil saya</h1>
<section class="card form-card">
<dl><?php
foreach (['name'=>'Nama lengkap','email'=>'Email','test_number'=>'Nomor Test','phone'=>'Telepon','school_origin'=>'Asal sekolah','address'=>'Alamat'] as $key=>$label): ?><dt><?= e($label) ?></dt>
<dd><?= e($a[$key]) ?></dd><?php
endforeach;
?></dl>
<p class="muted">Hubungi admin penerimaan jika biodata perlu diperbaiki.</p>
</section><?php
require '../includes/footer.php';
?>