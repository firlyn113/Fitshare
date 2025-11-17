<?php
// admin/roadmap/delete.php
require_once '../../includes/db.php';

$id = (int)($_GET['id'] ?? 0);
if ($id) {
    $stmt = $pdo->prepare("DELETE FROM roadmap_steps WHERE id = ?");
    $stmt->execute([$id]);
}

header('Location: index.php');
exit;