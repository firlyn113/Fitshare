<?php
// nutrition.php
$title = 'Nutrisi - FitShare';
include 'includes/header.php';

require_once 'includes/db.php';

// Ambil kategori untuk filter
$stmt = $pdo->query("SELECT id, name FROM nutrition_categories ORDER BY name ASC");
$categories = $stmt->fetchAll();

// Ambil nutrisi berdasarkan filter
$category_filter = (int)($_GET['cat'] ?? 0);
$search = trim($_GET['q'] ?? '');

$conditions = [];
$params = [];

if ($category_filter) {
    $conditions[] = "n.category_id = ?";
    $params[] = $category_filter;
}

if ($search) {
    $conditions[] = "(n.name LIKE ? OR n.description LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

$conditions[] = "n.is_published = 1";

$whereClause = "WHERE " . implode(" AND ", $conditions);
$stmt = $pdo->prepare("
    SELECT n.*, nc.name as category_name 
    FROM nutritions n 
    LEFT JOIN nutrition_categories nc ON n.category_id = nc.id 
    $whereClause
    ORDER BY n.name ASC
");
$stmt->execute($params);
$nutritions = $stmt->fetchAll();
?>

<div class="container my-5">
    <div class="text-center mb-5">
        <h1 class="display-5 fw-bold">Nutrisi Sehat untuk Anda</h1>
        <p class="lead">Temukan makanan dan minuman sehat beserta kandungan gizinya.</p>
    </div>

    <!-- Filter -->
    <div class="row mb-4">
        <div class="col-md-6">
            <select class="form-select" id="categoryFilter">
                <option value="">Semua Kategori</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= $category_filter == $cat['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-6">
            <input type="text" id="searchInput" class="form-control" placeholder="Cari makanan/minuman..." value="<?= htmlspecialchars($search) ?>">
        </div>
    </div>

    <!-- Daftar Nutrisi -->
    <?php if (empty($nutritions)): ?>
        <div class="alert alert-info text-center">
            Makanan/minuman tidak ditemukan.
        </div>
    <?php else: ?>
        <div class="row g-4" id="nutritionGrid">
            <?php foreach ($nutritions as $n): ?>
                <div class="col-md-6 col-lg-4 nutrition-item">
                    <div class="card h-100 shadow-sm">
                        <?php if ($n['thumbnail']): ?>
                            <img src="assets/uploads/nutritions/<?= htmlspecialchars($n['thumbnail']) ?>" 
                                 class="card-img-top nutrition-thumb" 
                                 alt="<?= htmlspecialchars($n['name']) ?>"
                                 data-bs-toggle="modal" 
                                 data-bs-target="#nutritionModal<?= $n['id'] ?>"
                                 style="height: 180px; object-fit: cover; cursor: pointer;">
                        <?php else: ?>
                            <div class="bg-light d-flex align-items-center justify-content-center nutrition-thumb" 
                                 data-bs-toggle="modal" 
                                 data-bs-target="#nutritionModal<?= $n['id'] ?>"
                                 style="height: 180px; cursor: pointer;">
                                <i class="fas fa-utensils text-muted fa-3x"></i>
                            </div>
                        <?php endif; ?>
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><?= htmlspecialchars($n['name']) ?></h5>
                            <p class="card-text flex-grow-1"><?= htmlspecialchars(substr(strip_tags($n['description']), 0, 100)) ?>...</p>
                            <div class="mt-auto">
                                <span class="badge bg-secondary mb-2"><?= htmlspecialchars($n['category_name']) ?></span>
                                <div class="nutrition-facts mt-2">
                                    <small class="d-block">
                                        <strong>Kal:</strong> <?= $n['calories'] ?> kcal |
                                        <strong>Protein:</strong> <?= $n['protein'] ?>g
                                    </small>
                                    <small class="d-block">
                                        <strong>Karbo:</strong> <?= $n['carbs'] ?>g |
                                        <strong>Lemak:</strong> <?= $n['fat'] ?>g
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Detail Nutrisi -->
                <div class="modal fade" id="nutritionModal<?= $n['id'] ?>" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title"><?= htmlspecialchars($n['name']) ?></h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body text-center">
                                <?php if ($n['thumbnail']): ?>
                                    <img src="assets/uploads/nutritions/<?= htmlspecialchars($n['thumbnail']) ?>" 
                                         class="img-fluid rounded mb-3" 
                                         alt="<?= htmlspecialchars($n['name']) ?>"
                                         style="max-height: 250px; object-fit: cover;">
                                <?php endif; ?>
                                
                                <p class="lead"><?= htmlspecialchars($n['description']) ?: 'Tidak ada deskripsi.' ?></p>

                                <div class="nutrition-details bg-light p-3 rounded">
                                    <h6>Informasi Gizi (per porsi)</h6>
                                    <div class="row text-center">
                                        <div class="col-3">
                                            <div class="bg-white p-2 rounded">
                                                <strong>Calories</strong><br>
                                                <?= $n['calories'] ?> kcal
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="bg-white p-2 rounded">
                                                <strong>Protein</strong><br>
                                                <?= $n['protein'] ?>g
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="bg-white p-2 rounded">
                                                <strong>Karbo</strong><br>
                                                <?= $n['carbs'] ?>g
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="bg-white p-2 rounded">
                                                <strong>Lemak</strong><br>
                                                <?= $n['fat'] ?>g
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <span class="badge bg-secondary"><?= htmlspecialchars($n['category_name']) ?></span>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Filter berdasarkan kategori
    document.getElementById('categoryFilter').addEventListener('change', function () {
        const catId = this.value;
        const search = document.getElementById('searchInput').value;
        let url = 'nutrition.php';
        if (catId) url += '?cat=' + catId;
        if (search) url += (url.includes('?') ? '&' : '?') + 'q=' + encodeURIComponent(search);
        window.location.href = url;
    });

    // Filter pencarian
    document.getElementById('searchInput').addEventListener('keyup', function (e) {
        if (e.key === 'Enter') {
            const search = this.value;
            const catId = document.getElementById('categoryFilter').value;
            let url = 'nutrition.php';
            if (search) url += '?q=' + encodeURIComponent(search);
            if (catId) url += (url.includes('?') ? '&' : '?') + 'cat=' + catId;
            window.location.href = url;
        }
    });
});
</script>

<?php
include 'includes/footer.php';
?>