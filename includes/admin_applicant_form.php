<?php
$error = '';
$data = [];
if ($editing) {
    $id = valid_id();
    $stmt = $pdo->prepare('SELECT a.*, u.name, u.email FROM applicants a JOIN users u ON u.id = a.user_id WHERE a.id = ?');
    $stmt->execute([$id]);
    $data = $stmt->fetch();
    if (!$data) {
        http_response_code(404);
        exit('Pendaftar tidak ditemukan.');
    }
    $userId = $data['user_id'];
}
// CRUD CALON MABA: admin hanya mengubah biodata, hasil test dihitung sistem.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();
    $data = applicant_data();
    $error = validate_applicant($data, $editing);
    if (!$error) {
        try {
            if ($editing) {
                $pdo->beginTransaction();
                $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ? WHERE id = ? AND role = 'user'");
                $stmt->execute([$data['name'], $data['email'], $userId]);
                if ($data['password'] !== '') {
                    $stmt = $pdo->prepare('UPDATE users SET password = ? WHERE id = ?');
                    $stmt->execute([password_hash($data['password'], PASSWORD_DEFAULT), $userId]);
                }
                $stmt = $pdo->prepare('UPDATE applicants SET phone = ?, address = ?, school_origin = ? WHERE id = ?');
                $stmt->execute([$data['phone'], $data['address'], $data['school_origin'], $id]);
                $pdo->commit();
            }
            else {
                create_applicant($data);
            }
            notice('Data calon mahasiswa berhasil disimpan.');
            go('admin/applicants.php');
        }
        catch (PDOException $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            if ($e->getCode() !== '23000') {
                throw $e;
            }
            $error = 'Email sudah digunakan oleh akun lain.';
        }
    }
}
$title = $editing ? 'Edit Calon Mahasiswa' : 'Tambah Calon Mahasiswa';
require __DIR__ . '/header.php';
?>
<h1><?= e($title) ?></h1>
<section class="card form-card"><?php
if ($error): ?><div class="error" role="alert"><?= e($error) ?></div><?php
endif;
?><form method="post"><?php
csrf();
require __DIR__ . '/applicant_fields.php';
?><div class="actions">
<button>Simpan Pendaftar</button>
<a href="applicants.php">Batal</a>
</div>
</form>
</section><?php
require __DIR__ . '/footer.php';
?>