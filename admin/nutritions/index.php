<?php
// admin/nutritions/index.php
require_once '../../includes/db.php';
require_once '../includes/header.php';
require_once '../includes/sidebar.php';

// Ambil semua nutrisi + kategori
$stmt = $pdo->query("
    SELECT n.*, nc.name as category_name 
    FROM nutritions n 
    LEFT JOIN nutrition_categories nc ON n.category_id = nc.id 
    ORDER BY n.name ASC
");
$nutritions = $stmt->fetchAll();
?>

<div class="main-content">
    <div class="container-fluid">
        <h2>🥑 Daftar Nutrisi</h2>
        <a href="create.php" class="btn btn-success mb-3">➕ Tambah Nutrisi</a>

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
                                <th>Kalori</th>
                                <th>Protein (g)</th>
                                <th>Karbo (g)</th>
                                <th>Lemak (g)</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($nutritions as $n): ?>
                            <tr>
                                <td><?= $n['id'] ?></td>
                                <td>
                                    <?php if ($n['thumbnail']): ?>
                                        <img src="../../assets/uploads/nutritions/<?= htmlspecialchars($n['thumbnail']) ?>" width="50" alt="thumb">
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($n['name']) ?></td>
                                <td><span class="badge bg-secondary"><?= htmlspecialchars($n['category_name']) ?></span></td>
                                <td><?= $n['calories'] ?></td>
                                <td><?= $n['protein'] ?></td>
                                <td><?= $n['carbs'] ?></td>
                                <td><?= $n['fat'] ?></td>
                                <td>
                                    <span class="badge <?= $n['is_published'] ? 'bg-success' : 'bg-warning' ?>">
                                        <?= $n['is_published'] ? 'Terbit' : 'Draft' ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="edit.php?id=<?= $n['id'] ?>" class="btn btn-sm btn-outline-success">✏️ Edit</a>
                                    <a href="delete.php?id=<?= $n['id'] ?>" class="btn btn-sm btn-outline-danger" 
                                       onclick="return confirm('Yakin ingin menghapus?')">🗑️ Hapus</a>
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