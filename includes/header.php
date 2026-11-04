<?php
if (!isset($title)) {
    $title = 'PMB CAMPUS';
}
?>
<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($title) ?> · PMB CAMPUS</title>
<link rel="stylesheet" href="<?= url('assets/css/style.css') ?>">
</head>
<body>
<header>
<div class="nav-wrap">
<a class="brand" href="<?= url('index.php') ?>">
<span class="brand-mark">P</span> PMB CAMPUS</a>
<nav aria-label="Navigasi utama">
<?php
if (!empty($_SESSION['user_id'])): ?>
<?php
if ($_SESSION['role'] === 'admin'): ?>
<a href="<?= url('admin/dashboard.php') ?>">Ringkasan</a>
<a href="<?= url('admin/applicants.php') ?>">Pendaftar</a>
<a href="<?= url('admin/questions.php') ?>">Soal Test</a>
<a href="<?= url('admin/results.php') ?>">Hasil Test</a>
<a href="<?= url('admin/re_registration.php') ?>">Daftar Ulang</a>
<?php
else: ?>
<a href="<?= url('user/dashboard.php') ?>">Dashboard</a>
<a href="<?= url('user/profile.php') ?>">Profil Saya</a>
<?php
endif;
?>
<form method="post" action="<?= url('auth/logout.php') ?>" class="inline"><?php
csrf();
?><button class="nav-button">Keluar</button>
</form>
<?php
else: ?><a href="<?= url('auth/login.php') ?>">Masuk</a>
<a class="nav-button" href="<?= url('auth/register.php') ?>">Daftar Sekarang</a><?php
endif;
?>
</nav>
</div>
</header>
<main>
<?php
if (isset($_SESSION['notice'])): ?><div class="notice" role="status"><?= e($_SESSION['notice']) ?></div><?php
unset($_SESSION['notice']);
endif;
?>
