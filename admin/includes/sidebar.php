<?php
// admin/includes/sidebar.php
// Gunakan path absolut dari root web (dimulai dengan /)
// Ini akan selalu benar, tidak peduli dari folder mana halaman dipanggil
?>
<div class="sidebar d-none d-lg-block" style="width: 250px; overflow-y: auto;">
    <div class="p-3">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active fw-bold' : '' ?>" 
                   href="/Projek_firly/admin/dashboard.php">
                    📊 Dashboard
                </a>
            </li>
            <li class="nav-item mt-2">
                <strong class="text-muted">KONTEN</strong>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= strpos($_SERVER['PHP_SELF'], 'roadmap') !== false ? 'active' : '' ?>" 
                   href="/Projek_firly/admin/roadmap/">
                    🗺️ Roadmap
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= strpos($_SERVER['PHP_SELF'], 'categories/exercise') !== false || strpos($_SERVER['PHP_SELF'], 'exercises') !== false ? 'active' : '' ?>" 
                   href="/Projek_firly/admin/categories/exercise_categories.php">
                    🏋️ Kategori Latihan
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= strpos($_SERVER['PHP_SELF'], 'exercises') !== false ? 'active' : '' ?>" 
                   href="/Projek_firly/admin/exercises/">
                    🎯 Latihan
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= strpos($_SERVER['PHP_SELF'], 'categories/nutrition') !== false || strpos($_SERVER['PHP_SELF'], 'nutritions') !== false ? 'active' : '' ?>" 
                   href="/Projek_firly/admin/categories/nutrition_categories.php">
                    🥗 Kategori Nutrisi
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= strpos($_SERVER['PHP_SELF'], 'nutritions') !== false ? 'active' : '' ?>" 
                   href="/Projek_firly/admin/nutritions/">
                    🥑 Nutrisi
                </a>
            </li>
            <li class="nav-item mt-2">
                <strong class="text-muted">LAINNYA</strong>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= strpos($_SERVER['PHP_SELF'], 'discussions') !== false ? 'active' : '' ?>" 
                   href="/Projek_firly/admin/discussions.php">
                    💬 Diskusi
                </a>
            </li>
        </ul>
    </div>
</div>