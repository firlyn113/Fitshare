<?php
// roadmap.php
$title = 'Roadmap - FitShare';
include 'includes/header.php';

require_once 'includes/db.php';

// Ambil semua tahapan roadmap yang diterbitkan, urutkan berdasarkan order_index
$stmt = $pdo->query("
    SELECT * FROM roadmap_steps 
    WHERE is_published = 1 
    ORDER BY order_index ASC
");
$steps = $stmt->fetchAll();
?>

<div class="container my-5">
    <div class="text-center mb-5">
        <h1 class="display-5 fw-bold">Roadmap untuk Pemula</h1>
        <p class="lead">Mulai dari dasar dan bangun kebiasaan sehat secara bertahap. Centang tahapan yang sudah kamu selesaikan!</p>
        <button id="resetProgress" class="btn btn-warning mt-2">🔄 Reset Progres</button>
    </div>

    <?php if (empty($steps)): ?>
        <div class="alert alert-info text-center">
            Belum ada tahapan roadmap yang diterbitkan.
        </div>
    <?php else: ?>
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <!-- Timeline -->
                <div class="timeline">
                    <?php foreach ($steps as $index => $step): ?>
                        <div class="timeline-item">
                            <div class="timeline-marker bg-primary text-white rounded-circle d-flex align-items-center justify-content-center">
                                <?= $step['order_index'] ?>
                            </div>
                            <div class="timeline-content card shadow-sm">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <h5 class="card-title text-primary roadmap-title" 
                                            id="title-<?= $step['id'] ?>">
                                            <?= htmlspecialchars($step['title']) ?>
                                        </h5>
                                        <div class="form-check">
                                            <input class="form-check-input roadmap-checkbox" 
                                                   type="checkbox" 
                                                   value="<?= $step['id'] ?>" 
                                                   id="check-<?= $step['id'] ?>">
                                            <label class="form-check-label" for="check-<?= $step['id'] ?>">
                                                Selesai
                                            </label>
                                        </div>
                                    </div>
                                    <div class="card-text roadmap-content" id="content-<?= $step['id'] ?>">
                                        <?= $step['content'] ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<style>
.timeline {
    position: relative;
    padding: 2rem 0;
}

.timeline::before {
    content: '';
    position: absolute;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #dee2e6;
    left: 1rem;
    margin-left: -1px;
}

.timeline-item {
    position: relative;
    margin-bottom: 2rem;
}

.timeline-marker {
    position: absolute;
    width: 40px;
    height: 40px;
    z-index: 1;
}

.timeline-content {
    margin-left: 3rem;
    padding: 1.5rem;
}

/* Gaya untuk yang sudah dicentang */
.roadmap-title.checked {
    text-decoration: line-through;
    color: #6c757d;
}
.roadmap-content.checked {
    color: #6c757d;
}

body.dark-mode .timeline::before {
    background: #444;
}

body.dark-mode .timeline-content {
    background-color: #2c2c2c;
    border: 1px solid #444;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const checkboxes = document.querySelectorAll('.roadmap-checkbox');
    const resetBtn = document.getElementById('resetProgress');

    // Muat progres dari localStorage
    function loadProgress() {
        const saved = JSON.parse(localStorage.getItem('roadmapProgress')) || [];
        checkboxes.forEach(checkbox => {
            const stepId = parseInt(checkbox.value);
            if (saved.includes(stepId)) {
                checkbox.checked = true;
                markAsCompleted(stepId);
            }
        });
    }

    // Simpan progres ke localStorage
    function saveProgress() {
        const completed = Array.from(checkboxes)
            .filter(cb => cb.checked)
            .map(cb => parseInt(cb.value));
        localStorage.setItem('roadmapProgress', JSON.stringify(completed));
    }

    // Tandai elemen sebagai selesai
    function markAsCompleted(stepId) {
        const title = document.getElementById(`title-${stepId}`);
        const content = document.getElementById(`content-${stepId}`);
        title.classList.add('checked');
        content.classList.add('checked');
    }

    // Hapus centang dari elemen
    function unmarkAsCompleted(stepId) {
        const title = document.getElementById(`title-${stepId}`);
        const content = document.getElementById(`content-${stepId}`);
        title.classList.remove('checked');
        content.classList.remove('checked');
    }

    // Event listener untuk checkbox
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function () {
            const stepId = parseInt(this.value);
            if (this.checked) {
                markAsCompleted(stepId);
            } else {
                unmarkAsCompleted(stepId);
            }
            saveProgress();
        });
    });

    // Reset semua progres
    resetBtn.addEventListener('click', function () {
        if (confirm('Yakin ingin mereset semua progres?')) {
            checkboxes.forEach(checkbox => {
                checkbox.checked = false;
                const stepId = parseInt(checkbox.value);
                unmarkAsCompleted(stepId);
            });
            localStorage.removeItem('roadmapProgress');
        }
    });

    // Muat progres saat halaman dimuat
    loadProgress();
});
</script>

<?php
include 'includes/footer.php';
?>