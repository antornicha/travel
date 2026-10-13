<?php
// ตัวแปรที่หน้าอื่นกำหนดก่อน require: $pageTitle, $activeMenu
$pageTitle  = $pageTitle  ?? 'ระบบจัดการหลังบ้าน';
$activeMenu = $activeMenu ?? '';
$adminBase  = '/travel/admin/';
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($pageTitle) ?> | หลังบ้าน</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --teal-dark: #0f766e;
            --teal: #14b8a6;
            --sea: #0ea5e9;
        }
        body { font-family: 'Prompt', sans-serif; background: #f0fdfa; }
        .navbar-admin { background: linear-gradient(90deg, var(--teal-dark), var(--sea)); }
        .navbar-admin .nav-link { color: rgba(255,255,255,.85); }
        .navbar-admin .nav-link:hover,
        .navbar-admin .nav-link.active { color: #fff; font-weight: 500; }
        .stat-card { border: 0; border-radius: 1rem; color: #fff; box-shadow: 0 6px 18px rgba(0,0,0,.12); }
        .stat-places { background: linear-gradient(135deg, #0f766e, #14b8a6); }
        .stat-cats   { background: linear-gradient(135deg, #0369a1, #0ea5e9); }
        .stat-number { font-size: 2.8rem; font-weight: 600; line-height: 1; }
        .panel-card { border: 0; border-radius: 1rem; box-shadow: 0 4px 14px rgba(0,0,0,.08); }
        .btn-teal { background: var(--teal-dark); border-color: var(--teal-dark); color: #fff; }
        .btn-teal:hover { background: #115e59; border-color: #115e59; color: #fff; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark navbar-admin shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-semibold" href="<?= $adminBase ?>dashboard.php">&#9992; หลังบ้านท่องเที่ยว</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="adminNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link <?= $activeMenu === 'dashboard' ? 'active' : '' ?>"
                       href="<?= $adminBase ?>dashboard.php">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $activeMenu === 'places' ? 'active' : '' ?>"
                       href="<?= $adminBase ?>places/index.php">จัดการสถานที่</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $activeMenu === 'categories' ? 'active' : '' ?>"
                       href="<?= $adminBase ?>categories/index.php">จัดการหมวดหมู่</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/travel/index.php" target="_blank">ดูหน้าเว็บ</a>
                </li>
            </ul>
            <div class="d-flex align-items-center text-white">
                <span class="me-3 small">สวัสดี, <?= htmlspecialchars($_SESSION['admin_user'] ?? '') ?></span>
                <a href="<?= $adminBase ?>logout.php" class="btn btn-sm btn-light">ออกจากระบบ</a>
            </div>
        </div>
    </div>
</nav>

<main class="container py-4">

<?php
// ตัวแปรที่หน้าอื่นกำหนดก่อน require: $pageTitle, $activePage, $heroPage
$siteName   = 'เที่ยวทั่วไทย';
$pageTitle  = $pageTitle  ?? $siteName;
$activePage = $activePage ?? '';
$heroPage   = $heroPage   ?? false;
$base       = '/travel/';

$menu = [
    'home'       => ['หน้าแรก',          'index.php'],
    'places'     => ['สถานที่ท่องเที่ยว', 'places.php'],
    'categories' => ['หมวดหมู่',          'categories.php'],
    'gallery'    => ['แกลเลอรี',          'gallery.php'],
    'about'      => ['เกี่ยวกับเรา',      'about.php'],
    'contact'    => ['ติดต่อเรา',         'contact.php'],
];

$fullTitle = ($pageTitle === $siteName) ? $siteName : $pageTitle . ' | ' . $siteName;
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($fullTitle) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Anuphan:wght@300;400;500&family=Noto+Serif+Thai:wght@600&display=swap" rel="stylesheet">
    <link href="<?= $base ?>assets/css/style.css" rel="stylesheet">
</head>
<body class="<?= $heroPage ? 'has-hero' : 'no-hero' ?>">

<nav id="siteNav" class="site-nav navbar navbar-expand-lg navbar-dark fixed-top <?= $heroPage ? 'on-hero' : 'is-solid' ?>">
    <div class="container">
        <a class="navbar-brand" href="<?= $base ?>index.php"><?= htmlspecialchars($siteName) ?></a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#mainMenu" aria-controls="mainMenu"
                aria-expanded="false" aria-label="เปิดเมนู">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainMenu">
            <ul class="navbar-nav ms-auto">
                <?php foreach ($menu as $key => [$label, $file]): ?>
                    <li class="nav-item">
                        <a class="nav-link <?= $activePage === $key ? 'active' : '' ?>"
                           <?= $activePage === $key ? 'aria-current="page"' : '' ?>
                           href="<?= $base . $file ?>"><?= htmlspecialchars($label) ?></a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</nav>