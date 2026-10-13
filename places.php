<?php
require __DIR__ . '/config/db.php';

$uploadUrl = '/travel/uploads/';
$perPage   = 9;

$q        = trim($_GET['q'] ?? '');
$catId    = (int) ($_GET['category'] ?? 0);
$province = trim($_GET['province'] ?? '');
$page     = max(1, (int) ($_GET['page'] ?? 1));

$categories = $pdo->query('SELECT id, name FROM categories ORDER BY name')->fetchAll();
$provinces  = $pdo->query(
    "SELECT DISTINCT province FROM places
     WHERE province IS NOT NULL AND province <> ''
     ORDER BY province"
)->fetchAll(PDO::FETCH_COLUMN);

// สร้างเงื่อนไขกรองแบบ prepared statement
$where  = [];
$params = [];
if ($q !== '') {
    $where[]  = 'p.name LIKE ?';
    $params[] = '%' . $q . '%';
}
if ($catId > 0) {
    $where[]  = 'p.category_id = ?';
    $params[] = $catId;
}
if ($province !== '') {
    $where[]  = 'p.province = ?';
    $params[] = $province;
}
$whereSql = $where ? ' WHERE ' . implode(' AND ', $where) : '';

// นับจำนวนผลทั้งหมด
$stmt = $pdo->prepare('SELECT COUNT(*) FROM places p' . $whereSql);
$stmt->execute($params);
$total = (int) $stmt->fetchColumn();

$totalPages = max(1, (int) ceil($total / $perPage));
$page       = min($page, $totalPages);
$offset     = ($page - 1) * $perPage;

// ดึงรายการของหน้านี้
$stmt = $pdo->prepare(
    'SELECT p.id, p.name, p.province, p.image, c.name AS category_name
     FROM places p
     LEFT JOIN categories c ON p.category_id = c.id'
    . $whereSql .
    ' ORDER BY p.created_at DESC, p.id DESC
     LIMIT ? OFFSET ?'
);
foreach ($params as $i => $value) {
    $stmt->bindValue($i + 1, $value);
}
$stmt->bindValue(count($params) + 1, $perPage, PDO::PARAM_INT);
$stmt->bindValue(count($params) + 2, $offset, PDO::PARAM_INT);
$stmt->execute();
$places = $stmt->fetchAll();

$hasFilter = ($q !== '' || $catId > 0 || $province !== '');

// สร้างลิงก์เปลี่ยนหน้า โดยคงตัวกรองไว้
function pageUrl(int $n, string $q, int $catId, string $province): string
{
    $qs = array_filter([
        'q'        => $q,
        'category' => $catId > 0 ? $catId : '',
        'province' => $province,
        'page'     => $n > 1 ? $n : '',
    ], fn($v) => $v !== '');
    return '/travel/places.php' . ($qs ? '?' . http_build_query($qs) : '');
}

$pageTitle  = 'สถานที่ท่องเที่ยว';
$activePage = 'places';
$heroPage   = false;
require __DIR__ . '/includes/header.php';
?>

<main>
    <section class="page-head">
        <div class="container">
            <h1>สถานที่ท่องเที่ยว</h1>
            <p class="page-head-lead">ค้นหาตามชื่อ หรือเลือกหมวดหมู่และจังหวัดที่สนใจ</p>
        </div>
    </section>

    <section class="filter-bar">
        <div class="container">
            <form method="get" action="/travel/places.php" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label for="q" class="form-label">ชื่อสถานที่</label>
                    <input type="text" class="form-control" id="q" name="q"
                           placeholder="พิมพ์ชื่อที่ต้องการค้นหา"
                           value="<?= htmlspecialchars($q) ?>">
                </div>
                <div class="col-md-3">
                    <label for="category" class="form-label">หมวดหมู่</label>
                    <select class="form-select" id="category" name="category">
                        <option value="">ทุกหมวดหมู่</option>
                        <?php foreach ($categories as $c): ?>
                            <option value="<?= (int)$c['id'] ?>" <?= $catId === (int)$c['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($c['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="province" class="form-label">จังหวัด</label>
                    <select class="form-select" id="province" name="province">
                        <option value="">ทุกจังหวัด</option>
                        <?php foreach ($provinces as $pv): ?>
                            <option value="<?= htmlspecialchars($pv) ?>" <?= $province === $pv ? 'selected' : '' ?>>
                                <?= htmlspecialchars($pv) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-2 flex-wrap">
                    <button type="submit" class="btn-mango border-0">ค้นหา</button>
                    <a href="/travel/places.php" class="btn-line">ล้างตัวกรอง</a>
                </div>
            </form>
        </div>
    </section>

    <section class="section pt-4">
        <div class="container">
            <p class="result-count">พบสถานที่ <?= $total ?> แห่ง</p>

            <?php if ($total === 0): ?>
                <div class="empty-state">
                    <h2>ไม่พบสถานที่ที่ตรงกับเงื่อนไข</h2>
                    <p class="prose">ลองเปลี่ยนคำค้นหา หรือเลือกหมวดหมู่และจังหวัดใหม่</p>
                    <?php if ($hasFilter): ?>
                        <a href="/travel/places.php" class="btn-line">ล้างตัวกรองทั้งหมด</a>
                    <?php else: ?>
                        <p class="text-muted">ยังไม่มีข้อมูลสถานที่ ผู้ดูแลระบบเพิ่มได้ที่หลังบ้าน</p>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-4 g-lg-5">
                    <?php foreach ($places as $p): ?>
                        <div class="col">
                            <a href="/travel/place-detail.php?id=<?= (int)$p['id'] ?>" class="feature-sm">
                                <?php if ($p['image']): ?>
                                    <img src="<?= $uploadUrl . htmlspecialchars(rawurlencode($p['image'])) ?>"
                                         alt="<?= htmlspecialchars($p['name']) ?>">
                                <?php else: ?>
                                    <div class="img-empty">ไม่มีรูป</div>
                                <?php endif; ?>
                                <span class="tag tag-soft"><?= htmlspecialchars($p['category_name'] ?? 'ไม่ระบุหมวดหมู่') ?></span>
                                <h3><?= htmlspecialchars($p['name']) ?></h3>
                                <p class="mb-0"><?= htmlspecialchars($p['province'] ?? '') ?></p>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>

                <?php if ($totalPages > 1): ?>
                    <nav class="pager mt-5" aria-label="เปลี่ยนหน้า">
                        <ul class="pagination justify-content-center flex-wrap mb-0">
                            <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                                <a class="page-link" href="<?= pageUrl($page - 1, $q, $catId, $province) ?>">ก่อนหน้า</a>
                            </li>
                            <?php
                            $last = 0;
                            for ($i = 1; $i <= $totalPages; $i++):
                                if ($i !== 1 && $i !== $totalPages && abs($i - $page) > 2) {
                                    continue;
                                }
                                if ($last && $i - $last > 1): ?>
                                    <li class="page-item disabled"><span class="page-link">...</span></li>
                                <?php endif; ?>
                                <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                                    <a class="page-link" href="<?= pageUrl($i, $q, $catId, $province) ?>"
                                       <?= $i === $page ? 'aria-current="page"' : '' ?>><?= $i ?></a>
                                </li>
                                <?php $last = $i; ?>
                            <?php endfor; ?>
                            <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
                                <a class="page-link" href="<?= pageUrl($page + 1, $q, $catId, $province) ?>">ถัดไป</a>
                            </li>
                        </ul>
                    </nav>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php require __DIR__ . '/includes/footer.php'; ?>