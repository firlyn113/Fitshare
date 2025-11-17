<?php
// admin/nutritions/delete.php
require_once '../../includes/db.php';

$id = (int)($_GET['id'] ?? 0);
if ($id) {
    // Ambil nama file thumbnail untuk dihapus
    $stmt = $pdo->prepare("SELECT thumbnail FROM nutritions WHERE id = ?");
    $stmt->execute([$id]);
    $nutrition = $stmt->fetch();

    if ($nutrition && $nutrition['thumbnail']) {
        $path = '../../assets/uploads/nutritions/' . $nutrition['thumbnail'];
        if (file_exists($path)) unlink($path);
    }

    $stmt = $pdo->prepare("DELETE FROM nutritions WHERE id = ?");
    $stmt->execute([$id]);
}

header('Location: index.php');
exit;