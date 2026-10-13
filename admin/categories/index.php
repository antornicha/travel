<?php
require __DIR__ . '/../auth_check.php';
require __DIR__ . '/../../config/db.php';

// ข้อความแจ้งผล (เก็บใน session แล้วแสดงครั้งเดียว)
$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

$categories = $pdo->query(
    'SELECT c.id, c.name, COUNT(p.id) AS place_count
     FROM categories c
     LEFT JOIN places p ON p.category_id = c.id
     GROUP BY c.id, c.name
     ORDER BY c.id'
)->fetchAll();

$pageTitle  = 'จัดการหมวดหมู่';
$activeMenu = 'categories';
require __DIR__ . '/../includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-semibold mb-0">จัดการหมวดหมู่</h3>
    <a href="add.php" class="btn btn-teal">+ เพิ่มหมวดหมู่</a>
</div>

<?php if ($flash): ?>
    <div class="alert alert-<?= htmlspecialchars($flash['type']) ?> alert-dismissible fade show" role="alert">
        <?= htmlspecialchars($flash['msg']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="card panel-card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th style="width:80px;">#</th>
                    <th>ชื่อหมวดหมู่</th>
                    <th class="text-center" style="width:160px;">จำนวนสถานที่</th>
                    <th class="text-end" style="width:200px;">จัดการ</th>
                </tr>
            </thead>
            <tbody>
            <?php if (count($categories) === 0): ?>
                <tr>
                    <td colspan="4" class="text-center text-muted py-4">ยังไม่มีหมวดหมู่</td>
                </tr>
            <?php else: ?>
                <?php foreach ($categories as $i => $cat): ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td><?= htmlspecialchars($cat['name']) ?></td>
                    <td class="text-center">
                        <span class="badge bg-info-subtle text-info-emphasis"><?= (int)$cat['place_count'] ?></span>
                    </td>
                    <td class="text-end">
                        <a href="edit.php?id=<?= (int)$cat['id'] ?>" class="btn btn-sm btn-outline-primary">แก้ไข</a>
                        <form action="delete.php" method="post" class="d-inline"
                              onsubmit="return confirm('ต้องการลบหมวดหมู่ &quot;<?= htmlspecialchars($cat['name'], ENT_QUOTES) ?>&quot; ใช่หรือไม่?');">
                            <input type="hidden" name="id" value="<?= (int)$cat['id'] ?>">
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