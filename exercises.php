<?php
// exercises.php
$title = 'Latihan - FitShare';
include 'includes/header.php';

require_once 'includes/db.php';

// Ambil kategori untuk filter
$stmt = $pdo->query("SELECT id, name FROM exercise_categories ORDER BY name ASC");
$categories = $stmt->fetchAll();

// Ambil latihan berdasarkan filter
$category_filter = (int)($_GET['cat'] ?? 0);
$search = trim($_GET['q'] ?? '');

$conditions = [];
$params = [];

if ($category_filter) {
    $conditions[] = "e.category_id = ?";
    $params[] = $category_filter;
}

if ($search) {
    $conditions[] = "(e.title LIKE ? OR e.description LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

$conditions[] = "e.is_published = 1";

$whereClause = "WHERE " . implode(" AND ", $conditions);
$stmt = $pdo->prepare("
    SELECT e.*, ec.name as category_name 
    FROM exercises e 
    LEFT JOIN exercise_categories ec ON e.category_id = ec.id 
    $whereClause
    ORDER BY e.title ASC
");
$stmt->execute($params);
$exercises = $stmt->fetchAll();
?>

<div class="container my-5">
    <div class="text-center mb-5">
        <h1 class="display-5 fw-bold">Latihan untuk Anda</h1>
        <p class="lead">Temukan latihan sesuai kebutuhan dan tingkat kesulitan Anda.</p>
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
            <input type="text" id="searchInput" class="form-control" placeholder="Cari latihan..." value="<?= htmlspecialchars($search) ?>">
        </div>
    </div>

    <!-- Daftar Latihan -->
    <?php if (empty($exercises)): ?>
        <div class="alert alert-info text-center">
            Latihan tidak ditemukan.
        </div>
    <?php else: ?>
        <div class="row g-4" id="exerciseGrid">
            <?php foreach ($exercises as $e): ?>
                <div class="col-md-6 col-lg-4 exercise-item">
                    <div class="card h-100 shadow-sm">
                        <?php if ($e['thumbnail']): ?>
                            <img src="assets/uploads/exercises/<?= htmlspecialchars($e['thumbnail']) ?>" 
                                 class="card-img-top yt-thumb" 
                                 alt="<?= htmlspecialchars($e['title']) ?>" 
                                 data-yt-url="<?= htmlspecialchars($e['youtube_url']) ?>"
                                 style="height: 180px; object-fit: cover; cursor: pointer;">
                        <?php else: ?>
                            <div class="bg-light d-flex align-items-center justify-content-center" style="height: 180px;">
                                <i class="fas fa-dumbbell text-muted fa-3x"></i>
                            </div>
                        <?php endif; ?>
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><?= htmlspecialchars($e['title']) ?></h5>
                            <p class="card-text flex-grow-1"><?= htmlspecialchars(substr(strip_tags($e['description']), 0, 100)) ?>...</p>
                            <div class="mt-auto">
                                <span class="badge bg-secondary mb-2"><?= htmlspecialchars($e['category_name']) ?></span>
                                <div class="d-flex justify-content-between">
                                    <small class="text-muted"><?= ucfirst($e['difficulty']) ?></small>
                                    <small class="text-muted"><?= htmlspecialchars($e['duration_estimate']) ?></small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<!-- Modal YouTube -->
<div class="modal fade" id="ytModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Video Latihan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <div class="ratio ratio-16x9">
                    <iframe id="ytFrame" src="" title="YouTube video player" frameborder="0" allowfullscreen></iframe>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Filter berdasarkan kategori
    document.getElementById('categoryFilter').addEventListener('change', function () {
        const catId = this.value;
        const search = document.getElementById('searchInput').value;
        let url = 'exercises.php';
        if (catId) url += '?cat=' + catId;
        if (search) url += (url.includes('?') ? '&' : '?') + 'q=' + encodeURIComponent(search);
        window.location.href = url;
    });

    // Filter pencarian
    document.getElementById('searchInput').addEventListener('keyup', function (e) {
        if (e.key === 'Enter') {
            const search = this.value;
            const catId = document.getElementById('categoryFilter').value;
            let url = 'exercises.php';
            if (search) url += '?q=' + encodeURIComponent(search);
            if (catId) url += (url.includes('?') ? '&' : '?') + 'cat=' + catId;
            window.location.href = url;
        }
    });

    // Klik thumbnail buka YouTube
    document.querySelectorAll('.yt-thumb').forEach(img => {
        img.addEventListener('click', function () {
            const url = this.dataset.ytUrl;
            if (url) {
                // Ubah URL agar bisa embed
                let embedUrl = url;
                if (url.includes('watch?v=')) {
                    embedUrl = url.replace('watch?v=', 'embed/');
                } else if (url.includes('youtu.be/')) {
                    embedUrl = url.replace('youtu.be/', 'youtube.com/embed/');
                }
                document.getElementById('ytFrame').src = embedUrl;
                new bootstrap.Modal(document.getElementById('ytModal')).show();
            }
        });
    });

    // Reset iframe saat modal ditutup
    document.getElementById('ytModal').addEventListener('hidden.bs.modal', function () {
        document.getElementById('ytFrame').src = '';
    });
});
</script>

<?php
include 'includes/footer.php';
?>