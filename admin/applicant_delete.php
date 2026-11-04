<?php
require '../includes/init.php';
require_role('admin');
$id = valid_id();
$stmt = $pdo->prepare('SELECT a.user_id, u.name FROM applicants a JOIN users u ON u.id = a.user_id WHERE a.id = ?');
$stmt->execute([$id]);
$row = $stmt->fetch();
if (!$row) {
    http_response_code(404);
    exit('Pendaftar tidak ditemukan.');
}
// DELETE: relasi ON DELETE CASCADE ikut menghapus biodata dan jawaban milik akun.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ? AND role = 'user'");
    $stmt->execute([$row['user_id']]);
    notice('Pendaftar berhasil dihapus.');
    go('admin/applicants.php');
}
$title = 'Hapus Pendaftar';
require '../includes/header.php';
?><section class="card form-card">
<h1>Hapus pendaftar?</h1>
<p>Akun, biodata, dan jawaban test <strong><?= e($row['name']) ?></strong> akan dihapus permanen.</p>
<form method="post"><?php
csrf();
?><div class="actions">
<button>Ya, hapus pendaftar</button>
<a href="applicants.php">Batal</a>
</div>
</form>
</section><?php
require '../includes/footer.php';
?>