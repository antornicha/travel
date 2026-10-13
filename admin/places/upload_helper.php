<?php
const UPLOAD_DIR  = __DIR__ . '/../../uploads/';
const UPLOAD_MAX  = 2 * 1024 * 1024; // 2MB
const UPLOAD_URL  = '/travel/uploads/';

/**
 * จัดการอัปโหลดรูป
 * คืนค่า [ชื่อไฟล์ใหม่ หรือ null, ข้อความ error หรือ '']
 * ถ้าไม่ได้เลือกไฟล์ จะคืน [null, ''] (ไม่ถือว่าผิดพลาด)
 */
function uploadImage(?array $file): array
{
    if (!$file || $file['error'] === UPLOAD_ERR_NO_FILE) {
        return [null, ''];
    }

    if ($file['error'] === UPLOAD_ERR_INI_SIZE || $file['error'] === UPLOAD_ERR_FORM_SIZE) {
        return [null, 'ไฟล์รูปมีขนาดใหญ่เกิน 2MB'];
    }
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return [null, 'อัปโหลดรูปไม่สำเร็จ กรุณาลองใหม่'];
    }
    if ($file['size'] > UPLOAD_MAX) {
        return [null, 'ไฟล์รูปมีขนาดใหญ่เกิน 2MB'];
    }

    // ตรวจชนิดไฟล์จากเนื้อไฟล์จริง ไม่ใช่แค่นามสกุล
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime  = $finfo->file($file['tmp_name']);
    $allowed = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/webp' => 'webp',
    ];
    if (!isset($allowed[$mime])) {
        return [null, 'รองรับเฉพาะไฟล์รูป jpg, png, webp เท่านั้น'];
    }

    if (!is_dir(UPLOAD_DIR)) {
        mkdir(UPLOAD_DIR, 0755, true);
    }

    $newName = bin2hex(random_bytes(8)) . '.' . $allowed[$mime];
    if (!move_uploaded_file($file['tmp_name'], UPLOAD_DIR . $newName)) {
        return [null, 'บันทึกไฟล์รูปไม่สำเร็จ'];
    }

    return [$newName, ''];
}

/** ลบไฟล์รูปออกจากโฟลเดอร์ uploads */
function deleteImageFile(?string $filename): void
{
    if (!$filename) {
        return;
    }
    $path = UPLOAD_DIR . basename($filename); // basename กันการระบุ path แปลกๆ
    if (is_file($path)) {
        unlink($path);
    }
}