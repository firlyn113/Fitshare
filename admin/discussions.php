<?php
// admin/discussions.php
require_once '../includes/db.php';
require_once 'includes/header.php';
require_once 'includes/sidebar.php';

// Handle delete post
if (isset($_GET['delete_post'])) {
    $id = (int)$_GET['delete_post'];
    $pdo->prepare("DELETE FROM discussions WHERE id = ?")->execute([$id]);
    $message = "✅ Post berhasil dihapus.";
}

// Handle delete reply
if (isset($_GET['delete_reply'])) {
    $id = (int)$_GET['delete_reply'];
    $pdo->prepare("DELETE FROM discussion_replies WHERE id = ?")->execute([$id]);
    $message = "✅ Balasan berhasil dihapus.";
}

// Ambil semua diskusi dengan jumlah balasan
$stmt = $pdo->query("
    SELECT d.*, 
           (SELECT COUNT(*) FROM discussion_replies dr WHERE dr.discussion_id = d.id) as reply_count
    FROM discussions d 
    ORDER BY d.created_at DESC
");
$discussions = $stmt->fetchAll();
?>

<div class="main-content">
    <div class="container-fluid">
        <h2>💬 Manajemen Diskusi</h2>
        <?php if (isset($message)): ?>
            <div class="alert alert-success"><?= $message ?></div>
        <?php endif; ?>

        <div class="card">
            <div class="card-body">
                <?php if (empty($discussions)): ?>
                    <div class="alert alert-info text-center">Belum ada diskusi.</div>
                <?php else: ?>
                    <?php foreach ($discussions as $d): ?>
                        <div class="border p-3 mb-3 rounded bg-light">
                            <div class="d-flex justify-content-between">
                                <strong>Dari: <?= htmlspecialchars($d['nickname']) ?></strong>
                                <small class="text-muted"><?= date('d M Y H:i', strtotime($d['created_at'])) ?></small>
                            </div>
                            <p class="my-2"><?= nl2br(htmlspecialchars($d['content'])) ?></p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge bg-secondary"><?= $d['reply_count'] ?> balasan</span>
                                <a href="?delete_post=<?= $d['id'] ?>" class="btn btn-sm btn-outline-danger" 
                                   onclick="return confirm('Hapus post ini beserta semua balasannya?')">
                                    🗑️ Hapus Post
                                </a>
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
                                <div class="mt-3 ms-3">
                                    <?php foreach ($replies as $r): ?>
                                        <div class="border-start border-primary ps-3 py-2 my-2 bg-white">
                                            <div class="d-flex justify-content-between">
                                                <strong><?= htmlspecialchars($r['nickname']) ?></strong>
                                                <small class="text-muted"><?= date('d M Y H:i', strtotime($r['created_at'])) ?></small>
                                            </div>
                                            <p class="mb-1"><?= nl2br(htmlspecialchars($r['content'])) ?></p>
                                            <a href="?delete_reply=<?= $r['id'] ?>" class="btn btn-sm btn-outline-danger"
                                               onclick="return confirm('Hapus balasan ini?')">
                                                🗑️ Hapus
                                            </a>

                                            <!-- Nested Replies -->
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
                                                <div class="mt-2 ms-3">
                                                    <?php foreach ($nested_replies as $nr): ?>
                                                        <div class="border-start border-info ps-3 py-2 my-2 bg-light">
                                                            <div class="d-flex justify-content-between">
                                                                <strong><?= htmlspecialchars($nr['nickname']) ?></strong>
                                                                <small class="text-muted"><?= date('d M Y H:i', strtotime($nr['created_at'])) ?></small>
                                                            </div>
                                                            <p class="mb-1"><?= nl2br(htmlspecialchars($nr['content'])) ?></p>
                                                            <a href="?delete_reply=<?= $nr['id'] ?>" class="btn btn-sm btn-outline-danger"
                                                               onclick="return confirm('Hapus balasan ini?')">
                                                                🗑️ Hapus
                                                            </a>
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
    </div>
</div>

</body>
</html>