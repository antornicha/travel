<?php
require_once 'config/db.php';

$pageTitle = 'แกลเลอรีภาพสถานที่ท่องเที่ยว';
$activePage = 'gallery';
$heroPage = false;

// รับค่าหมวดหมู่สำหรับการกรอง
$selectedCategory = isset($_GET['category']) ? filter_var($_GET['category'], FILTER_VALIDATE_INT) : null;

// ดึงหมวดหมู่สำหรับแถบตัวกรอง
$stmtCat = $pdo->query("SELECT * FROM categories ORDER BY name ASC");
$categories = $stmtCat->fetchAll(PDO::FETCH_ASSOC);

// ดึงข้อมูลสถานที่ที่มีรูปภาพ
if ($selectedCategory) {
    $stmtPlaces = $pdo->prepare("SELECT p.*, c.name as category_name FROM places p JOIN categories c ON p.category_id = c.id WHERE p.category_id = ? AND p.image IS NOT NULL AND p.image != '' ORDER BY p.created_at DESC");
    $stmtPlaces->execute([$selectedCategory]);
} else {
    $stmtPlaces = $pdo->query("SELECT p.*, c.name as category_name FROM places p JOIN categories c ON p.category_id = c.id WHERE p.image IS NOT NULL AND p.image != '' ORDER BY p.created_at DESC");
}
$places = $stmtPlaces->fetchAll(PDO::FETCH_ASSOC);

require_once 'includes/header.php';
?>

<div class="page-head">
    <div class="container">
        <h1>แกลเลอรีภาพถ่าย</h1>
        <p class="page-head-lead">รวบรวมภาพบรรยากาศและความงดงามของสถานที่ท่องเที่ยวต่างๆ ทั่วประเทศไทย</p>
    </div>
</div>

<div class="container section">
    <!-- แถบเลือกหมวดหมู่แบบปุ่มแคปซูล -->
    <div class="filter-bar mb-4 d-flex flex-wrap gap-2 justify-content-center">
        <a href="gallery.php" class="btn <?php echo is_null($selectedCategory) ? 'btn-mango' : 'btn-line'; ?> rounded-pill">ทั้งหมด</a>
        <?php foreach ($categories as $cat): ?>
            <a href="gallery.php?category=<?php echo $cat['id']; ?>" class="btn <?php echo ($selectedCategory === (int)$cat['id']) ? 'btn-mango' : 'btn-line'; ?> rounded-pill">
                <?php echo htmlspecialchars($cat['name']); ?>
            </a>
        <?php endforeach; ?>
    </div>

    <?php if (empty($places)): ?>
        <div class="empty-state">
            <p>ไม่พบรูปภาพสถานที่ท่องเที่ยวในหมวดหมู่นี้</p>
        </div>
    <?php else: ?>
        <!-- Masonry ด้วย CSS columns -->
        <div class="gallery-masonry" style="column-count: 3; column-gap: 1.5rem;">
            <?php foreach ($places as $index => $place): ?>
                <div class="mb-4" style="break-inside: avoid;">
                    <a href="#" class="gallery-item d-block text-decoration-none" 
                       data-bs-toggle="modal" 
                       data-bs-target="#galleryModal"
                       data-index="<?php echo $index; ?>"
                       data-image="/travel/uploads/<?php echo htmlspecialchars($place['image']); ?>"
                       data-name="<?php echo htmlspecialchars($place['name']); ?>"
                       data-province="<?php echo htmlspecialchars($place['province']); ?>"
                       data-category="<?php echo htmlspecialchars($place['category_name']); ?>"
                       data-url="place-detail.php?id=<?php echo $place['id']; ?>">
                        <img src="/travel/uploads/<?php echo htmlspecialchars($place['image']); ?>" 
                             alt="<?php echo htmlspecialchars($place['name']); ?>" 
                             class="img-fluid w-100" 
                             style="border-radius: var(--radius-img); object-fit: contain;">
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<!-- Bootstrap Modal สำหรับแสดงภาพใหญ่ -->
<div class="modal fade" id="galleryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-dark text-white">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="modalTitle"></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center position-relative p-0">
                <img id="modalImage" src="" alt="" class="img-fluid max-vh-75" style="max-height: 70vh; object-fit: contain;">
                
                <!-- ปุ่มก่อนหน้า/ถัดไป -->
                <button type="button" id="prevBtn" class="position-absolute top-50 start-0 translate-middle-y btn btn-dark bg-opacity-50 text-white border-0 ms-2 px-3 py-2">&lsaquo;</button>
                <button type="button" id="nextBtn" class="position-absolute top-50 end-0 translate-middle-y btn btn-dark bg-opacity-50 text-white border-0 me-2 px-3 py-2">&rsaquo;</button>
            </div>
            <div class="modal-footer border-0 justify-content-between">
                <div>
                    <span id="modalCategory" class="tag tag-soft me-2"></span>
                    <span id="modalProvince" class="text-light small"></span>
                </div>
                <a id="modalLink" href="#" class="btn btn-mango btn-sm">ดูรายละเอียดสถานที่</a>
            </div>
        </div>
    </div>
</div>

<style>
/* ปรับแต่ง Responsive สำหรับ CSS columns แกลเลอรี */
@media (max-width: 992px) {
    .gallery-masonry { column-count: 2 !important; }
}
@media (max-width: 576px) {
    .gallery-masonry { column-count: 1 !important; }
}

/* ปิดเอฟเฟกต์โฮเด้งตามเงื่อนไข และรองรับ prefers-reduced-motion */
.gallery-item img {
    transition: none !important;
    transform: none !important;
}
@media (prefers-reduced-motion: reduce) {
    * {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
        scroll-behavior: auto !important;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const galleryItems = document.querySelectorAll('.gallery-item');
    const modalImage = document.getElementById('modalImage');
    const modalTitle = document.getElementById('modalTitle');
    const modalProvince = document.getElementById('modalProvince');
    const modalCategory = document.getElementById('modalCategory');
    const modalLink = document.getElementById('modalLink');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');

    let currentIndex = 0;
    const itemsData = Array.from(galleryItems).map(item => ({
        image: item.getAttribute('data-image'),
        name: item.getAttribute('data-name'),
        province: item.getAttribute('data-province'),
        category: item.getAttribute('data-category'),
        url: item.getAttribute('data-url')
    }));

    function updateModal(index) {
        if (index < 0) index = itemsData.length - 1;
        if (index >= itemsData.length) index = 0;
        currentIndex = index;

        const data = itemsData[currentIndex];
        modalImage.src = data.image;
        modalImage.alt = data.name;
        modalTitle.textContent = data.name;
        modalProvince.textContent = 'จังหวัด' + data.province;
        modalCategory.textContent = data.category;
        modalLink.href = data.url;
    }

    galleryItems.forEach((item, index) => {
        item.addEventListener('click', function () {
            updateModal(index);
        });
    });

    prevBtn.addEventListener('click', function () {
        updateModal(currentIndex - 1);
    });

    nextBtn.addEventListener('click', function () {
        updateModal(currentIndex + 1);
    });

    // รองรับการกดลูกศรซ้าย/ขวาบนคีย์บอร์ด
    document.addEventListener('keydown', function (e) {
        const modalElement = document.getElementById('galleryModal');
        if (modalElement.classList.contains('show')) {
            if (e.key === 'ArrowLeft') {
                updateModal(currentIndex - 1);
            } else if (e.key === 'ArrowRight') {
                updateModal(currentIndex + 1);
            }
        }
    });
});
</script>

<?php require_once 'includes/footer.php'; ?>