<?php
require '../includes/init.php';
require_role('admin');
$filter = $_GET['status'] ?? '';
$sql = "SELECT a.*, u.name FROM applicants a JOIN users u ON u.id = a.user_id WHERE a.test_status != 'pending'";
$params = [];
if (in_array($filter, ['passed','failed'], true)) {
    $sql .= ' AND a.test_status = ?';
    $params[] = $filter;
}
$sql .= ' ORDER BY a.id DESC';
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$rows = $stmt->fetchAll();
$filterLabel = 'Semua hasil';
if (in_array($filter, ['passed', 'failed'], true)) {
    $filterLabel = status_label($filter);
}
$title = 'Hasil Test';
require '../includes/header.php';
?>
<p class="eyebrow">MONITORING SELEKSI</p>
<h1>Hasil test pendaftar</h1>
<div class="actions filter">
<a class="button secondary" href="results.php">Semua hasil</a>
<a class="button secondary" href="?status=passed">Lulus</a>
<a class="button secondary" href="?status=failed">Tidak Lulus</a>
</div>
<p>Filter: <?= e($filterLabel) ?></p>
<div class="table-wrap">
<table>
<thead>
<tr>
<th>Nama</th>
<th>Nomor Test</th>
<th>Nilai</th>
<th>Status</th>
</tr>
</thead>
<tbody><?php
foreach ($rows as $r): ?><tr>
<td><?= e($r['name']) ?></td>
<td><?= e($r['test_number']) ?></td>
<td><?= e($r['test_score']) ?></td>
<td>
<span class="badge"><?= status_label($r['test_status']) ?></span>
</td>
</tr><?php
endforeach;
?><?php
if (!$rows): ?><tr>
<td colspan="4" class="empty">Belum ada hasil untuk kategori ini.</td>
</tr><?php
endif;
?></tbody>
</table>
</div><?php
require '../includes/footer.php';
?>