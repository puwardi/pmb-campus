<?php
require '../includes/init.php';
require_role('admin');
$rows = $pdo->query('SELECT * FROM test_questions ORDER BY id')->fetchAll();
$title = 'Kelola Soal';
require '../includes/header.php';
?>
<div class="page-heading">
<div>
<p class="eyebrow">BANK SOAL SELEKSI</p>
<h1>Kelola soal test</h1>
</div>
<a class="button" href="question_add.php">+ Tambah Soal</a>
</div>
<p class="muted">Perubahan soal berlaku untuk test yang belum dimulai. Hasil test sebelumnya tetap tersimpan.</p>
<div class="table-wrap">
<table>
<thead>
<tr>
<th>No.</th>
<th>Pertanyaan</th>
<th>Kunci</th>
<th>Aksi</th>
</tr>
</thead>
<tbody><?php
foreach ($rows as $i=>$r): ?><tr>
<td><?= $i+1 ?></td>
<td><?= e($r['question']) ?></td>
<td><?= e($r['correct_answer']) ?></td>
<td class="actions">
<a href="question_edit.php?id=<?= $r['id'] ?>">Edit</a>
<a href="question_delete.php?id=<?= $r['id'] ?>">Hapus</a>
</td>
</tr><?php
endforeach;
?><?php
if (!$rows): ?><tr>
<td colspan="4" class="empty">Belum ada soal. Tambahkan soal pertama.</td>
</tr><?php
endif;
?></tbody>
</table>
</div><?php
require '../includes/footer.php';
?>