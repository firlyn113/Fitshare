<?php
// admin/index.php
session_start();

// Cek apakah admin sudah login (sesi 'admin_id' atau 'admin_logged_in' ada)
// Sesuaikan dengan cara login Anda — contoh umum:
if (!isset($_SESSION['admin_id']) || !isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    // Belum login → redirect ke halaman login admin
    header("Location: login.php");
    exit();
}

// Jika sudah login → arahkan ke dashboard
header("Location: dashboard.php");
exit();
?>