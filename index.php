<?php
require 'includes/init.php';
$title = 'Penerimaan Mahasiswa Baru';
require 'includes/header.php';
?>
<section class="hero">
<div>
<p class="eyebrow">PENERIMAAN MAHASISWA BARU <?= date('Y') ?></p>
<h1>Mulai perjalanan<br>barumu di sini.</h1>
<p class="lead">Daftar, ikuti test seleksi, dan temukan langkah berikutnya. Seluruh proses pendaftaran dalam satu tempat yang mudah digunakan.</p>
<div class="actions">
<a class="button" href="<?= url('auth/register.php') ?>">Daftar sebagai calon mahasiswa →</a>
<a class="button secondary" href="<?= url('auth/login.php') ?>">Saya sudah punya akun</a>
</div>
</div>
<aside class="hero-note">
<span class="eyebrow">JALUR PENDAFTARAN</span>
<h2>Satu langkah hari ini.<br>Peluang baru esok hari.</h2>
<p>Siapkan biodata dan asal sekolahmu untuk memulai pendaftaran.</p>
<div class="note-line">01 / Registrasi online</div>
<div class="note-line">02 / Test pilihan ganda</div>
<div class="note-line">03 / Daftar ulang & NIM</div>
</aside>
</section>
<section>
<p class="eyebrow">ALUR PENDAFTARAN</p>
<h2>Dari calon mahasiswa, menjadi mahasiswa.</h2>
<div class="steps">
<article>
<b>01</b>
<h3>Buat akun</h3>
<p>Lengkapi biodata. Nomor test otomatis tersedia setelah registrasi.</p>
</article>
<article>
<b>02</b>
<h3>Ikuti seleksi</h3>
<p>Kerjakan test satu kali. Nilai minimal kelulusan <?= PASSING_SCORE ?>.</p>
</article>
<article>
<b>03</b>
<h3>Konfirmasi daftar ulang</h3>
<p>Jika lulus, lakukan daftar ulang untuk mendapatkan NIM.</p>
</article>
</div>
</section>
<p class="muted">Pengelola penerimaan? <a href="<?= url('admin/login.php') ?>">Masuk sebagai admin</a>
</p>
<?php
require 'includes/footer.php';
?>