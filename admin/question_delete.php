<?php
require '../includes/init.php';
require_role('admin');
$id = valid_id();
$stmt = $pdo->prepare('SELECT * FROM test_questions WHERE id = ?');
$stmt->execute([$id]);
$row = $stmt->fetch();
if (!$row) {
    http_response_code(404);
    exit('Soal tidak ditemukan.');
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();
    $stmt = $pdo->prepare('DELETE FROM test_questions WHERE id = ?');
    $stmt->execute([$id]);
    notice('Soal berhasil dihapus.');
    go('admin/questions.php');
}
$title = 'Hapus Soal';
require '../includes/header.php';
?><section class="card form-card">
<h1>Hapus soal?</h1>
<p><?= e($row['question']) ?></p>
<p>Riwayat jawaban dan nilai pendaftar tetap tersimpan.</p>
<form method="post"><?php
csrf();
?><div class="actions">
<button>Ya, hapus soal</button>
<a href="questions.php">Batal</a>
</div>
</form>
</section><?php
require '../includes/footer.php';
?>