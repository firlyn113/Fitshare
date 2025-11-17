<?php
// fitshare/reset_admin.php
require_once 'includes/db.php';

$username = 'admin';
$password = 'admin123'; // Anda bisa ganti di sini

$hashed = password_hash($password, PASSWORD_BCRYPT);

// Hapus user lama (jika ada), lalu insert baru
try {
    $pdo->exec("DELETE FROM users WHERE username = '$username'");
    $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'admin')");
    $stmt->execute([$username, $hashed]);

    echo "<div style='padding:20px;font-family:sans-serif'>";
    echo "<h2>✅ Berhasil!</h2>";
    echo "<p>Akun admin telah di-reset:</p>";
    echo "<ul>";
    echo "<li><strong>Username:</strong> <code>admin</code></li>";
    echo "<li><strong>Password:</strong> <code>admin123</code></li>";
    echo "</ul>";
    echo "<p>✅ Sekarang coba login di: <a href='admin/login.php'>admin/login.php</a></p>";
    echo "<p><strong>Jangan lupa hapus file ini setelah berhasil!</strong></p>";
    echo "</div>";
} catch (Exception $e) {
    die("❌ Error: " . $e->getMessage());
}