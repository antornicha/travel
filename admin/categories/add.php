<?php
require __DIR__ . '/../auth_check.php';
require __DIR__ . '/../../config/db.php';

$error = '';
$name  = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');

    if ($name === '') {
        $error = 'กรุณากรอกชื่อหมวดหมู่';
    } else {
        $stmt = $pdo->prepare('SELECT COUNT(*) FROM categories WHERE name = ?');
        $stmt->execute([$name]);

        if ($stmt->fetchColumn() > 0) {
            $error = 'มีหมวดหมู่ชื่อนี้อยู่แล้ว';
        } else {
            $stmt = $pdo->prepare('INSERT INTO categories (name) VALUES (?)');
            $stmt->execute([$name]);
            $_SESSION['flash'] = ['type' => 'success', 'msg' => 'เพิ่มหมวดหมู่เรียบร้อยแล้ว'];
            header('Location: index.php');
            exit;
        }
    }
}

$pageTitle  = 'เพิ่มหมวดหมู่';
$activeMenu = 'categories';
require __DIR__ . '/../includes/header.php';
?>

<h3 class="fw-semibold mb-4">เพิ่มหมวดหมู่</h3>

<div class="card panel-card" style="max-width:600px;">
    <div class="card-body p-4">
        <?php if ($error): ?>
            <div class="alert alert-danger py-2"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="post">
            <div class="mb-3">
                <label for="name" class="form-label">ชื่อหมวดหมู่</label>
                <input type="text" class="form-control" id="name" name="name"
                       maxlength="100" required autofocus
                       placeholder="เช่น ทะเล, ภูเขา, วัด"
                       value="<?= htmlspecialchars($name) ?>">
            </div>
            <button type="submit" class="btn btn-teal">บันทึก</button>
            <a href="index.php" class="btn btn-outline-secondary">ยกเลิก</a>
        </form>
    </div>
</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>
