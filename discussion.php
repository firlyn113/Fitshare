<?php
// discussion.php
$title = 'Diskusi - FitShare';
include 'includes/header.php';

require_once 'includes/db.php';

// Handle submit post baru
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_post'])) {
    $nickname = trim($_POST['nickname'] ?? 'Anon');
    $content = trim($_POST['content'] ?? '');

    if ($content) {
        // Validasi sederhana: pastikan tidak spam (isi tidak hanya simbol)
        if (preg_match('/^[!@#$%^&*()_+\-=\[\]{};\':"\\|,.<>\/? ]+$/', $content)) {
            $error = "❌ Konten tidak valid.";
        } else {
            $stmt = $pdo->prepare("INSERT INTO discussions (nickname, content) VALUES (?, ?)");
            $stmt->execute([htmlspecialchars($nickname, ENT_QUOTES), htmlspecialchars($content, ENT_QUOTES)]);
        }
    }
}

// Handle submit reply
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_reply'])) {
    $discussion_id = (int)($_POST['discussion_id'] ?? 0);
    $parent_reply_id = (int)($_POST['parent_reply_id'] ?? null);
    $nickname = trim($_POST['reply_nickname'] ?? 'Anon');
    $content = trim($_POST['reply_content'] ?? '');

    if ($discussion_id && $content) {
        $stmt = $pdo->prepare("INSERT INTO discussion_replies (discussion_id, parent_reply_id, nickname, content) VALUES (?, ?, ?, ?)");
        $stmt->execute([$discussion_id, $parent_reply_id ?: null, htmlspecialchars($nickname, ENT_QUOTES), htmlspecialchars($content, ENT_QUOTES)]);
    }
}

// Ambil semua post (dengan jumlah reply)
$stmt = $pdo->query("
    SELECT d.*, 
           (SELECT COUNT(*) FROM discussion_replies dr WHERE dr.discussion_id = d.id) as reply_count
    FROM discussions d 
    ORDER BY d.created_at DESC
");
$discussions = $stmt->fetchAll();
?>

<div class="container my-5">
    <div class="text-center mb-5">
        <h1 class="display-5 fw-bold">Forum Diskusi</h1>
        <p class="lead">Berbagi cerita, tanya jawab, dan saling mendukung dalam perjalanan sehat Anda.</p>
    </div>

    <!-- Form Tambah Post -->
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <h5>Bagikan Cerita atau Tanyakan Sesuatu</h5>
            <form method="POST">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Nama (opsional)</label>
                        <input type="text" name="nickname" class="form-control" placeholder="FitFanatic123" maxlength="50">
                    </div>
                    <div class="col-md-8 mb-3">
                        <label class="form-label">Pesan *</label>
                        <textarea name="content" class="form-control" rows="3" required placeholder="Apa yang ingin Anda bagikan atau tanyakan?"></textarea>
                    </div>
                </div>
                <button type="submit" name="submit_post" class="btn btn-primary">Kirim</button>
            </form>
        </div>
    </div>

    <!-- Daftar Diskusi -->
    <div class="discussion-list">
        <?php if (empty($discussions)): ?>
            <div class="alert alert-info text-center">
                Belum ada diskusi. Jadilah yang pertama untuk memulai!
            </div>
        <?php else: ?>
            <?php foreach ($discussions as $d): ?>
                <div class="card mb-4 shadow-sm discussion-post">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <div>
                            <strong><?= htmlspecialchars($d['nickname']) ?></strong>
                            <small class="text-muted">• <?= date('d M Y H:i', strtotime($d['created_at'])) ?></small>
                        </div>
                        <span class="badge bg-secondary"><?= $d['reply_count'] ?> balasan</span>
                    </div>
                    <div class="card-body">
                        <p class="card-text"><?= nl2br(htmlspecialchars($d['content'])) ?></p>
                        <button class="btn btn-sm btn-outline-primary reply-btn" data-discussion-id="<?= $d['id'] ?>">
                            💬 Balas
                        </button>
                    </div>

                    <!-- Form Balasan -->
                    <div class="reply-form-container d-none">
                        <div class="card-footer bg-light">
                            <form method="POST" class="reply-form">
                                <input type="hidden" name="discussion_id" value="<?= $d['id'] ?>">
                                <div class="row">
                                    <div class="col-md-4 mb-2">
                                        <input type="text" name="reply_nickname" class="form-control form-control-sm" placeholder="Nama Anda" maxlength="50">
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <input type="text" name="reply_content" class="form-control form-control-sm" placeholder="Tulis balasan..." required>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="submit" name="submit_reply" class="btn btn-sm btn-success w-100">Kirim</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Daftar Balasan -->
                    <?php
                    $stmt = $pdo->prepare("
                        SELECT * FROM discussion_replies 
                        WHERE discussion_id = ? AND parent_reply_id IS NULL 
                        ORDER BY created_at ASC
                    ");
                    $stmt->execute([$d['id']]);
                    $replies = $stmt->fetchAll();
                    ?>
                    <?php if ($replies): ?>
                        <div class="card-body bg-light border-top">
                            <?php foreach ($replies as $r): ?>
                                <div class="reply-item mb-3 p-2 border-start border-primary border-3 bg-white rounded">
                                    <div class="d-flex justify-content-between">
                                        <strong><?= htmlspecialchars($r['nickname']) ?></strong>
                                        <small class="text-muted"><?= date('d M Y H:i', strtotime($r['created_at'])) ?></small>
                                    </div>
                                    <p class="mb-1"><?= nl2br(htmlspecialchars($r['content'])) ?></p>
                                    <button class="btn btn-sm btn-outline-secondary btn-reply-to" 
                                            data-discussion-id="<?= $d['id'] ?>" 
                                            data-parent-id="<?= $r['id'] ?>">
                                        🔄 Balas
                                    </button>

                                    <!-- Balasan dari balasan (nested reply) -->
                                    <?php
                                    $stmt_nested = $pdo->prepare("
                                        SELECT * FROM discussion_replies 
                                        WHERE parent_reply_id = ? 
                                        ORDER BY created_at ASC
                                    ");
                                    $stmt_nested->execute([$r['id']]);
                                    $nested_replies = $stmt_nested->fetchAll();
                                    ?>
                                    <?php if ($nested_replies): ?>
                                        <div class="nested-replies mt-2 ms-3">
                                            <?php foreach ($nested_replies as $nr): ?>
                                                <div class="reply-item p-2 border-start border-info border-2 bg-light rounded">
                                                    <div class="d-flex justify-content-between">
                                                        <strong><?= htmlspecialchars($nr['nickname']) ?></strong>
                                                        <small class="text-muted"><?= date('d M Y H:i', strtotime($nr['created_at'])) ?></small>
                                                    </div>
                                                    <p class="mb-0"><?= nl2br(htmlspecialchars($nr['content'])) ?></p>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Toggle form balasan utama
    document.querySelectorAll('.reply-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const container = this.closest('.discussion-post').querySelector('.reply-form-container');
            container.classList.toggle('d-none');
        });
    });

    // Toggle form balasan ke reply tertentu
    document.querySelectorAll('.btn-reply-to').forEach(btn => {
        btn.addEventListener('click', function () {
            const discussionId = this.dataset.discussionId;
            const parentId = this.dataset.parentId;
            const postContainer = this.closest('.discussion-post');
            let formContainer = postContainer.querySelector('.reply-form-container');

            // Jika form belum ada, buat
            if (!formContainer) {
                formContainer = document.createElement('div');
                formContainer.className = 'reply-form-container d-none';
                formContainer.innerHTML = `
                    <div class="card-footer bg-light">
                        <form method="POST" class="reply-form">
                            <input type="hidden" name="discussion_id" value="${discussionId}">
                            <input type="hidden" name="parent_reply_id" value="${parentId}">
                            <div class="row">
                                <div class="col-md-4 mb-2">
                                    <input type="text" name="reply_nickname" class="form-control form-control-sm" placeholder="Nama Anda" maxlength="50">
                                </div>
                                <div class="col-md-6 mb-2">
                                    <input type="text" name="reply_content" class="form-control form-control-sm" placeholder="Tulis balasan..." required>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" name="submit_reply" class="btn btn-sm btn-success w-100">Kirim</button>
                                </div>
                            </div>
                        </form>
                    </div>
                `;
                // Sisipkan setelah elemen tombol
                this.parentNode.parentNode.parentNode.insertBefore(formContainer, this.parentNode.parentNode.nextSibling);
            }

            // Toggle tampilan
            formContainer.classList.toggle('d-none');
        });
    });
});
</script>

<?php
include 'includes/footer.php';
?>