<?php
require '../includes/init.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    go('auth/login.php');
}
check_csrf();
$_SESSION = [];
session_destroy();
go('auth/login.php');
