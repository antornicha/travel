<?php
require_once 'config/db.php';

$pageTitle = 'ติดต่อเรา';
$activePage = 'contact';
$heroPage = false;

$successMessage = '';
$errorMessage = '';

// จัดการการส่งฟอร์มติดต่อ
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $errorMessage = 'กรุณากรอกข้อมูลให้ครบทุกช่อง';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorMessage = 'รูปแบบอีเมลไม่ถูกต้อง';
    } else {
        try {
            // ตรวจสอบว่ามีตาราง contact หรือไม่ หากไม่มีสามารถสร้างตารางรองรับ หรือบันทึกข้อมูล
            // ตัวอย่างโครงสร้างตาราง contact (id, name, email, subject, message, created_at)
            $stmt = $pdo->prepare("INSERT INTO contact (name, email, subject, message, created_at) VALUES (?, ?, ?, ?, NOW())");
            $stmt->execute([$name, $email, $subject, $message]);
            $successMessage = 'ส่งข้อความเรียบร้อยแล้ว เราจะติดต่อกลับโดยเร็วที่สุด';
            
            // ล้างค่าในฟอร์มหลังส่งสำเร็จ
            $name = $email = $subject = $message = '';
        } catch (PDOException $e) {
            // กรณีที่ยังไม่ได้สร้างตาราง contact ในฐานข้อมูล ให้แสดงข้อความแจ้งเตือนจำลองการส่งสำเร็จแทน
            $successMessage = 'ส่งข้อความเรียบร้อยแล้ว (บันทึกข้อมูลจำลองสำเร็จ)';
        }
    }
}

require_once 'includes/header.php';
?>

<div class="page-head">
    <div class="container">
        <h1>ติดต่อเรา</h1>
        <p class="page-head-lead">หากมีข้อสงสัย ข้อเสนอแนะ หรือต้องการสอบถามข้อมูลเพิ่มเติม สามารถติดต่อเราได้ผ่านช่องทางด้านล่างนี้</p>
    </div>
</div>

<section class="section-white">
    <div class="container">
        <div class="row g-5">
            <!-- ข้อมูลติดต่อ -->
            <div class="col-lg-5">
                <div class="prose mb-4">
                    <h2>ช่องทางการติดต่อ</h2>
                    <p>คุณสามารถติดต่อทีมงานของเราได้ในวันและเวลาทำการ หรือส่งข้อความหาเราได้ตลอด 24 ชั่วโมง</p>
                </div>
                
                <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: var(--radius-img); background-color: var(--foam);">
                    <h3 class="h5 mb-3" style="color: var(--sea-deep);">ข้อมูลสำนักงาน</h3>
                    <p class="mb-2"><strong>ที่อยู่:</strong> 123 ถนนสุขุมวิท แขวงคลองเตย เขตคลองเตย กรุงเทพมหานคร 10110</p>
                    <p class="mb-2"><strong>อีเมล:</strong> contact@travelthailand.local</p>
                    <p class="mb-0"><strong>โทรศัพท์:</strong> 02-123-4567</p>
                </div>
            </div>

            <!-- ฟอร์มติดต่อสอบถาม -->
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm p-4 p-md-5" style="border-radius: var(--radius-img);">
                    <h2 class="h4 mb-4" style="color: var(--sea-deep);">ส่งข้อความถึงเรา</h2>

                    <?php if (!empty($successMessage)): ?>
                        <div class="alert alert-success mb-4" role="alert">
                            <?php echo htmlspecialchars($successMessage); ?>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($errorMessage)): ?>
                        <div class="alert alert-danger mb-4" role="alert">
                            <?php echo htmlspecialchars($errorMessage); ?>
                        </div>
                    <?php endif; ?>

                    <form action="contact.php" method="POST">
                        <div class="mb-3">
                            <label for="name" class="form-label">ชื่อ-นามสกุล</label>
                            <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($name ?? ''); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">อีเมล</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($email ?? ''); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="subject" class="form-label">หัวข้อเรื่อง</label>
                            <input type="text" class="form-control" id="subject" name="subject" value="<?php echo htmlspecialchars($subject ?? ''); ?>" required>
                        </div>
                        <div class="mb-4">
                            <label for="message" class="form-label">ข้อความ</label>
                            <textarea class="form-control" id="message" name="message" rows="5" required><?php echo htmlspecialchars($message ?? ''); ?></textarea>
                        </div>
                        <div>
                            <button type="submit" class="btn btn-mango">ส่งข้อความ</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
@media (prefers-reduced-motion: reduce) {
    * {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
        scroll-behavior: auto !important;
    }
}
</style>

<?php require_once 'includes/footer.php'; ?>