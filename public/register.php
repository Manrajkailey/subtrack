<?php
require_once __DIR__ . '/bootstrap.php';

if ($authService->currentUser() !== null) {
    redirect('dashboard.php');
}

$errors = [];
$old = ['name' => '', 'email' => '', 'business_name' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrf();

    $old['name'] = trim($_POST['name'] ?? '');
    $old['email'] = trim($_POST['email'] ?? '');
    $old['business_name'] = trim($_POST['business_name'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    if ($password !== $confirm) {
        $errors[] = 'Passwords do not match.';
    } else {
        try {
            $user = $authService->register($old['name'], $old['email'], $password, $old['business_name']);
            $authService->login($user);
            flash('success', 'Welcome to ' . APP_NAME . '! Your account is ready.');
            redirect('dashboard.php');
        } catch (InvalidArgumentException $e) {
            $errors[] = $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create account · <?= e(APP_NAME) ?></title>
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
                <h1>Create your account</h1>
                <p>Start tracking your subscription revenue in minutes.</p>

                <?php foreach ($errors as $error): ?>
                    <div class="alert alert-error"><?= e($error) ?></div>
                <?php endforeach; ?>

                <form method="post" novalidate>
                    <?= csrfField() ?>
                    <div class="two-col">
                        <div class="field">
                            <label for="name">Your name</label>
                            <input type="text" id="name" name="name" value="<?= e($old['name']) ?>" placeholder="Full name" required>
                        </div>
                        <div class="field">
                            <label for="business_name">Business name</label>
                            <input type="text" id="business_name" name="business_name" value="<?= e($old['business_name']) ?>" placeholder="e.g. FitZone Gym" required>
                        </div>
                    </div>
                    <div class="field">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" value="<?= e($old['email']) ?>" placeholder="you@business.com" required>
                    </div>
                    <div class="two-col">
                        <div class="field">
                            <label for="password">Password</label>
                            <input type="password" id="password" name="password" minlength="6" placeholder="6+ characters" required>
                        </div>
                        <div class="field">
                            <label for="confirm_password">Confirm password</label>
                            <input type="password" id="confirm_password" name="confirm_password" minlength="6" placeholder="Repeat password" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg btn-block">Create account</button>
                </form>
                <div class="auth-foot">Already have an account? <a href="login.php">Sign in</a></div>
            </div>
        </div>
    </div>
    <div class="auth-photo">
        <img src="assets/img/photos/team-meeting.jpg" alt="A small team reviewing results together in an open office">
        <div class="overlay"></div>
        <div class="content">
            <div class="float-card">
                <div><div class="k">Set up</div><div class="v">5 min</div></div>
                <div><div class="k">Modules</div><div class="v">5</div></div>
                <div><div class="k">Cost</div><div class="v">Free</div></div>
            </div>
            <h2>Stop guessing. Start knowing your numbers.</h2>
            <p>Add your plans and customers once — SubTrack handles renewals, revenue and reports from there.</p>
        </div>
    </div>
</div>
</body>
</html>
