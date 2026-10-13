<?php
require __DIR__ . '/../auth_check.php';
require __DIR__ . '/../../config/db.php';
require __DIR__ . '/upload_helper.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$id = (int) ($_POST['id'] ?? 0);

$stmt = $pdo->prepare('SELECT image FROM places WHERE id = ?');
$stmt->execute([$id]);
$place = $stmt->fetch();

if (!$place) {
    $_SESSION['flash'] = ['type' => 'danger', 'msg' => 'ไม่พบสถานที่ที่ต้องการลบ'];
} else {
    $stmt = $pdo->prepare('DELETE FROM places WHERE id = ?');
    $stmt->execute([$id]);
    deleteImageFile($place['image']);
    $_SESSION['flash'] = ['type' => 'success', 'msg' => 'ลบสถานที่เรียบร้อยแล้ว'];
}

header('Location: index.php');
exit;