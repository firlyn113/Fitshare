<?php
// admin/nutritions/edit.php
require_once '../../includes/db.php';
require_once '../includes/header.php';
require_once '../includes/sidebar.php';

$id = (int)($_GET['id'] ?? 0);
if (!$id) {
    header('Location: index.php');
    exit;
}

// Ambil data nutrisi
$stmt = $pdo->prepare("SELECT * FROM nutritions WHERE id = ?");
$stmt->execute([$id]);
$nutrition = $stmt->fetch();

if (!$nutrition) {
    header('Location: index.php');
    exit;
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $category_id = (int)($_POST['category_id'] ?? 0);
    $calories = (int)($_POST['calories'] ?? 0);
    $protein = (int)($_POST['protein'] ?? 0);
    $carbs = (int)($_POST['carbs'] ?? 0);
    $fat = (int)($_POST['fat'] ?? 0);
    $is_published = isset($_POST['is_published']) ? 1 : 0;

    if (!$name || !$category_id) {
        $message = "❌ Harap isi field yang wajib: Nama dan Kategori.";
    } else {
        $thumbnail = $nutrition['thumbnail']; // tetap simpan yang lama jika tidak upload baru

        if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === 0) {
            // Hapus gambar lama
            if ($nutrition['thumbnail']) {
                $old_path = '../../assets/uploads/nutritions/' . $nutrition['thumbnail'];
                if (file_exists($old_path)) unlink($old_path);
            }

            $allowed = ['jpg', 'jpeg', 'png'];
            $ext = strtolower(pathinfo($_FILES['thumbnail']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, $allowed)) {
                $filename = 'nut_' . time() . '_' . uniqid() . '.' . $ext;
                $target = '../../assets/uploads/nutritions/' . $filename;

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
                UPDATE nutritions SET category_id = ?, name = ?, description = ?, calories = ?, protein = ?, carbs = ?, fat = ?, thumbnail = ?, is_published = ?
                WHERE id = ?
            ");
            $stmt->execute([$category_id, $name, $description, $calories, $protein, $carbs, $fat, $thumbnail, $is_published, $id]);
            $message = "✅ Nutrisi berhasil diperbarui.";
        }
    }
}

// Ambil kategori
$stmt = $pdo->query("SELECT id, name FROM nutrition_categories ORDER BY name ASC");
$categories = $stmt->fetchAll();
?>

<div class="main-content">
    <div class="container-fluid">
        <h2>✏️ Edit Nutrisi</h2>
        <?php if ($message): ?>
            <div class="alert <?= strpos($message, '✅') !== false ? 'alert-success' : 'alert-danger' ?>"><?= $message ?></div>
        <?php endif; ?>

        <div class="card">
            <div class="card-body">
                <form method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Nama Makanan/Minuman *</label>
                                <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($nutrition['name']) ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Deskripsi</label>
                                <textarea name="description" class="form-control" rows="3"><?= htmlspecialchars($nutrition['description']) ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Kategori *</label>
                                <select name="category_id" class="form-select" required>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?= $cat['id'] ?>" <?= $cat['id'] == $nutrition['category_id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($cat['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Kalori (kcal)</label>
                                <input type="number" name="calories" class="form-control" min="0" value="<?= $nutrition['calories'] ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Protein (g)</label>
                                <input type="number" name="protein" class="form-control" min="0" value="<?= $nutrition['protein'] ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Karbohidrat (g)</label>
                                <input type="number" name="carbs" class="form-control" min="0" value="<?= $nutrition['carbs'] ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Lemak (g)</label>
                                <input type="number" name="fat" class="form-control" min="0" value="<?= $nutrition['fat'] ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Thumbnail Saat Ini</label><br>
                                <?php if ($nutrition['thumbnail']): ?>
                                    <img src="../../assets/uploads/nutritions/<?= htmlspecialchars($nutrition['thumbnail']) ?>" width="100" class="img-thumbnail">
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
                                <input type="checkbox" name="is_published" class="form-check-input" id="published" <?= $nutrition['is_published'] ? 'checked' : '' ?>>
                                <label class="form-check-label" for="published">Terbitkan</label>
                            </div>
                            <button type="submit" class="btn btn-success">Simpan Perubahan</button>
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