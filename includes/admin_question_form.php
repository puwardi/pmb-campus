<?php
$data = [];
$error = '';
if ($editing) {
    $id = valid_id();
    $stmt = $pdo->prepare('SELECT * FROM test_questions WHERE id = ?');
    $stmt->execute([$id]);
    $data = $stmt->fetch();
    if (!$data) {
        http_response_code(404);
        exit('Soal tidak ditemukan.');
    }
}
// CRUD SOAL: semua input dimasukkan melalui parameter SQL.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();
    foreach (['question','option_a','option_b','option_c','option_d','correct_answer'] as $key) {
        $data[$key] = trim($_POST[$key] ?? '');
        if ($data[$key] === '' || strlen($data[$key]) > 2000) {
            $error = 'Lengkapi semua kolom (maksimal 2000 karakter).';
        }
    }
    if (!in_array($data['correct_answer'], ['A','B','C','D'], true)) {
        $error = 'Pilih kunci jawaban A–D.';
    }
    if (!$error) {
        $values = [$data['question'],$data['option_a'],$data['option_b'],$data['option_c'],$data['option_d'],$data['correct_answer']];
        if ($editing) {
            $sql = 'UPDATE test_questions SET question = ?, option_a = ?, option_b = ?, option_c = ?, option_d = ?, correct_answer = ? WHERE id = ?';
            $values[] = $id;
        }
        else {
            $sql = 'INSERT INTO test_questions (question, option_a, option_b, option_c, option_d, correct_answer) VALUES (?, ?, ?, ?, ?, ?)';
        }
        $stmt = $pdo->prepare($sql);
        $stmt->execute($values);
        notice('Soal berhasil disimpan.');
        go('admin/questions.php');
    }
}
$title = $editing ? 'Edit Soal' : 'Tambah Soal';
require __DIR__ . '/header.php';
?>
<h1><?= e($title) ?></h1>
<section class="card form-card"><?php
if ($error): ?><div class="error"><?= e($error) ?></div><?php
endif;
?><form method="post"><?php
csrf();
?><label>Pertanyaan<textarea name="question" required maxlength="2000" rows="3"><?= e($data['question'] ?? '') ?></textarea>
</label>
<div class="form-grid"><?php
foreach (['a','b','c','d'] as $letter): ?><label>Pilihan <?= strtoupper($letter) ?><input name="option_<?= $letter ?>" required maxlength="2000" value="<?= e($data['option_' . $letter] ?? '') ?>">
</label><?php
endforeach;
?></div>
<label>Jawaban benar<select name="correct_answer" required>
<option value="">Pilih jawaban</option><?php
foreach (['A','B','C','D'] as $letter): ?><option <?= ($data['correct_answer'] ?? '') === $letter ? 'selected' : '' ?>><?= $letter ?></option><?php
endforeach;
?></select>
</label>
<div class="actions">
<button>Simpan Soal</button>
<a href="questions.php">Batal</a>
</div>
</form>
</section><?php
require __DIR__ . '/footer.php';
?>