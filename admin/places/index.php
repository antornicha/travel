<?php
require __DIR__ . '/../auth_check.php';
require __DIR__ . '/../../config/db.php';
require __DIR__ . '/upload_helper.php';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

$q = trim($_GET['q'] ?? '');

$sql = 'SELECT p.id, p.name, p.province, p.image, p.created_at, c.name AS category_name
        FROM places p
        LEFT JOIN categories c ON p.category_id = c.id';
$params = [];
if ($q !== '') {
    $sql .= ' WHERE p.name LIKE ?';
    $params[] = '%' . $q . '%';
}
$sql .= ' ORDER BY p.created_at DESC, p.id DESC';

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$places = $stmt->fetchAll();

$pageTitle  = 'จัดการสถานที่';
$activeMenu = 'places';
require __DIR__ . '/../includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-semibold mb-0">จัดการสถานที่ท่องเที่ยว</h3>
    <a href="add.php" class="btn btn-teal">+ เพิ่มสถานที่</a>
</div>

<?php if ($flash): ?>
    <div class="alert alert-<?= htmlspecialchars($flash['type']) ?> alert-dismissible fade show" role="alert">
        <?= htmlspecialchars($flash['msg']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<form method="get" class="row g-2 mb-3">
    <div class="col-sm-8 col-md-6">
        <input type="text" name="q" class="form-control" placeholder="ค้นหาตามชื่อสถานที่"
               value="<?= htmlspecialchars($q) ?>">
    </div>
    <div class="col-auto">
        <button type="submit" class="btn btn-teal">ค้นหา</button>
        <?php if ($q !== ''): ?>
            <a href="index.php" class="btn btn-outline-secondary">ล้างการค้นหา</a>
        <?php endif; ?>
    </div>
</form>

<div class="card panel-card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th style="width:110px;">รูป</th>
                    <th>ชื่อสถานที่</th>
                    <th>จังหวัด</th>
                    <th>หมวดหมู่</th>
                    <th>วันที่เพิ่ม</th>
                    <th class="text-end" style="width:170px;">จัดการ</th>
                </tr>
            </thead>
            <tbody>
            <?php if (count($places) === 0): ?>
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">
                        <?= $q !== '' ? 'ไม่พบสถานที่ที่ค้นหา' : 'ยังไม่มีข้อมูลสถานที่' ?>
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($places as $p): ?>
                <tr>
                    <td>
                        <?php if ($p['image']): ?>
                            <img src="<?= UPLOAD_URL . htmlspecialchars($p['image']) ?>" alt=""
                                 class="rounded" style="width:90px;height:60px;object-fit:cover;">
                        <?php else: ?>
                            <div class="bg-light text-muted small d-flex align-items-center justify-content-center rounded"
                                 style="width:90px;height:60px;">ไม่มีรูป</div>
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($p['name']) ?></td>
                    <td><?= htmlspecialchars($p['province'] ?? '-') ?></td>
                    <td>
                        <span class="badge bg-info-subtle text-info-emphasis">
                            <?= htmlspecialchars($p['category_name'] ?? 'ไม่ระบุ') ?>
                        </span>
                    </td>
                    <td><?= date('d/m/Y', strtotime($p['created_at'])) ?></td>
                    <td class="text-end">
                        <a href="edit.php?id=<?= (int)$p['id'] ?>" class="btn btn-sm btn-outline-primary">แก้ไข</a>
                        <form action="delete.php" method="post" class="d-inline"
                              onsubmit="return confirm('ต้องการลบ &quot;<?= htmlspecialchars($p['name'], ENT_QUOTES) ?>&quot; ใช่หรือไม่?');">
                            <input type="hidden" name="id" value="<?= (int)$p['id'] ?>">
                            <button type="submit" class="btn btn-sm btn-outline-danger">ลบ</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>
