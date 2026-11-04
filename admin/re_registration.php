<?php
require '../includes/init.php';
require_role('admin');
$rows = $pdo->query('SELECT a.*, u.name FROM applicants a JOIN users u ON u.id = a.user_id WHERE a.re_registration_status = 1 ORDER BY a.id DESC')->fetchAll();
$title = 'Daftar Ulang';
require '../includes/header.php';
?>
<p class="eyebrow">PENERIMAAN MAHASISWA</p>
<h1>Pendaftar yang daftar ulang</h1>
<div class="table-wrap">
<table>
<thead>
<tr>
<th>Nama</th>
<th>Nomor Test</th>
<th>Status Daftar Ulang</th>
<th>NIM</th>
</tr>
</thead>
<tbody><?php
foreach ($rows as $r): ?><tr>
<td><?= e($r['name']) ?></td>
<td><?= e($r['test_number']) ?></td>
<td>
<span class="badge">Sudah</span>
</td>
<td><?= e($r['nim']) ?></td>
</tr><?php
endforeach;
?><?php
if (!$rows): ?><tr>
<td colspan="4" class="empty">Belum ada pendaftar yang melakukan daftar ulang.</td>
</tr><?php
endif;
?></tbody>
</table>
</div><?php
require '../includes/footer.php';
?>