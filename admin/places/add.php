<?php
require __DIR__ . '/../auth_check.php';
require __DIR__ . '/../../config/db.php';
require __DIR__ . '/upload_helper.php';

$categories = $pdo->query('SELECT id, name FROM categories ORDER BY name')->fetchAll();

$error = '';
$data  = ['name' => '', 'province' => '', 'category_id' => '', 'description' => '', 'location' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($data as $key => $_) {
        $data[$key] = trim($_POST[$key] ?? '');
    }

    if ($data['name'] === '') {
        $error = 'กรุณากรอกชื่อสถานที่';
    } elseif ($data['province'] === '') {
        $error = 'กรุณากรอกจังหวัด';
    } else {
        $stmt = $pdo->prepare('SELECT COUNT(*) FROM categories WHERE id = ?');
        $stmt->execute([(int)$data['category_id']]);
        if ($stmt->fetchColumn() == 0) {
            $error = 'กรุณาเลือกหมวดหมู่';
        }
    }

    $imageName = null;
    if ($error === '') {
        [$imageName, $uploadError] = uploadImage($_FILES['image'] ?? null);
        if ($uploadError !== '') {
            $error = $uploadError;
        }
    }

    if ($error === '') {
        $stmt = $pdo->prepare(
            'INSERT INTO places (category_id, name, province, description, location, image)
             VALUES (?, ?, ?, ?, ?, ?)'
        );
        $stmt->execute([
            (int)$data['category_id'],
            $data['name'],
            $data['province'],
            $data['description'],
            $data['location'],
            $imageName,
        ]);
        $_SESSION['flash'] = ['type' => 'success', 'msg' => 'เพิ่มสถานที่เรียบร้อยแล้ว'];
        header('Location: index.php');
        exit;
    }
}

$pageTitle  = 'เพิ่มสถานที่';
$activeMenu = 'places';
require __DIR__ . '/../includes/header.php';
?>

<h3 class="fw-semibold mb-4">เพิ่มสถานที่ท่องเที่ยว</h3>

<div class="card panel-card" style="max-width:800px;">
    <div class="card-body p-4">
        <?php if ($error): ?>
            <div class="alert alert-danger py-2"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="post" enctype="multipart/form-data">
            <div class="row g-3">
                <div class="col-md-8">
                    <label for="name" class="form-label">ชื่อสถานที่ <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="name" name="name" maxlength="150" required
                           value="<?= htmlspecialchars($data['name']) ?>">
                </div>
                <div class="col-md-4">
                    <label for="province" class="form-label">จังหวัด <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="province" name="province" maxlength="100" required
                           value="<?= htmlspecialchars($data['province']) ?>">
                </div>
                <div class="col-md-6">
                    <label for="category_id" class="form-label">หมวดหมู่ <span class="text-danger">*</span></label>
                    <select class="form-select" id="category_id" name="category_id" required>
                        <option value="">-- เลือกหมวดหมู่ --</option>
                        <?php foreach ($categories as $c): ?>
                            <option value="<?= (int)$c['id'] ?>"
                                <?= (string)$c['id'] === (string)$data['category_id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($c['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="location" class="form-label">ที่ตั้ง / ลิงก์แผนที่</label>
                    <input type="text" class="form-control" id="location" name="location" maxlength="255"
                           placeholder="เช่น ลิงก์ Google Maps หรือที่อยู่"
                           value="<?= htmlspecialchars($data['location']) ?>">
                </div>
                <div class="col-12">
                    <label for="description" class="form-label">รายละเอียด</label>
                    <textarea class="form-control" id="description" name="description" rows="5"><?= htmlspecialchars($data['description']) ?></textarea>
                </div>
                <div class="col-12">
                    <label for="image" class="form-label">รูปภาพ (jpg, png, webp ไม่เกิน 2MB)</label>
                    <input type="file" class="form-control" id="image" name="image"
                           accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp">
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-teal">บันทึก</button>
                <a href="index.php" class="btn btn-outline-secondary">ยกเลิก</a>
            </div>
        </form>
    </div>
</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>