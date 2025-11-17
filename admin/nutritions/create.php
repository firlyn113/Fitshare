<?php
// admin/nutritions/create.php
require_once '../../includes/db.php';
require_once '../includes/header.php';
require_once '../includes/sidebar.php';

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
        $thumbnail = null;

        if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === 0) {
            $allowed = ['jpg', 'jpeg', 'png'];
            $ext = strtolower(pathinfo($_FILES['thumbnail']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, $allowed)) {
                $filename = 'nut_' . time() . '_' . uniqid() . '.' . $ext;
                $target = '../../assets/uploads/nutritions/' . $filename;

                if (move_uploaded_file($_FILES['thumbnail']['tmp_name'], $target)) {
                    $thumbnail = $filename;
                } else {
                    $message = "❌ Gagal mengunggah gambar.";
                }
            } else {
                $message = "❌ Format gambar tidak diizinkan. Hanya JPG/PNG.";
            }
        }

        if (!$message) {
            $stmt = $pdo->prepare("
                INSERT INTO nutritions (category_id, name, description, calories, protein, carbs, fat, thumbnail, is_published)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([$category_id, $name, $description, $calories, $protein, $carbs, $fat, $thumbnail, $is_published]);
            $message = "✅ Nutrisi berhasil ditambahkan.";
        }
    }
}

// Ambil kategori untuk dropdown
$stmt = $pdo->query("SELECT id, name FROM nutrition_categories ORDER BY name ASC");
$categories = $stmt->fetchAll();
?>

<div class="main-content">
    <div class="container-fluid">
        <h2>➕ Tambah Nutrisi Baru</h2>
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
                                <input type="text" name="name" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Deskripsi</label>
                                <textarea name="description" class="form-control" rows="3"></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Kategori *</label>
                                <select name="category_id" class="form-select" required>
                                    <option value="">Pilih kategori</option>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Kalori (kcal)</label>
                                <input type="number" name="calories" class="form-control" min="0" value="0">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Protein (g)</label>
                                <input type="number" name="protein" class="form-control" min="0" value="0">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Karbohidrat (g)</label>
                                <input type="number" name="carbs" class="form-control" min="0" value="0">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Lemak (g)</label>
                                <input type="number" name="fat" class="form-control" min="0" value="0">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Thumbnail</label>
                                <input type="file" name="thumbnail" class="form-control" accept="image/*">
                                <small class="text-muted">JPG/PNG maks 2MB</small>
                            </div>
                            <div class="mb-3 form-check">
                                <input type="checkbox" name="is_published" class="form-check-input" id="published">
                                <label class="form-check-label" for="published">Terbitkan</label>
                            </div>
                            <button type="submit" class="btn btn-success">Tambah Nutrisi</button>
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