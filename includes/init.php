<?php
require_once __DIR__ . '/../config/database.php';
// SESSION DAN CSRF: identitas login dan token pengaman form disimpan di server.
ini_set('session.use_strict_mode', '1');
session_set_cookie_params(['httponly' => true, 'samesite' => 'Lax']);
session_start();
$base = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
if (in_array(basename($base), ['admin', 'auth', 'user'])) {
    $base = dirname($base);
}
define('BASE_URL', rtrim(str_replace('\\', '/', $base), '/.'));
function url($page) {
    return BASE_URL . '/' . $page;
}
function e($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}
function go($page) {
    header('Location: ' . url($page));
    exit;
}
function csrf() {
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    echo '<input type="hidden" name="csrf" value="' . e($_SESSION['csrf']) . '">';
}
function check_csrf() {
    if (!isset($_POST['csrf'], $_SESSION['csrf']) || !is_string($_POST['csrf']) || !hash_equals($_SESSION['csrf'], $_POST['csrf'])) {
        http_response_code(403);
        exit('Form kedaluwarsa. Muat ulang halaman dan coba kembali.');
    }
}
function require_role($role) {
    if (empty($_SESSION['user_id'])) {
        go($role === 'admin' ? 'admin/login.php' : 'auth/login.php');
    }
    if ($_SESSION['role'] !== $role) {
        go($_SESSION['role'] . '/dashboard.php');
    }
    global $pdo;
    $stmt = $pdo->prepare('SELECT id FROM users WHERE id = ? AND role = ?');
    $stmt->execute([$_SESSION['user_id'], $role]);
    if (!$stmt->fetch()) {
        session_destroy();
        go('auth/login.php');
    }
}
function applicant() {
    global $pdo;
    $stmt = $pdo->prepare('SELECT a.*, u.name, u.email FROM applicants a JOIN users u ON u.id = a.user_id WHERE a.user_id = ?');
    $stmt->execute([$_SESSION['user_id']]);
    return $stmt->fetch();
}
function valid_id() {
    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    if (!$id || $id < 1) {
        http_response_code(400);
        exit('ID tidak valid.');
    }
    return $id;
}
function notice($text) {
    $_SESSION['notice'] = $text;
}
function status_label($status) {
    $labels = ['pending' => 'Belum Mengerjakan', 'passed' => 'Lulus', 'failed' => 'Tidak Lulus'];
    return $labels[$status] ?? $status;
}
// SIMPAN PENDAFTAR: transaksi memastikan akun dan biodata tersimpan bersama.
function create_applicant($data) {
    global $pdo;
    $pdo->beginTransaction();
    try {
        $stmt = $pdo->prepare('INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)');
        $stmt->execute([$data['name'], $data['email'], password_hash($data['password'], PASSWORD_DEFAULT), 'user']);
        $userId = $pdo->lastInsertId();
        $stmt = $pdo->prepare('INSERT INTO applicants (user_id, phone, address, school_origin) VALUES (?, ?, ?, ?)');
        $stmt->execute([$userId, $data['phone'], $data['address'], $data['school_origin']]);
        $id = $pdo->lastInsertId();
        // ID AUTO_INCREMENT unik; penghapusan tidak membuat nomor lama dipakai ulang.
        $number = 'TEST-' . date('Y') . '-' . str_pad($id, 4, '0', STR_PAD_LEFT);
        $stmt = $pdo->prepare('UPDATE applicants SET test_number = ? WHERE id = ?');
        $stmt->execute([$number, $id]);
        $pdo->commit();
    }
    catch (Throwable $e) {
        $pdo->rollBack();
        throw $e;
    }
}
function applicant_data() {
    $data = [];
    foreach (['name', 'email', 'phone', 'address', 'school_origin', 'password'] as $key) {
        $data[$key] = trim((string) ($_POST[$key] ?? ''));
    }
    $data['email'] = strtolower($data['email']);
    return $data;
}
function validate_applicant($data, $editing = false) {
    foreach (['name' => 100, 'email' => 150, 'phone' => 25, 'address' => 1000, 'school_origin' => 150] as $key => $max) {
        if ($data[$key] === '' || strlen($data[$key]) > $max) {
            return 'Lengkapi semua biodata sesuai batas panjang kolom.';
        }
    }
    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        return 'Alamat email tidak valid.';
    }
    if ((!$editing || $data['password'] !== '') && (strlen($data['password']) < 8 || strlen($data['password']) > 72)) {
        return 'Password harus 8–72 karakter.';
    }
    return '';
}
