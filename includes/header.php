<?php
// includes/header.php
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'FitShare - Panduan Gaya Hidup Sehat' ?></title>
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome (ikon) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <!-- CSS Kustom -->
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- Font Inter & Poppins -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@600;700&display=swap" rel="stylesheet">
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">FitShare</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">🏠 Beranda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="roadmap.php">🗺️ Roadmap</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="exercises.php">🏋️ Latihan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="nutrition.php">🥗 Nutrisi</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="discussion.php">💬 Diskusi</a>
                </li>
            </ul>
        </div>
    </div>
</nav>