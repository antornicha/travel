<?php
require __DIR__ . '/../auth_check.php';
require __DIR__ . '/../../config/db.php';

// รับเฉพาะ POST เท่านั้น
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$id = (int) ($_POST['id'] ?? 0);

// ตรวจว่ามีสถานที่ใช้หมวดหมู่นี้อยู่หรือไม่
$stmt = $pdo->prepare('SELECT COUNT(*) FROM places WHERE category_id = ?');
$stmt->execute([$id]);
$used = (int) $stmt->fetchColumn();

if ($used > 0) {
    $_SESSION['flash'] = [
        'type' => 'danger',
        'msg'  => "ไม่สามารถลบได้ เพราะมีสถานที่ $used รายการใช้หมวดหมู่นี้อยู่"
    ];
} else {
    $stmt = $pdo->prepare('DELETE FROM categories WHERE id = ?');
    $stmt->execute([$id]);

    $_SESSION['flash'] = $stmt->rowCount() > 0
        ? ['type' => 'success', 'msg' => 'ลบหมวดหมู่เรียบร้อยแล้ว']
        : ['type' => 'danger',  'msg' => 'ไม่พบหมวดหมู่ที่ต้องการลบ'];
}

header('Location: index.php');
exit;
