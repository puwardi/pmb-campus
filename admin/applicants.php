<?php
require '../includes/init.php';
require_role('admin');
$rows = $pdo->query('SELECT a.*, u.name, u.email FROM applicants a JOIN users u ON u.id = a.user_id ORDER BY a.id DESC')->fetchAll();
$title = 'Semua Pendaftar';
require '../includes/header.php';
?>
<div class="page-heading">
<div>
<p class="eyebrow">DATA CALON MAHASISWA</p>
<h1>Semua pendaftar</h1>
</div>
<a class="button" href="<?= url('admin/applicant_add.php') ?>">+ Tambah Pendaftar</a>
</div>
<div class="table-wrap">
<table>
<thead>
<tr>
<th>Nama</th>
<th>Email</th>
<th>Nomor Test</th>
<th>Status Test</th>
<th>Aksi</th>
</tr>
</thead>
<tbody><?php
foreach ($rows as $r): ?><tr>
<td><?= e($r['name']) ?></td>
<td><?= e($r['email']) ?></td>
<td><?= e($r['test_number']) ?></td>
<td>
<span class="badge"><?= status_label($r['test_status']) ?></span>
</td>
<td class="actions">
<a href="applicant_edit.php?id=<?= $r['id'] ?>">Edit</a>
<a href="applicant_delete.php?id=<?= $r['id'] ?>">Hapus</a>
</td>
</tr><?php
endforeach;
?><?php
if (!$rows): ?><tr>
<td colspan="5" class="empty">Belum ada calon mahasiswa yang mendaftar.</td>
</tr><?php
endif;
?></tbody>
</table>
</div><?php
require '../includes/footer.php';
?>