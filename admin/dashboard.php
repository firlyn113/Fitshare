<?php
require_once '../includes/db.php';
require_once 'includes/header.php';
require_once 'includes/sidebar.php';

// Ambil statistik dasar
$stmt = $pdo->query("SELECT COUNT(*) FROM exercises WHERE is_published = 1");
$published_exercises = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT COUNT(*) FROM nutritions WHERE is_published = 1");
$published_nutritions = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT COUNT(*) FROM roadmap_steps WHERE is_published = 1");
$published_roadmap = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT COUNT(*) FROM discussions");
$total_discussions = $stmt->fetchColumn();
?>

<div class="main-content">
    <div class="container-fluid">
        <h2>📊 Dashboard Admin</h2>
        <p class="text-muted">Selamat datang kembali! Berikut ringkasan konten FitShare.</p>

        <div class="row">
            <div class="col-md-3 mb-4">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h5>✅ Latihan Terpublikasi</h5>
                        <h2 class="mb-0"><?= $published_exercises ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h5>🥑 Nutrisi Terpublikasi</h5>
                        <h2 class="mb-0"><?= $published_nutritions ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <h5>🗺️ Tahapan Roadmap</h5>
                        <h2 class="mb-0"><?= $published_roadmap ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card bg-warning text-dark">
                    <div class="card-body">
                        <h5>💬 Diskusi</h5>
                        <h2 class="mb-0"><?= $total_discussions ?></h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="alert alert-info">
            <h5>🎯 Tips</h5>
            <ul>
                <li>Mulai dengan mengelola <strong>Kategori Latihan</strong> sebelum menambahkan data latihan.</li>
                <li>Thumbnail latihan/nutrisi akan disimpan di folder <code>assets/uploads/</code>.</li>
                <li>Klik <strong>Lihat Website</strong> di navbar untuk melihat tampilan publik.</li>
            </ul>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>