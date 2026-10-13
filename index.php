<?php
require __DIR__ . '/config/db.php';

$uploadUrl = '/travel/uploads/';

// รูปพื้นหลัง hero: สถานที่ล่าสุดที่มีรูป
$hero = $pdo->query(
    "SELECT name, province, image
     FROM places
     WHERE image IS NOT NULL AND image <> ''
     ORDER BY created_at DESC, id DESC
     LIMIT 1"
)->fetch();

// สถานที่แนะนำ: ล่าสุด 5 แห่ง
$featured = $pdo->query(
    'SELECT p.id, p.name, p.province, p.image, c.name AS category_name
     FROM places p
     LEFT JOIN categories c ON p.category_id = c.id
     ORDER BY p.created_at DESC, p.id DESC
     LIMIT 5'
)->fetchAll();

// หมวดหมู่พร้อมจำนวนสถานที่
$categories = $pdo->query(
    'SELECT c.id, c.name, COUNT(p.id) AS place_count
     FROM categories c
     LEFT JOIN places p ON p.category_id = c.id
     GROUP BY c.id, c.name
     ORDER BY c.name'
)->fetchAll();

$big   = $featured[0] ?? null;
$small = array_slice($featured, 1, 4);

$heroStyle = '';
if ($hero) {
    $heroUrl   = $uploadUrl . rawurlencode($hero['image']);
    $heroStyle = ' style="--hero-image: url(\'' . htmlspecialchars($heroUrl, ENT_QUOTES) . '\')"';
}

$pageTitle  = 'เที่ยวทั่วไทย';
$activePage = 'home';
$heroPage   = true;
require __DIR__ . '/includes/header.php';
?>

<main>
    <!-- ภาพหลัก -->
    <section class="hero<?= $hero ? ' has-photo' : '' ?>"<?= $heroStyle ?>>
        <div class="container hero-inner">
            <h1>ทะเล ภูเขา วัดเก่า ครบในที่เดียว</h1>
            <p class="hero-lead">
                รวมสถานที่ท่องเที่ยวทั่วประเทศไทยไว้ในที่เดียว
                เลือกดูตามจังหวัดหรือประเภทของที่เที่ยวที่คุณสนใจ
            </p>
            <a href="/travel/places.php" class="btn-mango me-2 mb-2">ดูสถานที่ทั้งหมด</a>
            <a href="/travel/categories.php" class="btn-line mb-2">ดูตามหมวดหมู่</a>
        </div>
        <?php if ($hero): ?>
            <p class="hero-credit">
                <?= htmlspecialchars($hero['name']) ?><?= $hero['province'] ? ', ' . htmlspecialchars($hero['province']) : '' ?>
            </p>
        <?php endif; ?>
    </section>

    <!-- สถานที่แนะนำ -->
    <section class="section">
        <div class="container">
            <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
                <h2 class="mb-0">สถานที่แนะนำ</h2>
                <a href="/travel/places.php" class="link-plain">ดูสถานที่ทั้งหมด</a>
            </div>

            <?php if (!$big): ?>
                <p class="text-muted">ยังไม่มีข้อมูลสถานที่ ผู้ดูแลระบบเพิ่มได้ที่หลังบ้าน</p>
            <?php else: ?>
                <div class="row g-4">
                    <!-- ใบใหญ่ -->
                    <div class="col-lg-6">
                        <a href="/travel/place-detail.php?id=<?= (int)$big['id'] ?>" class="feature-big">
                            <?php if ($big['image']): ?>
                                <img src="<?= $uploadUrl . htmlspecialchars(rawurlencode($big['image'])) ?>"
                                     alt="<?= htmlspecialchars($big['name']) ?>">
                            <?php endif; ?>
                            <div class="feature-big-body">
                                <span class="tag tag-light"><?= htmlspecialchars($big['category_name'] ?? 'ไม่ระบุหมวดหมู่') ?></span>
                                <h3><?= htmlspecialchars($big['name']) ?></h3>
                                <p class="mb-0"><?= htmlspecialchars($big['province'] ?? '') ?></p>
                            </div>
                        </a>
                    </div>

                    <!-- ใบเล็ก -->
                    <div class="col-lg-6">
                        <div class="row g-4">
                            <?php foreach ($small as $p): ?>
                                <div class="col-6">
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
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- หมวดหมู่ -->
    <section class="section section-white">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-4">
                    <h2>เลือกเที่ยวตามประเภท</h2>
                    <p class="prose">
                        อยากพักผ่อนริมทะเล เดินป่า ไหว้พระ หรือเดินชมเมืองเก่า
                        เลือกประเภทที่ชอบ แล้วดูสถานที่ทั้งหมดในหมวดนั้นได้เลย
                    </p>
                </div>
                <div class="col-lg-8">
                    <?php if (count($categories) === 0): ?>
                        <p class="text-muted">ยังไม่มีหมวดหมู่</p>
                    <?php else: ?>
                        <ul class="cat-list">
                            <?php foreach ($categories as $c): ?>
                                <li>
                                    <a href="/travel/places.php?category=<?= (int)$c['id'] ?>">
                                        <span><?= htmlspecialchars($c['name']) ?></span>
                                        <span class="count"><?= (int)$c['place_count'] ?> แห่ง</span>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- ปิดท้าย -->
    <section class="section closing">
        <div class="container">
            <h2>ยังไม่รู้จะไปไหน ลองเริ่มจากรายการทั้งหมด</h2>
            <p class="prose mb-4">ดูทุกสถานที่ แยกตามจังหวัดและหมวดหมู่ พร้อมรูปและรายละเอียด</p>
            <a href="/travel/places.php" class="btn-mango">ดูสถานที่ทั้งหมด</a>
        </div>
    </section>
</main>

<?php require __DIR__ . '/includes/footer.php'; ?>
 <div class="card" data-aos="fade-up">

       <!-- เนื้อหาการ์ดของคุณ -->

   </div>