<?php
require_once __DIR__ . '/bootstrap.php';

$currentUser = $authService->requireLogin();
$userId = $currentUser->id;
$pageTitle = 'Reports';
$activeNav = 'reports';

$month = $_GET['month'] ?? date('Y-m');
if (!preg_match('/^\d{4}-\d{2}$/', $month)) {
    $month = date('Y-m');
}

$monthStart = new DateTimeImmutable($month . '-01');
$monthEnd = $monthStart->modify('last day of this month');
$from = $monthStart->format('Y-m-d');
$to = $monthEnd->format('Y-m-d');

$revenue = $revenueCalculator->totalRevenueInRange($userId, $from, $to);
$newSubs = $subscriptionRepository->countNewInRange($userId, $from, $to);
$cancelledSubs = $subscriptionRepository->countCancelledInRange($userId, $from, $to);
$activeAtEnd = $subscriptionRepository->countActiveAsOf($userId, $to);

$prevMonth = $monthStart->modify('-1 month')->format('Y-m');
$nextMonth = $monthStart->modify('+1 month')->format('Y-m');
$isCurrentMonth = $month === date('Y-m');

require __DIR__ . '/partials/app_header.php';
?>

<div class="page-header">
    <div>
        <h1>Reports</h1>
        <div class="sub">Monthly business summary</div>
    </div>
</div>

<div class="month-picker">
    <a href="reports.php?month=<?= e($prevMonth) ?>" class="btn btn-secondary btn-sm" style="width:auto;">&larr; Prev</a>
    <form method="get" style="display:flex; gap:8px; align-items:center;">
        <input type="month" name="month" value="<?= e($month) ?>" onchange="this.form.submit()"
               style="background:var(--surface); border:1px solid var(--border-strong); color:var(--heading); border-radius:8px; padding:8px 12px; font:500 14px var(--font);">
        <noscript><button type="submit" class="btn btn-secondary btn-sm">Go</button></noscript>
    </form>
    <?php if (!$isCurrentMonth): ?>
        <a href="reports.php?month=<?= e($nextMonth) ?>" class="btn btn-secondary btn-sm" style="width:auto;">Next &rarr;</a>
    <?php endif; ?>
</div>

<div class="panel">
    <h3>Monthly Revenue Report — <?= e($monthStart->format('F Y')) ?></h3>
    <div class="report-summary" style="margin-top:16px; margin-bottom:0;">
        <div class="kpi-card">
            <div class="label">Total Revenue</div>
            <div class="value"><?= e(inr($revenue)) ?></div>
        </div>
        <div class="kpi-card">
            <div class="label">New Subscriptions</div>
            <div class="value"><?= (int) $newSubs ?></div>
        </div>
        <div class="kpi-card">
            <div class="label">Cancelled</div>
            <div class="value"><?= (int) $cancelledSubs ?></div>
        </div>
        <div class="kpi-card">
            <div class="label">Active (end of month)</div>
            <div class="value"><?= (int) $activeAtEnd ?></div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/partials/app_footer.php'; ?>
