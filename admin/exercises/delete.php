<?php
// admin/exercises/delete.php
require_once '../../includes/db.php';

$id = (int)($_GET['id'] ?? 0);
if ($id) {
    // Ambil nama file thumbnail untuk dihapus
    $stmt = $pdo->prepare("SELECT thumbnail FROM exercises WHERE id = ?");
    $stmt->execute([$id]);
    $exercise = $stmt->fetch();

    if ($exercise && $exercise['thumbnail']) {
        $path = '../../assets/uploads/exercises/' . $exercise['thumbnail'];
        if (file_exists($path)) unlink($path);
    }

    $stmt = $pdo->prepare("DELETE FROM exercises WHERE id = ?");
    $stmt->execute([$id]);
}

header('Location: index.php');
exit;