<?php
/**
 * Shared header for public pages (home, about, contact).
 * Expects $pageTitle and optionally $activePublicNav to be set before
 * this partial is required.
 */
$activePublicNav = $activePublicNav ?? '';
$publicNav = [
    'home' => ['index.php', 'Home'],
    'features' => ['index.php#features', 'Features'],
    'about' => ['about.php', 'About'],
    'contact' => ['contact.php', 'Contact'],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle ?? APP_TAGLINE) ?> · <?= e(APP_NAME) ?></title>
    <meta name="description" content="<?= e(APP_TAGLINE) ?> — track subscriptions, MRR, ARR, churn and renewals in one dashboard.">
    <link rel="preload" href="assets/fonts/inter-latin-800-normal.woff2" as="font" type="font/woff2" crossorigin>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<header class="site-header">
    <div class="container">
        <a href="index.php" class="brand"><span class="logo-mark">S</span><?= e(APP_NAME) ?></a>
        <nav class="nav-links" aria-label="Main">
            <?php foreach ($publicNav as $key => [$href, $label]): ?>
                <a href="<?= e($href) ?>" class="<?= $activePublicNav === $key ? 'active' : '' ?>"><?= e($label) ?></a>
            <?php endforeach; ?>
        </nav>
        <div class="nav-actions">
            <a href="login.php" class="signin">Sign in</a>
            <a href="register.php" class="btn btn-primary btn-sm">Get started</a>
        </div>
    </div>
</header>
