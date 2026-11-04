<?php
// KONEKSI DATABASE: sesuaikan dengan MySQL XAMPP di komputer Anda.
const DB_HOST = '127.0.0.1';
const DB_NAME = 'pmb_campus';
const DB_USER = 'root';
const DB_PASSWORD = '';
// BATAS KELULUSAN: nilai minimal untuk dinyatakan lulus.
const PASSING_SCORE = 70;
try {
    $pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4', DB_USER, DB_PASSWORD, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, PDO::ATTR_EMULATE_PREPARES => false]);
}
catch (PDOException $e) {
    http_response_code(503);
    exit('Database belum terhubung. Periksa config/database.php dan import database/pmb_campus.sql.');
}
