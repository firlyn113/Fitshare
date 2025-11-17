<?php
// admin/roadmap/edit.php
require_once '../../includes/db.php';
require_once '../includes/header.php';
require_once '../includes/sidebar.php';

$id = (int)($_GET['id'] ?? 0);
if (!$id) {
    header('Location: index.php');
    exit;
}

// Ambil data roadmap
$stmt = $pdo->prepare("SELECT * FROM roadmap_steps WHERE id = ?");
$stmt->execute([$id]);
$step = $stmt->fetch();

if (!$step) {
    header('Location: index.php');
    exit;
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $order_index = (int)($_POST['order_index'] ?? 0);
    $is_published = isset($_POST['is_published']) ? 1 : 0;

    if (!$title || !$content) {
        $message = "❌ Harap isi judul dan konten.";
    } else {
        if ($order_index <= 0) {
            $message = "❌ Order index harus lebih besar dari 0.";
        } else {
            $stmt = $pdo->prepare("UPDATE roadmap_steps SET title = ?, content = ?, order_index = ?, is_published = ? WHERE id = ?");
            $stmt->execute([$title, $content, $order_index, $is_published, $id]);
            $message = "✅ Tahapan roadmap berhasil diperbarui.";
        }
    }
}
?>

<div class="main-content">
    <div class="container-fluid">
        <h2>✏️ Edit Tahapan Roadmap</h2>
        <?php if ($message): ?>
            <div class="alert <?= strpos($message, '✅') !== false ? 'alert-success' : 'alert-danger' ?>"><?= $message ?></div>
        <?php endif; ?>

        <div class="card">
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Judul Tahapan *</label>
                        <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($step['title']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Konten *</label>
                        <textarea name="content" class="form-control" rows="6" required><?= htmlspecialchars($step['content']) ?></textarea>
                        <small class="text-muted">Gunakan format HTML jika perlu (misal: <p>, <ul>).</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Urutan (Order Index) *</label>
                        <input type="number" name="order_index" class="form-control" min="1" value="<?= $step['order_index'] ?>" required>
                        <small class="text-muted">Semakin kecil angka, semakin awal tahapan muncul.</small>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" name="is_published" class="form-check-input" id="published" <?= $step['is_published'] ? 'checked' : '' ?>>
                        <label class="form-check-label" for="published">Terbitkan</label>
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    <a href="index.php" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>

</body>
</html>