<?php
// admin/categories/nutrition_categories.php
require_once '../../includes/db.php';
require_once '../includes/header.php';
require_once '../includes/sidebar.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $id = (int)($_POST['id'] ?? 0);
        $name = trim($_POST['name'] ?? '');

        if ($_POST['action'] === 'create' && $name) {
            $slug = preg_replace('/[^a-z0-9]+/', '-', strtolower($name));
            $stmt = $pdo->prepare("INSERT INTO nutrition_categories (name, slug) VALUES (?, ?)");
            $stmt->execute([$name, $slug]);
            $message = "✅ Kategori berhasil ditambahkan.";
        }

        if ($_POST['action'] === 'update' && $id && $name) {
            $slug = preg_replace('/[^a-z0-9]+/', '-', strtolower($name));
            $stmt = $pdo->prepare("UPDATE nutrition_categories SET name = ?, slug = ? WHERE id = ?");
            $stmt->execute([$name, $slug, $id]);
            $message = "✅ Kategori berhasil diperbarui.";
        }

        if ($_POST['action'] === 'delete' && $id) {
            $stmt = $pdo->prepare("DELETE FROM nutrition_categories WHERE id = ?");
            $stmt->execute([$id]);
            $message = "✅ Kategori berhasil dihapus.";
        }
    }
}

$stmt = $pdo->query("SELECT * FROM nutrition_categories ORDER BY name ASC");
$categories = $stmt->fetchAll();
?>

<div class="main-content">
    <div class="container-fluid">
        <h2>🥗 Kategori Nutrisi</h2>
        <?php if (isset($message)): ?>
            <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">Tambah Kategori Baru</h5>
            </div>
            <div class="card-body">
                <form method="POST">
                    <input type="hidden" name="action" value="create">
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">Nama Kategori</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="col-md-6 d-flex align-items-end">
                            <button type="submit" class="btn btn-success w-100">Tambah</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-light">
                <h5 class="mb-0">Daftar Kategori Saat Ini</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama</th>
                                <th>Slug</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($categories as $cat): ?>
                            <tr>
                                <td><?= $cat['id'] ?></td>
                                <td><?= htmlspecialchars($cat['name']) ?></td>
                                <td><code><?= htmlspecialchars($cat['slug']) ?></code></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary edit-btn" 
                                            data-id="<?= $cat['id'] ?>" 
                                            data-name="<?= htmlspecialchars($cat['name']) ?>">
                                        ✏️ Edit
                                    </button>
                                    <form method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus?');">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?= $cat['id'] ?>">
                                        <button type="submit" class="btn btn-sm btn-outline-danger">🗑️ Hapus</button>
                                    </form>
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

<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Kategori</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="action" value="update">
                    <input type="hidden" name="id" id="edit-id">
                    <div class="mb-3">
                        <label class="form-label">Nama Kategori</label>
                        <input type="text" name="name" id="edit-name" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.querySelectorAll('.edit-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        document.getElementById('edit-id').value = btn.dataset.id;
        document.getElementById('edit-name').value = btn.dataset.name;
        new bootstrap.Modal(document.getElementById('editModal')).show();
    });
});
</script>
</body>
</html>