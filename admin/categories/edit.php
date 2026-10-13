<?php
require __DIR__ . '/../auth_check.php';
require __DIR__ . '/../../config/db.php';

$id = (int) ($_GET['id'] ?? $_POST['id'] ?? 0);

$stmt = $pdo->prepare('SELECT id, name FROM categories WHERE id = ?');
$stmt->execute([$id]);
$category = $stmt->fetch();

if (!$category) {
    $_SESSION['flash'] = ['type' => 'danger', 'msg' => 'ไม่พบหมวดหมู่ที่ต้องการแก้ไข'];
    header('Location: index.php');
    exit;
}

$error = '';
$name  = $category['name'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');

    if ($name === '') {
        $error = 'กรุณากรอกชื่อหมวดหมู่';
    } else {
        $stmt = $pdo->prepare('SELECT COUNT(*) FROM categories WHERE name = ? AND id <> ?');
        $stmt->execute([$name, $id]);

        if ($stmt->fetchColumn() > 0) {
            $error = 'มีหมวดหมู่ชื่อนี้อยู่แล้ว';
        } else {
            $stmt = $pdo->prepare('UPDATE categories SET name = ? WHERE id = ?');
            $stmt->execute([$name, $id]);
            $_SESSION['flash'] = ['type' => 'success', 'msg' => 'แก้ไขหมวดหมู่เรียบร้อยแล้ว'];
            header('Location: index.php');
            exit;
        }
    }
}

$pageTitle  = 'แก้ไขหมวดหมู่';
$activeMenu = 'categories';
require __DIR__ . '/../includes/header.php';
?>

<h3 class="fw-semibold mb-4">แก้ไขหมวดหมู่</h3>

<div class="card panel-card" style="max-width:600px;">
    <div class="card-body p-4">
        <?php if ($error): ?>
            <div class="alert alert-danger py-2"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="post">
            <input type="hidden" name="id" value="<?= (int)$id ?>">
            <div class="mb-3">
                <label for="name" class="form-label">ชื่อหมวดหมู่</label>
                <input type="text" class="form-control" id="name" name="name"
                       maxlength="100" required autofocus
                       value="<?= htmlspecialchars($name) ?>">
            </div>
            <button type="submit" class="btn btn-teal">บันทึกการแก้ไข</button>
            <a href="index.php" class="btn btn-outline-secondary">ยกเลิก</a>
        </form>
    </div>
</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>