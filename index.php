<?php
$title = 'FitShare - Panduan Gaya Hidup Sehat';
include 'includes/header.php';
?>

<!-- Hero Section -->
<section class="hero">
  <div class="container text-center">
    <h1 class="text-white">Mulai Gaya Hidup Sehat Hari Ini</h1>
    <p class="lead text-white">Dengan FitShare, Anda tidak sendiri dalam perjalanan menuju kesehatan yang lebih baik.</p>
    <div class="mt-4">
      <a href="#features" class="btn btn-light btn-lg">Jelajahi Fitur</a>
      <a href="roadmap.php" class="btn btn-outline-light btn-lg">Mulai Roadmap</a>
    </div>
  </div>
</section>

<!-- Features Section -->
<section id="features" class="py-5">
  <div class="container">
    <h2 class="text-center mb-5 fw-bold display-5">Fitur Utama</h2>
    <div class="row g-4">
      <!-- Roadmap -->
      <div class="col-md-6 col-lg-3">
        <div class="feature-card">
          <div class="feature-icon bg-primary">
            <i class="fas fa-route"></i>
          </div>
          <h5>Roadmap</h5>
          <p>Panduan langkah demi langkah untuk pemula memulai fitness.</p>
          <a href="roadmap.php" class="btn btn-outline-primary">Mulai Sekarang</a>
        </div>
      </div>
      <!-- Latihan -->
      <div class="col-md-6 col-lg-3">
        <div class="feature-card">
          <div class="feature-icon bg-success">
            <i class="fas fa-dumbbell"></i>
          </div>
          <h5>Latihan</h5>
          <p>Daftar latihan dengan video panduan dan penjelasan gerakan.</p>
          <a href="exercises.php" class="btn btn-outline-success">Jelajahi</a>
        </div>
      </div>
      <!-- Nutrisi -->
      <div class="col-md-6 col-lg-3">
        <div class="feature-card">
          <div class="feature-icon bg-warning">
            <i class="fas fa-utensils"></i>
          </div>
          <h5>Nutrisi</h5>
          <p>Informasi makanan sehat dan kandungan gizinya.</p>
          <a href="nutrition.php" class="btn btn-outline-warning">Lihat Database</a>
        </div>
      </div>
      <!-- Diskusi -->
      <div class="col-md-6 col-lg-3">
        <div class="feature-card">
          <div class="feature-icon bg-info">
            <i class="fas fa-comments"></i>
          </div>
          <h5>Komunitas</h5>
          <p>Bertanya, berbagi, dan saling mendukung dalam forum aktif.</p>
          <a href="discussion.php" class="btn btn-outline-info">Gabung Sekarang</a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Stats Section -->
<section class="stats-section py-5">
  <div class="container">
    <h2 class="text-center mb-5 fw-bold display-5">Kenapa Memilih FitShare?</h2>
    <div class="row text-center">
      <div class="col-md-3">
        <div class="stat-item">
          <div class="stat-number">100+</div>
          <div class="stat-label">Latihan Terarah</div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="stat-item">
          <div class="stat-number">50+</div>
          <div class="stat-label">Makanan Sehat</div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="stat-item">
          <div class="stat-number">25+</div>
          <div class="stat-label">Tahapan Roadmap</div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="stat-item">
          <div class="stat-number">2.5K+</div>
          <div class="stat-label">Anggota Aktif</div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- CTA Section -->
<section class="cta-section">
  <div class="container text-center">
    <h2 class="text-white">Siap Memulai Transformasi Anda?</h2>
    <p class="lead text-white">Gabung ribuan orang lainnya yang sudah memulai perjalanan sehat mereka.</p>
    <div class="mt-4">
      <a href="register.php" class="btn btn-success btn-lg">Daftar Gratis</a>
      <a href="roadmap.php" class="btn btn-outline-light btn-lg">Lihat Demo</a>
    </div>
    <div class="trust-badges text-white">
      <span>✅ Gratis Selamanya</span>
      <span>✅ Tanpa Iklan</span>
      <span>✅ Data Pribadi Aman</span>
    </div>
  </div>
</section>

<!-- Dark Mode Toggle -->
<!-- <button id="darkModeToggle" class="dark-mode-toggle" aria-label="Toggle dark mode">
  <i class="fas fa-moon"></i>
</button> -->

<?php include 'includes/footer.php'; ?>

<!-- Dark Mode JS -->
<script>
document.addEventListener('DOMContentLoaded', () => {
  const toggle = document.getElementById('darkModeToggle');
  const icon = toggle.querySelector('i');
  
  // Cek tema saat ini
  const currentTheme = localStorage.getItem('theme') || 
                      (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
  
  // Terapkan tema
  document.documentElement.setAttribute('data-theme', currentTheme);
  if (currentTheme === 'dark') {
    icon.classList.replace('fa-moon', 'fa-sun');
  }

  // Toggle tema
  toggle.addEventListener('click', () => {
    const newTheme = document.documentElement.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
    document.documentElement.setAttribute('data-theme', newTheme);
    localStorage.setItem('theme', newTheme);
    
    if (newTheme === 'dark') {
      icon.classList.replace('fa-moon', 'fa-sun');
    } else {
      icon.classList.replace('fa-sun', 'fa-moon');
    }
  });
});
</script>