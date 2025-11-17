<?php
// admin/exercises/edit.php
require_once '../../includes/db.php';
require_once '../includes/header.php';
require_once '../includes/sidebar.php';

$id = (int)($_GET['id'] ?? 0);
if (!$id) {
    header('Location: index.php');
    exit;
}

// Ambil data latihan
$stmt = $pdo->prepare("SELECT * FROM exercises WHERE id = ?");
$stmt->execute([$id]);
$exercise = $stmt->fetch();

if (!$exercise) {
    header('Location: index.php');
    exit;
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $category_id = (int)($_POST['category_id'] ?? 0);
    $difficulty = trim($_POST['difficulty'] ?? '');
    $duration = trim($_POST['duration_estimate'] ?? '');
    $equipment = trim($_POST['equipment_needed'] ?? '');
    $youtube_url = trim($_POST['youtube_url'] ?? '');
    $is_published = isset($_POST['is_published']) ? 1 : 0;

    // Validasi YouTube URL
    $pattern = '/^(https?:\/\/)?(www\.)?(youtube\.com\/(watch\?v=|embed\/)|youtu\.be\/)[a-zA-Z0-9_-]{11}/';
    if (!preg_match($pattern, $youtube_url)) {
        $message = "❌ URL YouTube tidak valid.";
    } elseif (!$title || !$description || !$category_id) {
        $message = "❌ Harap isi field yang wajib: Nama, Deskripsi, dan Kategori.";
    } else {
        $thumbnail = $exercise['thumbnail']; // tetap simpan yang lama jika tidak upload baru

        if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === 0) {
            // Hapus gambar lama
            if ($exercise['thumbnail']) {
                $old_path = '../../assets/uploads/exercises/' . $exercise['thumbnail'];
                if (file_exists($old_path)) unlink($old_path);
            }

            $allowed = ['jpg', 'jpeg', 'png'];
            $ext = strtolower(pathinfo($_FILES['thumbnail']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, $allowed)) {
                $filename = 'ex_' . time() . '_' . uniqid() . '.' . $ext;
                $target = '../../assets/uploads/exercises/' . $filename;

                if (move_uploaded_file($_FILES['thumbnail']['tmp_name'], $target)) {
                    $thumbnail = $filename;
                } else {
                    $message = "❌ Gagal mengunggah gambar baru.";
                }
            } else {
                $message = "❌ Format gambar tidak diizinkan. Hanya JPG/PNG.";
            }
        }

        if (!$message) {
            $stmt = $pdo->prepare("
                UPDATE exercises SET category_id = ?, title = ?, description = ?, difficulty = ?, 
                duration_estimate = ?, equipment_needed = ?, youtube_url = ?, thumbnail = ?, is_published = ?
                WHERE id = ?
            ");
            $stmt->execute([$category_id, $title, $description, $difficulty, $duration, $equipment, $youtube_url, $thumbnail, $is_published, $id]);
            $message = "✅ Latihan berhasil diperbarui.";
        }
    }
}

// Ambil kategori
$stmt = $pdo->query("SELECT id, name FROM exercise_categories ORDER BY name ASC");
$categories = $stmt->fetchAll();
?>

<div class="main-content">
    <div class="container-fluid">
        <h2>✏️ Edit Latihan</h2>
        <?php if ($message): ?>
            <div class="alert <?= strpos($message, '✅') !== false ? 'alert-success' : 'alert-danger' ?>"><?= $message ?></div>
        <?php endif; ?>

        <div class="card">
            <div class="card-body">
                <form method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Nama Latihan *</label>
                                <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($exercise['title']) ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Deskripsi *</label>
                                <textarea name="description" class="form-control" rows="4" required><?= htmlspecialchars($exercise['description']) ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Kategori *</label>
                                <select name="category_id" class="form-select" required>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?= $cat['id'] ?>" <?= $cat['id'] == $exercise['category_id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($cat['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Tingkat Kesulitan</label>
                                <select name="difficulty" class="form-select">
                                    <option value="beginner" <?= $exercise['difficulty'] === 'beginner' ? 'selected' : '' ?>>Pemula</option>
                                    <option value="intermediate" <?= $exercise['difficulty'] === 'intermediate' ? 'selected' : '' ?>>Menengah</option>
                                    <option value="advanced" <?= $exercise['difficulty'] === 'advanced' ? 'selected' : '' ?>>Lanjutan</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Durasi Estimasi</label>
                                <input type="text" name="duration_estimate" class="form-control" value="<?= htmlspecialchars($exercise['duration_estimate']) ?>" placeholder="Misal: 10-15 menit">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Peralatan Dibutuhkan</label>
                                <input type="text" name="equipment_needed" class="form-control" value="<?= htmlspecialchars($exercise['equipment_needed']) ?>" placeholder="Misal: Dumbbell, Matras">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">URL Video YouTube</label>
                                <input type="url" name="youtube_url" class="form-control" value="<?= htmlspecialchars($exercise['youtube_url']) ?>" placeholder="https://www.youtube.com/watch?v=...">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Thumbnail Saat Ini</label><br>
                                <?php if ($exercise['thumbnail']): ?>
                                    <img src="../../assets/uploads/exercises/<?= htmlspecialchars($exercise['thumbnail']) ?>" width="100" class="img-thumbnail">
                                <?php else: ?>
                                    <em class="text-muted">Tidak ada gambar</em>
                                <?php endif; ?>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Upload Thumbnail Baru</label>
                                <input type="file" name="thumbnail" class="form-control" accept="image/*">
                                <small class="text-muted">Jika dikosongkan, gambar lama akan tetap digunakan.</small>
                            </div>
                            <div class="mb-3 form-check">
                                <input type="checkbox" name="is_published" class="form-check-input" id="published" <?= $exercise['is_published'] ? 'checked' : '' ?>>
                                <label class="form-check-label" for="published">Terbitkan</label>
                            </div>
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            <a href="index.php" class="btn btn-secondary">Batal</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

</body>
</html>