<?php
/**
 * Shared app-shell header for every logged-in page: a top bar with the
 * nav links, theme picker and account menu aligned to the right.
 * Expects $currentUser (User) and $activeNav (string) to be set by the
 * including page before this partial is required.
 */
$navItems = [
    'dashboard' => ['dashboard.php', 'Dashboard'],
    'customers' => ['customers.php', 'Customers'],
    'plans' => ['plans.php', 'Plans'],
    'subscriptions' => ['subscriptions.php', 'Subscriptions'],
    'revenue' => ['revenue.php', 'Revenue'],
    'renewals' => ['renewals.php', 'Renewals'],
    'reports' => ['reports.php', 'Reports'],
];
$nameParts = preg_split('/\s+/', trim($currentUser->name)) ?: ['?'];
$initials = strtoupper(substr($nameParts[0], 0, 1) . (count($nameParts) > 1 ? substr(end($nameParts), 0, 1) : ''));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle ?? APP_NAME) ?> · <?= e(APP_NAME) ?></title>
    <link rel="preload" href="assets/fonts/inter-latin-800-normal.woff2" as="font" type="font/woff2" crossorigin>
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="assets/js/chart-colors.js"></script>
</head>
<body class="app-body">
<div class="app-topbar">
    <div class="app-topbar-inner">
        <a href="dashboard.php" class="brand"><span class="logo-mark">S</span><?= e(APP_NAME) ?></a>
        <div class="app-nav">
            <?php foreach ($navItems as $key => [$href, $label]): ?>
                <a href="<?= e($href) ?>" class="<?= $activeNav === $key ? 'active' : '' ?>"><?= e($label) ?></a>
            <?php endforeach; ?>
            <div class="app-user-menu">
                <div class="avatar" aria-hidden="true"><?= e($initials) ?></div>
                <div class="biz-name">
                    <strong><?= e($currentUser->businessName) ?></strong>
                    <span><?= e($currentUser->name) ?></span>
                </div>
                <a href="logout.php" class="logout-link">Log out</a>
            </div>
        </div>
    </div>
</div>
<main class="main">
    <?php foreach (getFlashes() as $flash): ?>
        <div class="alert alert-<?= $flash['type'] === 'error' ? 'error' : 'success' ?>"><?= e($flash['message']) ?></div>
    <?php endforeach; ?>
