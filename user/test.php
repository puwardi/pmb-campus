<?php
require '../includes/init.php';
require_role('user');
$a = applicant();
if ($a['test_status'] !== 'pending') {
    go('user/test_result.php');
}
$error = '';
// AMBIL SOAL: salinan soal disimpan di session supaya perubahan admin tidak
// mengubah test yang sedang dikerjakan. Kunci jawaban tidak dikirim ke HTML.
if (!isset($_SESSION['test_questions'])) {
    $_SESSION['test_questions'] = $pdo->query('SELECT * FROM test_questions ORDER BY id')->fetchAll();
}
$questions = $_SESSION['test_questions'];
if (!$questions) {
    unset($_SESSION['test_questions']);
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();
    $answers = $_POST['answers'] ?? [];
    if (!is_array($answers) || !$questions) {
        $error = 'Soal belum tersedia.';
    }
    foreach ($questions as $q) {
        if (!isset($answers[$q['id']]) || !in_array($answers[$q['id']], ['A','B','C','D'], true)) {
            $error = 'Jawab semua soal sebelum mengirim test.';
        }
    }
    if (!$error) {
        $pdo->beginTransaction();
        try {
            // Kunci pendaftar agar pengiriman ganda tidak menghitung ulang nilai.
            $stmt = $pdo->prepare('SELECT test_status FROM applicants WHERE id = ? FOR UPDATE');
            $stmt->execute([$a['id']]);
            if ($stmt->fetchColumn() !== 'pending') {
                $pdo->rollBack();
                go('user/test_result.php');
            }
            $correct = 0;
            $stmt = $pdo->prepare('INSERT INTO test_answers (applicant_id, question_text, selected_answer, correct_answer, is_correct) VALUES (?, ?, ?, ?, ?)');
            foreach ($questions as $q) {
                $isCorrect = $answers[$q['id']] === $q['correct_answer'] ? 1 : 0;
                $correct += $isCorrect;
                $stmt->execute([$a['id'], $q['question'], $answers[$q['id']], $q['correct_answer'], $isCorrect]);
            }
            // NILAI DAN KELULUSAN: bandingkan nilai asli sebelum pembulatan tampilan.
            $score = $correct / count($questions) * 100;
            $status = $score >= PASSING_SCORE ? 'passed' : 'failed';
            $stmt = $pdo->prepare('UPDATE applicants SET test_score = ?, test_status = ? WHERE id = ?');
            $stmt->execute([round($score, 2), $status, $a['id']]);
            $pdo->commit();
            unset($_SESSION['test_questions']);
            go('user/test_result.php');
        }
        catch (Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            throw $e;
        }
    }
}
$title = 'Test Seleksi';
require '../includes/header.php';
?>
<p class="eyebrow">LANGKAH 02 · SELEKSI</p>
<h1>Test calon mahasiswa</h1>
<p class="lead"><?= count($questions) ?> soal pilihan ganda · Nilai minimal <?= PASSING_SCORE ?> · Satu kali pengerjaan</p>
<?php
if ($error): ?><div class="error" role="alert"><?= e($error) ?></div><?php
endif;
?>
<?php
if (!$questions): ?><div class="card">Soal belum tersedia. Silakan kembali lagi setelah admin menambahkan soal.</div><?php
else: ?>
<form method="post"><?php
csrf();
?><?php
foreach ($questions as $i=>$q): ?><fieldset class="question">
<legend><?= $i+1 ?>. <?= e($q['question']) ?></legend><?php
foreach (['A','B','C','D'] as $letter): ?><label class="choice">
<input type="radio" name="answers[<?= $q['id'] ?>]" value="<?= $letter ?>" required <?= (($answers[$q['id']] ?? '') === $letter) ? 'checked' : '' ?>>
<span>
<b><?= $letter ?>.</b> <?= e($q['option_' . strtolower($letter)]) ?></span>
</label><?php
endforeach;
?></fieldset><?php
endforeach;
?><p class="muted">Periksa jawabanmu. Jawaban tidak dapat diubah setelah dikirim.</p>
<button>Kirim jawaban & lihat hasil →</button>
</form><?php
endif;
?>
<?php
require '../includes/footer.php';
?>