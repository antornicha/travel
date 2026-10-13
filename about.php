<?php
require_once 'config/db.php';

$pageTitle = 'เกี่ยวกับเรา';
$activePage = 'about';
$heroPage = false;

require_once 'includes/header.php';
?>

<div class="page-head">
    <div class="container">
        <h1>เกี่ยวกับเรา</h1>
        <p class="page-head-lead">เรื่องราวและแรงบันดาลใจเบื้องหลังการพาคุณไปสำรวจความงดงามของสถานที่ท่องเที่ยวทั่วประเทศไทย</p>
    </div>
</div>

<section class="section-white">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <div class="prose">
                    <h2>แรงบันดาลใจของเรา</h2>
                    <p>ประเทศไทยเป็นดินแดนที่มีความหลากหลายทางธรรมชาติ วัฒนธรรม และวิถีชีวิต ตั้งแต่ชายหาดฝั่งทะเลอันดามันและอ่าวไทย ไปจนถึงขุนเขาอันสลับซับซ้อนในภาคเหนือ เว็บไซต์นี้ถูกสร้างขึ้นมาด้วยความตั้งใจที่จะเป็นศูนย์กลางข้อมูลการท่องเที่ยวที่เข้าถึงง่าย น่าเชื่อถือ และสร้างแรงบันดาลใจให้ทุกคนออกเดินทาง</p>
                    <p>เราคัดสรรและรวบรวมข้อมูลสถานที่ท่องเที่ยวสำคัญ พร้อมแบ่งหมวดหมู่เพื่อให้คุณวางแผนการเดินทางได้อย่างสะดวก ไม่ว่าจะเป็นสายธรรมชาติ สายวัฒนธรรม หรือผู้ที่กำลังมองหาจุดพักผ่อนใหม่ๆ ในวันหยุด</p>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="prose">
                    <h2>พันธกิจในการเดินทาง</h2>
                    <p>เรามุ่งหวังที่จะส่งเสริมการท่องเที่ยวภายในประเทศ สนับสนุนชุมชนท้องถิ่น และกระตุ้นให้เกิดความตระหนักรู้ในการอนุรักษ์สิ่งแวดล้อมและทรัพยากรธรรมชาติ ทุกสถานที่ที่เราแนะนำผ่านการคัดเลือกเพื่อให้ผู้เยี่ยมชมได้รับข้อมูลที่เป็นประโยชน์และภาพบรรยากาศที่แท้จริง</p>
                    <p>มาร่วมค้นพบมุมมองใหม่ๆ และสร้างความทรงจำที่ดีไปกับการเดินทางในประเทศไทยด้วยกัน</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section" style="background-color: var(--foam);">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center prose">
                <h2>ร่วมแบ่งปันการเดินทางของคุณ</h2>
                <p>หากคุณมีสถานที่ท่องเที่ยวที่น่าสนใจ หรือต้องการแนะนำติชมการแสดงผลข้อมูล สามารถติดต่อเราได้ตลอดเวลา เพื่อให้เว็บไซต์แห่งนี้เป็นแหล่งรวมข้อมูลการท่องเที่ยวที่สมบูรณ์แบบที่สุดสำหรับทุกคน</p>
                <div class="mt-4">
                    <a href="contact.php" class="btn btn-mango">ติดต่อเรา</a>
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