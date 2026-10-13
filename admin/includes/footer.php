</main>

<footer class="text-center text-muted small py-3">
    ระบบจัดการหลังบ้าน &middot; เว็บไซต์แนะนำสถานที่ท่องเที่ยวในประเทศไทย
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<footer class="site-footer">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-5">
                <p class="footer-brand"><?= htmlspecialchars($siteName) ?></p>
                <p class="footer-text">
                    เว็บไซต์รวบรวมสถานที่ท่องเที่ยวทั่วประเทศไทย ตั้งแต่ทะเลอันดามัน
                    ภูเขาทางภาคเหนือ ไปจนถึงเมืองเก่าอายุหลายร้อยปี
                    พร้อมข้อมูลจังหวัดและที่ตั้งไว้ช่วยวางแผนการเดินทาง
                </p>
            </div>

            <div class="col-6 col-lg-3">
                <h2 class="footer-title">ลิงก์ด่วน</h2>
                <ul class="list-unstyled footer-links">
                    <?php foreach ($menu as $key => [$label, $file]): ?>
                        <li><a href="<?= $base . $file ?>"><?= htmlspecialchars($label) ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="col-6 col-lg-4">
                <h2 class="footer-title">ติดต่อเรา</h2>
                <ul class="list-unstyled footer-links">
                    <li>อีเมล: contact@thaitravel.example</li>
                    <li>จัดทำเป็นส่วนหนึ่งของโปรเจคกลุ่ม</li>
                    <li>ข้อมูลและรูปภาพใช้เพื่อการศึกษา</li>
                </ul>
            </div>
        </div>

        <p class="footer-copy">&copy; <?= date('Y') ?> <?= htmlspecialchars($siteName) ?></p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
// แถบเมนู: โปร่งใสบน hero แล้วเปลี่ยนเป็นสีทึบเมื่อเลื่อนลงหรือเปิดเมนูมือถือ
(function () {
    var nav = document.getElementById('siteNav');
    if (!nav) return;
    var onHero = nav.classList.contains('on-hero');

    function update() {
        var solid = !onHero || window.scrollY > 40 || nav.classList.contains('menu-open');
        nav.classList.toggle('is-solid', solid);
    }

    var menu = document.getElementById('mainMenu');
    if (menu) {
        menu.addEventListener('show.bs.collapse', function () { nav.classList.add('menu-open'); update(); });
        menu.addEventListener('hide.bs.collapse', function () { nav.classList.remove('menu-open'); update(); });
    }

    window.addEventListener('scroll', update, { passive: true });
    update();
})();
</script>
</body>
</html>