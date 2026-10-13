<?php
require __DIR__ . '/auth_check.php';
require __DIR__ . '/../config/db.php';

$totalPlaces     = (int) $pdo->query('SELECT COUNT(*) FROM places')->fetchColumn();
$totalCategories = (int) $pdo->query('SELECT COUNT(*) FROM categories')->fetchColumn();

$latest = $pdo->query(
    'SELECT p.id, p.name, p.created_at, c.name AS category_name
     FROM places p
     LEFT JOIN categories c ON p.category_id = c.id
     ORDER BY p.created_at DESC, p.id DESC
     LIMIT 5'
)->fetchAll();

$pageTitle  = 'Dashboard';
$activeMenu = 'dashboard';
require __DIR__ . '/includes/header.php';
?>

<h3 class="fw-semibold mb-4">ภาพรวมระบบ</h3>

<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="card stat-card stat-places">
            <div class="card-body p-4 d-flex justify-content-between align-items-center">
                <div>
                    <div class="mb-2">สถานที่ท่องเที่ยวทั้งหมด</div>
                    <div class="stat-number"><?= $totalPlaces ?></div>
                </div>
                <div style="font-size:3rem;opacity:.5;">&#127958;</div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card stat-card stat-cats">
            <div class="card-body p-4 d-flex justify-content-between align-items-center">
                <div>
                    <div class="mb-2">หมวดหมู่ทั้งหมด</div>
                    <div class="stat-number"><?= $totalCategories ?></div>
                </div>
                <div style="font-size:3rem;opacity:.5;">&#128193;</div>
            </div>
        </div>
    </div>
</div>

<div class="mb-4 d-flex flex-wrap gap-2">
    <a href="places/index.php" class="btn btn-teal">จัดการสถานที่</a>
    <a href="categories/index.php" class="btn btn-outline-secondary">จัดการหมวดหมู่</a>
</div>

<div class="card panel-card">
    <div class="card-header bg-white fw-semibold py-3">สถานที่ที่เพิ่มล่าสุด 5 รายการ</div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>ชื่อสถานที่</th>
                    <th>หมวดหมู่</th>
                    <th>วันที่เพิ่ม</th>
                </tr>
            </thead>
            <tbody>
            <?php if (count($latest) === 0): ?>
                <tr>
                    <td colspan="3" class="text-center text-muted py-4">ยังไม่มีข้อมูลสถานที่</td>
                </tr>
            <?php else: ?>
                <?php foreach ($latest as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td>
                        <span class="badge bg-info-subtle text-info-emphasis">
                            <?= htmlspecialchars($row['category_name'] ?? 'ไม่ระบุ') ?>
                        </span>
                    </td>
                    <td><?= date('d/m/Y H:i', strtotime($row['created_at'])) ?></td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>