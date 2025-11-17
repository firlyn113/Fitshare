<?php
// admin/roadmap/index.php
require_once '../../includes/db.php';
require_once '../includes/header.php';
require_once '../includes/sidebar.php';

// Ambil semua tahapan roadmap, diurutkan berdasarkan order_index
$stmt = $pdo->query("SELECT * FROM roadmap_steps ORDER BY order_index ASC");
$steps = $stmt->fetchAll();
?>

<div class="main-content">
    <div class="container-fluid">
        <h2>🗺️ Roadmap Tahapan</h2>
        <p class="text-muted">Urutan roadmap akan ditampilkan sesuai <code>order_index</code>. Anda bisa ubah urutan di form edit.</p>
        <a href="create.php" class="btn btn-primary mb-3">➕ Tambah Tahapan</a>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Urutan</th>
                                <th>Judul</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($steps as $step): ?>
                            <tr>
                                <td><?= $step['id'] ?></td>
                                <td><span class="badge bg-info">#<?= $step['order_index'] ?></span></td>
                                <td><?= htmlspecialchars($step['title']) ?></td>
                                <td>
                                    <span class="badge <?= $step['is_published'] ? 'bg-success' : 'bg-warning' ?>">
                                        <?= $step['is_published'] ? 'Terbit' : 'Draft' ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="edit.php?id=<?= $step['id'] ?>" class="btn btn-sm btn-outline-primary">✏️ Edit</a>
                                    <a href="delete.php?id=<?= $step['id'] ?>" class="btn btn-sm btn-outline-danger" 
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