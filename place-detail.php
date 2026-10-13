<?php
require __DIR__ . '/config/db.php';

$uploadUrl = '/travel/uploads/';
$id = (int) ($_GET['id'] ?? 0);

$stmt = $pdo->prepare(
    'SELECT p.id, p.category_id, p.name, p.province, p.description, p.location, p.image,
            c.name AS category_name
     FROM places p
     LEFT JOIN categories c ON p.category_id = c.id
     WHERE p.id = ?'
);
$stmt->execute([$id]);
$place = $stmt->fetch();

// สถานที่อื่นในหมวดเดียวกัน
$related = [];
if ($place && $place['category_id']) {
    $stmt = $pdo->prepare(
        'SELECT p.id, p.name, p.province, p.image, c.name AS category_name
         FROM places p
         LEFT JOIN categories c ON p.category_id = c.id
         WHERE p.category_id = ? AND p.id <> ?
         ORDER BY p.created_at DESC, p.id DESC
         LIMIT 3'
    );
    $stmt->execute([$place['category_id'], $place['id']]);
    $related = $stmt->fetchAll();
}

if (!$place) {
    http_response_code(404);
}

$pageTitle  = $place ? $place['name'] : 'ไม่พบสถานที่';
$activePage = 'places';
$heroPage   = false;
require __DIR__ . '/includes/header.php';
?>

<main class="section detail">
    <div class="container">

    <?php if (!$place): ?>
        <div class="empty-state">
            <h1 class="h2">ไม่พบสถานที่นี้</h1>
            <p class="prose">สถานที่ที่คุณเปิดอาจถูกลบไปแล้ว หรือลิงก์ไม่ถูกต้อง</p>
            <a href="/travel/places.php" class="btn-mango">กลับไปหน้ารายการ</a>
        </div>

    <?php else:
        $loc   = trim($place['location'] ?? '');
        $isUrl = (bool) preg_match('~^https?://~i', $loc);
    ?>
        <?php if ($place['image']): ?>
            <img class="detail-img"
                 src="<?= $uploadUrl . htmlspecialchars(rawurlencode($place['image'])) ?>"
                 alt="<?= htmlspecialchars($place['name']) ?>">
        <?php endif; ?>

        <div class="row g-5 mt-0">
            <div class="col-lg-8">
                <span class="tag tag-soft"><?= htmlspecialchars($place['category_name'] ?? 'ไม่ระบุหมวดหมู่') ?></span>
                <h1 class="detail-title"><?= htmlspecialchars($place['name']) ?></h1>
                <p class="detail-province"><?= htmlspecialchars($place['province'] ?? '') ?></p>

                <div class="prose">
                    <?php if (trim($place['description'] ?? '') !== ''): ?>
                        <p><?= nl2br(htmlspecialchars($place['description'])) ?></p>
                    <?php else: ?>
                        <p class="text-muted">ยังไม่มีรายละเอียดของสถานที่นี้</p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="col-lg-4">
                <aside class="info-box">
                    <dl class="mb-0">
                        <dt>จังหวัด</dt>
                        <dd><?= htmlspecialchars($place['province'] ?: 'ไม่ได้ระบุ') ?></dd>

                        <dt>หมวดหมู่</dt>
                        <dd><?= htmlspecialchars($place['category_name'] ?? 'ไม่ได้ระบุ') ?></dd>

                        <dt>ที่ตั้ง</dt>
                        <dd class="mb-0">
                            <?php if ($loc === ''): ?>
                                ไม่ได้ระบุ
                            <?php elseif ($isUrl): ?>
                                <a href="<?= htmlspecialchars($loc, ENT_QUOTES) ?>"
                                   class="btn-mango mt-1" target="_blank" rel="noopener noreferrer">เปิดแผนที่</a>
                            <?php else: ?>
                                <?= htmlspecialchars($loc) ?>
                            <?php endif; ?>
                        </dd>
                    </dl>
                </aside>
            </div>
        </div>

        <?php if (count($related) > 0): ?>
            <div class="related">
                <h2>สถานที่อื่นในหมวดเดียวกัน</h2>
                <div class="row g-4 g-lg-5 mt-1">
                    <?php foreach ($related as $r): ?>
                        <div class="col-md-4">
                            <a href="/travel/place-detail.php?id=<?= (int)$r['id'] ?>" class="feature-sm">
                                <?php if ($r['image']): ?>
                                    <img src="<?= $uploadUrl . htmlspecialchars(rawurlencode($r['image'])) ?>"
                                         alt="<?= htmlspecialchars($r['name']) ?>">
                                <?php else: ?>
                                    <div class="img-empty">ไม่มีรูป</div>
                                <?php endif; ?>
                                <span class="tag tag-soft"><?= htmlspecialchars($r['category_name'] ?? 'ไม่ระบุหมวดหมู่') ?></span>
                                <h3><?= htmlspecialchars($r['name']) ?></h3>
                                <p class="mb-0"><?= htmlspecialchars($r['province'] ?? '') ?></p>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <div class="mt-5">
            <a href="/travel/places.php" class="btn-line">กลับไปหน้ารายการ</a>
        </div>
    <?php endif; ?>

    </div>
</main>

<?php require __DIR__ . '/includes/footer.php'; ?>