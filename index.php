<?php
// index.php
$title = 'FitShare - Panduan Gaya Hidup Sehat';
include 'includes/header.php';
?>

<!-- Hero Section -->
<section class="hero bg-primary text-white py-5 text-center">
    <div class="container">
        <h1 class="display-4 fw-bold">Mulai Gaya Hidup Sehat Hari Ini</h1>
        <p class="lead">Dengan FitShare, Anda tidak sendiri dalam perjalanan menuju kesehatan yang lebih baik.</p>
        <a href="#features" class="btn btn-light btn-lg mt-3">Jelajahi Fitur</a>
    </div>
</section>

<!-- Features Section -->
<section id="features" class="py-5">
    <div class="container">
        <h2 class="text-center mb-5">Fitur Utama</h2>
        <div class="row g-4">
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 text-center shadow-sm animate-card">
                    <div class="card-body d-flex flex-column align-items-center">
                        <i class="fas fa-route fa-3x text-primary mb-3"></i>
                        <h5 class="card-title">Roadmap</h5>
                        <p class="card-text">Panduan langkah demi langkah untuk pemula memulai fitness.</p>
                        <a href="roadmap.php" class="btn btn-outline-primary mt-auto">Mulai</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 text-center shadow-sm animate-card">
                    <div class="card-body d-flex flex-column align-items-center">
                        <i class="fas fa-dumbbell fa-3x text-success mb-3"></i>
                        <h5 class="card-title">Latihan</h5>
                        <p class="card-text">Daftar latihan dengan video panduan dan penjelasan.</p>
                        <a href="exercises.php" class="btn btn-outline-success mt-auto">Jelajahi</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 text-center shadow-sm animate-card">
                    <div class="card-body d-flex flex-column align-items-center">
                        <i class="fas fa-utensils fa-3x text-warning mb-3"></i>
                        <h5 class="card-title">Nutrisi</h5>
                        <p class="card-text">Informasi makanan sehat dan kandungan gizinya.</p>
                        <a href="nutrition.php" class="btn btn-outline-warning mt-auto">Lihat</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 text-center shadow-sm animate-card">
                    <div class="card-body d-flex flex-column align-items-center">
                        <i class="fas fa-comments fa-3x text-info mb-3"></i>
                        <h5 class="card-title">Diskusi</h5>
                        <p class="card-text">Bertanya, berbagi, dan saling mendukung.</p>
                        <a href="discussion.php" class="btn btn-outline-info mt-auto">Bergabung</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="py-5 bg-light">
    <div class="container text-center">
        <h2>Kenapa Memilih FitShare?</h2>
        <div class="row mt-4">
            <div class="col-md-3">
                <h3 class="text-primary">100+</h3>
                <p>Latihan Terarah</p>
            </div>
            <div class="col-md-3">
                <h3 class="text-success">50+</h3>
                <p>Makanan Sehat</p>
            </div>
            <div class="col-md-3">
                <h3 class="text-warning">20+</h3>
                <p>Tahapan Roadmap</p>
            </div>
            <div class="col-md-3">
                <h3 class="text-info">1000+</h3>
                <p>Komunitas Peduli</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-5 bg-dark text-white text-center">
    <div class="container">
        <h2 class="mb-4">Siap Memulai?</h2>
        <p class="lead mb-4">Gabung ribuan orang lainnya yang sudah memulai perjalanan sehat mereka.</p>
        <a href="roadmap.php" class="btn btn-light btn-lg">Mulai Roadmap Saya</a>
    </div>
</section>

<!-- Dark Mode Toggle -->
<div class="position-fixed bottom-0 end-0 p-3">
    <button id="darkModeToggle" class="btn btn-outline-light dark-mode-toggle shadow">
        <i class="fas fa-moon"></i> Mode Gelap
    </button>
</div>

<?php
include 'includes/footer.php';
?>