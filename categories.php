<?php
require_once 'config/db.php';

$pageTitle = 'หมวดหมู่สถานที่ท่องเที่ยว';
$activePage = 'categories';
$heroPage = false;

// ดึงข้อมูลหมวดหมู่ทั้งหมด
$stmtCat = $pdo->query("SELECT * FROM categories ORDER BY name ASC");
$categories = $stmtCat->fetchAll(PDO::FETCH_ASSOC);

require_once 'includes/header.php';
?>

<div class="page-head">
    <div class="container">
        <h1>หมวดหมู่สถานที่ท่องเที่ยว</h1>
        <p class="page-head-lead">ค้นหาสถานที่ท่องเที่ยวในประเทศไทยตามหมวดหมู่ที่คุณสนใจ พร้อมสำรวจจุด check-in ยอดนิยมในแต่ละประเภท</p>
    </div>
</div>

<?php if (empty($categories)): ?>
    <div class="container section">
        <div class="empty-state">
            <p>ยังไม่มีหมวดหมู่สถานที่ท่องเที่ยวในขณะนี้</p>
        </div>
    </div>
<?php else: ?>
    <?php foreach ($categories as $index => $cat): ?>
        <?php
        $catId = $cat['id'];
        
        // นับจำนวนสถานที่ทั้งหมดในหมวดนี้
        $stmtCount = $pdo->prepare("SELECT COUNT(*) FROM places WHERE category_id = ?");
        $stmtCount->execute([$catId]);
        $totalPlaces = $stmtCount->fetchColumn();

        // ดึงรายชื่อสถานที่สูงสุด 5 รายการ
        $stmtPlaces = $pdo->prepare("SELECT id, name, province FROM places WHERE category_id = ? ORDER BY created_at DESC LIMIT 5");
        $stmtPlaces->execute([$catId]);
        $placesList = $stmtPlaces->fetchAll(PDO::FETCH_ASSOC);

        // ดึงรูปจากสถานที่ล่าสุดในหมวดที่มีรูป
        $stmtImg = $pdo->prepare("SELECT image FROM places WHERE category_id = ? AND image IS NOT NULL AND image != '' ORDER BY created_at DESC LIMIT 1");
        $stmtImg->execute([$catId]);
        $latestImage = $stmtImg->fetchColumn();

        // สลับพื้นหลังระหว่างขาวกับ --foam
        $sectionClass = ($index % 2 === 0) ? 'section-white' : 'section';
        // สลับตำแหน่งรูปซ้าย-ขวา
        $isEven = ($index % 2 === 0);
        ?>

        <section id="category-<?php echo htmlspecialchars($catId); ?>" class="<?php echo $sectionClass; ?>">
            <div class="container">
                <div class="row align-items-center g-4 <?php echo $isEven ? '' : 'flex-row-reverse'; ?>">
                    <div class="col-lg-6">
                        <?php if ($latestImage): ?>
                            <img src="/travel/uploads/<?php echo htmlspecialchars($latestImage); ?>" alt="<?php echo htmlspecialchars($cat['name']); ?>" class="img-fluid w-100" style="border-radius: var(--radius-img); object-fit: cover; max-height: 400px;">
                        <?php else: ?>
                            <div class="empty-state bg-light p-5 text-center" style="border-radius: var(--radius-img);">
                                <p class="mb-0 text-muted">ยังไม่มีรูปภาพในหมวดนี้</p>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="col-lg-6">
                        <div class="prose">
                            <h2><?php echo htmlspecialchars($cat['name']); ?></h2>
                            <span class="tag tag-soft mb-3">ทั้งหมด <?php echo $totalPlaces; ?> สถานที่</span>
                            
                            <?php if (empty($placesList)): ?>
                                <p class="text-muted">ยังไม่มีสถานที่ในหมวดนี้</p>
                            <?php else: ?>
                                <ul class="list-unstyled mb-4">
                                    <?php foreach ($placesList as $place): ?>
                                        <li class="mb-2">
                                            <a href="place-detail.php?id=<?php echo $place['id']; ?>" class="link-plain fw-medium">
                                                <?php echo htmlspecialchars($place['name']); ?>
                                            </a>
                                            <span class="text-muted small ms-1">(<?php echo htmlspecialchars($place['province']); ?>)</span>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>

                            <div>
                                <a href="places.php?category=<?php echo $catId; ?>" class="btn btn-mango">ดูทั้งหมดในหมวดนี้</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    <?php endforeach; ?>
<?php endif; ?>

<?php require_once 'includes/footer.php'; ?>