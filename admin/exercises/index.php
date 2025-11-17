<?php
// admin/exercises/index.php
require_once '../../includes/db.php';
require_once '../includes/header.php';
require_once '../includes/sidebar.php';

// Ambil semua latihan + kategori
$stmt = $pdo->query("
    SELECT e.*, ec.name as category_name 
    FROM exercises e 
    LEFT JOIN exercise_categories ec ON e.category_id = ec.id 
    ORDER BY e.title ASC
");
$exercises = $stmt->fetchAll();
?>

<div class="main-content">
    <div class="container-fluid">
        <h2>🎯 Daftar Latihan</h2>
        <a href="create.php" class="btn btn-primary mb-3">➕ Tambah Latihan</a>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Gambar</th>
                                <th>Nama</th>
                                <th>Kategori</th>
                                <th>Tingkat</th>
                                <th>Durasi</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($exercises as $e): ?>
                            <tr>
                                <td><?= $e['id'] ?></td>
                                <td>
                                    <?php if ($e['thumbnail']): ?>
                                        <img src="../../assets/uploads/exercises/<?= htmlspecialchars($e['thumbnail']) ?>" width="50" alt="thumb">
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($e['title']) ?></td>
                                <td><span class="badge bg-secondary"><?= htmlspecialchars($e['category_name']) ?></span></td>
                                <td><span class="badge bg-info"><?= htmlspecialchars($e['difficulty']) ?></span></td>
                                <td><?= htmlspecialchars($e['duration_estimate']) ?></td>
                                <td>
                                    <span class="badge <?= $e['is_published'] ? 'bg-success' : 'bg-warning' ?>">
                                        <?= $e['is_published'] ? 'Terbit' : 'Draft' ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="edit.php?id=<?= $e['id'] ?>" class="btn btn-sm btn-outline-primary">✏️ Edit</a>
                                    <a href="delete.php?id=<?= $e['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Yakin ingin menghapus?')">🗑️ Hapus</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>