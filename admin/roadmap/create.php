<?php
// admin/roadmap/create.php
require_once '../../includes/db.php';
require_once '../includes/header.php';
require_once '../includes/sidebar.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $order_index = (int)($_POST['order_index'] ?? 0);
    $is_published = isset($_POST['is_published']) ? 1 : 0;

    if (!$title || !$content) {
        $message = "❌ Harap isi judul dan konten.";
    } else {
        // Pastikan order_index tidak bentrok
        if ($order_index <= 0) {
            // Jika tidak diisi, ambil nilai tertinggi + 1
            $stmt = $pdo->query("SELECT MAX(order_index) as max FROM roadmap_steps");
            $max = $stmt->fetch();
            $order_index = ($max['max'] ?? 0) + 1;
        }

        $stmt = $pdo->prepare("INSERT INTO roadmap_steps (title, content, order_index, is_published) VALUES (?, ?, ?, ?)");
        $stmt->execute([$title, $content, $order_index, $is_published]);
        $message = "✅ Tahapan roadmap berhasil ditambahkan.";
    }
}
?>

<div class="main-content">
    <div class="container-fluid">
        <h2>➕ Tambah Tahapan Roadmap</h2>
        <?php if ($message): ?>
            <div class="alert <?= strpos($message, '✅') !== false ? 'alert-success' : 'alert-danger' ?>"><?= $message ?></div>
        <?php endif; ?>

        <div class="card">
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Judul Tahapan *</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Konten *</label>
                        <textarea name="content" class="form-control" rows="6" required></textarea>
                        <small class="text-muted">Gunakan format HTML jika perlu (misal: <p>, <ul>).</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Urutan (Order Index)</label>
                        <input type="number" name="order_index" class="form-control" min="1" placeholder="Biarkan kosong untuk otomatis">
                        <small class="text-muted">Semakin kecil angka, semakin awal tahapan muncul.</small>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" name="is_published" class="form-check-input" id="published" checked>
                        <label class="form-check-label" for="published">Terbitkan</label>
                    </div>
                    <button type="submit" class="btn btn-primary">Tambah Tahapan</button>
                    <a href="index.php" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>

</body>
</html>