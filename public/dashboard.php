<?php
require_once __DIR__ . '/bootstrap.php';

$currentUser = $authService->requireLogin();
$userId = $currentUser->id;
$pageTitle = 'Dashboard';
$activeNav = 'dashboard';

$totalRevenue = $revenueCalculator->totalRevenue($userId);
$mrr = $revenueCalculator->mrr($userId);
$activeSubscribers = $subscriptionRepository->countActive($userId);
$cancelledSubscribers = $subscriptionRepository->countCancelled($userId);

$monthlySeries = $revenueCalculator->monthlyRevenueSeries($userId, 6);
$monthLabels = array_map(fn ($k) => (new DateTimeImmutable($k . '-01'))->format('M Y'), array_keys($monthlySeries));
$monthValues = array_values($monthlySeries);

$planPopularity = $revenueCalculator->planPopularity($userId);
$planCounts = array_map(fn ($p) => $p['count'], $planPopularity);
$maxPlanCount = $planCounts === [] ? 1 : max(1, ...$planCounts);

$today = date('Y-m-d');
$thisMonthStart = date('Y-m-01');
$newThisMonth = $subscriptionRepository->countNewInRange($userId, $thisMonthStart, $today);
$cancelledThisMonth = $subscriptionRepository->countCancelledInRange($userId, $thisMonthStart, $today);
$revenueThisMonth = $revenueCalculator->totalRevenueInRange($userId, $thisMonthStart, $today);

require __DIR__ . '/partials/app_header.php';
?>

<div class="page-header">
    <div>
        <h1>Dashboard</h1>
        <div class="sub">Overview for <?= e($currentUser->businessName) ?> · <?= e((new DateTimeImmutable())->format('d M Y')) ?></div>
    </div>
    <a href="subscription_form.php" class="btn btn-primary" style="width:auto;">+ New Subscription</a>
</div>

<div class="kpi-grid">
    <div class="kpi-card">
        <div class="label">Total Revenue</div>
        <div class="value"><?= e(inr($totalRevenue)) ?></div>
        <div class="delta text-dim">All-time, paid payments</div>
    </div>
    <div class="kpi-card">
        <div class="label">Monthly Recurring Revenue</div>
        <div class="value"><?= e(inr($mrr)) ?></div>
        <div class="delta text-dim">ARR: <?= e(inr($revenueCalculator->arr($userId))) ?></div>
    </div>
    <div class="kpi-card">
        <div class="label">Active Subscribers</div>
        <div class="value"><?= (int) $activeSubscribers ?></div>
        <div class="delta up">+<?= (int) $newThisMonth ?> new this month</div>
    </div>
    <div class="kpi-card">
        <div class="label">Cancelled Subscriptions</div>
        <div class="value"><?= (int) $cancelledSubscribers ?></div>
        <div class="delta <?= $cancelledThisMonth > 0 ? 'down' : 'text-dim' ?>"><?= (int) $cancelledThisMonth ?> this month</div>
    </div>
    <div class="kpi-card">
        <div class="label">Revenue This Month</div>
        <div class="value"><?= e(inr($revenueThisMonth)) ?></div>
        <div class="delta text-dim">Collected so far</div>
    </div>
</div>

<div class="panel-grid">
    <div class="panel">
        <h3>Revenue Overview — last 6 months</h3>
        <div class="chart-box">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>
    <div class="panel">
        <h3>Subscription Plans</h3>
        <?php if (empty($planPopularity)): ?>
            <p class="text-dim">No plans yet. <a href="plan_form.php">Add one</a>.</p>
        <?php else: ?>
            <?php foreach ($planPopularity as $row): ?>
                <div class="plan-bar-row">
                    <div class="name"><?= e($row['plan']->planName) ?></div>
                    <div class="track"><div class="fill" style="width: <?= round(($row['count'] / $maxPlanCount) * 100) ?>%;"></div></div>
                    <div class="count"><?= (int) $row['count'] ?> users</div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<script src="assets/js/chart.umd.min.js"></script>
<script>
(function () {
    var c = SubTrackColors.read();
    Chart.defaults.font.family = "Inter, -apple-system, 'Segoe UI', Roboto, Arial, sans-serif";
    var chart = new Chart(document.getElementById('revenueChart'), {
        type: 'line',
        data: {
            labels: <?= json_encode($monthLabels) ?>,
            datasets: [{
                label: 'Revenue',
                data: <?= json_encode($monthValues) ?>,
                borderColor: c.primary,
                backgroundColor: c.primarySoft,
                fill: true,
                tension: 0.35,
                borderWidth: 3,
                pointRadius: 4,
                pointBackgroundColor: c.primary,
                pointBorderColor: c.surface,
                pointBorderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { color: c.grid }, ticks: { color: c.tick } },
                y: { grid: { color: c.grid }, ticks: { color: c.tick }, beginAtZero: true }
            }
        }
    });

})();
</script>

<?php require __DIR__ . '/partials/app_footer.php'; ?>
