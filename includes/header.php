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
   <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">