<?php
session_start();
require __DIR__ . '/../config/db.php';

// ถ้า login อยู่แล้ว ไปหน้า dashboard เลย
if (isset($_SESSION['admin_id'])) {
    header('Location: dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare('SELECT id, username, password FROM admins WHERE username = ?');
    $stmt->execute([$username]);
    $admin = $stmt->fetch();

    if ($admin && password_verify($password, $admin['password'])) {
        session_regenerate_id(true);
        $_SESSION['admin_id']   = $admin['id'];
        $_SESSION['admin_user'] = $admin['username'];
        header('Location: dashboard.php');
        exit;
    } else {
        $error = 'ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง';
    }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>เข้าสู่ระบบผู้ดูแล</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Prompt', sans-serif;
            min-height: 100vh;
            background: linear-gradient(135deg, #0f766e 0%, #0ea5e9 100%);
            display: flex;
            align-items: center;
        }
        .login-card {
            border: 0;
            border-radius: 1.25rem;
            box-shadow: 0 15px 40px rgba(0, 0, 0, .25);
        }
        .login-icon {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            background: #e0f2fe;
            color: #0369a1;
            font-size: 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
        }
        .btn-login {
            background: #0f766e;
            border: 0;
            padding: .7rem;
            font-weight: 500;
        }
        .btn-login:hover { background: #115e59; }
    </style>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-11 col-sm-8 col-md-6 col-lg-4">
            <div class="card login-card">
                <div class="card-body p-4 p-md-5">
                    <div class="login-icon">&#9992;</div>
                    <h4 class="text-center fw-semibold mb-1">ระบบจัดการหลังบ้าน</h4>
                    <p class="text-center text-muted mb-4">เว็บไซต์แนะนำสถานที่ท่องเที่ยว</p>

                    <?php if ($error): ?>
                        <div class="alert alert-danger py-2" role="alert">
                            <?= htmlspecialchars($error) ?>
                        </div>
                    <?php endif; ?>

                    <form method="post" autocomplete="off">
                        <div class="mb-3">
                            <label for="username" class="form-label">ชื่อผู้ใช้</label>
                            <input type="text" class="form-control form-control-lg" id="username"
                                   name="username" required autofocus
                                   value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
                        </div>
                        <div class="mb-4">
                            <label for="password" class="form-label">รหัสผ่าน</label>
                            <input type="password" class="form-control form-control-lg" id="password"
                                   name="password" required>
                        </div>
                        <button type="submit" class="btn btn-login btn-primary w-100 text-white">
                            เข้าสู่ระบบ
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>