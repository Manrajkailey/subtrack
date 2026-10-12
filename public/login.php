<?php
require_once __DIR__ . '/bootstrap.php';

if ($authService->currentUser() !== null) {
    redirect('dashboard.php');
}

$error = null;
$oldEmail = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrf();

    $oldEmail = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $user = $authService->attemptLogin($oldEmail, $password);

    if ($user === null) {
        $error = 'Incorrect email or password.';
    } else {
        $authService->login($user);
        redirect('dashboard.php');
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign in · <?= e(APP_NAME) ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="auth-layout">
    <div class="auth-form-side">
        <div class="top">
            <a href="index.php" class="brand"><span class="logo-mark">S</span><?= e(APP_NAME) ?></a>
        </div>
        <div class="auth-form-wrap">
            <div class="auth-form">
                <h1>Welcome back</h1>
                <p>Sign in to see how your business is doing today.</p>

                <?php if ($error): ?>
                    <div class="alert alert-error"><?= e($error) ?></div>
                <?php endif; ?>

                <form method="post" novalidate>
                    <?= csrfField() ?>
                    <div class="field">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" value="<?= e($oldEmail) ?>" placeholder="you@business.com" required autofocus>
                    </div>
                    <div class="field">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" placeholder="Your password" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg btn-block">Sign in</button>
                </form>
                <div class="demo-box"><strong>Demo account:</strong> owner@subtrack.test / password123</div>
                <div class="auth-foot">New to <?= e(APP_NAME) ?>? <a href="register.php">Create an account</a></div>
            </div>
        </div>
    </div>
    <div class="auth-photo">
        <img src="assets/img/photos/team-laptops.jpg" alt="A team working on laptops around a shared table">
        <div class="overlay"></div>
        <div class="content">
            <div class="float-card">
                <div><div class="k">MRR</div><div class="v">₹31,468</div></div>
                <div><div class="k">Active</div><div class="v">35</div></div>
                <div><div class="k">Churn</div><div class="v">2.8%</div></div>
            </div>
            <h2>Your recurring revenue, always up to date.</h2>
            <p>MRR, ARR, churn and upcoming renewals — calculated the moment anything changes.</p>
        </div>
    </div>
</div>
</body>
</html>
